@extends('home')

@section('title')
    <title>Create Delivery | FBWS</title>
@endsection

@section('content')
    <style>
        .cart-box {
            border: 1px solid #f1f1f1;
            border-radius: 12px;
            padding: 14px;
            background: #fff;
        }

        .cart-row {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px dashed #eee;
            padding: 10px 0;
        }

        .cart-row:last-child {
            border-bottom: none;
        }

        .qty-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid #eee;
            background: #fff;
        }

        .qty-input {
            width: 70px;
            text-align: center;
            border-radius: 10px;
            border: 1px solid #eee;
            height: 34px;
        }
    </style>

    <div class="card">
        <div class="card-header border-bottom">
            <h1 style="font-size:1.4rem;font-weight:700;margin:0;">Create Delivery</h1>
            <small class="text-muted">User select karo, items add karo, qty +/- karo, date/time set karo.</small>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('deliveries.store') }}" id="deliveryForm">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select User</label>
                        <input type="hidden" name="user_id" id="delivery-create-user-id" value="{{ old('user_id') ?? '' }}"
                            required>

                        <button type="button" id="delivery-create-user-btn"
                            class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center gap-2 justify-content-between"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-flex align-items-center gap-2">
                                <img id="delivery-create-user-avatar" src="{{ asset('assets/img/avatars/5.png') }}"
                                    alt="User" style="width:28px;height:28px;object-fit:cover;border-radius:50%;">
                                <span id="delivery-create-user-name" class="fw-semibold">-- Select User --</span>
                            </span>
                        </button>

                        <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                            <li class="dropdown-header">Select user</li>
                            @foreach ($users as $u)
                                @php
                                    $photoPath = $u->profile_photo ? str_replace('\\', '/', $u->profile_photo) : null;
                                    $uAvatar = $photoPath ? asset($photoPath) : asset('assets/img/avatars/5.png');
                                    $uLabel = $u->name . ($u->email ? ' (' . $u->email . ')' : '');
                                @endphp
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);"
                                        data-delivery-user-id="{{ (int) $u->id }}"
                                        data-delivery-user-name="{{ $uLabel }}"
                                        data-delivery-user-avatar="{{ $uAvatar }}">
                                        <img src="{{ $uAvatar }}" alt="User"
                                            style="width:32px;height:32px;object-fit:cover;border-radius:50%;">
                                        <div class="lh-sm">
                                            <div class="fw-semibold">{{ $u->name }}</div>
                                            <div class="text-muted small">{{ $u->email }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Delivery Date</label>
                        <input type="date" class="form-control" name="delivery_date" value="{{ old('delivery_date') }}"
                            required>
                        @error('delivery_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Delivery Time</label>
                        <input type="time" class="form-control" name="delivery_time" value="{{ old('delivery_time') }}"
                            required>
                        @error('delivery_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Notes (optional)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Any notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Add Item</label>
                        <button type="button" id="itemDropdownBtn"
                            class="btn btn-outline-secondary dropdown-toggle w-100 d-flex align-items-center justify-content-between"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-flex align-items-center gap-2">
                                <span class="fw-semibold">-- Select Item --</span>
                            </span>
                        </button>

                        <ul class="dropdown-menu w-100" style="max-height:320px; overflow:auto;">
                            <li class="dropdown-header">Select item</li>
                            @foreach ($items as $it)
                                @php
                                    $imgPath =
                                        isset($it->image) && $it->image ? str_replace('\\', '/', $it->image) : null;
                                    $imgUrl = $imgPath ? asset($imgPath) : null;
                                @endphp
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);"
                                        data-item-id="{{ (int) $it->id }}" data-item-name="{{ $it->name }}"
                                        data-item-image="{{ $imgUrl ?? '' }}">
                                        @if ($imgUrl)
                                            <img src="{{ $imgUrl }}" alt="Item"
                                                style="width:36px;height:28px;object-fit:cover;border-radius:6px;border:1px solid #eee;">
                                        @else
                                            <span
                                                style="width:36px;height:28px;border-radius:6px;border:1px solid #eee;background:#f8fafc;display:inline-flex;align-items:center;justify-content:center;font-size:11px;color:#94a3b8;">
                                                NA
                                            </span>
                                        @endif
                                        <span class="fw-semibold">{{ $it->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <small class="text-muted">Item select karte hi cart me add ho jayega (duplicate avoid).</small>
                    </div>

                    <div class="col-md-12">
                        <div class="cart-box">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="m-0 fw-bold">Delivery Cart</h5>
                                <span class="text-muted small" id="cartCount">0 items</span>
                            </div>

                            <div id="cartEmpty" class="text-center text-muted py-4">
                                No item added yet.
                            </div>

                            <div id="cartList"></div>
                        </div>

                        @error('items')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn" style="background:#F7721E;color:#fff;">
                        Save Delivery
                    </button>
                    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const cart = new Map(); // item_id => {name, qty}

        const itemDropdownBtn = document.getElementById('itemDropdownBtn');
        const cartList = document.getElementById('cartList');
        const cartEmpty = document.getElementById('cartEmpty');
        const cartCount = document.getElementById('cartCount');
        const itemChoices = document.querySelectorAll('[data-item-id][data-item-name]');

        function renderCart() {
            const items = Array.from(cart.entries());

            cartCount.textContent = `${items.length} items`;

            if (items.length === 0) {
                cartEmpty.classList.remove('d-none');
                cartList.innerHTML = '';
                return;
            }

            cartEmpty.classList.add('d-none');

            cartList.innerHTML = items.map(([id, row], idx) => {
                return `
                <div class="cart-row">
                    <div style="min-width:260px;">
                        <div class="fw-semibold">${row.name}</div>
                        <div class="text-muted small">Item ID: ${id}</div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="qty-btn" onclick="decQty(${id})">-</button>

                        <input class="qty-input" type="number" min="1" value="${row.qty}"
                               oninput="setQty(${id}, this.value)" />

                        <button type="button" class="qty-btn" onclick="incQty(${id})">+</button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(${id})">
                            Remove
                        </button>
                    </div>

                    <!-- hidden inputs for laravel -->
                    <input type="hidden" name="items[${idx}][item_id]" value="${id}">
                    <input type="hidden" name="items[${idx}][qty]" value="${row.qty}" id="hiddenQty_${id}">
                </div>
            `;
            }).join('');
        }

        function incQty(id) {
            const row = cart.get(id);
            row.qty += 1;
            cart.set(id, row);
            document.getElementById('hiddenQty_' + id).value = row.qty;
            renderCart();
        }

        function decQty(id) {
            const row = cart.get(id);
            row.qty = Math.max(1, row.qty - 1);
            cart.set(id, row);
            document.getElementById('hiddenQty_' + id).value = row.qty;
            renderCart();
        }

        function setQty(id, val) {
            val = parseInt(val || '1', 10);
            val = isNaN(val) ? 1 : Math.max(1, val);
            const row = cart.get(id);
            row.qty = val;
            cart.set(id, row);
            const hidden = document.getElementById('hiddenQty_' + id);
            if (hidden) hidden.value = row.qty;
            renderCart();
        }

        function removeItem(id) {
            cart.delete(id);
            renderCart();
        }

        function addItemToCart(id, name) {
            id = parseInt(id || '0', 10);
            if (!id) return;

            if (!cart.has(id)) {
                cart.set(id, {
                    name: name || ('Item #' + id),
                    qty: 1
                });
            }
            renderCart();
        }

        itemChoices.forEach(function(el) {
            el.addEventListener('click', function() {
                addItemToCart(this.getAttribute('data-item-id'), this.getAttribute('data-item-name'));
            });
        });

        // initial render
        renderCart();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btnName = document.getElementById('delivery-create-user-name');
            var btnAvatar = document.getElementById('delivery-create-user-avatar');
            var hiddenInput = document.getElementById('delivery-create-user-id');
            var items = document.querySelectorAll(
                '[data-delivery-user-id][data-delivery-user-name][data-delivery-user-avatar]');

            if (!btnName || !btnAvatar || !hiddenInput || !items.length) return;

            function setSelected(userId, userName, userAvatar) {
                hiddenInput.value = userId || '';
                btnName.textContent = userName || '-- Select User --';
                if (userAvatar) btnAvatar.src = userAvatar;
            }

            if (hiddenInput.value) {
                var current = Array.from(items).find(function(el) {
                    return (el.getAttribute('data-delivery-user-id') || '') === (hiddenInput.value || '');
                });
                if (current) {
                    setSelected(
                        current.getAttribute('data-delivery-user-id'),
                        current.getAttribute('data-delivery-user-name'),
                        current.getAttribute('data-delivery-user-avatar')
                    );
                }
            }

            items.forEach(function(item) {
                item.addEventListener('click', function() {
                    setSelected(
                        this.getAttribute('data-delivery-user-id'),
                        this.getAttribute('data-delivery-user-name'),
                        this.getAttribute('data-delivery-user-avatar')
                    );
                });
            });
        });
    </script>

    {{-- sweetalert errors --}}
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
@endsection
