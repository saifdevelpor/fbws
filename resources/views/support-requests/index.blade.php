@extends('home')

@section('title')
    <title>Support Requests | FBWS</title>
@endsection

@section('content')
    <style>
        :root { --accent:#F7721E; --line:#e5e7eb; --muted:#64748b; --shadow:0 12px 34px rgba(15,23,42,.06); }
        .page-wrap { padding: 14px; }
        .cardx { background:#fff; border:1px solid var(--line); border-radius:18px; box-shadow:var(--shadow); }
        .pill { border-radius:999px; padding:6px 10px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:6px; }
        .person { display:flex; align-items:center; gap:10px; min-width:200px; }
        .person .avatar { width:36px; height:36px; border-radius:999px; object-fit:cover; border:1px solid rgba(15,23,42,.08); }
    </style>

    <div class="page-wrap">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h4 class="mb-1 fw-bold">Support Requests</h4>
                <div class="text-muted small">Admin panel for welfare help cases, priorities, and approval workflow.</div>
            </div>
            <a href="{{ route('admin.support-requests.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>

        <div class="cardx p-3 mb-3">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All status</option>
                        <option value="new" @selected(request('status') === 'new')>New</option>
                        <option value="under_review" @selected(request('status') === 'under_review')>Under Review</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="fulfilled" @selected(request('status') === 'fulfilled')>Fulfilled</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="">All priority</option>
                        <option value="low" @selected(request('priority') === 'low')>Low</option>
                        <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                        <option value="high" @selected(request('priority') === 'high')>High</option>
                        <option value="urgent" @selected(request('priority') === 'urgent')>Urgent</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All category</option>
                        <option value="medical" @selected(request('category') === 'medical')>Medical</option>
                        <option value="education" @selected(request('category') === 'education')>Education</option>
                        <option value="ration" @selected(request('category') === 'ration')>Ration</option>
                        <option value="emergency" @selected(request('category') === 'emergency')>Emergency</option>
                        <option value="other" @selected(request('category') === 'other')>Other</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Title, details or number">
                </div>
                <div class="col-12 d-grid d-md-flex justify-content-md-end">
                    <button class="btn text-white" style="background:#F7721E;"><i class="ti ti-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <div class="cardx">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Open</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            @php
                                $priorityMap = [
                                    'low' => ['bg' => 'rgba(148,163,184,.14)', 'text' => '#475569'],
                                    'medium' => ['bg' => 'rgba(245,158,11,.12)', 'text' => '#b45309'],
                                    'high' => ['bg' => 'rgba(249,115,22,.12)', 'text' => '#c2410c'],
                                    'urgent' => ['bg' => 'rgba(239,68,68,.12)', 'text' => '#b91c1c'],
                                ];
                                $statusMap = [
                                    'new' => ['bg' => 'rgba(245,158,11,.12)', 'text' => '#92400e', 'label' => 'New'],
                                    'under_review' => ['bg' => 'rgba(59,130,246,.12)', 'text' => '#1d4ed8', 'label' => 'Under Review'],
                                    'approved' => ['bg' => 'rgba(16,185,129,.12)', 'text' => '#047857', 'label' => 'Approved'],
                                    'fulfilled' => ['bg' => 'rgba(34,197,94,.12)', 'text' => '#166534', 'label' => 'Fulfilled'],
                                    'rejected' => ['bg' => 'rgba(239,68,68,.12)', 'text' => '#b91c1c', 'label' => 'Rejected'],
                                ];
                                $avatar = $item->user?->profile_photo ? asset($item->user->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif');
                            @endphp
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>{{ $item->created_at?->format('d M Y') }}</td>
                                <td>
                                    <div class="person">
                                        <img src="{{ $avatar }}" alt="avatar" class="avatar">
                                        <div>
                                            <div class="fw-semibold">{{ $item->user?->name ?? 'User' }}</div>
                                            <div class="small text-muted">{{ $item->contact_number ?: ($item->user?->email ?? 'No contact') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->title }}</div>
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::words($item->details, 10, '...') }}</div>
                                </td>
                                <td class="text-capitalize">{{ $item->category }}</td>
                                <td><span class="pill" style="background:{{ $priorityMap[$item->priority]['bg'] ?? 'rgba(245,158,11,.12)' }};color:{{ $priorityMap[$item->priority]['text'] ?? '#b45309' }};">{{ ucfirst($item->priority) }}</span></td>
                                <td><span class="pill" style="background:{{ $statusMap[$item->status]['bg'] ?? 'rgba(245,158,11,.12)' }};color:{{ $statusMap[$item->status]['text'] ?? '#92400e' }};">{{ $statusMap[$item->status]['label'] ?? ucfirst($item->status) }}</span></td>
                                <td>{{ $item->amount_needed ? 'Rs ' . number_format((float) $item->amount_needed, 0) : '-' }}</td>
                                <td><a href="{{ route('admin.support-requests.show', \Illuminate\Support\Facades\Crypt::encryptString((string) $item->id)) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No support requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">{{ $items->links() }}</div>
    </div>
@endsection
