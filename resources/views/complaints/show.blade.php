@extends('home')

@section('title')
    <title>View Details Complaint / Suggestion | FBWS</title>
@endsection

@section('content')
    <style>
        :root {
            --accent: #F7721E;
            --text: #0f172a;
            --muted: #6b7280;
            --line: #e9eef6;
            --shadow: 0 10px 30px rgba(2, 6, 23, .06);
            --r: 16px;
        }

        body {
            background: #f6f8fc;
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

        .headbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .headbar h4 {
            margin: 0;
            font-weight: 900;
            color: var(--text);
        }

        .sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        .btn-accent {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 800;
        }

        .btn-accent:hover {
            opacity: .92;
            color: #fff;
        }

        .btn-soft {
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 10px 14px;
            font-weight: 800;
            color: var(--text);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .2px;
            border: 1px solid rgba(15, 23, 42, .08);
            background: #fff;
        }

        .pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            display: inline-block;
        }

        .grid-info {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .info-box {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 12px;
            background: #fff;
            height: 100%;
        }

        .info-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 800;
            margin-bottom: 6px;
        }

        .info-value {
            color: var(--text);
            font-weight: 900;
        }

        .block {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            background: #fff;
        }

        .block-title {
            font-size: 12px;
            color: var(--muted);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .message-box {
            line-height: 1.8;
            color: #0f172a;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .section-head {
            padding: 14px 14px 0 14px;
        }

        .section-head h5 {
            margin: 0;
            font-weight: 900;
            color: var(--text);
        }

        .section-head small {
            color: var(--muted);
        }

        .form-select,
        .form-control {
            border-radius: 12px;
            border: 1px solid var(--line);
            padding: 10px 12px;
        }

        .note-hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }

        @media (max-width: 992px) {
            .grid-info {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {

            .btn-accent,
            .btn-soft {
                width: 100%;
            }
        }
    </style>

    <div class="page-wrap" style="max-width: 980px;margin:0 auto;">

        @php
            $status = strtolower($complaint->status ?? 'new');
            $statusMap = [
                'new' => ['dot' => '#f59e0b', 'bg' => 'rgba(245,158,11,.12)', 'text' => '#92400e', 'label' => 'NEW'],
                'in_progress' => [
                    'dot' => '#0ea5e9',
                    'bg' => 'rgba(14,165,233,.12)',
                    'text' => '#075985',
                    'label' => 'IN PROGRESS',
                ],
                'resolved' => [
                    'dot' => '#22c55e',
                    'bg' => 'rgba(34,197,94,.12)',
                    'text' => '#166534',
                    'label' => 'RESOLVED',
                ],
                'closed' => [
                    'dot' => '#64748b',
                    'bg' => 'rgba(100,116,139,.12)',
                    'text' => '#334155',
                    'label' => 'CLOSED',
                ],
            ];
            $st = $statusMap[$status] ?? $statusMap['new'];

            $type = strtolower($complaint->type ?? 'complaint');
            $typeMap = [
                'complaint' => [
                    'dot' => '#ef4444',
                    'bg' => 'rgba(239,68,68,.12)',
                    'text' => '#b91c1c',
                    'label' => 'COMPLAINT',
                ],
                'suggestion' => [
                    'dot' => '#3b82f6',
                    'bg' => 'rgba(59,130,246,.12)',
                    'text' => '#1d4ed8',
                    'label' => 'SUGGESTION',
                ],
            ];
            $tp = $typeMap[$type] ?? $typeMap['complaint'];

            $fromLabel = $complaint->is_anonymous ? 'Anonymous' : optional($complaint->user)->name ?? 'User deleted';
        @endphp

        {{-- Header --}}
        <div class="headbar">
            <div>
                <h4>Complaint / Suggestion Detail</h4>
                <div class="sub">Full details + status update from admin panel.</div>

                <div class="d-flex flex-wrap gap-2 mt-2">
                    <span class="pill"
                        style="background: {{ $tp['bg'] }}; color: {{ $tp['text'] }}; border-color: rgba(15,23,42,.06);">
                        <span class="dot" style="background: {{ $tp['dot'] }};"></span>
                        {{ $tp['label'] }}
                    </span>

                    <span class="pill"
                        style="background: {{ $st['bg'] }}; color: {{ $st['text'] }}; border-color: rgba(15,23,42,.06);">
                        <span class="dot" style="background: {{ $st['dot'] }};"></span>
                        {{ $st['label'] }}
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.complaints.index') }}" class="btn-accent">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>

        {{-- Details --}}
        <div class="cardx mb-3">
            <div class="p-3 border-bottom" style="border-color: var(--line) !important;">
                <div class="fw-bold" style="color:var(--text);">Details</div>
                <div class="sub">Who submitted, when submitted, and full message.</div>
            </div>

            <div class="p-3">
                <div class="grid-info mb-3">
                    <div class="info-box">
                        <div class="info-label">From</div>
                        <div class="info-value">{{ $fromLabel }}</div>
                        @if (!$complaint->is_anonymous)
                            <div class="note-hint">{{ optional($complaint->user)->email ?? '—' }}</div>
                        @endif
                    </div>

                    <div class="info-box">
                        <div class="info-label">Created At</div>
                        <div class="info-value">
                            {{ $complaint->created_at ? $complaint->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                        </div>
                        <div class="note-hint">{{ config('app.timezone') }} Time</div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">
                            {{ $complaint->updated_at ? $complaint->updated_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                        </div>
                        <div class="note-hint">{{ config('app.timezone') }} Time</div>
                    </div>
                </div>

                <div class="block mb-3">
                    <div class="block-title">Subject</div>
                    <div style="font-weight:900;color:var(--text);">
                        {{ $complaint->subject ?? 'NA' }}
                    </div>
                </div>

                <div class="block">
                    <div class="block-title">Message</div>
                    <div class="message-box">{{ $complaint->message ?? 'NA' }}</div>
                </div>

                @if ($complaint->admin_note)
                    <div class="mt-3">
                        <div class="block" style="background: rgba(14,165,233,.08); border-color: rgba(14,165,233,.18);">
                            <div class="block-title" style="color:#075985;">Admin Note</div>
                            <div style="font-weight:800;color:#0f172a;">
                                {{ $complaint->admin_note }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Update --}}
        <div class="cardx">
            <div class="section-head">
                <h5>Update Status</h5>
                <small>Change status and optionally add an admin note.</small>
            </div>

            <div class="p-3">
                <form method="POST" action="{{ route('admin.complaints.update', \Illuminate\Support\Facades\Crypt::encryptString((string) $complaint->id)) }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="new" @selected($complaint->status === 'new')>New</option>
                                <option value="in_progress" @selected($complaint->status === 'in_progress')>In Progress</option>
                                <option value="resolved" @selected($complaint->status === 'resolved')>Resolved</option>
                                <option value="closed" @selected($complaint->status === 'closed')>Closed</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold">Admin Note (optional)</label>
                            <input name="admin_note" class="form-control"
                                placeholder="e.g. Issue verified, items will be checked tomorrow..."
                                value="{{ old('admin_note', $complaint->admin_note) }}">
                            <div class="note-hint">This note will be visible in this complaint detail screen.</div>
                        </div>

                        <div class="col-12 d-flex gap-2 flex-wrap">
                            <button class="btn-accent">
                                <i class="ti ti-device-floppy me-1"></i> Update
                            </button>

                            <a href="{{ route('admin.complaints.index') }}" class="btn-soft">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'کامیابی!',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'اوہ!',
                text: @json($errors->first()),
            });
        </script>
    @endif
@endsection
