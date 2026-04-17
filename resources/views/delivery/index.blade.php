@extends('home')

@section('title')
    <title>Deliveries | FBWS</title>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;margin:0;">Deliveries</h1>
            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('deliveries.create') }}" class="btn" style="background:#F7721E;color:#fff;">
                    <i class="ti ti-plus"></i> New Delivery
                </a>
            @endif
        </div>

        <div class="table text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Items</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th style="width:160px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($orders as $i => $o)
                        @php
                            // items array for modal
                            $itemsArr = [];
                            foreach ($o->items as $it) {
                                $itemsArr[] = [
                                    'name' => $it->item?->name ?? 'NA',
                                    'qty' => (int) ($it->qty ?? 0),
                                    'image' => $it->item?->image ? asset($it->item->image) : null,
                                ];
                            }

                            $orderPayload = [
                                'id' => $o->id,
                                'user_name' => $o->user?->name ?? 'NA',
                                'user_email' => $o->user?->email ?? '',
                                'date' => \Carbon\Carbon::parse($o->delivery_date)->format('d M Y'),
                                'time' => \Carbon\Carbon::parse($o->delivery_time)->format('h:i A'),
                                'created_by' => $o->creator?->name ?? 'NA',
                                'notes' => $o->notes ?? 'NA',
                                'items' => $itemsArr,
                                'print_url' => route('deliveries.print', \Illuminate\Support\Facades\Crypt::encryptString((string) $o->id)),
                            ];
                        @endphp

                        <tr>
                            <td>{{ $orders->firstItem() + $i }}</td>
                            <td class="fw-medium">{{ $o->user?->name ?? 'NA' }}</td>
                            <td>{{ \Carbon\Carbon::parse($o->delivery_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($o->delivery_time)->format('h:i A') }}</td>
                            <td>{{ $o->items->count() }}</td>
                            <td>{{ $o->creator?->name ?? 'NA' }}</td>
                            <td>{{ $o->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</td>

                            <td>
                                <div class="dropdown">
                                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu">

                                        {{-- ✅ VIEW (single modal) --}}
                                        <a href="javascript:void(0)" class="dropdown-item js-view-delivery"
                                            data-order='@json($orderPayload)'>
                                            <i class="ti ti-eye me-1"></i> View
                                        </a>
                                        @if (auth()->check() && auth()->user()->role === 'admin')
                                            {{-- EDIT --}}
                                            <a class="dropdown-item" href="{{ route('deliveries.edit', \Illuminate\Support\Facades\Crypt::encryptString((string) $o->id)) }}">
                                                <i class="ti ti-pencil me-1"></i> Edit
                                            </a>

                                            {{-- PRINT --}}
                                            <a class="dropdown-item" target="_blank"
                                                href="{{ route('deliveries.print', \Illuminate\Support\Facades\Crypt::encryptString((string) $o->id)) }}">
                                                <i class="ti ti-printer me-1"></i> Print Slip
                                            </a>

                                            <div class="dropdown-divider"></div>

                                            {{-- DELETE --}}
                                            <a class="dropdown-item text-danger"
                                                onclick="confirmDeleteDelivery({{ $o->id }})">
                                                <i class="ti ti-trash me-1"></i> Delete
                                            </a>

                                            <form id="delete-delivery-{{ $o->id }}"
                                                action="{{ route('deliveries.destroy', \Illuminate\Support\Facades\Crypt::encryptString((string) $o->id)) }}" method="POST"
                                                style="display:none;">
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

        <div class="p-3">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- ✅ SINGLE VIEW MODAL (table ke bahar) --}}
    <div class="modal fade" id="deliveryViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="dvTitle">Delivery Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">User</div>
                                <div class="fw-bold" id="dvUserName">NA</div>
                                <div class="text-muted" id="dvUserEmail"></div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Delivery Date/Time</div>
                                <div class="fw-bold" id="dvDateTime">NA</div>
                                <div class="text-muted small" id="dvCreatedBy">Created By: NA</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Notes</div>
                                <div class="fw-medium" id="dvNotes">NA</div>
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
                            <tbody id="dvItemsTbody">
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No items</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">

                    <a target="_blank" href="#" id="dvPrintBtn" class="btn" style="background:#F7721E;color:#fff;">
                        <i class="ti ti-printer"></i> Print Slip
                    </a>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <script>
        function confirmDeleteDelivery(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This delivery will be deleted permanently.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-delivery-' + id).submit();
                }
            });
        }

        // ✅ View modal handler
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-view-delivery');
            if (!btn) return;

            const data = btn.dataset.order ? JSON.parse(btn.dataset.order) : null;
            if (!data) return;

            document.getElementById('dvTitle').textContent = `Delivery Details #${data.id}`;
            document.getElementById('dvUserName').textContent = data.user_name || 'NA';
            document.getElementById('dvUserEmail').textContent = data.user_email || '';
            document.getElementById('dvDateTime').textContent = `${data.date} • ${data.time}`;
            document.getElementById('dvCreatedBy').textContent = `Created By: ${data.created_by || 'NA'}`;
            document.getElementById('dvNotes').textContent = data.notes || 'NA';

            const tbody = document.getElementById('dvItemsTbody');
            const items = data.items || [];

            if (items.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="4" class="text-center py-4 text-muted">No items found.</td></tr>`;
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

            // print url
            const printBtn = document.getElementById('dvPrintBtn');
            printBtn.href = data.print_url || '#';

            // open modal
            const modal = new bootstrap.Modal(document.getElementById('deliveryViewModal'));
            modal.show();
        });
    </script>
@endsection
