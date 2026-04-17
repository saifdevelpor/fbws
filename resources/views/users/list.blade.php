@extends('home')

@section('title')
<title>Users | FBWS</title>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h1 style="font-size:1.5rem;font-weight:600;">Users</h1>

        <button class="btn-employee"
            style="color:white; background:#F7721E; padding:10px 20px; border-radius:5px; border: none;"
            data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="ti ti-plus"></i> نیا یوزر شامل کریں
        </button>
    </div>

    {{-- TABLE --}}
    <div class="card-datatable table-responsive text-nowrap">
        <table class="table" id="myTable1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Poition</th>
                    <th>Phone</th>
                    <th>WhatsApp</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $index => $user)
                @php
                // Role colors
                $roleColors = [
                'admin' => '#FF5733',
                'user' => '#FF8D1A',
                ];
                $bgColor = $roleColors[$user->role] ?? '#343A40';

                // Position badges
                $badges = [
                'Member' => 'secondary',
                'Legal Advisor' => 'dark',
                'Gernal Secretary' => 'warning',
                'Finance Secretary' => 'info',
                'President' => 'success',
                ];

                $photo = $user->profile_photo
                ? asset($user->profile_photo)
                : asset('assets/img/avatars/defualt_profile_imgavif.avif');

                // Build per-user WhatsApp link from phone number
                $rawPhone = preg_replace('/\D+/', '', $user->phone_number ?? '');
                if (strlen($rawPhone) > 0 && str_starts_with($rawPhone, '0')) {
                $rawPhone = '92' . substr($rawPhone, 1);
                }
                if (strlen($rawPhone) === 10) {
                $rawPhone = '92' . $rawPhone;
                }
                $waLink = strlen($rawPhone) >= 11 ? 'https://wa.me/' . $rawPhone : null;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>

                    {{-- USER + IMAGE --}}
                    <td>
                        <a href="#" class="d-flex align-items-center text-decoration-none text-dark">
                            <img src="{{ $photo }}" class="rounded-circle me-2"
                                style="width:32px;height:32px;object-fit:cover" alt="Profile">
                            <span class="fw-medium">{{ $user->name }}</span>
                        </a>
                    </td>

                    {{-- ROLE --}}
                    <td>
                        <span class="badge" style="background: {{ $bgColor }}; color: #fff;">
                            {{ $user->role }}
                        </span>
                    </td>
                    {{-- POSITION --}}
                    <td>
                        <span class="badge bg-{{ $badges[$user->position] ?? 'secondary' }}">
                            {{ $user->position ?? 'Member' }}
                        </span>
                    </td>

                    <td>{{ $user->phone_number ?? 'NA' }}</td>

                    {{-- Button --}}
                    <td>
                        @if ($waLink)
                        <a href="{{ $waLink }}" target="_blank" rel="noopener"
                            class="btn btn-success btn-sm d-inline-flex align-items-center" title="Open WhatsApp chat">
                            <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp
                        </a>
                        @else
                        <span class="badge bg-secondary">No Number</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td>
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>

                            <div class="dropdown-menu">
                                {{-- ✅ NEW VIEW --}}
                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#viewUser{{ $user->id }}">
                                    <i class="ti ti-eye me-1"></i> View
                                </a>

                                <a class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#editUser{{ $user->id }}">
                                    <i class="ti ti-pencil me-1"></i> Edit
                                </a>

                                <a class="dropdown-item"
                                    href="{{ route('account.membership-card.user', \Illuminate\Support\Facades\Crypt::encryptString((string) $user->id)) }}" target="_blank">
                                    <i class="ti ti-id-badge-2 me-1"></i> E ID-Card
                                </a>

                                <a class="dropdown-item text-danger" onclick="confirmDelete({{ $user->id }})">
                                    <i class="ti ti-trash me-1"></i> Delete
                                </a>

                                <form id="delete-form-{{ $user->id }}" action="{{ route('user-delete', \Illuminate\Support\Facades\Crypt::encryptString((string) $user->id)) }}"
                                    method="POST" style="display:none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- ✅ VIEW MODALS (Complete Profile) --}}
@foreach ($users as $user)
@php
$photo = $user->profile_photo
? asset($user->profile_photo)
: asset('assets/img/avatars/defualt_profile_imgavif.avif');

$roleColors = [
'admin' => '#FF5733',
'user' => '#FF8D1A',
];
$bgColor = $roleColors[$user->role] ?? '#343A40';
@endphp

<div class="modal fade" id="viewUser{{ $user->id }}" tabindex="-1" aria-labelledby="viewUserLabel{{ $user->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content user-view-modal">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="viewUserLabel{{ $user->id }}">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-2">

                {{-- Top Profile Header --}}
                <div class="user-view-top">
                    <div class="user-view-cover"></div>

                    <div class="user-view-profile d-flex align-items-center gap-3">
                        <div class="user-view-avatar">
                            <img src="{{ $photo }}" alt="Profile">
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h4 class="mb-0">{{ $user->name ?? 'NA' }}</h4>
                                <span class="badge" style="background: {{ $bgColor }}; color:#fff;">
                                    {{ strtoupper($user->role ?? 'NA') }}
                                </span>
                            </div>
                            <div class="text-muted small">
                                {{ $user->email ?? 'NA' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="row g-3 mt-3">

                    {{-- Name --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Name</div>
                            <div class="info-value">{{ $user->name ?? 'NA' }}</div>
                        </div>
                    </div>

                    {{-- Position --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Position</div>
                            <div class="info-value">{{ $user->position ?? 'NA' }}</div>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Role</div>
                            <div class="info-value">
                                <span class="badge" style="background: {{ $bgColor }}; color:#fff;">
                                    {{ strtoupper($user->role ?? 'NA') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- ID Card --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">ID Card</div>
                            <div class="info-value">{{ $user->id_card ?? 'NA' }}</div>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $user->phone_number ?? 'NA' }}</div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $user->email ?? 'NA' }}</div>
                        </div>
                    </div>

                    {{-- Joined --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Joined</div>
                            <div class="info-value">
                                {{ $user->created_at ? $user->created_at->timezone(config('app.timezone'))->format('d M Y') : 'NA' }}
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $user->address ?? 'NA' }}</div>
                        </div>
                    </div>

                </div>

                {{-- Footer Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="button" class="btn" style="background:#F7721E;color:#fff" data-bs-dismiss="modal"
                        data-bs-toggle="modal" data-bs-target="#editUser{{ $user->id }}">
                        Edit User
                    </button>
                </div>

            </div>

        </div>
    </div>
</div>
@endforeach


{{-- EDIT MODALS (placed outside the table) --}}
@foreach ($users as $index => $user)
<div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form method="POST" action="{{ route('user-update', \Illuminate\Support\Facades\Crypt::encryptString((string) $user->id)) }}" enctype="multipart/form-data"
            class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <!-- Row 1: Name -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="name{{ $user->id }}" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name{{ $user->id }}" name="name"
                            value="{{ $user->name }}" required>
                    </div>
                </div>

                <!-- Row 2: Email + Role -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email{{ $user->id }}" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email{{ $user->id }}" name="email"
                            value="{{ $user->email }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="role{{ $user->id }}" class="form-label">Role</label>
                        <select name="role" id="role{{ $user->id }}" class="form-control" required>
                            @foreach (['admin', 'user'] as $r)
                            <option value="{{ $r }}" {{ $user->role == $r ? 'selected' : '' }}>
                                {{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="position{{ $user->id }}" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position{{ $user->id }}" name="position"
                            value="{{ $user->position }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="id_card{{ $user->id }}" class="form-label">ID Card</label>
                        <input type="text" class="form-control" id="id_card{{ $user->id }}" name="id_card"
                            value="{{ $user->id_card }}">
                    </div>
                </div>

                <!-- Row 3: Profile Photo + Phone -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="profile_photo{{ $user->id }}" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="profile_photo{{ $user->id }}" name="profile_photo">
                        @if ($user->profile_photo)
                        <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" class="mt-2 rounded-circle"
                            style="width:80px; height:80px; object-fit:cover;">
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="phone{{ $user->id }}" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone{{ $user->id }}" name="phone_number"
                            value="{{ $user->phone_number }}" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mt-3">
                    <button type="submit" class="btn" style="background: #F7721E; color:white;">Update
                        User</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endforeach

{{-- CREATE MODAL --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <form method="POST" action="{{ route('user-save') }}" enctype="multipart/form-data" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter Password" required>
                </div>

                <!-- ID Card -->
                <div class="mb-3">
                    <label for="id_card" class="form-label">ID Card <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="id_card" name="id_card" placeholder="xxxxx-xxxxxxx-x">
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                        placeholder="Enter Phone Number" required>
                </div>

                <!-- Position -->
                <div class="mb-3">
                    <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="position" name="position" placeholder="Enter Position"
                        required>
                </div>

                <!-- Profile Photo -->
                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Profile Photo</label>
                    <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="d-grid mt-3">
                    <button type="submit" class="btn"
                        style="background: #F7721E; color: white; font-weight: 500;">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ✅ VIEW MODAL STYLES --}}
<style>
.user-view-modal {
    border-radius: 16px;
    overflow: hidden;
}

.user-view-top {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #eef2f7;
}

.user-view-cover {
    height: 110px;
    background: linear-gradient(135deg, rgba(247, 114, 30, .95), rgba(247, 114, 30, .25));
}

.user-view-profile {
    padding: 14px 16px 16px 16px;
    margin-top: -38px;
}

.user-view-avatar {
    width: 92px;
    height: 92px;
    border-radius: 999px;
    background: #fff;
    padding: 4px;
    border: 1px solid #eef2f7;
    box-shadow: 0 10px 30px rgba(2, 6, 23, .08);
    flex: 0 0 auto;
}

.user-view-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 999px;
    object-fit: cover;
    display: block;
}

.info-card {
    border: 1px solid #eef2f7;
    background: #fff;
    border-radius: 14px;
    padding: 12px 14px;
}

.info-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 700;
    margin-bottom: 3px;
}

.info-value {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    word-break: break-word;
}
</style>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- DELETE --}}
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
        text: 'This user will be deleted permanently',
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

@endsection
