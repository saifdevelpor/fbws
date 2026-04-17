@extends('home')

@section('title')
    <title>Payment History | FBWS</title>
@endsection

@section('content')
    @php
        $selectedMonth = request('month');
        $selectedYear = request('year');

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
                <h1 style="font-size:1.5rem;font-weight:600;margin:0;">Payment History</h1>
                <small class="text-muted">
                    @if (!empty($isAdmin) && $isAdmin)
                        Admin View: All Users
                    @else
                        My Payments Only
                    @endif
                </small>
            </div>

            <!-- ===== FILTERS ===== -->
            <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 flex-wrap">
                <div>
                    <select name="month" class="form-select form-select-sm">
                        <option value="">All Months</option>
                        @foreach ($months as $mVal => $mName)
                            <option value="{{ $mVal }}" {{ $selectedMonth == $mVal ? 'selected' : '' }}>
                                {{ $mName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="year" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        @for ($y = 2026; $y <= 2035; $y++)
                            <option value="{{ $y }}"
                                {{ (string) $selectedYear === (string) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-sm" style="background: #F7721E; color: white">
                    Filter
                </button>

                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                    Reset
                </a>
            </form>
        </div>

        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Month</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Receipt #</th>
                        <th>Picture</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($histories as $index => $h)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center text-decoration-none text-dark">
                                    <img src="{{ $h->user?->profile_photo
                                        ? asset($h->user->profile_photo)
                                        : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}"
                                        class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover"
                                        alt="Profile">
                                    <span class="fw-medium">{{ $h->user?->name ?? 'NA' }}</span>
                                </a>
                            </td>

                            <td>Rs {{ (int) $h->amount }}</td>
                            <td>{{ $h->month ?? 'NA' }}</td>

                            <td>
                                {{ $h->date ? \Carbon\Carbon::parse($h->date)->format('d M Y') : 'NA' }}
                            </td>

                            <td>
                                {{ $h->time ? \Carbon\Carbon::parse($h->time)->format('h:i A') : 'NA' }}
                            </td>

                            <td>{{ $h->receipt_number ?? 'NA' }}</td>

                            <td>
                                @if (!empty($h->picture))
                                    <a href="{{ asset($h->picture) }}" target="_blank">
                                        <img src="{{ asset($h->picture) }}"
                                            style="width:45px;height:45px;border-radius:8px;object-fit:cover"
                                            alt="receipt">
                                    </a>
                                @else
                                    NA
                                @endif
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
