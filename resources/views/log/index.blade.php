@extends('home')

@section('title')
    <title>Audit log | FBWS</title>
@endsection

@section('content')
    <div class="container py-4">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> Please fix the errors and try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-3">
            <div class="d-flex align-items-start gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center"
                    style="width:52px;height:52px;background:rgba(247,114,30,.12);color:#F7721E;">
                    <i class="bi bi-shield-lock fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Audit Logs</h4>
                    <div class="text-muted small">System activity &amp; changes tracking</div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                    <i class="bi bi-list-ul me-1"></i>
                    Total: {{ method_exists($logs, 'total') ? $logs->total() : $logs->count() }}
                </span>

                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filtersBox" aria-expanded="true" aria-controls="filtersBox">
                    <i class="bi bi-funnel me-1"></i> Filters
                </button>

                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-repeat me-1"></i> Reset
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="collapse show" id="filtersBox">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ url()->current() }}" class="row g-3 align-items-end">

                        <div class="col-12 col-lg-4">
                            <label class="form-label small text-muted mb-1">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control"
                                    placeholder="Search action / URL / Record ID..." />
                            </div>
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label small text-muted mb-1">Module</label>
                            <select name="module" class="form-select">
                                <option value="">All</option>
                                @foreach ($modules ?? [] as $m)
                                    <option value="{{ $m }}" @selected(($filters['module'] ?? '') === $m)>{{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-lg-2">
                            <label class="form-label small text-muted mb-1">Event</label>
                            <select name="event" class="form-select">
                                <option value="">All</option>
                                @foreach ($events ?? [] as $e)
                                    <option value="{{ $e }}" @selected(($filters['event'] ?? '') === $e)>{{ $e }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-lg-2">
                            <label class="form-label small text-muted mb-1">User</label>
                            <select name="user_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($users ?? [] as $u)
                                    <option value="{{ $u->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $u->id)>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-lg-1">
                            <label class="form-label small text-muted mb-1">From</label>
                            <input type="date" name="from" value="{{ $filters['from'] ?? '' }}"
                                class="form-control" />
                        </div>

                        <div class="col-6 col-lg-1">
                            <label class="form-label small text-muted mb-1">To</label>
                            <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control" />
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button class="btn btn-sm px-3" style="background:#F7721E;color:#fff">
                                <i class="bi bi-funnel me-1"></i> Apply
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary px-3">
                                <i class="bi bi-x-lg me-1"></i> Clear
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Bulk Delete Form (wraps list) --}}
        <form id="bulkDeleteForm" method="POST" action="{{ route('audit.logs.delete') }}">
            @csrf
            @method('DELETE')

            {{-- Bulk actions bar --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <button type="button" class="btn" onclick="confirmBulkDelete()"
                                style="background:#F7721E;color:#fff">
                                <i class="bi bi-trash3 me-1"></i> Delete Selected
                            </button>

                            <span class="badge bg-light text-dark border">
                                Selected: <span id="selectedCount">0</span>
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label small" for="selectAll">
                                    Select All (this page)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="small text-muted mt-2">
                        Tip: Select multiple logs and delete in one click.
                    </div>
                </div>
            </div>

            {{-- Logs --}}
            @forelse($logs as $log)
                @php
                    $old = is_array($log->old_values)
                        ? $log->old_values
                        : (json_decode($log->old_values ?? 'null', true) ?:
                        []);
                    $new = is_array($log->new_values)
                        ? $log->new_values
                        : (json_decode($log->new_values ?? 'null', true) ?:
                        []);

                    $keys = array_unique(array_merge(array_keys($old ?? []), array_keys($new ?? [])));
                    sort($keys);

                    $eventLower = strtolower($log->event ?? '');
                    $badgeClass = match ($eventLower) {
                        'created' => 'bg-success',
                        'updated' => 'bg-warning text-dark',
                        'deleted' => 'bg-danger',
                        'printed' => 'bg-info text-dark',
                        'login' => 'bg-primary',
                        'login failed' => 'bg-danger',
                        'logout' => 'bg-secondary',
                        default => 'bg-dark',
                    };

                    $accent = match ($eventLower) {
                        'created' => '#198754',
                        'updated' => '#ffc107',
                        'deleted' => '#dc3545',
                        'printed' => '#0dcaf0',
                        'login' => '#0d6efd',
                        'login failed' => '#dc3545',
                        'logout' => '#6c757d',
                        default => '#212529',
                    };

                    $collapseId = 'diff-' . $log->id;
                @endphp

                <div class="card border-0 shadow-sm mb-3 overflow-hidden">
                    <div class="row g-0">
                        {{-- Left Accent Bar --}}
                        <div class="col-auto" style="width:6px;background:{{ $accent }};"></div>

                        <div class="col">
                            <div class="card-body">

                                {{-- Top Row --}}
                                <div class="d-flex flex-column flex-xl-row justify-content-between gap-2">
                                    <div class="d-flex flex-wrap align-items-center gap-2">

                                        {{-- Checkbox --}}
                                        <div class="form-check mb-0 me-1">
                                            <input class="form-check-input log-checkbox" type="checkbox" name="ids[]"
                                                value="{{ $log->id }}" id="log_{{ $log->id }}">
                                        </div>

                                        <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                            {{ strtoupper($log->event) }}
                                        </span>
                                        <span class="fw-semibold">{{ $log->module }}</span>
                                        <span class="text-muted">•</span>
                                        <span class="text-muted">{{ $log->action }}</span>
                                    </div>

                                    <div class="text-muted small d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}
                                        </span>
                                    </div>
                                </div>

                                <hr class="my-3">

                                {{-- Meta --}}
                                <div class="row g-3">
                                    <div class="col-12 col-lg-4">
                                        <div class="p-3 rounded-4 bg-light border h-100">
                                            <div class="small text-muted mb-1">User</div>
                                            <div class="fw-semibold d-flex align-items-center gap-2">
                                                <i class="bi bi-person"></i>
                                                {{ $log->user?->name ?? 'System/Guest' }}
                                            </div>
                                            @if ($log->user?->email)
                                                <div class="text-muted small">{{ $log->user->email }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="p-3 rounded-4 bg-light border h-100">
                                            <div class="small text-muted mb-1">Record</div>
                                            <div class="small">
                                                <div><span class="text-muted">Type:</span> <span
                                                        class="fw-semibold">{{ $log->auditable_type ?? '-' }}</span></div>
                                                <div><span class="text-muted">ID:</span> <span
                                                        class="fw-semibold">{{ $log->auditable_id ?? '-' }}</span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4">
                                        <div class="p-3 rounded-4 bg-light border h-100">
                                            <div class="small text-muted mb-1">Request</div>
                                            <div class="small">
                                                <div><span class="text-muted">IP:</span> <span
                                                        class="fw-semibold">{{ $log->ip_address ?? '-' }}</span></div>
                                                <div class="text-truncate" style="max-width: 100%;">
                                                    <span class="text-muted">URL:</span> <span
                                                        class="fw-semibold">{{ $log->url ?? '-' }}</span>
                                                </div>
                                                <div><span class="text-muted">Method:</span> <span
                                                        class="fw-semibold">{{ $log->method ?? '-' }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Toggle --}}
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="fw-semibold">
                                        <i class="bi bi-arrow-left-right me-1"></i> Changes
                                        <span class="text-muted small ms-1">({{ count($keys) }} fields)</span>
                                    </div>

                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                        aria-expanded="false" aria-controls="{{ $collapseId }}">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </button>
                                </div>

                                {{-- Details (Default Closed) --}}
                                <div class="collapse mt-2" id="{{ $collapseId }}">
                                    @if (empty($keys))
                                        <div class="alert alert-light border mb-0">
                                            No values recorded.
                                        </div>
                                    @else
                                        <div class="table-responsive border rounded-4 mt-2">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 22%;">Field</th>
                                                        <th style="width: 39%;">Old</th>
                                                        <th style="width: 39%;">New</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($keys as $k)
                                                        @php
                                                            $ov = $old[$k] ?? null;
                                                            $nv = $new[$k] ?? null;

                                                            $ovText = is_array($ov)
                                                                ? json_encode(
                                                                    $ov,
                                                                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
                                                                )
                                                                : (string) ($ov ?? '');
                                                            $nvText = is_array($nv)
                                                                ? json_encode(
                                                                    $nv,
                                                                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
                                                                )
                                                                : (string) ($nv ?? '');
                                                            $changed = $ovText !== $nvText;
                                                        @endphp

                                                        <tr @if ($changed) class="table-warning" @endif>
                                                            <td class="fw-semibold">{{ $k }}</td>
                                                            <td>
                                                                <div class="rounded-3 border bg-white p-2">
                                                                    <pre class="mb-0 small" style="white-space: pre-wrap;">{{ $ovText }}</pre>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="rounded-3 border bg-white p-2">
                                                                    <pre class="mb-0 small" style="white-space: pre-wrap;">{{ $nvText }}</pre>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="text-muted small mt-2">
                                            <span class="badge bg-warning text-dark">Highlighted</span> rows mean changed.
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="alert alert-info border-0 shadow-sm">
                    <i class="bi bi-info-circle me-1"></i> No audit logs found.
                </div>
            @endforelse

        </form>

        {{-- Pagination --}}
        <div class="pagination-container text-center">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Page Scripts --}}
    <script>
        (function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = () => document.querySelectorAll('.log-checkbox');
            const selectedCountEl = document.getElementById('selectedCount');

            function updateSelectedCount() {
                const count = document.querySelectorAll('.log-checkbox:checked').length;
                if (selectedCountEl) selectedCountEl.textContent = count;
                if (selectAll) {
                    const all = checkboxes().length;
                    selectAll.checked = all > 0 && count === all;
                    selectAll.indeterminate = count > 0 && count < all;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes().forEach(cb => cb.checked = this.checked);
                    updateSelectedCount();
                });
            }

            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('log-checkbox')) {
                    updateSelectedCount();
                }
            });

            window.confirmBulkDelete = function() {
                const selected = document.querySelectorAll('.log-checkbox:checked');
                if (selected.length === 0) {
                    alert('Please select logs to delete.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected logs? This action cannot be undone.')) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            }

            // initial
            updateSelectedCount();
        })();
    </script>
@endsection
