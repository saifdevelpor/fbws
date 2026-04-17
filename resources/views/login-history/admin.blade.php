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

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle mb-0" id="myTable1">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 260px;">User</th>
                            <th style="width: 220px;">Date</th>
                            <th style="width: 140px;">Time</th>
                            <th style="width: 180px;">IP</th>
                            <th>Device</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $row)
                            <tr>
                                @php
                                    $rowAvatar = $row->user && $row->user->profile_photo
                                        ? asset($row->user->profile_photo)
                                        : asset('assets/img/avatars/5.png');
                                @endphp
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $rowAvatar }}" alt="User"
                                            style="width:34px;height:34px;object-fit:cover;border-radius:50%;border:1px solid #e2e8f0;">
                                        <span class="fw-semibold">{{ $row->user?->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="fw-semibold login-local-date"
                                    data-login-at="{{ optional($row->logged_in_at)->toIso8601String() }}">
                                    {{ optional($row->logged_in_at)->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="login-local-time"
                                        data-login-at="{{ optional($row->logged_in_at)->toIso8601String() }}">
                                        {{ optional($row->logged_in_at)?->timezone(config('app.timezone'))->format('h:i A') }}
                                    </span>
                                </td>
                                <td><span class="badge bg-secondary">{{ $row->ip_address ?? '—' }}</span></td>
                                <td class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit((string) ($row->user_agent ?? '—'), 120) }}
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $histories->links() }}
            </div>
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
