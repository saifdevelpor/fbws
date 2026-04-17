<?php

namespace App\Http\Controllers;

use App\Models\Damage;
use App\Models\Item;
use App\Models\User;
use App\Models\AuditLog; // ✅ ADD
use App\Support\Phone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DamageController extends Controller
{
    // ✅ Admin: all records | User: only own records
    public function index()
    {
        $auth = auth()->user();

        $items = Item::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        $damages = strtolower($auth->role) === 'admin'
            ? Damage::with(['user:id,name,profile_photo,phone_number,id_card', 'item:id,name,image'])->latest()->get()
            : Damage::with(['user:id,name,profile_photo,phone_number,id_card', 'item:id,name,image'])->where('user_id', $auth->id)->latest()->get();

        return view('damages.list', compact('damages', 'items', 'users', 'auth'));
    }

    // ✅ Admin only
    public function store(Request $request)
    {
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'items'       => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.fine' => 'required|numeric|min:0',
            'damage_date' => 'nullable|date',
            'note'        => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $createdCount = 0;

        foreach ($request->input('items', []) as $row) {
            $damage = Damage::create([
                'user_id' => $request->user_id,
                'item_id' => $row['item_id'],
                'qty' => $row['qty'],
                'fine' => $row['fine'],
                'damage_date' => $request->damage_date,
                'note' => $request->note,
            ]);

            // ✅ AUDIT LOG (CREATE)
            AuditLog::create([
                'user_id' => auth()->id(),
                'event' => 'created',
                'module' => 'Damages',
                'action' => 'Damage Record Added',
                'auditable_type' => Damage::class,
                'auditable_id' => $damage->id,
                'old_values' => null,
                'new_values' => $damage->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ]);

            $createdCount++;
        }

        return redirect()->back()->with('success', $createdCount . ' damage record(s) added successfully!');
    }

    // ✅ Admin only
    public function update(Request $request, $id)
    {
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $damage = Damage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'item_id'     => 'required|exists:items,id',
            'qty'         => 'required|integer|min:1',
            'fine'        => 'required|numeric|min:0',
            'damage_date' => 'nullable|date',
            'note'        => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ OLD VALUES before update
        $old = $damage->toArray();

        $damage->update([
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'qty' => $request->qty,
            'fine' => $request->fine,
            'damage_date' => $request->damage_date,
            'note' => $request->note,
        ]);

        // ✅ AUDIT LOG (UPDATE)
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => 'updated',
            'module' => 'Damages',
            'action' => 'Damage Record Updated',
            'auditable_type' => Damage::class,
            'auditable_id' => $damage->id,
            'old_values' => $old,
            'new_values' => $damage->fresh()->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        return redirect()->back()->with('success', 'Damage record updated successfully!');
    }

    // ✅ Admin only
    public function delete($id)
    {
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $damage = Damage::findOrFail($id);

        // ✅ OLD VALUES before delete
        $old = $damage->toArray();
        $damageId = $damage->id;

        $damage->delete();

        // ✅ AUDIT LOG (DELETE)
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => 'deleted',
            'module' => 'Damages',
            'action' => 'Damage Record Deleted',
            'auditable_type' => Damage::class,
            'auditable_id' => $damageId,
            'old_values' => $old,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        return redirect()->back()->with('success', 'Damage record deleted successfully!');
    }

    // ✅ Admin only (bulk/group delete)
    public function deleteGroup(Request $request)
    {
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $ids = collect($request->input('ids', []))
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return redirect()->back()->with('error', 'No damage records selected for delete.');
        }

        $rows = Damage::whereIn('id', $ids->all())->get();
        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'Selected damage records not found.');
        }

        $oldValues = $rows->map(fn($r) => $r->toArray())->values()->all();

        Damage::whereIn('id', $ids->all())->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => 'deleted',
            'module' => 'Damages',
            'action' => 'Damage Group Deleted',
            'auditable_type' => Damage::class,
            'auditable_id' => null,
            'old_values' => $oldValues,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        return redirect()->back()->with('success', $rows->count() . ' damage record(s) deleted successfully!');
    }

    public function print(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))
            ->map(fn($id) => (int) trim($id))
            ->filter(fn($id) => $id > 0)
            ->unique()
            ->values();

        abort_if($ids->isEmpty(), 404);

        $auth = auth()->user();
        $isAdmin = strtolower((string) ($auth->role ?? '')) === 'admin';

        $query = Damage::with(['user:id,name,profile_photo,phone_number,id_card', 'item:id,name,image'])
            ->whereIn('id', $ids->all());

        if (!$isAdmin) {
            $query->where('user_id', $auth->id);
        }

        $damages = $query->orderBy('id')->get();
        abort_if($damages->isEmpty(), 404);

        $first = $damages->first();
        $items = $damages->map(function ($d) {
            return [
                'name' => $d->item->name ?? 'NA',
                'qty' => (int) $d->qty,
                'fine' => (float) $d->fine,
            ];
        });

        $summaryLines = $items->map(fn($r) => "{$r['name']} (Qty: {$r['qty']}, Fine: Rs " . (int) $r['fine'] . ')')->implode("\n");
        $printUrl = route('damage-print', ['ids' => $ids->implode(',')]);
        $waText = "Damage Details\n"
            . "User: " . ($first->user->name ?? 'NA') . "\n"
            . "Date: " . ($first->damage_date ?? 'NA') . "\n"
            . "Total Fine: Rs " . (int) $damages->sum('fine') . "\n\n"
            . "Items:\n" . $summaryLines . "\n\n"
            . "Print Link: " . $printUrl;

        $waLink = null;
        if (!empty($first->user?->phone_number)) {
            $phone = Phone::toWhatsapp((string) $first->user->phone_number);
            $waLink = "https://wa.me/{$phone}?text=" . rawurlencode($waText);
        }

        return view('damages.print', [
            'damages' => $damages,
            'first' => $first,
            'totalQty' => (int) $damages->sum('qty'),
            'totalFine' => (float) $damages->sum('fine'),
            'waLink' => $waLink,
        ]);
    }
}
