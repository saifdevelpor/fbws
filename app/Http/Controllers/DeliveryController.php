<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\User;
use App\Models\Item;
use App\Models\AuditLog; // ✅ AUDIT
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class DeliveryController extends Controller
{
    private function ensureAuthenticated(Request $request): void
    {
        abort_unless($request->user(), 403);
    }

    private function decryptId(string $encryptedId): int
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    private function isAdmin($user): bool
    {
        return in_array(strtolower((string) ($user?->role ?? '')), ['admin'], true);
    }

    // ✅ Audit helper (clean + reusable)
    private function audit(string $event, string $action, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'Deliveries',
            'action' => $action,
            'auditable_type' => DeliveryOrder::class,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    public function index(Request $request)
    {
        $this->ensureAuthenticated($request);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $itemImageColExists = Schema::hasColumn('items', 'image');

        $with = [
            'user:id,name,email',
            'creator:id,name',
            'items.item:id,name' . ($itemImageColExists ? ',image' : ''),
        ];

        $query = DeliveryOrder::with($with)->latest();

        if (!$isAdmin) {
            $query->where('user_id', $auth->id);
        }

        $orders = $query->paginate(15);

        return view('delivery.index', compact('orders', 'isAdmin'));
    }

    public function create(Request $request)
    {
        $this->ensureAuthenticated($request);

        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $users = $isAdmin
            ? User::select('id', 'name', 'email', 'profile_photo')->latest()->get()
            : User::select('id', 'name', 'email', 'profile_photo')->where('user_id', $auth->id)->latest()->get();

        $itemImageColExists = Schema::hasColumn('items', 'image');

        $items = Item::select('id', 'name')
            ->when($itemImageColExists, fn($q) => $q->addSelect('image'))
            ->orderBy('name')
            ->get();

        return view('delivery.create', compact('users', 'items'));
    }

    public function store(Request $request)
    {
        $this->ensureAuthenticated($request);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $allowedUserIds = $isAdmin
            ? null
            : User::where('user_id', $auth->id)->pluck('id')->toArray();

        $validator = Validator::make($request->all(), [
            'user_id'          => 'required|exists:users,id',
            'delivery_date'    => 'required|date',
            'delivery_time'    => 'required',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.qty'      => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$isAdmin && $allowedUserIds && !in_array((int)$request->user_id, $allowedUserIds, true)) {
            return back()->with('error', 'You are not allowed to create delivery for this user.')->withInput();
        }

        $order = DB::transaction(function () use ($request, $auth) {

            $order = DeliveryOrder::create([
                'user_id'       => $request->user_id,
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
                'notes'         => $request->notes,
                'created_by'    => $auth->id,
            ]);

            foreach ($request->items as $row) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $order->id,
                    'item_id'           => $row['item_id'],
                    'qty'               => $row['qty'],
                ]);
            }

            return $order;
        });

        // ✅ AUDIT (CREATE) + items snapshot
        $new = $order->fresh()->toArray();
        $new['items'] = DeliveryOrderItem::where('delivery_order_id', $order->id)
            ->get(['item_id', 'qty'])
            ->toArray();

        $this->audit('created', 'Delivery Created', $order->id, null, $new);

        return redirect()->route('deliveries.index')->with('success', 'Delivery created successfully!');
    }

    public function edit(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $order = DeliveryOrder::with(['items.item'])->findOrFail($this->decryptId($id));

        if (!$isAdmin && (int)$order->created_by !== (int)$auth->id) {
            abort(403);
        }

        $users = $isAdmin
            ? User::select('id', 'name', 'email', 'profile_photo')->latest()->get()
            : User::select('id', 'name', 'email', 'profile_photo')->where('user_id', $auth->id)->latest()->get();

        $itemImageColExists = Schema::hasColumn('items', 'image');

        $items = Item::select('id', 'name')
            ->when($itemImageColExists, fn($q) => $q->addSelect('image'))
            ->orderBy('name')
            ->get();

        return view('delivery.edit', compact('order', 'users', 'items'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $order = DeliveryOrder::with('items')->findOrFail($this->decryptId($id));

        if (!$isAdmin && (int)$order->created_by !== (int)$auth->id) {
            abort(403);
        }

        $allowedUserIds = $isAdmin
            ? null
            : User::where('user_id', $auth->id)->pluck('id')->toArray();

        $validator = Validator::make($request->all(), [
            'user_id'          => 'required|exists:users,id',
            'delivery_date'    => 'required|date',
            'delivery_time'    => 'required',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.qty'      => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$isAdmin && $allowedUserIds && !in_array((int)$request->user_id, $allowedUserIds, true)) {
            return back()->with('error', 'You are not allowed to update delivery for this user.')->withInput();
        }

        // ✅ OLD snapshot before update
        $old = $order->toArray();
        $old['items'] = DeliveryOrderItem::where('delivery_order_id', $order->id)
            ->get(['item_id', 'qty'])
            ->toArray();

        DB::transaction(function () use ($request, $order) {

            $order->update([
                'user_id'       => $request->user_id,
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
                'notes'         => $request->notes,
            ]);

            DeliveryOrderItem::where('delivery_order_id', $order->id)->delete();

            foreach ($request->items as $row) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $order->id,
                    'item_id'           => $row['item_id'],
                    'qty'               => $row['qty'],
                ]);
            }
        });

        // ✅ NEW snapshot after update
        $newOrder = DeliveryOrder::findOrFail($order->id);
        $new = $newOrder->toArray();
        $new['items'] = DeliveryOrderItem::where('delivery_order_id', $order->id)
            ->get(['item_id', 'qty'])
            ->toArray();

        $this->audit('updated', 'Delivery Updated', $order->id, $old, $new);

        return redirect()->route('deliveries.index')->with('success', 'Delivery updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $order = DeliveryOrder::findOrFail($this->decryptId($id));

        if (!$isAdmin && (int)$order->created_by !== (int)$auth->id) {
            abort(403);
        }

        // ✅ OLD snapshot before delete
        $old = $order->toArray();
        $old['items'] = DeliveryOrderItem::where('delivery_order_id', $order->id)
            ->get(['item_id', 'qty'])
            ->toArray();

        $orderId = $order->id;

        DB::transaction(function () use ($order) {
            DeliveryOrderItem::where('delivery_order_id', $order->id)->delete();
            $order->delete();
        });

        // ✅ AUDIT (DELETE)
        $this->audit('deleted', 'Delivery Deleted', $orderId, $old, null);

        return back()->with('success', 'Delivery deleted successfully!');
    }

    public function print(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $itemImageColExists = Schema::hasColumn('items', 'image');

        $with = [
            'user:id,name,email,phone_number,address,profile_photo',
            'creator:id,name',
            'items.item:id,name' . ($itemImageColExists ? ',image' : ''),
        ];

        $order = DeliveryOrder::with($with)->findOrFail($this->decryptId($id));

        if (!$isAdmin && (int)$order->created_by !== (int)$auth->id) {
            abort(403);
        }

        // ✅ OPTIONAL: log print action (uncomment if you want)
        // $this->audit('printed', 'Delivery Print Viewed', $order->id, null, null);

        return view('delivery.print', compact('order'));
    }
}
