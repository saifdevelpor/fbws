@extends('home')

@section('title')
    <title>New Order Request | FBWS</title>
@endsection

@section('content')
    <style>
        .order-create-wrap .oc-hero {
            background: linear-gradient(135deg, rgba(247, 114, 30, 0.12) 0%, rgba(247, 114, 30, 0.02) 50%, transparent 100%);
            border-radius: 1rem 1rem 0 0;
            margin: -1.5rem -1.5rem 1.25rem -1.5rem;
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(247, 114, 30, 0.15);
        }

        .order-create-wrap .oc-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(145deg, #F7721E, #e86816);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 10px 28px rgba(247, 114, 30, 0.35);
        }

        .order-create-wrap .oc-item-card {
            border: 1px solid #e8ecf1;
            border-radius: 1rem;
            background: #fafbfc;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .order-create-wrap .oc-item-card:hover {
            border-color: rgba(247, 114, 30, 0.25);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .order-create-wrap .oc-line-badge {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #e8ecf1;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .order-create-wrap .oc-notes-card {
            border-radius: 1rem;
            border: 1px solid #e8ecf1;
            background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
        }

        .order-create-wrap .btn-submit-order {
            background: #F7721E;
            border-color: #F7721E;
            color: #fff;
            font-weight: 600;
            border-radius: 0.65rem;
            padding: 0.65rem 1.25rem;
        }

        .order-create-wrap .btn-submit-order:hover {
            background: #e06518;
            border-color: #e06518;
            color: #fff;
        }

        .order-create-wrap .btn-add-line {
            border-radius: 0.65rem;
            border-width: 2px;
            font-weight: 500;
        }

        .order-create-wrap .oc-accent {
            color: #F7721E;
        }

        .order-create-wrap #add-row {
            width: 100%;
        }

        @media (min-width: 576px) {
            .order-create-wrap #add-row {
                width: auto;
            }
        }

        @media (max-width: 991.98px) {
            .order-create-wrap .oc-hero {
                margin: -1rem -1rem 1rem -1rem;
                padding: 1.25rem 1rem 1rem;
            }
        }

        /* Match items/list.blade.php table thumbnails: 55×55, object-fit cover, rounded */
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

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

    <div class="order-create-wrap order-item-select-page">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-muted">{{ __('menu.dashboard') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('orders.index') }}" class="text-muted">{{ __('menu.all_orders') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('menu.new_order') }}</li>
            </ol>
        </nav>

        @php
            $orderLines = old('items');
            $orderLines =
                is_array($orderLines) && count($orderLines) > 0 ? array_values($orderLines) : [['item_id' => '', 'qty' => 1]];
        @endphp

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="oc-hero">
                    <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
                        <div class="oc-icon flex-shrink-0">
                            <i class="ti ti-shopping-cart-plus"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h1 class="h4 mb-1 fw-bold text-heading">Create Order Request</h1>
                            <p class="text-muted mb-0 small">
                                Choose items and quantities. You can add more lines before submitting. Optional notes help
                                the team process your request faster.
                            </p>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex gap-2">
                            <i class="ti ti-alert-circle fs-5 flex-shrink-0 mt-1"></i>
                            <div>
                                <strong>Please fix the following:</strong>
                                <ul class="mb-0 mt-2 small ps-3">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('orders.store') }}" method="POST" id="order-create-form">
                    @csrf

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <h2 class="h6 text-uppercase text-muted fw-bold mb-0">Order lines</h2>
                                <span class="badge bg-label-secondary rounded-pill" id="line-count-badge">1 line</span>
                            </div>

                            <div id="items-wrap" class="d-flex flex-column gap-3">
                                @foreach ($orderLines as $i => $line)
                                    <div class="oc-item-card p-3 p-md-4 item-row">
                                        <div class="d-flex align-items-start gap-3 mb-3">
                                            <span class="oc-line-badge line-num">{{ $i + 1 }}</span>
                                            <div class="flex-grow-1 min-w-0">
                                                <label class="form-label fw-semibold mb-1">Item</label>
                                                <select name="items[{{ $i }}][item_id]" class="form-select order-item-select"
                                                    required>
                                                    <option value="">Select item</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-img="{{ $item->image ? e(asset($item->image)) : '' }}"
                                                            {{ (int) ($line['item_id'] ?? '') === (int) $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-items-end">
                                            <div class="col-sm-4 col-md-3">
                                                <label class="form-label fw-semibold mb-1">Quantity</label>
                                                <input type="number" name="items[{{ $i }}][qty]" class="form-control"
                                                    min="1" value="{{ old('items.' . $i . '.qty', $line['qty'] ?? 1) }}"
                                                    required>
                                            </div>
                                            <div class="col-sm-auto ms-sm-auto">
                                                <button type="button"
                                                    class="btn btn-outline-danger remove-row d-inline-flex align-items-center gap-1">
                                                    <i class="ti ti-trash"></i>
                                                    <span class="d-none d-sm-inline">Remove</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="add-row"
                                class="btn btn-outline-primary btn-add-line mt-3 d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="ti ti-plus"></i>
                                Add another item
                            </button>
                        </div>

                        <div class="col-lg-4">
                            <div class="oc-notes-card p-4 h-100 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="ti ti-notes oc-accent"></i>
                                    <h2 class="h6 mb-0 fw-bold">Notes</h2>
                                </div>
                                <p class="text-muted small mb-3">Delivery preferences, sizes, or anything the admin should
                                    know.</p>
                                <textarea name="notes" rows="6" class="form-control flex-grow-1"
                                    placeholder="Optional notes for this order…">{{ old('notes') }}</textarea>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-lg btn-submit-order d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="ti ti-send"></i>
                                    Submit request
                                </button>
                                <a href="{{ route('orders.index') }}"
                                    class="btn btn-outline-secondary btn-lg d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="ti ti-arrow-left"></i>
                                    Back to orders
                                </a>
                            </div>
                        </div>
                    </div>
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
            const lineBadge = document.getElementById('line-count-badge');
            let idx = {{ count($orderLines) }};

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

            function lineCount() {
                return document.querySelectorAll('#items-wrap .item-row').length;
            }

            function renumberLines() {
                document.querySelectorAll('#items-wrap .item-row .line-num').forEach((el, i) => {
                    el.textContent = String(i + 1);
                });
                const n = lineCount();
                lineBadge.textContent = n === 1 ? '1 line' : `${n} lines`;
            }

            function refreshRemoveButtons() {
                const rows = document.querySelectorAll('#items-wrap .item-row');
                rows.forEach((row) => {
                    const btn = row.querySelector('.remove-row');
                    if (!btn) return;
                    const only = rows.length === 1;
                    btn.disabled = only;
                    btn.classList.toggle('opacity-50', only);
                    btn.classList.toggle('pe-none', only);
                });
            }

            $('#order-create-form .order-item-select').each(function() {
                initOrderItemSelect($(this));
            });

            addBtn.addEventListener('click', () => {
                const html = `
                <div class="oc-item-card p-3 p-md-4 item-row">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <span class="oc-line-badge line-num">1</span>
                        <div class="flex-grow-1 min-w-0">
                            <label class="form-label fw-semibold mb-1">Item</label>
                            <select name="items[${idx}][item_id]" class="form-select order-item-select" required>
                                <option value="">Select item</option>
                                ${itemOptionsHtml()}
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-4 col-md-3">
                            <label class="form-label fw-semibold mb-1">Quantity</label>
                            <input type="number" name="items[${idx}][qty]" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-sm-auto ms-sm-auto">
                            <button type="button" class="btn btn-outline-danger remove-row d-inline-flex align-items-center gap-1">
                                <i class="ti ti-trash"></i>
                                <span class="d-none d-sm-inline">Remove</span>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                itemsWrap.insertAdjacentHTML('beforeend', html);
                idx++;
                const $newSel = $(itemsWrap).find('.item-row').last().find('select.order-item-select');
                initOrderItemSelect($newSel);
                renumberLines();
                refreshRemoveButtons();
            });

            document.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.remove-row');
                if (!removeBtn || removeBtn.disabled) return;
                const rows = document.querySelectorAll('#items-wrap .item-row');
                if (rows.length === 1) return;
                const row = removeBtn.closest('.item-row');
                const $sel = $(row).find('select.order-item-select');
                if ($sel.hasClass('select2-hidden-accessible')) {
                    $sel.select2('destroy');
                }
                row.remove();
                renumberLines();
                refreshRemoveButtons();
            });

            renumberLines();
            refreshRemoveButtons();
        });
    </script>
@endpush
