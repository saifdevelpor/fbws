@extends('home')

@section('title')
    <title>View admin Complaint / Suggestion | FBWS</title>
@endsection

@section('content')
    <style>
        :root {
            --accent: #F7721E;
            --muted: #6b7280;
            --line: #e9eef6;
            --shadow: 0 10px 30px rgba(2, 6, 23, .06);
            --r: 16px;
        }

        .page-wrap {
            padding: 14px;
        }

        .cardx {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--r);
            box-shadow: var(--shadow);
        }

        .page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .page-head h4 {
            margin: 0;
            font-weight: 900;
            color: #0f172a;
        }

        .page-head .sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        .filter-bar {
            padding: 12px;
        }

        .filter-bar .form-select,
        .filter-bar .form-control {
            border-radius: 12px;
            border: 1px solid var(--line);
            font-size: 14px;
            padding: 10px 12px;
        }

        .btn-accent {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 700;
        }

        .btn-accent:hover {
            opacity: .92;
            color: #fff;
        }

        .btn-outline-soft {
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 10px 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .table thead th {
            font-size: 12px;
            color: #334155;
            background: #f8fafc;
            border-bottom: 1px solid var(--line) !important;
            padding: 12px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .pill-badge {
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .2px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pill-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            display: inline-block;
        }

        .from-user {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 160px;
        }

        .from-user .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            object-fit: cover;
            border: 1px solid rgba(15, 23, 42, .08);
        }

        .from-user .name {
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .from-user .meta {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .subject-col {
            max-width: 420px;
        }

        .subject-col .subject {
            font-weight: 700;
            color: #0f172a;
        }

        .subject-col .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Mobile polish */
        @media (max-width: 576px) {
            .page-head {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar {
                padding: 10px;
            }

            .subject-col {
                max-width: 260px;
            }
        }
    </style>

    <div class="page-wrap">

        {{-- Header --}}
        <div class="page-head">
            <div>
                <h4>Complaints & Suggestions</h4>
                <div class="sub">Admin panel — filter aur complaints/suggestions ko manage karein.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.complaints.index') }}" class="btn-outline-soft">
                    <i class="ti ti-refresh me-1"></i> Reset
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="cardx filter-bar mb-3">
            <form class="row g-2 align-items-end" method="GET">

                <!-- TYPE -->
                <div class="col-12 col-md-2">
                    <label class="form-label mb-1 fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="complaint" @selected(request('type') === 'complaint')>Complaint</option>
                        <option value="suggestion" @selected(request('type') === 'suggestion')>Suggestion</option>
                    </select>
                </div>

                <!-- STATUS -->
                <div class="col-12 col-md-2">
                    <label class="form-label mb-1 fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="new" @selected(request('status') === 'new')>New</option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                        <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                        <option value="closed" @selected(request('status') === 'closed')>Closed</option>
                    </select>
                </div>

                <!-- SEEN / UNSEEN -->
                <div class="col-12 col-md-2">
                    <label class="form-label mb-1 fw-semibold">Seen</label>
                    <select name="seen" class="form-select">
                        <option value="">All</option>
                        <option value="1" @selected(request('seen') === '1')>Seen</option>
                        <option value="0" @selected(request('seen') === '0')>Unseen</option>
                    </select>
                </div>

                <!-- SEARCH -->
                <div class="col-12 col-md-4">
                    <label class="form-label mb-1 fw-semibold">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Search subject / message...">
                </div>

                <!-- BUTTON -->
                <div class="col-12 col-md-2 d-grid">
                    <button class="btn-accent">
                        <i class="ti ti-filter me-1"></i> Filter
                    </button>
                </div>

            </form>
        </div>

        {{-- Table --}}
        <div class="cardx">
            <div class="p-3 border-bottom" style="border-color: var(--line) !important;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="fw-bold" style="color:#0f172a;">
                        Total: {{ $complaints->total() ?? $complaints->count() }}
                    </div>
                    <div class="small text-muted">
                        Latest records shown below
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0" id="myTable1">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th style="width:120px;">Date</th>
                                <th>From</th>
                                <th style="width:130px;">Type</th>
                                <th>Subject</th>
                                <th style="width:150px;">Status</th>
                                <th style="width:90px;">View</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($complaints as $index => $c)
                                @php
                                    $type = strtolower($c->type ?? '');
                                    $typeDot = $type === 'complaint' ? '#ef4444' : '#3b82f6';
                                    $typeBg = $type === 'complaint' ? 'rgba(239,68,68,.10)' : 'rgba(59,130,246,.10)';
                                    $typeText = $type === 'complaint' ? '#b91c1c' : '#1d4ed8';

                                    $status = strtolower($c->status ?? 'new');
                                    $statusMap = [
                                        'new' => [
                                            'dot' => '#f59e0b',
                                            'bg' => 'rgba(245,158,11,.12)',
                                            'text' => '#92400e',
                                            'label' => 'New',
                                        ],
                                        'in_progress' => [
                                            'dot' => '#0ea5e9',
                                            'bg' => 'rgba(14,165,233,.12)',
                                            'text' => '#075985',
                                            'label' => 'In Progress',
                                        ],
                                        'resolved' => [
                                            'dot' => '#22c55e',
                                            'bg' => 'rgba(34,197,94,.12)',
                                            'text' => '#166534',
                                            'label' => 'Resolved',
                                        ],
                                        'closed' => [
                                            'dot' => '#64748b',
                                            'bg' => 'rgba(100,116,139,.12)',
                                            'text' => '#334155',
                                            'label' => 'Closed',
                                        ],
                                    ];
                                    $st = $statusMap[$status] ?? $statusMap['new'];

                                    $avatar = $c->user?->profile_photo
                                        ? asset($c->user->profile_photo)
                                        : asset('assets/img/avatars/defualt_profile_imgavif.avif');

                                    $fromName = $c->is_anonymous
                                        ? 'Anonymous'
                                        : optional($c->user)->name ?? 'User deleted';
                                    $fromMeta = $c->is_anonymous ? 'Hidden user' : optional($c->user)->email ?? '—';
                                @endphp

                                <tr>
                                    <td class="fw-semibold">{{ $index + 1 }}</td>

                                    <td class="text-muted">
                                        {{ $c->created_at ? $c->created_at->format('d M Y') : 'NA' }}
                                    </td>

                                    <td>
                                        <div class="from-user">
                                            @if ($c->is_anonymous)
                                                <img src="{{ asset('assets/img/avatars/defualt_profile_imgavif.avif') }}"
                                                    class="avatar" alt="Anonymous">
                                            @else
                                                <img src="{{ $avatar }}" class="avatar" alt="Profile">
                                            @endif

                                            <div>
                                                <div class="name">{{ $fromName }}</div>
                                                <div class="meta">{{ $fromMeta }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="pill-badge"
                                            style="background: {{ $typeBg }}; color: {{ $typeText }};">
                                            <span class="dot" style="background: {{ $typeDot }};"></span>
                                            {{ ucfirst($type) }}
                                        </span>
                                    </td>

                                    <td class="subject-col">
                                        <div class="subject">
                                            {{ $c->subject ?? 'NA' }}
                                        </div>
                                        <div class="hint">
                                            {{ \Illuminate\Support\Str::words($c->message ?? '', 10, '...') }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="pill-badge"
                                            style="background: {{ $st['bg'] }}; color: {{ $st['text'] }};">
                                            <span class="dot" style="background: {{ $st['dot'] }};"></span>
                                            {{ $st['label'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('admin.complaints.show', \Illuminate\Support\Facades\Crypt::encryptString((string) $c->id)) }}">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $complaints->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
