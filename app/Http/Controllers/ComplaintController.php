<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\AuditLog;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ComplaintController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(strtolower((string) auth()->user()?->role) === 'admin', 403);
    }

    private function decryptId(string $encryptedId): int
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function create()
    {
        if (!auth()->check()) {
            session(['url.intended' => route('complaints.create')]);

            return redirect()->route('website.help-center', ['login_modal' => 1]);
        }

        return view('complaints.create');
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            session(['url.intended' => route('complaints.create')]);

            return redirect()->route('website.help-center', ['login_modal' => 1]);
        }

        $data = $request->validate([
            'type' => 'required|in:complaint,suggestion',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|min:10',
            'is_anonymous' => 'nullable|boolean',
            'is_seen'   => 'nullable|boolean',
        ]);

        $isAnonymous = (bool) ($data['is_anonymous'] ?? false);

        $complaint = Complaint::create([
            'user_id' => $isAnonymous ? null : auth()->id(),
            'type' => $data['type'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'is_anonymous' => $isAnonymous,
            'status' => 'new',
            'is_seen' => false
        ]);

        // ✅ AUDIT LOG (CREATE)
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => 'created',
            'module' => 'Complaints',
            'action' => 'Complaint Submitted',
            'auditable_type' => Complaint::class,
            'auditable_id' => $complaint->id,
            'old_values' => null,
            'new_values' => $complaint->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        return redirect()->route('complaints.mine')->with('success', 'Submitted successfully!');
    }

    public function myComplaints()
    {
        if (!auth()->check()) {
            session(['url.intended' => route('complaints.mine')]);

            return redirect()->route('website.help-center', ['login_modal' => 1]);
        }

        $items = Complaint::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('complaints.mine', compact('items'));
    }

    // ADMIN
    public function index(Request $request)
    {
        $this->ensureAdmin();

        $q = Complaint::query();

        // ✅ Status filter
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        // ✅ Type filter
        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }

        // ✅ Seen / Unseen filter (0 / 1)
        // Note: filled('seen') works for '0' too, so OK.
        if ($request->filled('seen')) {
            $q->where('is_seen', (int) $request->seen);
        }

        // ✅ Search filter
        // Your blade uses name="q", but to be safe we also accept name="search"
        $search = $request->input('q', $request->input('search'));

        if (!empty($search)) {
            $q->where(function ($sub) use ($search) {
                $sub->where('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $complaints = $q->latest()->paginate(15)->withQueryString();

        return view('complaints.index', compact('complaints'));
    }
    public function show($complaint)
    {
        $this->ensureAdmin();

        $complaint = Complaint::findOrFail($this->decryptId($complaint));

        $role = strtolower(auth()->user()->role ?? '');

        if ($role === 'admin' && !$complaint->is_seen) {
            $complaint->is_seen = 1;
            $complaint->save();
        }

        return view('complaints.show', compact('complaint'));
    }

    public function update(Request $request, $complaint)
    {
        $this->ensureAdmin();

        $complaint = Complaint::findOrFail($this->decryptId($complaint));

        $data = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'admin_note' => 'nullable|string',
        ]);

        // ✅ OLD VALUES before update
        $old = $complaint->toArray();

        $complaint->status = $data['status'];
        $complaint->admin_note = $data['admin_note'] ?? null;

        if ($data['status'] === 'resolved' && $complaint->resolved_at === null) {
            $complaint->resolved_at = now();
        }

        $complaint->save();

        // ✅ AUDIT LOG (UPDATE)
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => 'updated',
            'module' => 'Complaints',
            'action' => 'Complaint Updated',
            'auditable_type' => Complaint::class,
            'auditable_id' => $complaint->id,
            'old_values' => $old,
            'new_values' => $complaint->fresh()->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        return back()->with('success', 'Updated!');
    }

    // ✅ OPTIONAL: Delete function + log
    public function destroy(Complaint $complaint)
    {
        $old = $complaint->toArray();
        $id = $complaint->id;

        $complaint->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => 'deleted',
            'module' => 'Complaints',
            'action' => 'Complaint Deleted',
            'auditable_type' => Complaint::class,
            'auditable_id' => $id,
            'old_values' => $old,
            'new_values' => null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);

        return back()->with('success', 'Deleted!');
    }
}
