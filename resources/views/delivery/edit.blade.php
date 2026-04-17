@extends('home')

@section('title')
    <title>Edit Delivery | FBWS</title>
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

        .item-meta {
            min-width: 260px;
        }

        .thumb {
            width: 46px;
            height: 36px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }
    </style>

    <div class="card">
        <div class="card-header border-bottom">
            <h1 style="font-size:1.4rem;font-weight:700;margin:0;">Edit Delivery #{{ $order->id }}</h1>
            <small class="text-muted">Items cart style me edit karo, qty +/- karo, phir update.</small>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('deliveries.update', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}" id="deliveryForm">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select User</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Select User --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ old('user_id', $order->user_id) == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Delivery Date</label>
                        <input type="date" class="form-control" name="delivery_date"
                            value="{{ old('delivery_date', \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d')) }}"
                            required>
                        @error('delivery_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Delivery Time</label>
                        <input type="time" class="form-control" name="delivery_time"
                            value="{{ old('delivery_time', \Carbon\Carbon::parse($order->delivery_time)->format('H:i')) }}"
                            required>
                        @error('delivery_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Notes (optional)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Any notes...">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Add Item</label>
                        <select id="itemSelect" class="form-control">
                            <option value="">-- Select Item --</option>
                            @foreach ($items as $it)
                                <option value="{{ $it->id }}" data-name="{{ $it->name }}"
                                    data-image="{{ isset($it->image) && $it->image ? asset($it->image) : '' }}">
                                    {{ $it->name }}
                                </option>
                            @endforeach
                        </select>
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
                        Update Delivery
                    </button>
                    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>

    @php
        // Prefill cart from existing order items
        $prefill = [];
        foreach ($order->items as $oi) {
            $prefill[] = [
                'item_id' => $oi->item_id,
                'name' => $oi->item?->name ?? 'NA',
                'qty' => (int) ($oi->qty ?? 1),
                'image' => $oi->item?->image ? asset($oi->item->image) : null,
            ];
        }
    @endphp

    <script>
        // ✅ Cart Map: item_id => {name, qty, image}
        const cart = new Map();

        const itemSelect = document.getElementById('itemSelect');
        const cartList = document.getElementById('cartList');
        const cartEmpty = document.getElementById('cartEmpty');
        const cartCount = document.getElementById('cartCount');

        // ✅ Prefill existing items
        const prefill = @json($prefill);
        prefill.forEach(row => {
            cart.set(parseInt(row.item_id, 10), {
                name: row.name,
                qty: parseInt(row.qty, 10) || 1,
                image: row.image || null
            });
        });

        function esc(s) {
            return (s ?? '').toString()
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", "&#039;");
        }

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

                const imgHtml = row.image ?
                    `<img src="${row.image}" class="thumb" alt="img">` :
                    `<span class="text-muted">NA</span>`;

                return `
                <div class="cart-row">
                    <div class="item-meta">
                        <div class="d-flex align-items-center gap-2">
                            ${imgHtml}
                            <div>
                                <div class="fw-semibold">${esc(row.name)}</div>
                                <div class="text-muted small">Item ID: ${id}</div>
                            </div>
                        </div>
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

        window.incQty = function(id) {
            const row = cart.get(id);
            row.qty += 1;
            cart.set(id, row);
            renderCart();
        }

        window.decQty = function(id) {
            const row = cart.get(id);
            row.qty = Math.max(1, row.qty - 1);
            cart.set(id, row);
            renderCart();
        }

        window.setQty = function(id, val) {
            val = parseInt(val || '1', 10);
            val = isNaN(val) ? 1 : Math.max(1, val);
            const row = cart.get(id);
            row.qty = val;
            cart.set(id, row);
            renderCart();
        }

        window.removeItem = function(id) {
            cart.delete(id);
            renderCart();
        }

        itemSelect.addEventListener('change', () => {
            const id = parseInt(itemSelect.value || '0', 10);
            if (!id) return;

            const opt = itemSelect.options[itemSelect.selectedIndex];
            const name = opt.dataset.name || opt.text;
            const image = opt.dataset.image || null;

            if (!cart.has(id)) {
                cart.set(id, {
                    name,
                    qty: 1,
                    image: image || null
                });
            }

            itemSelect.value = '';
            renderCart();
        });

        // initial render
        renderCart();
    </script>

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

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error'))
            });
        </script>
    @endif
@endsection
