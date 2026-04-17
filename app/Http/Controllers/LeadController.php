<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Support\LeadWhatsAppMessage;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private function ensureAdmin(): void
    {
        abort_unless(auth()->check() && strtolower((string) auth()->user()->role) === 'admin', 403);
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        session(['leads_last_seen_at' => now()->toIso8601String()]);

        $query = Lead::query()->latest();
        $status = (string) $request->query('status', '');
        $search = trim((string) $request->query('q', ''));

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('father_name', 'like', "%{$search}%")
                    ->orWhere('id_card', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->get();
        $statuses = ['new', 'contacted', 'approved', 'rejected'];

        return view('leads.index', compact('leads', 'statuses', 'status', 'search'));
    }

    public function update(Request $request, int $id)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'status' => 'required|in:new,contacted,approved,rejected',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update([
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        $leadDigits = preg_replace('/\D+/', '', (string) $lead->phone);
        if (preg_match('/^0\d{10}$/', $leadDigits)) {
            $leadDigits = '92' . substr($leadDigits, 1);
        }

        $leadWaLink = null;
        if ($leadDigits !== '') {
            $note = trim((string) ($data['admin_note'] ?? ''));
            $msg = LeadWhatsAppMessage::statusUpdate(
                $lead,
                (string) $data['status'],
                $note,
            );
            $leadWaLink = 'https://wa.me/' . $leadDigits . '?text=' . rawurlencode($msg);
        }

        return back()
            ->with('success', 'Lead updated successfully.')
            ->with('lead_wa_link', $leadWaLink);
    }

    public function destroy(int $id)
    {
        $this->ensureAdmin();

        Lead::findOrFail($id)->delete();
        return back()->with('success', 'Lead deleted successfully.');
    }
}
