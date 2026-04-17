@extends('home')

@section('title')
<title>My Profile | FBWS</title>
@endsection

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap" rel="stylesheet">
@php
$roleLabel = ucfirst(strtolower((string) ($user->role ?? 'member')));
$isAdmin = strtolower((string) ($user->role ?? '')) === 'admin';
$ordersCount = isset($lastOrders) ? $lastOrders->count() : 0;
$verificationUrl = route('account.membership-card.verify', \Illuminate\Support\Facades\Crypt::encryptString((string) $user->id));
$qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=0&data=' . urlencode($verificationUrl);
@endphp

<style>
.profile-page {
    --p-accent: #F7721E;
    --p-radius: 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.profile-hero {
    border-radius: var(--p-radius);
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #fff 0%, #fff8f3 50%, #ffe8d6 100%);
    box-shadow: 0 12px 40px rgba(15, 23, 42, .08);
    margin-bottom: 1.25rem;
}

.profile-hero-banner {
    height: 100px;
    background: linear-gradient(120deg, #1e293b 0%, #334155 40%, var(--p-accent) 100%);
    opacity: .95;
}

.profile-hero-body {
    padding: 0 1.5rem 1.5rem;
    margin-top: -52px;
    position: relative;
}

.profile-avatar {
    width: 104px;
    height: 104px;
    border-radius: 1.25rem;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 10px 30px rgba(15, 23, 42, .12);
    background: #f1f5f9;
}

.profile-role-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
    background: rgba(247, 114, 30, .12);
    color: #c2410c;
    border: 1px solid rgba(247, 114, 30, .22);
}

.profile-role-pill.admin {
    background: rgba(37, 99, 235, .1);
    color: #1d4ed8;
    border-color: rgba(37, 99, 235, .22);
}

.profile-stat-card {
    border-radius: var(--p-radius);
    border: 1px solid #e2e8f0;
    background: #fff;
    padding: 1rem 1.15rem;
    height: 100%;
    box-shadow: 0 6px 20px rgba(15, 23, 42, .05);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.profile-stat-card:hover {
    border-color: rgba(247, 114, 30, .25);
    box-shadow: 0 10px 28px rgba(247, 114, 30, .08);
}

.profile-stat-card .label {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #64748b;
}

.profile-stat-card .value {
    font-size: 1.35rem;
    font-weight: 900;
    color: #0f172a;
    margin-top: 4px;
}

.profile-tabs .nav-link {
    border-radius: 12px !important;
    font-weight: 700;
    font-size: 13px;
    color: #64748b;
    border: 1px solid transparent;
    padding: 0.55rem 1rem;
}

.profile-tabs .nav-link:hover {
    color: var(--p-accent);
    background: rgba(247, 114, 30, .06);
}

.profile-tabs .nav-link.active {
    color: #fff !important;
    background: linear-gradient(135deg, var(--p-accent), #e86816) !important;
    border-color: transparent;
    box-shadow: 0 8px 20px rgba(247, 114, 30, .3);
}

.profile-card {
    border-radius: var(--p-radius);
    border: 1px solid #e2e8f0;
    box-shadow: 0 8px 28px rgba(15, 23, 42, .06);
    overflow: hidden;
}

.profile-card .card-header {
    background: linear-gradient(180deg, #fafafa 0%, #fff 100%);
    border-bottom: 1px solid #e2e8f0;
    font-weight: 800;
    padding: 1rem 1.25rem;
}

.profile-info-row {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
}

.profile-info-row:last-child {
    border-bottom: none;
}

.profile-info-row i {
    color: var(--p-accent);
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.profile-info-row .k {
    font-weight: 700;
    color: #64748b;
    min-width: 110px;
}

.btn-profile-primary {
    background: var(--p-accent);
    border-color: var(--p-accent);
    color: #fff;
    font-weight: 700;
    border-radius: 10px;
    padding: 0.55rem 1.35rem;
}

.btn-profile-primary:hover {
    background: #e06518;
    border-color: #e06518;
    color: #fff;
}

.profile-form-section-title {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #64748b;
    margin-bottom: 1rem;
    padding-bottom: 6px;
    border-bottom: 2px solid #f1f5f9;
}

.profile-urdu {
    font-family: 'Noto Nastaliq Urdu', serif;
    direction: rtl;
    text-align: right;
    line-height: 1.9;
}

#tab-profile .col-lg-5 .profile-card .card-body .profile-info-row:nth-child(1) .fw-semibold,
#tab-profile .col-lg-5 .profile-card .card-body .profile-info-row:nth-child(4) .fw-semibold,
#tab-profile .col-lg-5 .profile-card .card-body .profile-info-row:nth-child(5) .fw-semibold,
input[name="name"],
input[name="position"],
textarea[name="address"] {
    font-family: 'Noto Nastaliq Urdu', serif;
}

textarea[name="address"] {
    direction: rtl;
    text-align: right;
}

.profile-qr-card {
    border-radius: var(--p-radius);
    border: 1px solid #e2e8f0;
    background: linear-gradient(180deg, #fff, #fff8f3);
    box-shadow: 0 8px 28px rgba(15, 23, 42, .06);
    padding: 1rem;
    text-align: center;
}

.profile-qr-card img {
    width: 140px;
    height: 140px;
    object-fit: contain;
    padding: 8px;
    border-radius: 18px;
    background: #fff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
}
</style>

<div class="profile-page">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">My profile</li>
        </ol>
    </nav>

    {{-- Hero --}}
    <div class="profile-hero">
        <div class="profile-hero-banner"></div>
        <div class="profile-hero-body">
            <div class="d-flex flex-column flex-md-row align-items-start gap-3 gap-md-4">
                @if ($user->profile_photo)
                <img src="{{ asset($user->profile_photo) }}" alt="{{ $user->name }}" class="profile-avatar" />
                @else
                <img src="{{ asset('assets/img/avatars/5.png') }}" alt="{{ $user->name }}" class="profile-avatar" />
                @endif
                <div class="flex-grow-1 pt-md-2">
                    <span class="profile-role-pill {{ $isAdmin ? 'admin' : '' }}">
                        <i class="ti {{ $isAdmin ? 'ti-shield-check' : 'ti-user' }}"></i>
                        {{ $roleLabel }}
                    </span>
                    <h3 class="fw-bold text-heading mt-2 mb-1 profile-urdu">{{ $user->name }}</h3>
                    <p class="text-muted mb-2 small mb-md-3">
                        <i class="ti ti-mail me-1"></i>{{ $user->email ?? '—' }}
                        @if ($user->phone_number)
                        <span class="d-none d-sm-inline">·</span>
                        <span class="d-block d-sm-inline mt-1 mt-sm-0"><i
                                class="ti ti-phone me-1"></i>{{ $user->phone_number }}</span>
                        @endif
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="ti ti-info-circle me-1 text-primary"></i>
                        You are viewing <strong>your own</strong> profile, payments, and delivery orders only.
                        @if ($isAdmin)
                        Admin tools for other users are under <strong>User management</strong>.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick stats --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4">
            <div class="profile-stat-card">
                <div class="label">Total paid</div>
                <div class="value text-success">Rs {{ number_format((float) ($totalPaid ?? 0), 0) }}</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="profile-stat-card">
                <div class="label">Recent deliveries</div>
                <div class="value" style="color:var(--p-accent)">{{ $ordersCount }}</div>
                <div class="small text-muted mt-1">Last 5 listed below</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="profile-stat-card">
                <div class="label">Member since</div>
                <div class="value" style="font-size:1.05rem;">
                    {{ $user->created_at ? $user->created_at->timezone(config('app.timezone'))->format('M Y') : '—' }}
                </div>
                <div class="small text-muted mt-1">Account activity is private to you</div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="profile-tabs mb-3">
        <ul class="nav nav-pills flex-column flex-sm-row gap-2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab-profile" role="tab">
                    <i class="ti ti-user-check me-1"></i> Profile & settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-account" role="tab">
                    <i class="ti ti-receipt me-1"></i> Payments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-orders" role="tab">
                    <i class="ti ti-truck-delivery me-1"></i> My deliveries
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        {{-- Profile tab --}}
        <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
            <div class="row g-3">
                <div class="col-lg-5">
                    <div class="card profile-card mb-0">
                        <div class="card-header">About you</div>
                        <div class="card-body">
                            <div class="profile-info-row">
                                <i class="ti ti-user"></i>
                                <div><span class="k">Name</span><br><span
                                        class="fw-semibold text-heading profile-urdu">{{ $user->name }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <i class="ti ti-id"></i>
                                <div><span class="k">ID card</span><br><span
                                        class="fw-semibold">{{ $user->id_card ?? '—' }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <i class="ti ti-badge"></i>
                                <div><span class="k">Role</span><br><span
                                        class="fw-semibold">{{ $user->role ?? '—' }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <i class="ti ti-briefcase"></i>
                                <div><span class="k">Position</span><br><span
                                        class="fw-semibold">{{ $user->position ?? '—' }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <i class="ti ti-map-pin"></i>
                                <div><span class="k">Address</span><br><span
                                        class="fw-semibold">{{ $user->address ?? '—' }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <i class="ti ti-flag"></i>
                                <div><span class="k">Country</span><br><span class="fw-semibold">Pakistan</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid mt-3">
                        <a href="{{ route('password.edit') }}" class="btn btn-outline-secondary rounded-3">
                            <i class="ti ti-lock me-1"></i> Change password (dedicated page)
                        </a>
                    </div>
                    <div class="profile-qr-card mt-3">
                        <div class="small text-uppercase fw-bold text-muted mb-2">Profile QR</div>
                        <img src="{{ $qrImageUrl }}" alt="Profile Verification QR">
                        <div class="fw-semibold mt-3">Scan to Verify</div>
                        <div class="text-muted small mt-1">Yehi QR E ID-Card verification page open karega.</div>
                        <a href="{{ $verificationUrl }}" target="_blank" class="btn btn-profile-primary mt-3">
                            <i class="ti ti-external-link me-1"></i> Open Verification
                        </a>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card profile-card mb-0">
                        <div class="card-header">Edit profile</div>
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="profile-form-section-title">Personal</div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Full name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">ID card</label>
                                    <input type="text" name="id_card" class="form-control"
                                        value="{{ old('id_card', $user->id_card) }}" placeholder="CNIC / ID">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" readonly
                                        disabled>
                                    <div class="form-text">Email change is not available here; contact admin if
                                        needed.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Position</label>
                                    <input type="text" name="position" class="form-control bg-light"
                                        value="{{ old('position', $user->position) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone</label>
                                    <input type="text" name="phone_number" class="form-control"
                                        value="{{ old('phone_number', $user->phone_number) }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Address</label>
                                    <textarea name="address" class="form-control" rows="3"
                                        placeholder="Your address">{{ old('address', $user->address) }}</textarea>
                                </div>

                                <div class="profile-form-section-title mt-4">Photo</div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Profile picture</label>
                                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                    @if ($user->profile_photo)
                                    <div class="mt-2">
                                        <img src="{{ asset($user->profile_photo) }}" alt="Preview"
                                            class="rounded-3 border" style="width:88px;height:88px;object-fit:cover;">
                                    </div>
                                    @endif
                                </div>

                                <div class="profile-form-section-title mt-4">Security (optional)</div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">New password</label>
                                    <input type="password" name="password" class="form-control"
                                        autocomplete="new-password" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Confirm new password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        autocomplete="new-password">
                                </div>

                                <button type="submit" class="btn btn-profile-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Save changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payments tab --}}
        <div class="tab-pane fade" id="tab-account" role="tabpanel">
            <div class="card profile-card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span>Your payments</span>
                    <span class="badge bg-label-success rounded-pill px-3 py-2">Total: Rs
                        {{ number_format((float) ($totalPaid ?? 0), 2) }}</span>
                </div>
                <div class="card-body">
                    @if (isset($payments) && $payments->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Receipt</th>
                                    <th>Month</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Picture</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $p)
                                <tr>
                                    <td>{{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="fw-semibold">{{ $p->receipt_number ?? '—' }}</td>
                                    <td>{{ $p->month ?? '—' }}</td>
                                    <td><span class="badge bg-success">Rs
                                            {{ number_format((float) $p->amount, 2) }}</span></td>
                                    <td>{{ $p->created_at ? $p->created_at->format('d-m-Y') : '—' }}</td>
                                    <td>
                                        @if ($p->time)
                                        {{ \Carbon\Carbon::parse($p->time)->format('h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->picture)
                                        <a href="{{ asset($p->picture) }}" target="_blank" class="d-inline-block">
                                            <img src="{{ asset($p->picture) }}" alt="receipt" class="rounded"
                                                style="width:48px;height:48px;object-fit:cover;">
                                        </a>
                                        @else
                                        <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $payments->links() }}</div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="ti ti-receipt fs-1 d-block mb-2 opacity-50"></i>
                        No payment records yet.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Deliveries tab --}}
        <div class="tab-pane fade" id="tab-orders" role="tabpanel">
            <div class="card profile-card">
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <span>Your delivery orders</span>
                    <small class="text-muted">Latest 5 (your account only)</small>
                </div>
                <div class="card-body">
                    @if (isset($lastOrders) && $lastOrders->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Delivery date</th>
                                    <th>Time</th>
                                    <th>Items</th>
                                    <th class="d-none d-md-table-cell">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lastOrders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->delivery_date ?? '—' }}</td>
                                    <td>
                                        @if ($order->delivery_time)
                                        {{ \Carbon\Carbon::parse($order->delivery_time)->timezone(config('app.timezone'))->format('h:i A') }}
                                        @else
                                        —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge text-white"
                                            style="background:var(--p-accent)">{{ $order->items->sum('qty') }}</span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        {{ \Illuminate\Support\Str::words($order->notes ?? '—', 12, '…') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="ti ti-truck fs-1 d-block mb-2 opacity-50"></i>
                        No delivery orders found for your account.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: "{{ session('success') }}",
    confirmButtonColor: '#EC6A18',
    confirmButtonText: 'OK'
});
</script>
@endif


@if ($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#d33',
    confirmButtonText: 'OK'
});
</script>
@endif
@endsection
