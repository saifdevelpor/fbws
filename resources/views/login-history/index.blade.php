@extends('home')

@section('title')
    <title>Login History | FBWS</title>
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
    @endphp

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 style="font-size:1.5rem;font-weight:600;margin:0;">Login History</h1>
                <small class="text-muted">My logins only</small>
            </div>

            <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 flex-wrap">
                <div>
                    <select name="month" class="form-select form-select-sm">
                        <option value="">All Months</option>
                        @foreach ($months as $mVal => $mName)
                            <option value="{{ $mVal }}"
                                {{ (string) $selectedMonth === (string) $mVal ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="year" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        @for ($y = 2036; $y >= 2026; $y--)
                            <option value="{{ $y }}"
                                {{ (string) $selectedYear === (string) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button class="btn btn-sm" style="background: #F7721E; color: white">Filter</button>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('login-history.index') }}">Reset</a>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="myTable1">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 220px;">Date</th>
                            <th style="width: 140px;">Time</th>
                            <th style="width: 180px;">IP</th>
                            <th>Device</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $row)
                            <tr>
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
        </div>
    </div>

    <script>
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
