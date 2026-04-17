@extends('home')

@section('title')
    <title>Welfare History | FBWS</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h1 style="font-size:1.5rem;font-weight:600;">Welfare History</h1>
                <small class="text-muted">
                    @if ($month)
                        Showing: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                    @else
                        Showing: All Months ({{ $year }})
                    @endif
                </small>
            </div>

            {{-- FILTERS --}}
            <form method="GET" action="{{ route('welfare.history') }}" class="d-flex gap-2 align-items-end">
                <div>
                    <label class="form-label mb-1">Year</label>
                    <input type="number" name="year" class="form-control" value="{{ $year }}"
                        style="width:110px;">
                </div>

                <div>
                    <label class="form-label mb-1">Month</label>
                    <select name="month" class="form-control" style="width:160px;">
                        <option value="">All Months</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ (int) $month === $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate($year, $m, 1)->locale('ur')->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button class="btn" style="background:#F7721E;color:white;height:40px;">
                    Filter
                </button>
            </form>
        </div>

        {{-- SUMMARY (only when a single month is selected) --}}
        @if ($month && $selectedMonth)
            <div class="p-3">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="text-muted">Opening Balance</div>
                            <div style="font-size:18px;font-weight:700;">Rs
                                {{ (int) $selectedMonth->opening_balance }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="text-muted">Total Received</div>
                            <div style="font-size:18px;font-weight:700;">Rs
                                {{ (int) ($computedTotalReceived ?? $selectedMonth->total_received) }}</div>
                            <small class="text-muted">
                                Payments: Rs {{ (int) ($computedPaymentsTotal ?? 0) }}
                                + Add Amount: Rs {{ (int) ($computedIncomeTotal ?? 0) }}
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="text-muted">Total Used</div>
                            <div style="font-size:18px;font-weight:700;">Rs
                                {{ (int) ($computedExpenseTotal ?? $selectedMonth->total_used) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3" style="border:1px solid #F7721E;">
                            <div class="text-muted">Remaining</div>
                            <div style="font-size:18px;font-weight:800;color:#F7721E;">
                                Rs {{ (int) ($computedClosingBalance ?? $selectedMonth->closing_balance) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($month && !$selectedMonth)
            <div class="p-3">
                <div class="alert alert-warning mb-0">
                    No monthly summary found for selected month.
                </div>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Month</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Purpose</th>
                        <th>Date</th>
                        <th>Bill</th>
                        <th>Added By</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $typeColors = ['income' => 'success', 'expense' => 'danger'];
                    @endphp

                    @forelse ($transactions as $index => $t)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ \Carbon\Carbon::createFromDate($t->month->year, $t->month->month, 1)->format('M Y') }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $typeColors[$t->type] ?? 'dark' }}">
                                    {{ ucfirst($t->type) }}
                                </span>
                            </td>

                            <td>Rs {{ (int) $t->amount }}</td>
                            <td>{{ $t->purpose ?? 'NA' }}</td>

                            <td>
                                {{ $t->tx_date ? \Carbon\Carbon::parse($t->tx_date)->timezone(config('app.timezone'))->format('d M Y') : 'NA' }}
                            </td>

                            <td>
                                @if ($t->bill_image)
                                    <a href="{{ asset($t->bill_image) }}" target="_blank">
                                        <img src="{{ asset($t->bill_image) }}"
                                            style="width:45px;height:45px;border-radius:8px;object-fit:cover"
                                            alt="bill">
                                    </a>
                                @else
                                    NA
                                @endif
                            </td>

                            <td>{{ auth()->user()->name ?? 'NA' }}</td>

                            <td>
                                {{ $t->created_at ? $t->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
