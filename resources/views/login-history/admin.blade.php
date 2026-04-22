@extends('home')

@section('title')
    <title>All Users login History | FBWS</title>
@endsection

@section('content')
    @php
        $selectedMonth = !empty($month) ? str_pad((string) $month, 2, '0', STR_PAD_LEFT) : '';
        $selectedYear = !empty($year) ? (string) $year : '';

        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        $selectedUserAvatar = $selectedUser && $selectedUser->profile_photo
            ? asset($selectedUser->profile_photo)
            : asset('assets/img/avatars/5.png');
    @endphp

    <style>
        .login-user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .login-user-card {
            border: 1px solid #e5edf7;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
            padding: 16px;
            height: 100%;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .login-user-card:hover {
            transform: translateY(-3px);
            border-color: rgba(247, 114, 30, .25);
            box-shadow: 0 16px 34px rgba(247, 114, 30, .10);
        }

        .login-user-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .login-user-main {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .login-user-main img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #dbe5f0;
        }

        .login-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(37, 99, 235, .10);
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 800;
        }

        .login-user-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-top: 12px;
        }

        .login-meta-box {
            border: 1px solid #e5edf7;
            border-radius: 14px;
            padding: 10px 12px;
            background: #fff;
        }

        .login-meta-box small {
            display: block;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: 700;
        }
    </style>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 style="font-size:1.5rem;font-weight:600;margin:0;">Users Login History</h1>
                <small class="text-muted">Admin view</small>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 flex-wrap">
                <div style="min-width:240px;">
                    <input type="hidden" name="user_id" id="login-history-user-id" value="{{ $selectedUserId ? (int) $selectedUserId : '' }}" required>

                    <button
                        type="button"
                        id="login-history-user-btn"
                        class="btn btn-sm btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <img
                            id="login-history-user-btn-avatar"
                            src="{{ $selectedUser ? $selectedUserAvatar : asset('assets/img/avatars/5.png') }}"
                            alt="User"
                            style="width:28px;height:28px;object-fit:cover;border-radius:50%;"
                        >
                        <span id="login-history-user-btn-name" class="fw-semibold">
                            {{ $selectedUser ? $selectedUser->name : 'Select user...' }}
                        </span>
                    </button>

                    <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                        <li class="dropdown-header">Select user</li>
                        @foreach ($users as $u)
                            @php
                                $uAvatar = $u->profile_photo ? asset($u->profile_photo) : asset('assets/img/avatars/5.png');
                            @endphp
                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center gap-2"
                                    href="javascript:void(0);"
                                    data-user-id="{{ (int) $u->id }}"
                                    data-user-name="{{ $u->name }}"
                                    data-user-avatar="{{ $uAvatar }}"
                                >
                                    <img src="{{ $uAvatar }}" alt="User" style="width:32px;height:32px;object-fit:cover;border-radius:50%;">
                                    <div class="lh-sm">
                                        <div class="fw-semibold">{{ $u->name }}</div>
                                        @if ($u->id_card)
                                            <div class="text-muted small">{{ $u->id_card }}</div>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <select name="month" class="form-select form-select-sm">
                        <option value="">All Months</option>
                        @foreach ($months as $mVal => $mName)
                            <option value="{{ $mVal }}" {{ (string) $selectedMonth === (string) $mVal ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="year" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        @for ($y = 2036; $y >= 2026; $y--)
                            <option value="{{ $y }}" {{ (string) $selectedYear === (string) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button class="btn btn-sm" style="background: #F7721E; color: white">Show</button>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('login-history.admin') }}">Reset</a>
            </form>
        </div>

        <div class="card-body">
            @if ($selectedUser)
                @php
                    $avatar = $selectedUser->profile_photo
                        ? asset($selectedUser->profile_photo)
                        : asset('assets/img/avatars/5.png');
                @endphp

                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="{{ $avatar }}" alt="User"
                        style="width:48px;height:48px;object-fit:cover;border-radius:50%;">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge bg-label-info">Selected</span>
                            <span class="fw-semibold">{{ $selectedUser->name }}</span>
                            <span class="text-muted small">
                                {{ $selectedUser->id_card ? ' • ' . $selectedUser->id_card : '' }}
                                {{ $selectedUser->email ? ' • ' . $selectedUser->email : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-muted">Showing current month login history for all users (your own logins are hidden). Select a user above to narrow down.</div>
            @endif

            @if ($historySummaries->isEmpty())
                <div class="alert alert-light border mt-3 mb-0">
                    No login history found for the selected filters.
                </div>
            @else
                <div class="login-user-grid mt-3">
                    @foreach ($historySummaries as $index => $summary)
                        @php
                            $summaryUser = $summary['user'] ?? null;
                            $latest = $summary['latest'] ?? null;
                            $summaryRows = $summary['rows'] ?? collect();
                            $summaryAvatar = $summaryUser && $summaryUser->profile_photo
                                ? asset($summaryUser->profile_photo)
                                : asset('assets/img/avatars/5.png');
                            $modalId = 'userLoginDetailModal' . $index;
                        @endphp

                        <div class="login-user-card">
                            <button
                                type="button"
                                class="btn p-0 border-0 bg-transparent text-start w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#{{ $modalId }}"
                            >
                                <div class="login-user-top">
                                    <div class="login-user-main">
                                        <img src="{{ $summaryAvatar }}" alt="User">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $summaryUser?->name ?? 'Unknown User' }}</div>
                                            <div class="text-muted small">{{ $summaryUser?->id_card ?: 'ID Card not available' }}</div>
                                        </div>
                                    </div>

                                    <span class="login-count-badge">{{ (int) ($summary['login_count'] ?? 0) }} times</span>
                                </div>

                                <div class="text-muted small">
                                    Latest login record and total monthly activity summary.
                                </div>

                                <div class="login-user-meta">
                                    <div class="login-meta-box">
                                        <small>Latest Date</small>
                                        <div class="fw-semibold login-local-date" data-login-at="{{ optional($latest?->logged_in_at)->toIso8601String() }}">
                                            {{ optional($latest?->logged_in_at)->format('d M Y') ?? '—' }}
                                        </div>
                                    </div>

                                    <div class="login-meta-box">
                                        <small>Latest Time</small>
                                        <div class="fw-semibold login-local-time" data-login-at="{{ optional($latest?->logged_in_at)->toIso8601String() }}">
                                            {{ optional($latest?->logged_in_at)?->timezone(config('app.timezone'))->format('h:i A') ?? '—' }}
                                        </div>
                                    </div>

                                    <div class="login-meta-box">
                                        <small>IP Address</small>
                                        <div class="fw-semibold">{{ $latest?->ip_address ?? '—' }}</div>
                                    </div>

                                    <div class="login-meta-box">
                                        <small>Role</small>
                                        <div class="fw-semibold text-capitalize">{{ $summaryUser?->role ?? 'User' }}</div>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $summaryAvatar }}" alt="User"
                                                style="width:48px;height:48px;object-fit:cover;border-radius:50%;border:1px solid #dbe5f0;">
                                            <div>
                                                <h5 class="modal-title mb-1">{{ $summaryUser?->name ?? 'Unknown User' }}</h5>
                                                <div class="text-muted small">
                                                    {{ (int) ($summary['login_count'] ?? 0) }} total logins
                                                    {{ $summaryUser?->id_card ? ' • ' . $summaryUser->id_card : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width:70px;">#</th>
                                                        <th style="width:180px;">Date</th>
                                                        <th style="width:140px;">Time</th>
                                                        <th style="width:170px;">IP</th>
                                                        <th>Device</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($summaryRows as $loginIndex => $loginRow)
                                                        <tr>
                                                            <td class="fw-semibold">{{ $loginIndex + 1 }}</td>
                                                            <td class="fw-semibold login-local-date" data-login-at="{{ optional($loginRow->logged_in_at)->toIso8601String() }}">
                                                                {{ optional($loginRow->logged_in_at)->format('d M Y') }}
                                                            </td>
                                                            <td>
                                                                <span class="login-local-time" data-login-at="{{ optional($loginRow->logged_in_at)->toIso8601String() }}">
                                                                    {{ optional($loginRow->logged_in_at)?->timezone(config('app.timezone'))->format('h:i A') }}
                                                                </span>
                                                            </td>
                                                            <td><span class="badge bg-secondary">{{ $loginRow->ip_address ?? '—' }}</span></td>
                                                            <td class="text-muted small">
                                                                {{ \Illuminate\Support\Str::limit((string) ($loginRow->user_agent ?? '—'), 140) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btnName = document.getElementById('login-history-user-btn-name');
            var btnAvatar = document.getElementById('login-history-user-btn-avatar');
            var hiddenInput = document.getElementById('login-history-user-id');
            var items = document.querySelectorAll('[data-user-id][data-user-name][data-user-avatar]');

            if (!btnName || !btnAvatar || !hiddenInput || !items.length) return;

            items.forEach(function (item) {
                item.addEventListener('click', function () {
                    hiddenInput.value = this.getAttribute('data-user-id') || '';
                    var name = this.getAttribute('data-user-name') || '';
                    var avatar = this.getAttribute('data-user-avatar') || '';
                    btnName.textContent = name || 'Select user...';
                    if (avatar) btnAvatar.src = avatar;
                });
            });
        });

        (function() {
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            function pad(n) {
                return n < 10 ? '0' + n : String(n);
            }

            document.querySelectorAll('.login-local-date, .login-local-time').forEach(function(el) {
                var raw = el.getAttribute('data-login-at');
                if (!raw) return;
                var dt = new Date(raw);
                if (isNaN(dt.getTime())) return;

                if (el.classList.contains('login-local-date')) {
                    el.textContent = pad(dt.getDate()) + ' ' + months[dt.getMonth()] + ' ' + dt.getFullYear();
                } else {
                    var hh = dt.getHours();
                    var ampm = hh >= 12 ? 'PM' : 'AM';
                    hh = hh % 12 || 12;
                    el.textContent = pad(hh) + ':' + pad(dt.getMinutes()) + ' ' + ampm;
                }
            });
        })();
    </script>
@endsection
