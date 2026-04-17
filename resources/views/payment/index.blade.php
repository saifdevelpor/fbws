@extends('home')

@section('title')
<title>
    Payments | FBWS</title>
@endsection

@section('content')
@php
$paymentRows = method_exists($payments, 'getCollection') ? $payments->getCollection() : collect($payments);
$totalPayments = $paymentStats['total_payments'] ?? (method_exists($payments, 'total') ? $payments->total() :
$paymentRows->count());
$totalAmount = $paymentStats['total_amount'] ?? (int) $paymentRows->sum('amount');
$paidMembers = $paymentStats['paid_members'] ?? $paymentRows->pluck('user_id')->filter()->unique()->count();
@endphp

<style>
.payments-page .summary-card {
    border: 1px solid #eceff3;
    border-radius: 14px;
    padding: 14px 16px;
    background: #fff;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .04);
}

.payments-page .summary-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
    margin-bottom: 6px;
}

.payments-page .summary-value {
    font-size: 24px;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
}

.payment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}

.payment-card {
    border: 1px solid #e7ebf1;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 12px 24px rgba(15, 23, 42, .05);
    overflow: hidden;
}

.payment-card-top {
    position: relative;
    padding: 14px 14px 8px;
    text-align: center;
    background: linear-gradient(180deg, rgba(247, 114, 30, .08), rgba(247, 114, 30, .02));
}

.payment-card-top img {
    width: 74px;
    height: 74px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
}

.payment-actions {
    position: absolute;
    right: 8px;
    top: 8px;
}

.payment-card-body {
    padding: 14px;
}

.payment-name {
    font-weight: 700;
    margin-bottom: 2px;
    color: #111827;
}

.payment-email {
    color: #6b7280;
    font-size: 12px;
    margin-bottom: 10px;
}

.payment-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.payment-meta-item {
    border: 1px solid #eef2f7;
    border-radius: 10px;
    padding: 8px 10px;
    background: #fafbfc;
}

.payment-meta-item small {
    color: #6b7280;
    display: block;
    font-size: 11px;
    margin-bottom: 2px;
}

.payment-meta-item strong {
    color: #111827;
    font-size: 13px;
}
</style>

<div class="card payments-page">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h1 style="font-size:1.5rem;font-weight:600;">Payments</h1>
        @if (auth()->check() && auth()->user()->role === 'admin')
        <button class="btn-employee"
            style="color:white; background:#F7721E; padding:10px 20px; border-radius:8px; border: none;"
            data-bs-toggle="modal" data-bs-target="#createPaymentModal">
            <i class="ti ti-plus"></i> نئی ادائیگی شامل کریں
        </button>
        @endif
    </div>

    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label"><i class="ti ti-receipt me-1"></i>Total Payments</div>
                    <div class="summary-value">{{ $totalPayments }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label"><i class="ti ti-currency-rupee me-1"></i>Total Amount</div>
                    <div class="summary-value">{{ number_format($totalAmount) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label"><i class="ti ti-users me-1"></i>Paid Members</div>
                    <div class="summary-value">{{ $paidMembers }}</div>
                </div>
            </div>
        </div>

        <div class="payment-grid">
            @forelse ($payments as $index => $payment)
            <div class="payment-card">
                <div class="payment-card-top">
                    @if (auth()->check() && auth()->user()->role === 'admin')
                    <div class="dropdown payment-actions">
                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item"
                                href="{{ route('payments.print', \Illuminate\Support\Facades\Crypt::encryptString((string) $payment->id)) }}"
                                target="_blank">
                                <i class="ti ti-printer me-1"></i> Print Receipt
                            </a>
                            <a class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#editPayment{{ $payment->id }}">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger" onclick="confirmDelete({{ $payment->id }})">
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                    @endif

                    <a href="{{ $payment->picture ? asset($payment->picture) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}"
                        target="_blank">
                        <img src="{{ $payment->picture ? asset($payment->picture) : ($payment->user?->profile_photo ? asset($payment->user->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif')) }}"
                            alt="receipt">
                    </a>
                </div>

                <div class="payment-card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge"
                            style="background:#f3f4f6;color:#111827;">#{{ ($payments->firstItem() ?? 0) + $index }}</span>
                        <span class="badge" style="background:rgba(247,114,30,.14);color:#9a4308;">
                            {{ $payment->month }}
                        </span>
                    </div>

                    <div class="payment-name">{{ $payment->user?->name ?? 'NA' }}</div>
                    <div class="payment-email">{{ $payment->user?->email ?? 'No email available' }}</div>

                    <div class="payment-meta mb-3">
                        <div class="payment-meta-item">
                            <small><i class="ti ti-currency-rupee me-1"></i>Amount</small>
                            <strong>{{ (int) $payment->amount }}</strong>
                        </div>
                        <div class="payment-meta-item">
                            <small><i class="ti ti-calendar-event me-1"></i>Date</small>
                            <strong>{{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}</strong>
                        </div>
                        <div class="payment-meta-item">
                            <small><i class="ti ti-hash me-1"></i>Receipt #</small>
                            <strong>{{ $payment->receipt_number ?? 'N/A' }}</strong>
                        </div>
                        <div class="payment-meta-item">
                            <small><i class="ti ti-clock me-1"></i>Time</small>
                            <strong>{{ \Carbon\Carbon::parse($payment->time)->format('h:i A') }}</strong>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        @if ($payment->whatsapp_link)
                        <a href="{{ $payment->whatsapp_link }}" target="_blank" class="btn btn-sm flex-fill"
                            style="background:#25D366; color:#fff;">
                            <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp
                        </a>
                        @else
                        <button class="btn btn-sm btn-light flex-fill" disabled>
                            <i class="ti ti-phone-off me-1"></i> No Phone
                        </button>
                        @endif

                        <a href="{{ route('payments.print', \Illuminate\Support\Facades\Crypt::encryptString((string) $payment->id)) }}"
                            target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="ti ti-printer"></i>
                        </a>
                    </div>
                </div>
            </div>

            <form id="delete-form-{{ $payment->id }}"
                action="{{ route('payments.delete', \Illuminate\Support\Facades\Crypt::encryptString((string) $payment->id)) }}"
                method="POST" style="display:none">
                @csrf
                @method('DELETE')
            </form>
            @empty
            <div class="alert alert-warning">
                No payment records found.
            </div>
            @endforelse
        </div>

        @if ($payments instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div class="d-flex flex-column align-items-center mt-4 gap-2">
            <small class="text-muted">
                Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of
                {{ method_exists($payments, 'total') ? $payments->total() : $payments->count() }} records
            </small>
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- EDIT MODALS --}}
@foreach ($payments as $payment)
<div class="modal fade" id="editPayment{{ $payment->id }}" tabindex="-1"
    aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form method="POST"
            action="{{ route('payments.update', \Illuminate\Support\Facades\Crypt::encryptString((string) $payment->id)) }}"
            enctype="multipart/form-data" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Edit Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">User <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Select User</option>
                        @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ $payment->user_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ ucfirst($u->role ?? 'user') }}){{ $u->email ? ' - ' . $u->email : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount"
                            value="{{ (int) $payment->amount }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Month <span class="text-danger">*</span></label>
                        <select name="month" class="form-control" required>
                            @php
                            $months = [
                            'جنوری',
                            'فروری',
                            'مارچ',
                            'اپریل',
                            'مئی',
                            'جون',
                            'جولائی',
                            'اگست',
                            'ستمبر',
                            'اکتوبر',
                            'نومبر',
                            'دسمبر',
                            ];
                            @endphp
                            <option value="">Select Month</option>
                            @foreach ($months as $m)
                            <option value="{{ $m }}" {{ $payment->month == $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" value="{{ $payment->date }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="time"
                            value="{{ \Carbon\Carbon::parse($payment->time)->format('H:i') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Receipt Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="receipt_number"
                        value="{{ $payment->receipt_number }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Receipt Picture (Optional)</label>
                    <input type="file" class="form-control" name="picture">
                    @if ($payment->picture)
                    <img src="{{ asset($payment->picture) }}" class="mt-2"
                        style="width:90px;height:90px;border-radius:10px;object-fit:cover;" alt="receipt">
                    @endif
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn" style="background:#F7721E;color:white;">
                        Update Payment
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endforeach

{{-- CREATE MODAL --}}
<div class="modal fade" id="createPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form method="POST" action="{{ route('payments.save') }}" enctype="multipart/form-data" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">User <span class="text-danger">*</span></label>
                    <input type="hidden" name="user_id" id="payment-create-user-id" value="{{ old('user_id') ?? '' }}"
                        required>

                    <button type="button" id="payment-create-user-btn"
                        class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center gap-2 justify-content-between"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="d-flex align-items-center gap-2">
                            <img id="payment-create-user-avatar" src="{{ asset('assets/img/avatars/5.png') }}"
                                alt="User" style="width:28px;height:28px;object-fit:cover;border-radius:50%;">
                            <span id="payment-create-user-name" class="fw-semibold">Select User</span>
                        </span>
                    </button>

                    <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                        <li class="dropdown-header">Select user</li>
                        @foreach ($users as $u)
                        @php
                        $photoPath = $u->profile_photo ? str_replace('\\', '/', $u->profile_photo) : null;
                        $uAvatar = $photoPath ? asset($photoPath) : asset('assets/img/avatars/5.png');
                        $uLabel = $u->name . ' (' . ucfirst($u->role ?? 'user') . ')' . ($u->email ? ' - ' . $u->email : '');
                        @endphp
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);"
                                data-payment-user-id="{{ (int) $u->id }}" data-payment-user-name="{{ $uLabel }}"
                                data-payment-user-avatar="{{ $uAvatar }}">
                                <img src="{{ $uAvatar }}" alt="User"
                                    style="width:32px;height:32px;object-fit:cover;border-radius:50%;">
                                <div class="lh-sm">
                                    <div class="fw-semibold">{{ $u->name }} <span class="text-muted small">({{ ucfirst($u->role ?? 'user') }})</span></div>
                                    <div class="text-muted small">{{ $u->email }}</div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Month <span class="text-danger">*</span></label>
                        @php
                        $months = [
                        'جنوری',
                        'فروری',
                        'مارچ',
                        'اپریل',
                        'مئی',
                        'جون',
                        'جولائی',
                        'اگست',
                        'ستمبر',
                        'اکتوبر',
                        'نومبر',
                        'دسمبر',
                        ];
                        @endphp
                        <select name="month" class="form-control" required>
                            <option value="">Select Month</option>
                            @foreach ($months as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="time" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Receipt Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="receipt_number" placeholder="e.g. FBWS-0001">
                </div>

                <div class="mb-3">
                    <label class="form-label">Receipt Picture (Optional)</label>
                    <input type="file" class="form-control" name="picture">
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn" style="background:#F7721E;color:white;font-weight:500;">
                        Save Payment
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: @json(session('success')),
    showConfirmButton: false,
    timer: 3000
});
</script>
@endif

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This payment will be deleted permanently',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btnName = document.getElementById('payment-create-user-name');
    var btnAvatar = document.getElementById('payment-create-user-avatar');
    var hiddenInput = document.getElementById('payment-create-user-id');
    var items = document.querySelectorAll(
        '[data-payment-user-id][data-payment-user-name][data-payment-user-avatar]');

    if (!btnName || !btnAvatar || !hiddenInput || !items.length) return;

    function setSelected(userId, userName, userAvatar) {
        hiddenInput.value = userId || '';
        btnName.textContent = userName || 'Select User';
        if (userAvatar) btnAvatar.src = userAvatar;
    }

    // Preselect from old input (validation fail)
    if (hiddenInput.value) {
        var current = Array.from(items).find(function(el) {
            return (el.getAttribute('data-payment-user-id') || '') === (hiddenInput.value || '');
        });
        if (current) {
            setSelected(
                current.getAttribute('data-payment-user-id'),
                current.getAttribute('data-payment-user-name'),
                current.getAttribute('data-payment-user-avatar')
            );
        }
    }

    items.forEach(function(item) {
        item.addEventListener('click', function() {
            setSelected(
                this.getAttribute('data-payment-user-id'),
                this.getAttribute('data-payment-user-name'),
                this.getAttribute('data-payment-user-avatar')
            );
        });
    });
});
</script>
@endsection
