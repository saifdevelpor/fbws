<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // ✅ Admin only
        abort_unless(auth()->check() && strtolower(auth()->user()->role) === 'admin', 403);

        $q = AuditLog::with('user');

        // -------- FILTERS --------

        if ($request->filled('module')) {
            $q->where('module', $request->module);
        }

        if ($request->filled('event')) {
            $q->where('event', $request->event);
        }

        if ($request->filled('user_id')) {
            $q->where('user_id', $request->user_id);
        }

        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $q->where(function ($sub) use ($search) {
                $sub->where('action', 'like', "%$search%")
                    ->orWhere('module', 'like', "%$search%")
                    ->orWhere('auditable_type', 'like', "%$search%")
                    ->orWhere('auditable_id', 'like', "%$search%");
            });
        }

        // -------- RESULT --------
        $logs = $q->latest()->paginate(10);

        // -------- FILTER DATA --------
        $modules = AuditLog::select('module')->distinct()->pluck('module');
        $events  = AuditLog::select('event')->distinct()->pluck('event');
        $users   = User::select('id', 'name')->orderBy('name')->get();

        $filters = $request->only([
            'module',
            'event',
            'user_id',
            'q',
            'from',
            'to',
        ]);

        return view('log.index', compact(
            'logs',
            'modules',
            'events',
            'users',
            'filters'
        ));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:audit_logs,id'
        ]);

        AuditLog::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' logs deleted successfully');
    }
}
