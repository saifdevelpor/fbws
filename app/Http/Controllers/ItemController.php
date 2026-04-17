<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\AuditLog; // ✅ ADD
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    private function audit(string $event, string $action, ?int $auditableId, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'Items',
            'action' => $action,
            'auditable_type' => Item::class,
            'auditable_id' => $auditableId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    public function index()
    {
        $items = Item::latest()->get();
        return view('items.list', compact('items'));
    }

    public function store(Request $request)
    {
        // ✅ OPTIONAL: Admin only (uncomment if needed)
        // abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'qty'   => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item = new Item();
        $item->name = $request->name;
        $item->qty  = $request->qty;

        if ($request->hasFile('image')) {
            $path = public_path('uploads/items');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move($path, $imageName);
            $item->image = 'uploads/items/' . $imageName;
        }

        $item->save();

        // ✅ AUDIT LOG (CREATE)
        $this->audit('created', 'Item Created', $item->id, null, $item->toArray());

        return redirect()->back()->with('success', 'Item saved successfully!');
    }

    public function update(Request $request, $id)
    {
        // ✅ OPTIONAL: Admin only (uncomment if needed)
        // abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $item = Item::findOrFail($id);

        // ✅ OLD snapshot before update
        $old = $item->toArray();

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'qty'   => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $item->name = $request->name;
        $item->qty  = $request->qty;

        // 🔹 Item Image Update
        if ($request->hasFile('image')) {

            if ($item->image && file_exists(public_path($item->image))) {
                unlink(public_path($item->image));
            }

            $path = public_path('uploads/items');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move($path, $imageName);
            $item->image = 'uploads/items/' . $imageName;
        }

        $item->save();

        // ✅ AUDIT LOG (UPDATE)
        $this->audit('updated', 'Item Updated', $item->id, $old, $item->fresh()->toArray());

        return redirect()->back()->with('success', 'Item updated successfully!');
    }

    public function delete($id)
    {
        // ✅ OPTIONAL: Admin only (uncomment if needed)
        // abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $item = Item::findOrFail($id);

        // ✅ OLD snapshot before delete
        $old = $item->toArray();
        $itemId = $item->id;

        if ($item->image && file_exists(public_path($item->image))) {
            unlink(public_path($item->image));
        }

        $item->delete();

        // ✅ AUDIT LOG (DELETE)
        $this->audit('deleted', 'Item Deleted', $itemId, $old, null);

        return redirect()->back()->with('success', 'Item deleted successfully!');
    }
}
