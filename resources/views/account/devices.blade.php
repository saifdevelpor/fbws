@extends('home')

@section('title')
<title>Account Login Devices | FBWS</title>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div
        class="card-header bg-white border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 style="font-size:1.5rem;font-weight:700;margin:0;">Account Login Devices</h1>
            <div class="text-muted small mt-1">Devices and browsers where your account has been used.</div>
        </div>
        <form id="logout-all-devices-form" action="{{ route('account.devices.logout-all') }}" method="POST">
            @csrf
            <button id="logout-all-devices-btn" type="button" class="btn text-white" style="background:#F7721E;">
                <i class="ti ti-logout-2 me-1"></i> Logout from all devices
            </button>
        </form>
    </div>

    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                    <div class="text-muted small">Total devices</div>
                    <div class="fs-2 fw-bold">{{ $devices->count() }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                    <div class="text-muted small">Latest login</div>
                    <div class="fw-bold">
                        {{ optional($latestLoginAt)->timezone(config('app.timezone'))->format('d M Y, h:i A') ?? 'NA' }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-4 p-3 h-100 bg-light-subtle">
                    <div class="text-muted small">Account</div>
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">{{ auth()->user()->role }}</div>
                </div>
            </div>
        </div>

        @if ($devices->isEmpty())
        <div class="text-center py-5 text-muted">
            No login devices found yet.
        </div>
        @else
        <div class="row g-3">
            @foreach ($devices as $device)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <div class="fw-bold fs-5">{{ $device['device_name'] }}</div>
                                <div class="text-muted">{{ $device['browser'] }}</div>
                            </div>
                            <span
                                class="badge rounded-pill {{ $device['is_current'] ? 'bg-success' : 'bg-secondary' }}">
                                {{ $device['is_current'] ? 'Current' : 'Past' }}
                            </span>
                        </div>

                        <div class="small text-muted mb-1">Operating system</div>
                        <div class="mb-3">{{ $device['platform'] }}</div>

                        <div class="small text-muted mb-1">IP address</div>
                        <div class="mb-3">{{ $device['ip_address'] }}</div>

                        <div class="small text-muted mb-1">First login</div>
                        <div class="mb-3">
                            {{ optional($device['first_login_at'])->timezone(config('app.timezone'))->format('d M Y, h:i A') ?? 'NA' }}
                        </div>

                        <div class="small text-muted mb-1">Last login</div>
                        <div class="mb-3">
                            {{ optional($device['last_login_at'])->timezone(config('app.timezone'))->format('d M Y, h:i A') ?? 'NA' }}
                        </div>

                        <div class="small text-muted mb-1">Times used</div>
                        <div class="fw-semibold">{{ $device['login_count'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('logout-all-devices-form');
    var button = document.getElementById('logout-all-devices-btn');
    if (!form || !button) return;

    button.addEventListener('click', function() {
        if (typeof Swal === 'undefined') return;

        Swal.fire({
            title: 'Logout From All Devices?',
            text: 'Do you want to logout from all devices?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-confirm me-2',
                cancelButton: 'btn btn-label-secondary'
            },
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });


    form.addEventListener('submit', function(e) {
        e.preventDefault();
    });
});
</script>
<style>
.btn-confirm {
    background-color: #F7721E !important;
    border-color: #F7721E !important;
    color: #fff !important;
}

.btn-confirm:hover {
    background-color: #e56516 !important;
    border-color: #e56516 !important;
}
</style>
@endsection