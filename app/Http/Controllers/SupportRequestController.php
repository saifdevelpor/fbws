<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SupportRequest;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SupportRequestController extends Controller
{
    private function decryptId(string $encryptedId): int
    {
        try {
            return (int) Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    private function audit(string $event, string $action, ?SupportRequest $requestItem = null, $old = null, $new = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'module' => 'SupportRequests',
            'action' => $action,
            'auditable_type' => SupportRequest::class,
            'auditable_id' => $requestItem?->id,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    private function ensureAuthenticated(string $intendedRoute)
    {
        if (!auth()->check()) {
            session(['url.intended' => route($intendedRoute)]);

            return redirect()->route('website.help-center', ['login_modal' => 1]);
        }

        return null;
    }

    private function ensureAdmin(): void
    {
        abort_unless(strtolower((string) auth()->user()?->role) === 'admin', 403);
    }

    public function create()
    {
        if ($redirect = $this->ensureAuthenticated('support-requests.create')) {
            return $redirect;
        }

        return view('support-requests.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->ensureAuthenticated('support-requests.create')) {
            return $redirect;
        }

        $data = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|in:medical,education,ration,emergency,other',
            'support_type' => 'required|in:financial,goods,service,volunteer',
            'priority' => 'required|in:low,medium,high,urgent',
            'amount_needed' => 'nullable|numeric|min:0',
            'contact_number' => 'nullable|string|max:30',
            'details' => 'required|string|min:20',
        ]);

        $supportRequest = SupportRequest::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'category' => $data['category'],
            'support_type' => $data['support_type'],
            'priority' => $data['priority'],
            'amount_needed' => $data['amount_needed'] ?? null,
            'contact_number' => $data['contact_number'] ?? null,
            'details' => $data['details'],
            'status' => 'new',
            'is_seen_admin' => false,
        ]);

        $this->audit('created', 'Support Request Submitted', $supportRequest, null, $supportRequest->toArray());

        return redirect()->route('support-requests.mine')->with('success', 'Support request submitted successfully.');
    }

    public function mine()
    {
        if ($redirect = $this->ensureAuthenticated('support-requests.mine')) {
            return $redirect;
        }

        $items = SupportRequest::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('support-requests.mine', compact('items'));
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $items = SupportRequest::query()
            ->with('user:id,name,email,profile_photo')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->priority))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = trim((string) $request->q);
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('details', 'like', "%{$search}%")
                        ->orWhere('contact_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('support-requests.index', compact('items'));
    }

    public function show($supportRequest)
    {
        $this->ensureAdmin();

        $supportRequest = SupportRequest::findOrFail($this->decryptId($supportRequest));

        if (!$supportRequest->is_seen_admin) {
            $supportRequest->forceFill(['is_seen_admin' => true])->save();
        }

        $supportRequest->load('user:id,name,email,phone_number,profile_photo');

        return view('support-requests.show', compact('supportRequest'));
    }

    public function update(Request $request, $supportRequest)
    {
        $this->ensureAdmin();

        $supportRequest = SupportRequest::findOrFail($this->decryptId($supportRequest));

        $data = $request->validate([
            'status' => 'required|in:new,under_review,approved,fulfilled,rejected',
            'priority' => 'required|in:low,medium,high,urgent',
            'admin_note' => 'nullable|string',
        ]);

        $old = $supportRequest->toArray();

        $supportRequest->status = $data['status'];
        $supportRequest->priority = $data['priority'];
        $supportRequest->admin_note = $data['admin_note'] ?? null;
        $supportRequest->is_seen_admin = true;

        if ($data['status'] === 'under_review' && $supportRequest->reviewed_at === null) {
            $supportRequest->reviewed_at = now();
        }

        if (in_array($data['status'], ['fulfilled', 'rejected'], true) && $supportRequest->resolved_at === null) {
            $supportRequest->resolved_at = now();
        }

        if (in_array($data['status'], ['new', 'under_review', 'approved'], true)) {
            $supportRequest->resolved_at = null;
        }

        $supportRequest->save();

        $this->audit('updated', 'Support Request Updated', $supportRequest, $old, $supportRequest->fresh()->toArray());

        return back()->with('success', 'Support request updated successfully.');
    }
}
