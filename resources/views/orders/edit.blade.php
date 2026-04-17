@extends('home')

@section('title')
    <title>Edit Order | FBWS</title>
@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

    <style>
        /* Same as items/list.blade.php Image column: 55×55 */
        .order-item-select-page .order-item-opt,
        .order-item-select2-dd .order-item-opt {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-height: 55px;
        }

        .order-item-select-page .order-item-opt__img,
        .order-item-select2-dd .order-item-opt__img {
            width: 55px !important;
            height: 55px !important;
            max-width: 55px !important;
            max-height: 55px !important;
            border-radius: 0.25rem;
            object-fit: cover;
            border: 1px solid #e8ecf1;
            flex-shrink: 0;
            background: #f1f5f9;
            vertical-align: middle;
        }

        .order-item-select-page .order-item-opt__ph,
        .order-item-select2-dd .order-item-opt__ph {
            width: 55px !important;
            height: 55px !important;
            max-width: 55px !important;
            max-height: 55px !important;
            border-radius: 0.25rem;
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .order-item-select-page .order-item-opt__txt {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .order-item-select2-dd .order-item-opt__txt {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }

        .order-item-select-page .select2-container {
            width: 100% !important;
        }

        .order-item-select-page .select2-container--default .select2-selection--single {
            min-height: 63px;
            height: auto;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            background-color: #fff;
        }

        .order-item-select-page .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.2;
            padding: 6px 2rem 6px 0.875rem;
        }

        .order-item-select-page .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 61px;
            top: 1px;
        }

        .order-item-select2-dd {
            border-color: #d9dee3;
            border-radius: 0.5rem;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        }

        .order-item-select2-dd .select2-results__option {
            padding: 0.5rem 0.75rem;
        }
    </style>

    <div class="order-item-select-page">
        <div class="card">
            <div class="card-header border-bottom">
                <h1 style="font-size:1.4rem;font-weight:600;margin:0;">Edit Order #{{ $order->id }}</h1>
            </div>

            <div class="card-body">
                <form action="{{ route('orders.update', \Illuminate\Support\Facades\Crypt::encryptString((string) $order->id)) }}" method="POST" id="order-edit-form">
                    @csrf
                    @method('PUT')

                    <div id="items-wrap">
                        @foreach ($order->items as $idx => $row)
                            <div class="row g-2 item-row mb-2">
                                <div class="col-md-7">
                                    <label class="form-label">Item</label>
                                    <select name="items[{{ $idx }}][item_id]" class="form-select order-item-select" required>
                                        <option value="">Select item</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}"
                                                data-img="{{ $item->image ? e(asset($item->image)) : '' }}"
                                                {{ (int) $row->item_id === (int) $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Qty</label>
                                    <input type="number" name="items[{{ $idx }}][qty]" class="form-control"
                                        min="1" value="{{ (int) $row->qty }}" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-row">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-row" class="btn btn-outline-primary mb-3">+ Add Item</button>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-control">{{ old('notes', $order->notes) }}</textarea>
                    </div>

                    <button class="btn" style="background:#F7721E;color:#fff;">Update Order</button>
                </form>
            </div>
        </div>
    </div>

    <script type="application/json" id="order-items-catalog">
        {!! json_encode(
            $items
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'image' => !empty($item->image) ? asset($item->image) : null,
                    ];
                })
                ->values(),
        ) !!}
    </script>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            const itemsWrap = document.getElementById('items-wrap');
            const addBtn = document.getElementById('add-row');
            let idx = {{ $order->items->count() }};

            const itemCatalog = JSON.parse(document.getElementById('order-items-catalog').textContent);

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function itemOptionsHtml() {
                return itemCatalog
                    .map((row) => {
                        const imgAttr = row.image ? ` data-img="${escapeHtml(row.image)}"` : '';
                        return `<option value="${escapeHtml(String(row.id))}"${imgAttr}>${escapeHtml(row.name)}</option>`;
                    })
                    .join('');
            }

            function formatOrderItem(state) {
                if (!state.id) {
                    return state.text;
                }
                const url = ($(state.element).attr('data-img') || '').trim();
                const $out = $('<span class="order-item-opt"></span>');
                if (url) {
                    $out.append($('<img class="order-item-opt__img" alt="" />').attr('src', url));
                } else {
                    $out.append(
                        $('<span class="order-item-opt__ph"><i class="ti ti-package"></i></span>')
                    );
                }
                $out.append($('<span class="order-item-opt__txt"></span>').text(state.text));
                return $out;
            }

            function initOrderItemSelect($sel) {
                if ($sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2('destroy');
                }
                $sel.select2({
                    width: '100%',
                    minimumResultsForSearch: 8,
                    dropdownParent: $('body'),
                    dropdownCssClass: 'order-item-select2-dd',
                    templateResult: formatOrderItem,
                    templateSelection: formatOrderItem
                });
            }

            $('#order-edit-form .order-item-select').each(function() {
                initOrderItemSelect($(this));
            });

            addBtn.addEventListener('click', () => {
                const html = `
                <div class="row g-2 item-row mb-2">
                    <div class="col-md-7">
                        <label class="form-label">Item</label>
                        <select name="items[${idx}][item_id]" class="form-select order-item-select" required>
                            <option value="">Select item</option>
                            ${itemOptionsHtml()}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="items[${idx}][qty]" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100 remove-row">Remove</button>
                    </div>
                </div>
            `;
                itemsWrap.insertAdjacentHTML('beforeend', html);
                idx++;
                const $newSel = $(itemsWrap).find('.item-row').last().find('select.order-item-select');
                initOrderItemSelect($newSel);
            });

            document.addEventListener('click', (e) => {
                const rm = e.target.closest('.remove-row');
                if (!rm) return;
                const rows = document.querySelectorAll('.item-row');
                if (rows.length === 1) return;
                const row = rm.closest('.item-row');
                const $sel = $(row).find('select.order-item-select');
                if ($sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2('destroy');
                }
                row.remove();
            });
        });
    </script>
@endpush
