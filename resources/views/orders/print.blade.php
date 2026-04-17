<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Slip #{{ $order->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}" />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
    :root {
        --slip-font: "Plus Jakarta Sans", "Noto Nastaliq Urdu", "Segoe UI", Roboto, Arial, sans-serif;
    }

    * {
        font-family: var(--slip-font);
    }

    body {
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
        color: #111827;
        min-height: 100vh;
    }

    .slip {
        max-width: 900px;
        margin: auto;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        overflow: hidden;
        min-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
    }

    .top {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #fafafa;
    }

    .btn {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 9px 12px;
        background: #fff;
        text-decoration: none;
        color: #111827;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-primary {
        background: #F7721E;
        border-color: #F7721E;
        color: #fff;
    }

    .content {
        padding: 18px 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px;
    }

    .label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .value {
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    thead {
        display: none;
    }

    tbody {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 10px;
        padding: 10px;
    }

    tbody tr {
        display: block;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px;
        background: #fff;
    }

    td {
        display: block;
        border: none !important;
        padding: 4px 0;
        text-align: left;
        vertical-align: middle;
    }

    td::before {
        content: attr(data-label) ": ";
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
    }

    .img {
        width: 70px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .foot {
        text-align: center;
        font-size: 13px;
        color: #6b7280;
        margin-top: auto;
        padding-top: 16px;
        font-weight: 600;
    }

    @media print {
        body {
            background: #fff;
            padding: 0;
        }

        .top {
            display: none;
        }

        .slip {
            box-shadow: none;
            border: none;
            min-height: 100vh;
        }
    }
    </style>
</head>

<body>
    <div class="slip">
        <div class="top">
            <div><strong>Order Slip</strong> #{{ $order->id }}</div>
            <div style="display:flex; gap:8px;">
                @if (!empty($shareUrl))
                <a class="btn" target="_blank" href="{{ $shareUrl }}">Share To Admin WhatsApp</a>
                @endif
                <button class="btn btn-primary" onclick="window.print()">Print Slip</button>
            </div>
        </div>

        <div class="content">
            <div class="meta">
                <div class="box">
                    <div class="label">User</div>
                    <div class="value">{{ $order->user?->name ?? 'N/A' }}</div>
                    <div>{{ $order->user?->email ?? '' }}</div>
                </div>
                <div class="box">
                    <div class="label">Status / Date</div>
                    <div class="value">{{ ucfirst($order->status) }}</div>
                    <div>{{ $order->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <div class="box" style="margin-bottom: 14px;">
                <div class="label">Notes</div>
                <div>{{ $order->notes ?: '-' }}</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th style="width:90px;">Image</th>
                        <th>Item</th>
                        <th style="width:100px;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $i => $row)
                    <tr>
                            <td data-label="No">{{ $i + 1 }}</td>
                            <td data-label="Image">
                            @if (!empty($row->item?->image))
                            <img class="img" src="{{ asset($row->item->image) }}" alt="item">
                            @else
                            NA
                            @endif
                        </td>
                            <td data-label="Item">{{ $row->item?->name ?? 'Item' }}</td>
                            <td data-label="Qty">{{ (int) $row->qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="foot">Website developed by M. Saif Ali | For website development inquiries: 0327-2000339</div>
        </div>
    </div>
</body>

</html>
