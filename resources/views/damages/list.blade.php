@extends('home')

@section('title')
    <title>Damages | FBWS</title>
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

        .dropdown-avatar {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            flex: 0 0 auto;
        }

        .dropdown-item-thumb {
            width: 36px;
            height: 28px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            flex: 0 0 auto;
        }

        .damage-view-head {
            border: 1px solid #eef2f7;
            border-radius: 14px;
            padding: 12px;
            background: #fafcff;
        }

        .damage-view-item-img {
            width: 100%;
            max-height: 160px;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 8px;
        }

        .damage-view-stat {
            border: 1px solid #eef2f7;
            border-radius: 10px;
            background: #fff;
            padding: 10px 12px;
        }

        .damage-view-label {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .damage-view-value {
            color: #0f172a;
            font-weight: 700;
            line-height: 1.4;
            word-break: break-word;
        }

        .damage-cart-row {
            border: 1px dashed #e2e8f0;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            background: #f8fafc;
        }
    </style>

    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h1 style="font-size:1.5rem;font-weight:600;">Damages</h1>

            @if (strtolower($auth->role) === 'admin')
                <button class="btn-employee"
                    style="color:white; background:#F7721E; padding:10px 20px; border-radius:5px; border: none;"
                    data-bs-toggle="modal" data-bs-target="#createDamageModal">
                    <i class="ti ti-plus"></i> نیا ڈیمیج شامل کریں
                </button>
            @endif
        </div>

        {{-- TABLE --}}
        <div class="card-datatable table-responsive text-nowrap">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Fine</th>
                        <th>Date</th>
                        <th>Note</th>

                        <th>Actions</th>

                    </tr>
                </thead>

                <tbody>
                    @php
                        $groupedDamages = $damages->groupBy(function ($r) {
                            $stamp = optional($r->created_at)->format('Y-m-d H:i:s') ?? 'na';
                            return implode('|', [
                                (int) $r->user_id,
                                (string) ($r->damage_date ?? 'na'),
                                trim((string) ($r->note ?? '')),
                                $stamp,
                            ]);
                        })->values();
                    @endphp
                    @foreach ($groupedDamages as $index => $group)
                        @php
                            $d = $group->first();
                            $isGroup = $group->count() > 1;
                            $groupQty = (int) $group->sum('qty');
                            $groupFine = (float) $group->sum('fine');
                            $groupIds = $group->pluck('id')->implode(',');
                            $waLines = $group
                                ->map(
                                    fn($row) => ($row->item->name ?? 'NA') .
                                        ' (Qty: ' .
                                        (int) ($row->qty ?? 0) .
                                        ', Fine: Rs ' .
                                        (int) ($row->fine ?? 0) .
                                        ')',
                                )
                                ->implode("\n");
                            $waText = "Damage Details\n"
                                . "User: " . ($d->user->name ?? 'NA') . "\n"
                                . "Date: " . ($d->damage_date ?? 'NA') . "\n"
                                . "Total Fine: Rs " . (int) $groupFine . "\n\n"
                                . "Items:\n" . $waLines . "\n\n"
                                . "Print Link: " . route('damage-print', ['ids' => $groupIds]);
                            $waCardLink = !empty($d->user?->phone_number)
                                ? 'https://wa.me/' . \App\Support\Phone::toWhatsapp((string) $d->user->phone_number) . '?text=' . rawurlencode($waText)
                                : null;
                        @endphp
                        @php
                            $userPhoto = !empty($d->user?->profile_photo) ? asset($d->user->profile_photo) : null;
                            $initials = collect(explode(' ', trim((string) ($d->user?->name ?? ''))))
                                ->filter()
                                ->take(2)
                                ->map(fn($p) => strtoupper(mb_substr($p, 0, 1)))
                                ->implode('');
                            $initials = $initials !== '' ? $initials : 'NA';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="user-chip">
                                    @if ($userPhoto)
                                        <img class="avatar" src="{{ $userPhoto }}" alt="User">
                                    @else
                                        <span class="avatar-fallback">{{ $initials }}</span>
                                    @endif
                                    <span>{{ $d->user->name ?? 'NA' }}</span>
                                </span>
                            </td>
                            <td>
                                @if ($isGroup)
                                    <span class="badge bg-label-info">{{ $group->count() }} items</span>
                                @else
                                    {{ $d->item->name ?? 'NA' }}
                                @endif
                            </td>
                            <td>{{ $groupQty }}</td>
                            <td>{{ (int) $groupFine }}</td>
                            <td>{{ $d->damage_date ?? 'NA' }}</td>
                            <td>{{ $d->note ?? '—' }}</td>


                            <td>
                                <div class="d-flex align-items-center gap-1 justify-content-end">
                                    @if (strtolower($auth->role) === 'admin' && $waCardLink)
                                        <a href="{{ $waCardLink }}" target="_blank" rel="noopener"
                                            class="btn btn-success btn-sm d-inline-flex align-items-center"
                                            title="WhatsApp Share">
                                            <i class="ti ti-brand-whatsapp"></i>
                                        </a>
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                        <a class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#viewDamageGroup{{ $index }}">
                                            <i class="ti ti-eye me-1"></i> View
                                        </a>
                                        @if (strtolower($auth->role) === 'admin')
                                            <a class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editDamage{{ $d->id }}">
                                                <i class="ti ti-pencil me-1"></i> Edit
                                            </a>

                                            @if ($isGroup)
                                                <a class="dropdown-item text-danger"
                                                    onclick="confirmDeleteGroup({{ $index }})">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </a>
                                                <form id="delete-group-form-{{ $index }}"
                                                    action="{{ route('damage-delete-group') }}" method="POST"
                                                    style="display:none">
                                                    @csrf
                                                    @method('DELETE')
                                                    @foreach ($group as $row)
                                                        <input type="hidden" name="ids[]" value="{{ $row->id }}">
                                                    @endforeach
                                                </form>
                                            @else
                                                <a class="dropdown-item text-danger"
                                                    onclick="confirmDelete({{ $d->id }})">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </a>
                                                <form id="delete-form-{{ $d->id }}"
                                                    action="{{ route('damage-delete', $d->id) }}" method="POST"
                                                    style="display:none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    {{-- GROUPED VIEW MODALS --}}
    @foreach ($groupedDamages as $index => $group)
        @php
            $d = $group->first();
            $userPhoto = $d->user?->profile_photo
                ? asset($d->user->profile_photo)
                : asset('assets/img/avatars/5.png');
            $groupFine = (float) $group->sum('fine');
            $groupQty = (int) $group->sum('qty');
            $groupIds = $group->pluck('id')->implode(',');
            $printUrl = route('damage-print', ['ids' => $groupIds]);
            $waPreviewLines = $group
                ->map(
                    fn($row) => ($row->item->name ?? 'NA') .
                        ' (Qty: ' .
                        (int) ($row->qty ?? 0) .
                        ', Fine: Rs ' .
                        (int) ($row->fine ?? 0) .
                        ')',
                )
                ->implode("\n");
            $waMsg = "Damage Details\n"
                . "User: " . ($d->user->name ?? 'NA') . "\n"
                . "Date: " . ($d->damage_date ?? 'NA') . "\n"
                . "Total Fine: Rs " . (int) $groupFine . "\n\n"
                . "Items:\n" . $waPreviewLines . "\n\n"
                . "Print Link: " . $printUrl;
            $waPreviewLink = !empty($d->user?->phone_number)
                ? 'https://wa.me/' . \App\Support\Phone::toWhatsapp((string) $d->user->phone_number) . '?text=' . rawurlencode($waMsg)
                : null;
        @endphp
        <div class="modal fade" id="viewDamageGroup{{ $index }}" tabindex="-1"
            aria-labelledby="viewDamageGroupModalLabel{{ $index }}" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDamageGroupModalLabel{{ $index }}">Damage Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="damage-view-head mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $userPhoto }}" class="rounded-circle" width="44" height="44"
                                    style="object-fit:cover;" alt="avatar">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $d->user->name ?? 'NA' }}</div>
                                    <div class="text-muted small">{{ $d->user->id_card ?? '' }}</div>
                                </div>
                                <span class="badge bg-label-warning">{{ $group->count() }} item(s)</span>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Total Qty</div>
                                    <div class="damage-view-value">{{ $groupQty }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Total Fine</div>
                                    <div class="damage-view-value">Rs {{ (int) $groupFine }}</div>
                                </div>
                            </div>
                        </div>

                        @foreach ($group as $row)
                            @php
                                $rowItemImage = $row->item?->image
                                    ? asset(str_replace('\\', '/', $row->item->image))
                                    : null;
                            @endphp
                            <div class="damage-cart-row">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if ($rowItemImage)
                                        <img src="{{ $rowItemImage }}" alt="Item" class="dropdown-item-thumb">
                                    @else
                                        <span
                                            class="dropdown-item-thumb d-inline-flex align-items-center justify-content-center small text-muted">NA</span>
                                    @endif
                                    <span class="fw-semibold">{{ $row->item->name ?? 'NA' }}</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="damage-view-stat">
                                            <div class="damage-view-label">Qty</div>
                                            <div class="damage-view-value">{{ (int) ($row->qty ?? 0) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="damage-view-stat">
                                            <div class="damage-view-label">Fine</div>
                                            <div class="damage-view-value">Rs {{ (int) ($row->fine ?? 0) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="damage-view-stat mt-2">
                            <div class="damage-view-label">Damage Date</div>
                            <div class="damage-view-value">{{ $d->damage_date ?? 'NA' }}</div>
                        </div>
                        <div class="damage-view-stat mt-2">
                            <div class="damage-view-label">Note</div>
                            <div class="damage-view-value">{{ $d->note ?? '—' }}</div>
                        </div>

                        @if (strtolower($auth->role) === 'admin')
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ $printUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="ti ti-printer me-1"></i> Print
                                </a>
                                @if ($waPreviewLink)
                                    <a href="{{ $waPreviewLink }}" target="_blank" rel="noopener"
                                        class="btn btn-success btn-sm">
                                        <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp Share
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- EDIT MODALS --}}
    @foreach ($damages as $d)
        {{-- VIEW MODAL --}}
        <div class="modal fade" id="viewDamage{{ $d->id }}" tabindex="-1"
            aria-labelledby="viewDamageModalLabel{{ $d->id }}" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDamageModalLabel{{ $d->id }}">Damage Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    @php
                        $userPhoto = $d->user?->profile_photo
                            ? asset($d->user->profile_photo)
                            : asset('assets/img/avatars/5.png');
                        $itemImage = $d->item?->image
                            ? asset(str_replace('\\', '/', $d->item->image))
                            : null;
                    @endphp

                    <div class="modal-body">
                        <div class="damage-view-head mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $userPhoto }}" class="rounded-circle" width="44" height="44"
                                    style="object-fit:cover;" alt="avatar">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $d->user->name ?? 'NA' }}</div>
                                    <div class="text-muted small">{{ $d->user->id_card ?? '' }}</div>
                                </div>
                                <span class="badge bg-label-warning">Damage Record</span>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                @if ($itemImage)
                                    <img src="{{ $itemImage }}" alt="Item image" class="damage-view-item-img">
                                @else
                                    <div
                                        class="damage-view-item-img d-flex align-items-center justify-content-center text-muted">
                                        No item image available
                                    </div>
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Item</div>
                                    <div class="damage-view-value">{{ $d->item->name ?? 'NA' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Qty</div>
                                    <div class="damage-view-value">{{ (int) ($d->qty ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Fine</div>
                                    <div class="damage-view-value">Rs {{ (int) ($d->fine ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Damage Date</div>
                                    <div class="damage-view-value">{{ $d->damage_date ?? 'NA' }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="damage-view-stat">
                                    <div class="damage-view-label">Note</div>
                                    <div class="damage-view-value">{{ $d->note ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            @if (strtolower($auth->role) === 'admin')
                                <button type="button" class="btn text-white" style="background:#F7721E"
                                    data-bs-dismiss="modal" data-bs-toggle="modal"
                                    data-bs-target="#editDamage{{ $d->id }}">
                                    Edit
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (strtolower($auth->role) === 'admin')
            <div class="modal fade" id="editDamage{{ $d->id }}" tabindex="-1"
                aria-labelledby="editDamageModalLabel{{ $d->id }}" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <form method="POST" action="{{ route('damage-update', $d->id) }}" class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title" id="editDamageModalLabel{{ $d->id }}">Edit Damage</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            @php
                                $selectedUser = $users->firstWhere('id', $d->user_id);
                                $selectedItem = $items->firstWhere('id', $d->item_id);
                                $selectedUserPhoto = !empty($selectedUser?->profile_photo)
                                    ? asset(str_replace('\\', '/', $selectedUser->profile_photo))
                                    : asset('assets/img/avatars/5.png');
                                $selectedItemImage = !empty($selectedItem?->image)
                                    ? asset(str_replace('\\', '/', $selectedItem->image))
                                    : '';
                            @endphp

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">User</label>
                                    <div data-avatar-dropdown>
                                        <input type="hidden" name="user_id" value="{{ $d->user_id }}" required>
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="d-flex align-items-center gap-2">
                                                <img class="dropdown-avatar" src="{{ $selectedUserPhoto }}"
                                                    alt="User">
                                                <span class="fw-semibold">
                                                    {{ $selectedUser?->name ?? 'Select User' }}
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                                            <li class="dropdown-header">Select user</li>
                                            @foreach ($users as $u)
                                                @php
                                                    $uPhoto = !empty($u->profile_photo)
                                                        ? asset(str_replace('\\', '/', $u->profile_photo))
                                                        : asset('assets/img/avatars/5.png');
                                                @endphp
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="javascript:void(0);" data-value="{{ $u->id }}"
                                                        data-label="{{ $u->name }}"
                                                        data-avatar="{{ $uPhoto }}">
                                                        <img src="{{ $uPhoto }}" alt="User" class="dropdown-avatar">
                                                        <span class="fw-semibold">{{ $u->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Item</label>
                                    <div data-avatar-dropdown>
                                        <input type="hidden" name="item_id" value="{{ $d->item_id }}" required>
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="d-flex align-items-center gap-2">
                                                @if ($selectedItemImage)
                                                    <img class="dropdown-item-thumb" src="{{ $selectedItemImage }}"
                                                        alt="Item">
                                                @else
                                                    <span class="dropdown-item-thumb d-inline-flex align-items-center justify-content-center small text-muted">NA</span>
                                                @endif
                                                <span class="fw-semibold">{{ $selectedItem?->name ?? 'Select Item' }}</span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                                            <li class="dropdown-header">Select item</li>
                                            @foreach ($items as $it)
                                                @php
                                                    $itImage = !empty($it->image)
                                                        ? asset(str_replace('\\', '/', $it->image))
                                                        : '';
                                                @endphp
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="javascript:void(0);" data-value="{{ $it->id }}"
                                                        data-label="{{ $it->name }}"
                                                        data-avatar="{{ $itImage }}">
                                                        @if ($itImage)
                                                            <img src="{{ $itImage }}" alt="Item"
                                                                class="dropdown-item-thumb">
                                                        @else
                                                            <span class="dropdown-item-thumb d-inline-flex align-items-center justify-content-center small text-muted">NA</span>
                                                        @endif
                                                        <span class="fw-semibold">{{ $it->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Qty</label>
                                    <input type="number" class="form-control" name="qty" min="1"
                                        value="{{ $d->qty }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Fine</label>
                                    <input type="number" step="0.01" min="0" class="form-control"
                                        name="fine" value="{{ $d->fine }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control" name="damage_date"
                                        value="{{ $d->damage_date }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea class="form-control" rows="2" name="note">{{ $d->note }}</textarea>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn" style="background:#F7721E; color:white;">
                                    Update Damage
                                </button>
                            </div>

                            @php
                                $editPrintUrl = route('damage-print', ['ids' => $d->id]);
                                $editWaText = "Damage Details\n"
                                    . "User: " . ($d->user->name ?? 'NA') . "\n"
                                    . "Date: " . ($d->damage_date ?? 'NA') . "\n"
                                    . "Item: " . ($d->item->name ?? 'NA') . "\n"
                                    . "Qty: " . (int) ($d->qty ?? 0) . "\n"
                                    . "Fine: Rs " . (int) ($d->fine ?? 0) . "\n\n"
                                    . "Print Link: " . $editPrintUrl;
                                $editWaLink = !empty($d->user?->phone_number)
                                    ? 'https://wa.me/' . \App\Support\Phone::toWhatsapp((string) $d->user->phone_number) . '?text=' . rawurlencode($editWaText)
                                    : null;
                            @endphp
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ $editPrintUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="ti ti-printer me-1"></i> Print
                                </a>
                                @if ($editWaLink)
                                    <a href="{{ $editWaLink }}" target="_blank" rel="noopener"
                                        class="btn btn-success btn-sm">
                                        <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp Share
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    {{-- CREATE MODAL --}}
    @if (strtolower($auth->role) === 'admin')
        <div class="modal fade" id="createDamageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form method="POST" action="{{ route('damage-save') }}" class="modal-content">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Create Damage</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">User <span class="text-danger">*</span></label>
                                @php
                                    $oldUser = $users->firstWhere('id', (int) old('user_id'));
                                    $oldUserPhoto = !empty($oldUser?->profile_photo)
                                        ? asset(str_replace('\\', '/', $oldUser->profile_photo))
                                        : asset('assets/img/avatars/5.png');
                                @endphp
                                <div data-avatar-dropdown>
                                    <input type="hidden" name="user_id" value="{{ old('user_id') }}" required>
                                    <button type="button"
                                        class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="d-flex align-items-center gap-2">
                                            <img class="dropdown-avatar" src="{{ $oldUserPhoto }}" alt="User">
                                            <span class="fw-semibold">{{ $oldUser?->name ?? 'Select User' }}</span>
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                                        <li class="dropdown-header">Select user</li>
                                        @foreach ($users as $u)
                                            @php
                                                $uPhoto = !empty($u->profile_photo)
                                                    ? asset(str_replace('\\', '/', $u->profile_photo))
                                                    : asset('assets/img/avatars/5.png');
                                            @endphp
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                    href="javascript:void(0);" data-value="{{ $u->id }}"
                                                    data-label="{{ $u->name }}" data-avatar="{{ $uPhoto }}">
                                                    <img src="{{ $uPhoto }}" alt="User" class="dropdown-avatar">
                                                    <span class="fw-semibold">{{ $u->name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Add Item <span class="text-danger">*</span></label>
                                <button type="button" id="damage-item-btn"
                                    class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold">Select Item</span>
                                    </span>
                                </button>
                                <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                                    <li class="dropdown-header">Select item</li>
                                    @foreach ($items as $it)
                                        @php
                                            $itImage = !empty($it->image)
                                                ? asset(str_replace('\\', '/', $it->image))
                                                : '';
                                        @endphp
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2"
                                                href="javascript:void(0);" data-damage-item-id="{{ $it->id }}"
                                                data-damage-item-name="{{ $it->name }}"
                                                data-damage-item-image="{{ $itImage }}">
                                                @if ($itImage)
                                                    <img src="{{ $itImage }}" alt="Item" class="dropdown-item-thumb">
                                                @else
                                                    <span class="dropdown-item-thumb d-inline-flex align-items-center justify-content-center small text-muted">NA</span>
                                                @endif
                                                <span class="fw-semibold">{{ $it->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Selected Items</label>
                                <div id="damage-cart-empty" class="text-muted small">No item selected yet.</div>
                                <div id="damage-cart-list"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="damage_date"
                                    value="{{ old('damage_date') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" rows="2" name="note">{{ old('note') }}</textarea>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn"
                                style="background:#F7721E; color:white; font-weight:500;">
                                Create Damage
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- SweetAlert + Validation --}}
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
        document.addEventListener('DOMContentLoaded', function() {
            const wrappers = document.querySelectorAll('[data-avatar-dropdown]');
            wrappers.forEach(function(wrapper) {
                const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                const button = wrapper.querySelector('button');
                const buttonLabel = wrapper.querySelector('button .fw-semibold');
                const options = wrapper.querySelectorAll('.dropdown-item[data-value][data-label]');

                if (!hiddenInput || !button || !buttonLabel || !options.length) return;

                options.forEach(function(option) {
                    option.addEventListener('click', function() {
                        const value = this.getAttribute('data-value') || '';
                        const label = this.getAttribute('data-label') || 'Select';
                        const sourceVisual = this.querySelector('img, .dropdown-item-thumb');
                        const buttonVisual = button.querySelector('img, .dropdown-item-thumb');

                        hiddenInput.value = value;
                        buttonLabel.textContent = label;

                        if (sourceVisual && buttonVisual) {
                            buttonVisual.replaceWith(sourceVisual.cloneNode(true));
                        }
                    });
                });
            });

            // Create Damage: multi-item cart
            const createModal = document.getElementById('createDamageModal');
            const cartList = document.getElementById('damage-cart-list');
            const cartEmpty = document.getElementById('damage-cart-empty');
            const itemOptions = document.querySelectorAll('[data-damage-item-id][data-damage-item-name]');

            if (!createModal || !cartList || !cartEmpty || !itemOptions.length) return;

            const damageCart = new Map(); // itemId => { name, image, qty, fine }

            function renderDamageCart() {
                const rows = Array.from(damageCart.entries());
                if (!rows.length) {
                    cartEmpty.classList.remove('d-none');
                    cartList.innerHTML = '';
                    return;
                }

                cartEmpty.classList.add('d-none');
                cartList.innerHTML = rows.map(function(entry, idx) {
                    const itemId = entry[0];
                    const row = entry[1];
                    const thumb = row.image ?
                        `<img src="${row.image}" class="dropdown-item-thumb" alt="Item">` :
                        `<span class="dropdown-item-thumb d-inline-flex align-items-center justify-content-center small text-muted">NA</span>`;

                    return `
                        <div class="damage-cart-row">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    ${thumb}
                                    <span class="fw-semibold">${row.name}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDamageItem(${itemId})">Remove</button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label mb-1">Qty</label>
                                    <input type="number" min="1" class="form-control" value="${row.qty}"
                                        oninput="setDamageQty(${itemId}, this.value)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1">Fine</label>
                                    <input type="number" min="0" step="0.01" class="form-control" value="${row.fine}"
                                        oninput="setDamageFine(${itemId}, this.value)">
                                </div>
                            </div>
                            <input type="hidden" name="items[${idx}][item_id]" value="${itemId}">
                            <input type="hidden" name="items[${idx}][qty]" value="${row.qty}" id="damage_qty_${itemId}">
                            <input type="hidden" name="items[${idx}][fine]" value="${row.fine}" id="damage_fine_${itemId}">
                        </div>
                    `;
                }).join('');
            }

            window.removeDamageItem = function(itemId) {
                damageCart.delete(itemId);
                renderDamageCart();
            };

            window.setDamageQty = function(itemId, value) {
                const row = damageCart.get(itemId);
                if (!row) return;
                const qty = Math.max(1, parseInt(value || '1', 10) || 1);
                row.qty = qty;
                damageCart.set(itemId, row);
                const hidden = document.getElementById('damage_qty_' + itemId);
                if (hidden) hidden.value = qty;
            };

            window.setDamageFine = function(itemId, value) {
                const row = damageCart.get(itemId);
                if (!row) return;
                const fine = Math.max(0, parseFloat(value || '0') || 0);
                row.fine = fine;
                damageCart.set(itemId, row);
                const hidden = document.getElementById('damage_fine_' + itemId);
                if (hidden) hidden.value = fine;
            };

            function addDamageItem(itemId, itemName, itemImage) {
                itemId = parseInt(itemId || '0', 10);
                if (!itemId) return;
                if (!damageCart.has(itemId)) {
                    damageCart.set(itemId, {
                        name: itemName || ('Item #' + itemId),
                        image: itemImage || '',
                        qty: 1,
                        fine: 0
                    });
                }
                renderDamageCart();
            }

            itemOptions.forEach(function(option) {
                option.addEventListener('click', function() {
                    addDamageItem(
                        this.getAttribute('data-damage-item-id'),
                        this.getAttribute('data-damage-item-name'),
                        this.getAttribute('data-damage-item-image')
                    );
                });
            });

            const oldItems = @json(old('items', []));
            if (Array.isArray(oldItems) && oldItems.length) {
                oldItems.forEach(function(row) {
                    const itemId = parseInt(row.item_id || '0', 10);
                    if (!itemId) return;
                    const matched = Array.from(itemOptions).find(function(opt) {
                        return parseInt(opt.getAttribute('data-damage-item-id') || '0', 10) === itemId;
                    });
                    addDamageItem(
                        itemId,
                        matched ? matched.getAttribute('data-damage-item-name') : ('Item #' + itemId),
                        matched ? matched.getAttribute('data-damage-item-image') : ''
                    );
                    const preset = damageCart.get(itemId);
                    preset.qty = Math.max(1, parseInt(row.qty || '1', 10) || 1);
                    preset.fine = Math.max(0, parseFloat(row.fine || '0') || 0);
                    damageCart.set(itemId, preset);
                });
                renderDamageCart();
            }
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This damage record will be deleted permanently',
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

        function confirmDeleteGroup(index) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'All grouped damage records will be deleted',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete all',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-group-form-' + index).submit();
                }
            });
        }

    </script>
@endsection
