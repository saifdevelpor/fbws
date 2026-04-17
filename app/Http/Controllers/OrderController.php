<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
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
        return strtolower((string) ($user?->role ?? '')) === 'admin';
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($this->isAdmin($request->user()), 403);
    }

    private function ensureOwner(Request $request, Order $order): void
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);
    }

    /**
     * WhatsApp link to notify admin about an order (wa.me + prefilled text).
     */
    private function buildAdminOrderWhatsAppUrl(Order $order): ?string
    {
        $adminNumber = preg_replace('/\D+/', '', (string) env('ADMIN_WHATSAPP_NUMBER', ''));
        if ($adminNumber === '') {
            return null;
        }

        if (!$order->relationLoaded('items')) {
            $order->load('items.item');
        }
        if (!$order->relationLoaded('user')) {
            $order->load('user');
        }

        $lines = $order->items->map(function ($row) {
            return '- ' . ($row->item->name ?? 'Item') . ' x ' . (int) $row->qty;
        })->implode("\n");

        $name = $order->user->name ?? 'N/A';

        $text = "New Order Request\n"
            . "Order ID: #{$order->id}\n"
            . "User: {$name}\n"
            . 'Status: ' . ucfirst((string) $order->status) . "\n"
            . "Items:\n{$lines}\n"
            . 'Notes: ' . ($order->notes ?: 'N/A');

        return 'https://wa.me/' . $adminNumber . '?text=' . rawurlencode($text);
    }

    public function index(Request $request)
    {
        $this->ensureAuthenticated($request);

        $auth = $request->user();
        $isAdmin = $this->isAdmin($auth);

        $itemImageColExists = Schema::hasColumn('items', 'image');
        $itemColumns = $itemImageColExists ? 'id,name,image' : 'id,name';
        $query = Order::with(['user:id,name,email,profile_photo', 'items.item:' . $itemColumns])->latest();

        if (!$isAdmin) {
            $query->where('user_id', $auth->id);
        }

        $orders = $query->paginate(15);

        if ($isAdmin) {
            Order::where('is_seen_admin', false)->update(['is_seen_admin' => true]);
        }

        return view('orders.index', compact('orders', 'isAdmin'));
    }

    public function create()
    {
        abort_unless(auth()->check(), 403);

        $itemCols = Schema::hasColumn('items', 'image') ? ['id', 'name', 'image'] : ['id', 'name'];
        $items = Item::select($itemCols)->orderBy('name')->get();
        return view('orders.create', compact('items'));
    }

    public function edit(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $order = Order::with('items.item')->findOrFail($this->decryptId((string) $id));
        abort_if($this->isAdmin($request->user()), 403);
        $this->ensureOwner($request, $order);

        $itemCols = Schema::hasColumn('items', 'image') ? ['id', 'name', 'image'] : ['id', 'name'];
        $items = Item::select($itemCols)->orderBy('name')->get();
        return view('orders.edit', compact('order', 'items'));
    }

    public function store(Request $request)
    {
        $this->ensureAuthenticated($request);

        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        $order = DB::transaction(function () use ($request, $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'notes' => $request->notes,
                'status' => 'pending',
                'is_seen_admin' => false,
            ]);

            foreach ($request->items as $row) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $row['item_id'],
                    'qty' => $row['qty'],
                ]);
            }

            return $order;
        });

        $order->load(['items.item', 'user']);
        $whatsAppUrl = $this->buildAdminOrderWhatsAppUrl($order);
        $order->update(['whatsapp_url' => $whatsAppUrl]);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order request sent successfully.')
            ->with('open_whatsapp_url', $whatsAppUrl);
    }

    public function update(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $order = Order::findOrFail($this->decryptId((string) $id));
        abort_if($this->isAdmin($request->user()), 403);
        $this->ensureOwner($request, $order);

        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $order) {
            $order->update([
                'notes' => $request->notes,
            ]);

            OrderItem::where('order_id', $order->id)->delete();

            foreach ($request->items as $row) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $row['item_id'],
                    'qty' => $row['qty'],
                ]);
            }
        });

        $order->refresh();
        $order->load(['items.item', 'user']);
        $order->update(['whatsapp_url' => $this->buildAdminOrderWhatsAppUrl($order)]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * User: open WhatsApp with current order details for admin (manual send / resend).
     */
    public function sendWhatsAppToAdmin(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        abort_if($this->isAdmin($request->user()), 403);

        $order = Order::with(['items.item', 'user'])->findOrFail($this->decryptId((string) $id));
        $this->ensureOwner($request, $order);

        $url = $this->buildAdminOrderWhatsAppUrl($order);
        if (!$url) {
            return back()->with('error', 'Admin WhatsApp number is not configured. Please set ADMIN_WHATSAPP_NUMBER in .env');
        }

        $order->update(['whatsapp_url' => $url]);

        return back()
            ->with('success', 'Opening WhatsApp to send this order to admin.')
            ->with('open_whatsapp_url', $url);
    }

    public function updateStatus(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $this->ensureAdmin($request);

        $request->validate([
            'status' => 'required|in:pending,confirmed,delivered,cancelled',
        ]);

        $order = Order::findOrFail($this->decryptId((string) $id));
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $order = Order::findOrFail($this->decryptId((string) $id));
        if (!$this->isAdmin($request->user())) {
            $this->ensureOwner($request, $order);
        }
        $order->delete();

        return back()->with('success', 'Order deleted successfully.');
    }

    public function print(Request $request, $id)
    {
        $this->ensureAuthenticated($request);

        $order = Order::with(['user', 'items.item', 'creator'])->findOrFail($this->decryptId((string) $id));

        if (!$this->isAdmin($request->user())) {
            $this->ensureOwner($request, $order);
        }

        $adminNumber = preg_replace('/\D+/', '', (string) env('ADMIN_WHATSAPP_NUMBER', ''));
        $shareUrl = null;

        if ($adminNumber !== '') {
            $lines = $order->items->map(function ($row) {
                return '- ' . ($row->item->name ?? 'Item') . ' x ' . (int) $row->qty;
            })->implode("\n");

            $text = "Order Slip Shared\n"
                . "Order ID: #{$order->id}\n"
                . "User: " . ($order->user->name ?? 'N/A') . "\n"
                . "Status: " . ucfirst((string) $order->status) . "\n"
                . "Items:\n{$lines}\n"
                . 'Notes: ' . ($order->notes ?: 'N/A');

            $shareUrl = 'https://wa.me/' . $adminNumber . '?text=' . rawurlencode($text);
        }

        return view('orders.print', compact('order', 'shareUrl'));
    }
}
