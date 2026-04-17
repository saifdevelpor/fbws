@extends('home')

@section('title')
    <title>My Support Requests | FBWS</title>
@endsection

@section('content')
    <style>
        .support-page { max-width: 1180px; margin: 0 auto; }
        .support-card { border:1px solid #e5e7eb; border-radius:18px; overflow:hidden; box-shadow:0 14px 34px rgba(15,23,42,.06); }
        .support-head { padding:16px 18px; background:linear-gradient(180deg,#fff7f2 0%, #ffffff 100%); border-bottom:1px solid #eef2f7; }
        .status-pill, .priority-pill { border-radius:999px; padding:6px 10px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:6px; }
        .detail-box { border:1px solid #eef2f7; border-radius:12px; padding:10px 12px; background:#fff; }
        .detail-label { font-size:12px; color:#64748b; margin-bottom:4px; }
        .detail-value { font-weight:700; color:#0f172a; }
    </style>

    <div class="container mt-3 support-page">
        <div class="support-card bg-white">
            <div class="support-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1 fw-bold">My Support Requests</h4>
                    <div class="text-muted small">Yahan aap apni submitted welfare help requests ka status dekh sakte hain.</div>
                </div>
                <a href="{{ route('support-requests.create') }}" class="btn btn-sm px-3" style="background:#F7721E;color:#fff;border:none;">
                    <i class="ti ti-plus me-1"></i> New Request
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Admin Note</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            @php
                                $statusMap = [
                                    'new' => ['bg' => 'rgba(245,158,11,.12)', 'text' => '#92400e', 'label' => 'New'],
                                    'under_review' => ['bg' => 'rgba(59,130,246,.12)', 'text' => '#1d4ed8', 'label' => 'Under Review'],
                                    'approved' => ['bg' => 'rgba(16,185,129,.12)', 'text' => '#047857', 'label' => 'Approved'],
                                    'fulfilled' => ['bg' => 'rgba(34,197,94,.12)', 'text' => '#166534', 'label' => 'Fulfilled'],
                                    'rejected' => ['bg' => 'rgba(239,68,68,.12)', 'text' => '#b91c1c', 'label' => 'Rejected'],
                                ];
                                $priorityMap = [
                                    'low' => ['bg' => 'rgba(148,163,184,.14)', 'text' => '#475569'],
                                    'medium' => ['bg' => 'rgba(245,158,11,.12)', 'text' => '#b45309'],
                                    'high' => ['bg' => 'rgba(249,115,22,.12)', 'text' => '#c2410c'],
                                    'urgent' => ['bg' => 'rgba(239,68,68,.12)', 'text' => '#b91c1c'],
                                ];
                                $st = $statusMap[$item->status] ?? $statusMap['new'];
                                $pr = $priorityMap[$item->priority] ?? $priorityMap['medium'];
                            @endphp
                            <tr>
                                <td>{{ $items->firstItem() + $index }}</td>
                                <td>{{ $item->created_at?->format('d M Y') }}</td>
                                <td class="fw-semibold">{{ $item->title }}</td>
                                <td class="text-capitalize">{{ $item->category }}</td>
                                <td><span class="priority-pill" style="background:{{ $pr['bg'] }};color:{{ $pr['text'] }};">{{ ucfirst($item->priority) }}</span></td>
                                <td><span class="status-pill" style="background:{{ $st['bg'] }};color:{{ $st['text'] }};">{{ $st['label'] }}</span></td>
                                <td>
                                    @if ($item->admin_note)
                                        <span class="badge bg-success-subtle text-success">Updated</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Waiting</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#supportModal{{ $item->id }}">View</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No support requests submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">{{ $items->links() }}</div>
    </div>

    @foreach ($items as $item)
        <div class="modal fade" id="supportModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">Support Request Detail</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6"><div class="detail-box"><div class="detail-label">Title</div><div class="detail-value">{{ $item->title }}</div></div></div>
                            <div class="col-md-3"><div class="detail-box"><div class="detail-label">Category</div><div class="detail-value text-capitalize">{{ $item->category }}</div></div></div>
                            <div class="col-md-3"><div class="detail-box"><div class="detail-label">Support Type</div><div class="detail-value text-capitalize">{{ str_replace('_', ' ', $item->support_type) }}</div></div></div>
                            <div class="col-md-4"><div class="detail-box"><div class="detail-label">Priority</div><div class="detail-value text-capitalize">{{ $item->priority }}</div></div></div>
                            <div class="col-md-4"><div class="detail-box"><div class="detail-label">Status</div><div class="detail-value text-capitalize">{{ str_replace('_', ' ', $item->status) }}</div></div></div>
                            <div class="col-md-4"><div class="detail-box"><div class="detail-label">Amount Needed</div><div class="detail-value">{{ $item->amount_needed ? 'Rs ' . number_format((float) $item->amount_needed, 2) : 'Not specified' }}</div></div></div>
                            <div class="col-12"><div class="detail-box"><div class="detail-label">Details</div><div class="detail-value" style="white-space:pre-wrap;">{{ $item->details }}</div></div></div>
                            <div class="col-12"><div class="detail-box"><div class="detail-label">Admin Note</div><div class="detail-value" style="white-space:pre-wrap;">{{ $item->admin_note ?: 'Admin ne abhi note add nahin kiya.' }}</div></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
