@extends('home')

@section('title')
    <title>Gallery | FBWS</title>
@endsection

@section('content')
    <style>
        .user-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .user-chip .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
            flex: 0 0 auto;
        }

        .user-chip .avatar-fallback {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            flex: 0 0 auto;
        }
    </style>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;">Gallery</h1>

            <button class="btn-employee"
                style="color:white; background:#F7721E; padding:10px 20px; border-radius:5px; border: none;"
                data-bs-toggle="modal" data-bs-target="#createImageModal">
                <i class="ti ti-plus"></i> نئی تصاویر / ویڈیوز
            </button>
        </div>

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Preview</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Uploaded</th>
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            <th>Uploaded By</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($images as $index => $img)
                        @php
                            $uploaderPhoto = !empty($img->user?->profile_photo) ? asset($img->user->profile_photo) : null;
                            $uploaderInitials = collect(explode(' ', trim((string) ($img->user?->name ?? ''))))
                                ->filter()
                                ->take(2)
                                ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))
                                ->implode('');
                            $uploaderInitials = $uploaderInitials !== '' ? $uploaderInitials : 'NA';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                @if ($img->isVideo())
                                    <a href="{{ $img->publicUrl() }}" target="_blank"
                                        class="d-flex align-items-center text-decoration-none text-dark">
                                        <video src="{{ $img->publicUrl() }}" class="rounded me-2" muted playsinline
                                            preload="metadata" style="width:78px;height:44px;object-fit:cover"></video>
                                    </a>
                                @else
                                    <a href="{{ $img->publicUrl() }}" target="_blank"
                                        class="d-flex align-items-center text-decoration-none text-dark">
                                        <img src="{{ $img->publicUrl() }}" class="rounded me-2"
                                            style="width:52px;height:38px;object-fit:cover" alt="Gallery">
                                    </a>
                                @endif
                            </td>

                            <td>{{ $img->isVideo() ? 'Video' : 'Image' }}</td>

                            <td>{{ $img->title ?? 'NA' }}</td>
                            <td>{{ $img->created_at?->format('d M, Y') }}</td>
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <td>
                                    <span class="user-chip">
                                        @if ($uploaderPhoto)
                                            <img class="avatar" src="{{ $uploaderPhoto }}" alt="User">
                                        @else
                                            <span class="avatar-fallback">{{ $uploaderInitials }}</span>
                                        @endif
                                        <span>{{ $img->user->name ?? 'Unknown User' }}</span>
                                    </span>
                                </td>
                            @endif
                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editImage{{ $img->id }}">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>

                                        <a class="dropdown-item text-danger" onclick="confirmDelete({{ $img->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </a>

                                        <form id="delete-form-{{ $img->id }}"
                                            action="{{ route('gallery.destroy', $img->id) }}" method="POST"
                                            style="display:none">
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

    {{-- EDIT MODALS --}}
    @foreach ($images as $img)
        <div class="modal fade" id="editImage{{ $img->id }}" tabindex="-1"
            aria-labelledby="editImageLabel{{ $img->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form method="POST" action="{{ route('gallery.update', $img->id) }}" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editImageLabel{{ $img->id }}">Edit media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ $img->title }}"
                                    placeholder="Optional title">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Replace file (optional)</label>
                                <input type="file" class="form-control" name="image"
                                    accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime">

                                <div class="mt-2">
                                    @if ($img->isVideo())
                                        <video src="{{ $img->publicUrl() }}" class="rounded w-100" controls
                                            style="max-height:220px;" preload="metadata"></video>
                                    @else
                                        <img src="{{ $img->publicUrl() }}" class="rounded"
                                            style="width:100%; max-height:220px; object-fit:cover;" alt="Current">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn" style="background:#F7721E; color:white;">
                                Update
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    @endforeach

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Upload images & videos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Title (optional)</label>
                        <input type="text" class="form-control" name="title"
                            placeholder="Title for all uploads (optional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select files <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="media[]" multiple
                            accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime" required>
                        <small class="text-muted">Multiple images or videos at once (max 50 MB each).</small>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn" style="background:#F7721E; color:white; font-weight:500;">
                            Upload
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Alerts --}}
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
                text: 'This item will be deleted permanently',
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
