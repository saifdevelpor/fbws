@extends('home')

@section('title')
    <title>Items | FBWS</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;">Items</h1>
            @if (auth()->check() && auth()->user()->role === 'admin')
                <button class="btn-employee"
                    style="color:white; background:#F7721E; padding:10px 20px; border-radius:5px; border: none;"
                    data-bs-toggle="modal" data-bs-target="#createItemModal">
                    <i class="ti ti-plus"></i> نئی چیز شامل کریں
                </button>
            @endif
        </div>

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Image</th>
                        @if (auth()->check() && auth()->user()->role === 'admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <a href="#" class="d-flex align-items-center text-decoration-none text-dark">
                                    <span class="fw-medium">{{ $item->name }}</span>
                                </a>
                            </td>

                            <td>
                                <span class="badge" style="background:#F7721E;color:#fff;">{{ $item->qty }}</span>
                            </td>

                            <td>
                                <img src="{{ $item->image ? asset($item->image) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}"
                                    class="rounded" style="width:55px;height:55px;object-fit:cover;">
                            </td>

                            {{-- ACTIONS --}}
                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <td>
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editItem{{ $item->id }}">
                                                <i class="ti ti-pencil me-1"></i> Edit
                                            </a>

                                            <a class="dropdown-item text-danger"
                                                onclick="confirmDelete({{ $item->id }})">
                                                <i class="ti ti-trash me-1"></i> Delete
                                            </a>

                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                style="display:none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- EDIT MODALS --}}
    @foreach ($items as $item)
        <div class="modal fade" id="editItem{{ $item->id }}" tabindex="-1"
            aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form method="POST" action="{{ route('items.update', $item->id) }}" enctype="multipart/form-data"
                    class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Item Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $item->name }}"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Qty</label>
                                <input type="number" class="form-control" name="qty" value="{{ $item->qty }}"
                                    min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Item Image</label>
                                <input type="file" class="form-control" name="image">
                            </div>
                        </div>

                        @if ($item->image)
                            <div class="mb-2">
                                <label class="form-label">Current Image</label><br>
                                <img src="{{ $item->image ? asset($item->image) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}"
                                    class="rounded" style="width:90px;height:90px;object-fit:cover;">
                            </div>
                        @endif

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn" style="background:#F7721E;color:white;">
                                Update Item
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createItemModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="e.g. Plates" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Qty <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="qty" placeholder="e.g. 50" min="0"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Item Image</label>
                        <input type="file" class="form-control" name="image">
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn" style="background:#F7721E;color:white;font-weight:500;">
                            Create Item
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

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
