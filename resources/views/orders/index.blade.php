@extends('home')

@section('title')
    <title>Orders | FBWS</title>
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

        @media (max-width: 576px) {
            .table-responsive {
                overflow: visible !important;
            }

            .mob-table thead {
                display: none;
            }

            .mob-table,
            .mob-table tbody,
            .mob-table tr,
            .mob-table td {
                display: block;
                width: 100%;
            }

            .mob-table tr {
                border: 1px solid #e9eef6;
                border-radius: 14px;
                padding: 10px;
                margin-bottom: 10px;
                background: #fff;
                box-shadow: 0 8px 20px rgba(2, 6, 23, .04);
            }

            .mob-table td {
                border: none !important;
                padding: 6px 0 !important;
                display: flex;
                justify-content: space-between;
                gap: 10px;
            }

            .mob-table td::before {
                content: attr(data-label);
                font-weight: 800;
                color: #64748b;
                font-size: 12px;
                flex: 0 0 42%;
            }

            .mob-table td .val {
                text-align: right;
                font-weight: 700;
                color: #0f172a;
                flex: 1;
                word-break: break-word;
            }

            .mob-actions {
                display: flex;
                justify-content: flex-end;
                gap: 6px;
            }

            .mob-actions .dropdown-menu {
                min-width: 250px;
            }
        }
    </style>
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;margin:0;">
                @if ($isAdmin)
                    All Order Requests
                @else
                    My Order Requests
                @endif
            </h1>
            @if (auth()->check() && strtolower(auth()->user()->role) === 'user')
                <a href="{{ route('orders.create') }}" class="btn" style="background:#F7721E;color:#fff;">
                    <i class="ti ti-plus"></i> New Order Request
                </a>
            @endif
        </div>

        <div class="card-body"></div>

        <div class="table">
            <div class="table-header">
                <table class="table mob-table" id="myTable1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Created At</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $order)
                            @php
                                $userPhoto = !empty($order->user?->profile_photo) ? asset($order->user->profile_photo) : null;
                                $initials = collect(explode(' ', trim((string) ($order->user?->name ?? ''))))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))
                                    ->implode('');
                                $initials = $initials !== '' ? $initials : 'NA';

                                $orderPayload = [
                                    'id' => $order->id,
                                    'user_name' => $order->user?->name ?? 'N/A',
                                    'user_email' => $order->user?->email ?? '',
                                    'user_photo' => $userPhoto,
                                    'notes' => $order->notes ?: '-',
                                    'status' => ucfirst($order->status),
                                    'created_at' => $order->created_at
                                        ?->timezone(config('app.timezone'))
                                        ->format('d M Y, h:i A'),
                                    'items' => $order->items
                                        ->map(function ($row) {
                                            return [
                                                'name' => $row->item?->name ?? 'Item',
                                                'qty' => (int) $row->qty,
                                                'image' => !empty($row->item?->image) ? asset($row->item->image) : null,
                                            ];
                                        })
                                        ->values()
                                        ->all(),
                                ];
                            @endphp
                            <tr>
                                <td data-label="#"><span class="val">{{ ($orders->firstItem() ?? 1) + $i }}</span></td>
                                <td data-label="User">
                                    <span class="val">
                                        <span class="user-chip">
                                            @if ($userPhoto)
                                                <img class="avatar" src="{{ $userPhoto }}" alt="User">
                                            @else
                                                <span class="avatar-fallback">{{ $initials }}</span>
                                            @endif
                                            <span>{{ $order->user?->name ?? 'N/A' }}</span>
                                        </span>
                                    </span>
                                </td>
                                <td data-label="Items">
                                    <span class="val">
                                        @foreach ($order->items as $row)
                                            <div>{{ $row->item?->name ?? 'Item' }} x {{ $row->qty }}</div>
                                        @endforeach
                                    </span>
                                </td>
                                <td data-label="Status">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'confirmed' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <span class="val"><span
                                            class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span></span>
                                </td>
                                <td data-label="Notes"><span class="val">{{ $order->notes ?: '-' }}</span></td>
                                <td data-label="Created At"><span
                                        class="val">{{ $order->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</span>
                                </td>
                                <td data-label="Action">
                                    <div class="dropdown mob-actions">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="javascript:void(0)" class="dropdown-item js-view-order"
                                                data-order='@json($orderPayload)'>
                                                <i class="ti ti-eye me-1"></i> View
                                            </a>
                                            <a class="dropdown-item" target="_blank"
                                                href="{{ route('orders.print', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}">
                                                <i class="ti ti-printer me-1"></i> Print Slip
                                            </a>

                                            @if ($isAdmin)
                                                <form action="{{ route('orders.status', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}" method="POST"
                                                    class="px-3 py-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label class="form-label small mb-1">Update Status</label>
                                                    <select name="status" class="form-select form-select-sm mb-2">
                                                        @foreach (['pending', 'confirmed', 'delivered', 'cancelled'] as $st)
                                                            <option value="{{ $st }}"
                                                                {{ $order->status === $st ? 'selected' : '' }}>
                                                                {{ ucfirst($st) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-sm btn-dark w-100" type="submit">Save
                                                        Status</button>
                                                </form>

                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                    onclick="confirmDeleteOrder({{ $order->id }})">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </a>
                                                <form id="delete-order-{{ $order->id }}"
                                                    action="{{ route('orders.destroy', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @else
                                                <form action="{{ route('orders.whatsapp-admin', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}"
                                                    method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="ti ti-brand-whatsapp me-1"></i> Order send to Admin
                                                    </button>
                                                </form>
                                                <a class="dropdown-item" href="{{ route('orders.edit', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}">
                                                    <i class="ti ti-pencil me-1"></i> Edit
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="javascript:void(0)" class="dropdown-item text-danger"
                                                    onclick="confirmDeleteOrder({{ $order->id }})">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </a>
                                                <form id="delete-order-{{ $order->id }}"
                                                    action="{{ route('orders.destroy', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="ovTitle">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">User</div>
                                <div class="d-flex align-items-center gap-2">
                                    <img id="ovUserPhoto" alt="User" style="width:44px;height:44px;border-radius:999px;object-fit:cover;border:1px solid #e2e8f0;display:none;">
                                    <div>
                                        <div class="fw-bold" id="ovUserName">NA</div>
                                        <div class="text-muted" id="ovUserEmail"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Status</div>
                                <div class="fw-bold" id="ovStatus">NA</div>
                                <div class="text-muted small" id="ovCreatedAt"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Notes</div>
                                <div class="fw-medium" id="ovNotes">NA</div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2">Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle no-card-convert">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th style="width:90px;">Image</th>
                                    <th>Item</th>
                                    <th style="width:120px;">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="ovItemsTbody">
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No items found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                html: @json(session('success')) +
                    @json(
                        session('open_whatsapp_url')
                            ? '<br><a target="_blank" href="' .
                                session('open_whatsapp_url') .
                                '" style="font-weight:600;">Open WhatsApp</a>'
                            : ''),
                timer: 3200,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Notice',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('open_whatsapp_url'))
        <script>
            window.open(@json(session('open_whatsapp_url')), '_blank');
        </script>
    @endif

    <script>
        function confirmDeleteOrder(id) {
            if (confirm('Are you sure you want to delete this order?')) {
                document.getElementById('delete-order-' + id).submit();
            }
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-view-order');
            if (!btn) return;

            const data = btn.dataset.order ? JSON.parse(btn.dataset.order) : null;
            if (!data) return;

            document.getElementById('ovTitle').textContent = `Order Details #${data.id}`;
            document.getElementById('ovUserName').textContent = data.user_name || 'NA';
            document.getElementById('ovUserEmail').textContent = data.user_email || '';
            const userPhotoEl = document.getElementById('ovUserPhoto');
            if (data.user_photo) {
                userPhotoEl.src = data.user_photo;
                userPhotoEl.style.display = 'block';
            } else {
                userPhotoEl.removeAttribute('src');
                userPhotoEl.style.display = 'none';
            }
            document.getElementById('ovStatus').textContent = data.status || 'NA';
            document.getElementById('ovCreatedAt').textContent = data.created_at || '';
            document.getElementById('ovNotes').textContent = data.notes || 'NA';

            const tbody = document.getElementById('ovItemsTbody');
            const items = data.items || [];

            if (items.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="4" class="text-center py-4 text-muted">No items found.</td></tr>';
            } else {
                tbody.innerHTML = items.map((it, idx) => {
                    const img = it.image ?
                        `<img src="${it.image}" style="width:70px;height:55px;object-fit:cover;border-radius:10px;">` :
                        `<span class="text-muted">NA</span>`;
                    return `
                    <tr>
                        <td>${idx + 1}</td>
                        <td>${img}</td>
                        <td class="fw-medium">${it.name ?? 'NA'}</td>
                        <td><span class="badge bg-primary">${it.qty ?? 0}</span></td>
                    </tr>
                    `;
                }).join('');
            }

            const modal = new bootstrap.Modal(document.getElementById('orderViewModal'));
            modal.show();
        });
    </script>
@endsection
