<!DOCTYPE html>
<!-- ✅ Urdu version (design same) -->
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}" />
    <title>سامان سلِپ #{{ $order->id }}</title>

    @php
    // ✅ change this to your real logo path
    $logoPath = 'assets/logo3.png';
    $brandName = 'فروکہ برادرز ویلفیئر سوسائٹی';
    $accent = '#F7721E';

    $slipNo = str_pad((string) $order->id, 5, '0', STR_PAD_LEFT);
    $createdAt = $order->created_at?->timezone(config('app.timezone'))->format('d M Y, h:i A');

    $deliveryDT =
    \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') .
    ' • ' .
    \Carbon\Carbon::parse($order->delivery_time)->format('h:i A');

    $totalQty = $order->items->sum('qty');
    $totalItems = $order->items->count();
    @endphp

    <!-- Optional: Better font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- ✅ Urdu-friendly fonts (Inter kept for design consistency; Noto Nastaliq for Urdu readability) -->
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
    :root {
        --accent: {
                {
                $accent
            }
        }

        ;
        --accent2: #ff9b63;
        --text: #0f172a;
        --muted: #64748b;
        --line: #e5e7eb;
        --soft: #f8fafc;
        --soft2: #f1f5f9;
        --card: #ffffff;
        --shadow: 0 18px 45px rgba(2, 6, 23, .08);
        --slip-font: "Plus Jakarta Sans",
        "Noto Nastaliq Urdu",
        "Segoe UI",
        Roboto,
        Arial,
        sans-serif;
    }

    * {
        box-sizing: border-box;
        font-family: var(--slip-font);
    }

    body {
        font-family: "Noto Nastaliq Urdu", Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        background:
            radial-gradient(1200px 600px at 10% -10%, rgba(247, 114, 30, .10), transparent 60%),
            radial-gradient(900px 500px at 90% 0%, rgba(247, 114, 30, .06), transparent 55%),
            #fff;
        color: var(--text);
        margin: 0;
        padding: 32px 18px;
        min-height: 100vh;
    }

    .wrap {
        max-width: 980px;
        margin: 0 auto;
    }

    /* Top bar + buttons */
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .hint {
        font-size: 12px;
        color: var(--muted);
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--accent);
        box-shadow: 0 0 0 4px rgba(247, 114, 30, .12);
    }

    .btns {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin: 0;
    }

    .btn {
        border: 1px solid var(--line);
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .9);
        cursor: pointer;
        font-weight: 800;
        color: #0f172a;
        transition: transform .08s ease, box-shadow .2s ease, border-color .2s ease;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        /* ✅ buttons keep crisp look */
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(2, 6, 23, .08);
        border-color: rgba(15, 23, 42, .15);
    }

    .btn-primary {
        border-color: transparent;
        background: linear-gradient(135deg, var(--accent), #ff8a3d);
        color: #fff;
        box-shadow: 0 14px 26px rgba(247, 114, 30, .22);
    }

    .btn-primary:hover {
        box-shadow: 0 16px 30px rgba(247, 114, 30, .28);
    }

    /* Slip card */
    .card {
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 20px;
        overflow: hidden;
        background: var(--card);
        box-shadow: var(--shadow);
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 64px);
    }

    /* Fancy header */
    .header {
        padding: 20px 22px;
        display: flex;
        gap: 14px;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(15, 23, 42, .08);
        background:
            radial-gradient(700px 260px at 10% 0%, rgba(247, 114, 30, .18), transparent 62%),
            radial-gradient(520px 240px at 90% 15%, rgba(255, 155, 99, .14), transparent 60%),
            linear-gradient(180deg, rgba(255, 255, 255, .85), rgba(255, 255, 255, .96));
    }

    .header::after {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(rgba(15, 23, 42, .06) 1px, transparent 1px);
        background-size: 18px 18px;
        opacity: .25;
        pointer-events: none;
    }

    .header>* {
        position: relative;
        z-index: 1;
    }

    .brand {
        display: flex;
        gap: 14px;
        align-items: center;
        min-width: 260px;
    }

    .logo {
        width: 74px;
        height: 74px;
        border-radius: 16px;
        background: rgba(255, 255, 255, .88);
        border: 1px solid rgba(15, 23, 42, .10);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 10px 22px rgba(2, 6, 23, .08);
    }

    .logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }

    .brand h1 {
        margin: 0;
        font-size: 18px;
        font-weight: 900;
        letter-spacing: .2px;
        line-height: 1.1;
    }

    .brand small {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        color: var(--muted);
        font-size: 12px;
        margin-top: 6px;
        align-items: center;
    }

    .chip {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        border: 1px solid rgba(15, 23, 42, .10);
        background: rgba(255, 255, 255, .72);
        font-weight: 800;
        font-size: 12px;
        color: #0f172a;
        white-space: nowrap;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        /* ✅ chips keep compact look */
        direction: ltr;
        /* ✅ numbers look clean inside chips */
    }

    .chip strong {
        color: var(--accent);
        font-weight: 900;
    }

    .delivery-badge {
        display: inline-flex;
        gap: 10px;
        align-items: center;
        padding: 10px 14px;
        border-radius: 999px;
        border: 1px solid rgba(247, 114, 30, .28);
        background: rgba(247, 114, 30, .10);
        color: #8a3300;
        font-weight: 900;
        font-size: 12px;
        white-space: nowrap;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        /* ✅ badge keep compact look */
        direction: ltr;
        /* ✅ delivery date/time render nicely */
    }

    .delivery-badge .ico {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        display: grid;
        place-items: center;
        background: rgba(247, 114, 30, .16);
        border: 1px solid rgba(247, 114, 30, .22);
    }

    .delivery-badge svg {
        width: 16px;
        height: 16px;
        fill: #8a3300;
    }

    /* Body */
    .body {
        padding: 18px 22px 14px;
        background: #fff;
        flex: 1;
    }

    .grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .box {
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 16px;
        padding: 14px 14px;
        background: linear-gradient(180deg, var(--soft), #fff);
    }

    .box-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .title {
        font-size: 12px;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--muted);
        font-weight: 900;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        /* ✅ keep uppercase look */
    }

    .mini {
        font-size: 11px;
        color: var(--muted);
        font-weight: 800;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .value {
        font-weight: 900;
        font-size: 15px;
    }

    .value2 {
        color: var(--muted);
        font-size: 12px;
        margin-top: 6px;
        line-height: 1.35;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        /* ✅ emails/phone better in Inter */
    }

    .pillrow {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .pill {
        padding: 8px 10px;
        border-radius: 12px;
        background: var(--soft2);
        border: 1px solid rgba(15, 23, 42, .08);
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        direction: ltr;
        /* ✅ numbers align well */
    }

    .pill b {
        color: var(--accent);
    }

    /* Table */
    .table-wrap {
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
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
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 12px;
        padding: 10px;
        background: #fff;
    }

    tbody td {
        display: block;
        border: none !important;
        padding: 4px 0;
        font-size: 13px;
        vertical-align: middle;
        color: #0f172a;
    }

    tbody td::before {
        content: attr(data-label) ": ";
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
    }

    .img {
        width: 64px;
        height: 48px;
        border-radius: 12px;
        border: 1px solid rgba(15, 23, 42, .10);
        background: #fff;
        object-fit: cover;
    }

    .itemName {
        font-weight: 900;
    }

    .qty {
        display: inline-flex;
        min-width: 56px;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 12px;
        background: rgba(247, 114, 30, .12);
        border: 1px solid rgba(247, 114, 30, .18);
        color: #8a3300;
        font-weight: 900;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        direction: ltr;
    }

    /* Footer */
    .footer {
        padding: 16px 22px 20px;
        border-top: 1px solid rgba(15, 23, 42, .08);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        background: #fff;
        margin-top: auto;
    }

    .signbox {
        border: 1px dashed rgba(15, 23, 42, .20);
        border-radius: 16px;
        padding: 12px 14px;
        background: linear-gradient(180deg, rgba(248, 250, 252, .8), #fff);
        min-height: 92px;
    }

    .signbox .cap {
        font-size: 12px;
        color: var(--muted);
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .signline {
        border-bottom: 1px solid rgba(15, 23, 42, .18);
        margin-top: 34px;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .note {
        grid-column: 1 / -1;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.4;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        font-family: Inter, "Noto Nastaliq Urdu", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .note b {
        color: #0f172a;
    }

    /* Responsive */
    @media (max-width: 820px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .grid {
            grid-template-columns: 1fr;
        }

        .footer {
            grid-template-columns: 1fr;
        }
    }

    /* Print */
    @media print {
        body {
            padding: 0;
            background: #fff;
        }

        .topbar {
            display: none;
        }

        .card {
            border: none;
            box-shadow: none;
            border-radius: 0;
            min-height: 100vh;
        }

        .header {
            background: #fff;
        }

        .header::after {
            display: none;
        }

        tbody tr:hover td {
            background: #fff;
        }
    }
    </style>
</head>

<body>
    <div class="wrap">

        <div class="topbar">
            <div class="hint">
                <span class="dot"></span>
                <span>سامان سلِپ تیار ہے۔ ریکارڈ کے لیے پرنٹ کریں۔</span>
            </div>

            <div class="btns">
                <button class="btn" onclick="window.close()">بند کریں</button>
                <button class="btn btn-primary" onclick="window.print()">پرنٹ کریں</button>
            </div>
        </div>

        <div class="card">

            <div class="header">
                <div class="brand">
                    <div class="logo">
                        <img src="{{ asset($logoPath) }}" alt="لوگو">
                    </div>
                    <div>
                        <h1>{{ $brandName }} — سامان سلِپ</h1>
                        <small>
                            <span class="chip">سلِپ نمبر <strong>{{ $slipNo }}</strong></span>
                            <span class="chip">تیار کردہ <strong>{{ $createdAt }}</strong></span>
                        </small>
                    </div>
                </div>

                <div class="delivery-badge">
                    <span class="ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M12 8a1 1 0 0 1 1 1v3.586l2.293 2.293a1 1 0 1 1-1.414 1.414l-2.586-2.586A1 1 0 0 1 11 13V9a1 1 0 0 1 1-1zm0-6a10 10 0 1 0 0 20 10 10 0 0 0 0-20z" />
                        </svg>
                    </span>
                    سامان: {{ $deliveryDT }}
                </div>
            </div>

            <div class="body">

                <div class="grid">

                    <div class="box">
                        <div class="box-header">
                            <div class="title">DELIVER TO</div>
                            <div class="mini">ممبر کی تفصیلات</div>
                        </div>

                        <div class="value">{{ $order->user?->name ?? 'دستیاب نہیں' }}</div>

                        <div class="value2">
                            {{ $order->user?->email ?? '' }}
                            @if ($order->user?->phone_number)
                            <br>فون: {{ $order->user->phone_number }}
                            @endif
                            @if ($order->user?->address)
                            <br>پتہ: {{ $order->user->address }}
                            @endif
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-header">
                            <div class="title">ORDER SUMMARY</div>
                            <div class="mini">مختصر جائزہ</div>
                        </div>

                        <div class="pillrow">
                            <div class="pill">آئٹمز: <b>{{ $totalItems }}</b></div>
                            <div class="pill">کل مقدار: <b>{{ $totalQty }}</b></div>
                        </div>

                        <div class="value2" style="margin-top:10px;">
                            تیار کرنے والا: <b>{{ $order->creator?->name ?? 'دستیاب نہیں' }}</b><br>
                            نوٹس: {{ $order->notes ?? 'دستیاب نہیں' }}
                        </div>
                    </div>

                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:70px;">نمبر</th>
                                <th style="width:110px;">تصویر</th>
                                <th>آئٹم</th>
                                <th style="width:140px; text-align:center;">مقدار</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $k => $it)
                            @php $img = $it->item?->image ?? null; @endphp
                            <tr>
                                <td data-label="نمبر" style="font-weight:900;">{{ $k + 1 }}</td>
                                <td data-label="تصویر">
                                    @if ($img)
                                    <img class="img" src="{{ asset($img) }}" alt="آئٹم">
                                    @else
                                    <img class="img" src="https://via.placeholder.com/64x48?text=NA" alt="NA">
                                    @endif
                                </td>
                                <td data-label="آئٹم" class="itemName">{{ $it->item?->name ?? 'دستیاب نہیں' }}</td>
                                <td data-label="مقدار" style="text-align:center;">
                                    <span class="qty">{{ $it->qty }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="footer">
                <div class="signbox">
                    <div class="cap">PREPARED BY</div>
                    <div class="signline">{{ $order->creator?->name ?? 'دستیاب نہیں' }}</div>
                </div>

                <div class="signbox">
                    <div class="cap">RECEIVER SIGNATURE</div>
                    <div class="signline">{{ $order->user?->name ?? 'دستیاب نہیں' }}</div>
                </div>

                <div class="note">
                    <span><b>{{ $brandName }}</b> • اندرونی ریکارڈ اور سامان کی تصدیق کے لیے۔</span>
                    <span>سلِپ نمبر <b>{{ $slipNo }}</b></span>
                    <span><b>Website developed by M. Saif Ali | For website development inquiries:
                            0327-2000339</b></span>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
