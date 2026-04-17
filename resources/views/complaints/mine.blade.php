@extends('home')

@section('title')
    <title>View User Complaint / Suggestion | FBWS</title>
@endsection
@section('content')
    <style>
        .suggestion-shell {
            max-width: 1200px;
            margin: 0 auto;
        }

        .suggestion-card {
            border: 1px solid #eef2f7;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        }

        .suggestion-header {
            background: linear-gradient(180deg, #fff7f2 0%, #ffffff 100%);
            border-bottom: 1px solid #eef2f7;
            padding: 14px 16px;
        }

        .suggestion-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .suggestion-subtitle {
            margin: 2px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .suggestion-table thead th {
            background: #f8fafc;
            color: #334155;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid #e2e8f0;
        }

        .suggestion-table td {
            vertical-align: middle;
            border-color: #eef2f7;
        }

        .subject-cell {
            font-weight: 700;
            color: #0f172a;
            max-width: 240px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .status-pill {
            text-transform: capitalize;
        }

        .reply-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 999px;
        }

        .reply-pill.pending {
            background: #f1f5f9;
            color: #475569;
        }

        .reply-pill.replied {
            background: #dcfce7;
            color: #166534;
        }

        .empty-wrap {
            text-align: center;
            padding: 30px 15px;
            color: #64748b;
        }

        .detail-block {
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 10px;
            background: #fff;
        }

        .detail-label {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .detail-value {
            color: #0f172a;
            font-weight: 700;
        }
    </style>

    <div class="container mt-3 suggestion-shell">
        <div class="suggestion-card bg-white">
            <div class="suggestion-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="suggestion-title">My Complaints & Suggestions</h4>
                    <p class="suggestion-subtitle">Track status, admin reply, and full details.</p>
                </div>
                <a href="{{ route('complaints.create') }}" class="btn btn-sm px-3"
                    style="background:#F7721E;color:#fff;border:none;">
                    <i class="ti ti-plus me-1"></i> New
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table suggestion-table mb-0" id="myTable1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Admin Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $c)
                                @php
                                    $typeClass = $c->type == 'complaint' ? 'danger' : 'primary';
                                    $statusClass = match ($c->status) {
                                        'new' => 'warning',
                                        'in_progress' => 'info',
                                        'resolved' => 'success',
                                        'closed' => 'secondary',
                                        default => 'dark',
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $c->created_at->format('d M Y') }}</td>
                                    <td><span class="badge bg-{{ $typeClass }}">{{ ucfirst($c->type) }}</span></td>
                                    <td class="subject-cell">{{ $c->subject }}</td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }} status-pill">
                                            {{ str_replace('_', ' ', $c->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($c->admin_note)
                                            <span class="reply-pill replied">Replied</span>
                                        @else
                                            <span class="reply-pill pending">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"
                                            data-bs-target="#complaintModal{{ $c->id }}">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-wrap">
                                        <i class="ti ti-message-circle-2" style="font-size:24px;"></i>
                                        <div class="mt-2">No complaints or suggestions submitted yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>


    <!-- ================= MODALS OUTSIDE TABLE ================= -->
    @foreach ($items as $c)
        @php
            $typeClass = $c->type == 'complaint' ? 'danger' : 'primary';

            $statusClass = match ($c->status) {
                'new' => 'warning',
                'in_progress' => 'info',
                'resolved' => 'success',
                'closed' => 'secondary',
                default => 'dark',
            };
        @endphp

        <div class="modal fade" id="complaintModal{{ $c->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">Detail View</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            <span class="badge bg-{{ $typeClass }}">{{ ucfirst($c->type) }}</span>
                            <span class="badge bg-{{ $statusClass }}">{{ str_replace('_', ' ', $c->status) }}</span>
                        </div>

                        <div class="detail-block">
                            <div class="detail-label">Subject</div>
                            <div class="detail-value">{{ $c->subject }}</div>
                        </div>

                        <div class="detail-block">
                            <div class="detail-label">Message</div>
                            <div class="detail-value" style="white-space:pre-wrap; font-weight:600;">
                                {{ $c->message }}
                            </div>
                        </div>

                        <div class="detail-block mb-0">
                            <div class="detail-label">Admin Response</div>
                            @if ($c->admin_note)
                                <div class="alert alert-info mb-0 py-2">
                                    {{ $c->admin_note }}
                                </div>
                            @else
                                <div class="text-muted">Admin ne abhi reply nahi diya.</div>
                            @endif
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach


    <!-- Bootstrap JS REQUIRED -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
