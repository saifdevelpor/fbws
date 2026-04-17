@extends('home')

@section('title')
    <title>Events | FBWS</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;">Events</h1>

            <button class="btn-employee"
                style="color:white; background:#F7721E; padding:10px 20px; border-radius:5px; border:none;"
                data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="ti ti-plus"></i> نئی تقریب شامل کریں
            </button>
        </div>

        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($events as $index => $event)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                @php
                                    $firstMedia = $event->media->sortBy(fn ($m) => [$m->sort_order, $m->id])->first();
                                @endphp
                                <div class="d-flex align-items-center">
                                    @if ($firstMedia && $firstMedia->type === 'video')
                                        <video src="{{ asset($firstMedia->path) }}" class="rounded-circle me-2"
                                            muted playsinline preload="metadata"
                                            style="width:32px;height:32px;object-fit:cover"></video>
                                    @else
                                        <img src="{{ $event->listThumbnailUrl() }}" class="rounded-circle me-2"
                                            style="width:32px;height:32px;object-fit:cover" alt="Event">
                                    @endif
                                    <span class="fw-medium">{{ $event->name }}</span>
                                </div>
                            </td>

                            <td style="max-width:260px; white-space:normal;">
                                {{ \Illuminate\Support\Str::limit($event->description, 80) }}
                            </td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editEvent{{ $event->id }}">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>

                                        <a class="dropdown-item text-danger" onclick="confirmDelete({{ $event->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </a>

                                        <form id="delete-form-{{ $event->id }}"
                                            action="{{ route('event-delete', $event->id) }}" method="POST"
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
    @foreach ($events as $event)
        <div class="modal fade" id="editEvent{{ $event->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form method="POST" action="{{ route('event-update', $event->id) }}" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Event Name *</label>
                            <input type="text" class="form-control" name="name" value="{{ $event->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ $event->description }}</textarea>
                        </div>

                        @if ($event->media->isNotEmpty())
                            <div class="mb-3">
                                <label class="form-label">Current media</label>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @foreach ($event->media->sortBy(fn ($m) => [$m->sort_order, $m->id]) as $m)
                                        <div class="border rounded p-1 text-center" style="max-width:120px;">
                                            @if ($m->type === 'video')
                                                <video src="{{ asset($m->path) }}" class="rounded" muted playsinline
                                                    preload="metadata" style="width:100px;height:70px;object-fit:cover"></video>
                                            @else
                                                <img src="{{ asset($m->path) }}" class="rounded"
                                                    style="width:100px;height:70px;object-fit:cover;" alt="">
                                            @endif
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox"
                                                    name="remove_media_ids[]" value="{{ $m->id }}"
                                                    id="rm{{ $m->id }}">
                                                <label class="form-check-label small" for="rm{{ $m->id }}">Remove</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Add images / videos</label>
                            <input type="file" class="form-control" name="media[]" multiple
                                accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime">
                            <small class="text-muted">Optional. Multiple files allowed (max 50 MB each).</small>
                        </div>

                        <div class="d-grid mt-3">
                            <button class="btn" style="background:#F7721E;color:#fff;">Update Event</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach


    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('event-save') }}" enctype="multipart/form-data" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Event Name *</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Event Name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Short description..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Images / videos</label>
                        <input type="file" class="form-control" name="media[]" multiple
                            accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime">
                        <small class="text-muted">Optional. You can select multiple files (max 50 MB each).</small>
                    </div>

                    <div class="d-grid mt-3">
                        <button class="btn" style="background:#F7721E;color:#fff;font-weight:500;">Create
                            Event</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert --}}
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
                text: 'This event will be deleted permanently',
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
