@extends('home')

@section('title')
    <title>Welfare Fund | FBWS</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h1 style="font-size:1.5rem;font-weight:600;">Welfare Fund</h1>
                <small class="text-muted">
                    Month: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                </small>
            </div>

            @if (auth()->check() && strtolower(auth()->user()->role) === 'admin')
                <div class="d-flex gap-2">
                    <button class="btn"
                        style="color:white; background:#F7721E; padding:10px 16px; border-radius:5px; border:none;"
                        data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                        <i class="ti ti-plus"></i> Add Amount
                    </button>

                    <button class="btn btn-dark" style="padding:10px 16px; border-radius:5px;" data-bs-toggle="modal"
                        data-bs-target="#addExpenseModal">
                        <i class="ti ti-minus"></i> Add Expense
                    </button>
                </div>
            @endif
        </div>

        {{-- SUMMARY --}}
        <div class="p-3">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted">Opening Balance</div>
                        <div style="font-size:18px;font-weight:700;">
                            Rs {{ (int) ($wMonth->opening_balance ?? 0) }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted">Total Received</div>
                        <div style="font-size:18px;font-weight:700;">
                            Rs {{ (int) ($totalReceived ?? 0) }}
                        </div>
                        <small class="text-muted">
                            Payments: Rs {{ (int) ($paymentsTotal ?? 0) }}
                            + Add Amount: Rs {{ (int) ($incomeTotal ?? 0) }}
                        </small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted">Total Used</div>
                        <div style="font-size:18px;font-weight:700;">
                            Rs {{ (int) ($totalUsed ?? 0) }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3" style="border:1px solid #F7721E;">
                        <div class="text-muted">Remaining</div>
                        <div style="font-size:18px;font-weight:800;color:#F7721E;">
                            Rs {{ (int) ($closingBalance ?? 0) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
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
                    @php $typeColors = ['income' => 'success', 'expense' => 'danger']; @endphp

                    @forelse ($transactions as $index => $t)
                        <tr>
                            <td>{{ $index + 1 }}</td>

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

                            <td>{{ $t->creator?->name ?? 'NA' }}</td>

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

    {{-- ADD INCOME MODAL --}}
    <div class="modal fade" id="addIncomeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('welfare.addIncome') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Welfare Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date (optional)</label>
                        <input type="date" class="form-control" name="tx_date">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note (optional)</label>
                        <input type="text" class="form-control" name="purpose" placeholder="e.g. Monthly contributions">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn" style="background:#F7721E;color:white;">
                            Add Amount
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ADD EXPENSE MODAL --}}
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('welfare.addExpense') }}" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Purpose (Required)</label>
                        <input type="text" class="form-control" name="purpose" placeholder="e.g. Plates purchase"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date (optional)</label>
                        <input type="date" class="form-control" name="tx_date">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bill Image (optional)</label>
                        <input type="file" class="form-control" name="bill_image" accept="image/*">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn" style="background:#F7721E;color:white;">
                            Save Expense
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error'))
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: @json(session('success')),
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endsection
