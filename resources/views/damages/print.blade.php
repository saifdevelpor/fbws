<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}" />
    <title>Damage Slip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @php
        $logoPath = 'assets/logo3.png';
        $brandName = 'Froqah Brothers Welfare Society';
        $accent = '#F7721E';
        $slipNo = 'DMG-' . str_pad((string) ($first->id ?? 0), 5, '0', STR_PAD_LEFT);
    @endphp
    <style>
        :root {
            --accent: {{ $accent }};
            --text: #0f172a;
            --muted: #64748b;
            --line: #e5e7eb;
            --soft: #f8fafc;
        }

        * {
            box-sizing: border-box;
            font-family: "Plus Jakarta Sans", Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
            color: var(--text);
            min-height: 100vh;
        }

        .slip {
            max-width: 960px;
            margin: auto;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: 0 16px 30px rgba(2, 6, 23, .08);
            overflow: hidden;
            min-height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--line);
            background: #fafafa;
        }

        .btn {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 9px 12px;
            background: #fff;
            text-decoration: none;
            color: #111827;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 16px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(180deg, #fff7f2, #fff);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fff;
            object-fit: contain;
            padding: 6px;
        }

        .chip {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            color: #92400e;
            background: #fff3e8;
            border: 1px solid #fed7aa;
            border-radius: 999px;
            padding: 7px 12px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .content {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }

        .box {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px;
            background: var(--soft);
        }

        .label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 5px;
            font-weight: 700;
        }

        .value {
            font-weight: 800;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 8px;
        }

        thead th {
            text-align: left;
            background: #f8fafc;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 10px;
            font-size: 13px;
        }

        tbody td {
            border-bottom: 1px solid #eef2f7;
            padding: 10px;
            vertical-align: middle;
            font-size: 13px;
        }

        .img {
            width: 66px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .totals {
            margin-top: 14px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .foot {
            margin-top: auto;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px dashed #cbd5e1;
            padding-top: 14px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .meta {
                grid-template-columns: 1fr;
            }
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
            <div><strong>Damage Slip</strong> #{{ $slipNo }}</div>
            <div style="display:flex; gap:8px;">
                <button class="btn btn-primary" onclick="window.print()">Print Slip</button>
            </div>
        </div>

        <div class="header">
            <div class="brand">
                <img class="logo" src="{{ asset($logoPath) }}" alt="Logo">
                <div>
                    <div style="font-weight:800; font-size:18px;">{{ $brandName }}</div>
                    <div style="font-size:13px; color:#64748b;">Damage & Fine Report</div>
                </div>
            </div>
            <div class="header-actions">
                @if ($waLink)
                    <a class="btn" target="_blank" href="{{ $waLink }}">Share on WhatsApp</a>
                @endif
                <span class="chip">{{ now()->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="content">
            <div class="meta">
                <div class="box">
                    <div class="label">User</div>
                    <div class="value">{{ $first->user->name ?? 'NA' }}</div>
                    <div style="margin-top:4px; color:#64748b;">Phone: {{ $first->user->phone_number ?? 'NA' }}</div>
                </div>
                <div class="box">
                    <div class="label">Reference</div>
                    <div class="value">Date: {{ $first->damage_date ?? 'NA' }}</div>
                    <div style="margin-top:4px; color:#64748b;">Note: {{ $first->note ?? '—' }}</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th style="width:90px;">Image</th>
                        <th>Item</th>
                        <th style="width:90px;">Qty</th>
                        <th style="width:120px;">Fine (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($damages as $i => $d)
                        @php
                            $img = $d->item?->image ? asset(str_replace('\\', '/', $d->item->image)) : null;
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                @if ($img)
                                    <img class="img" src="{{ $img }}" alt="item">
                                @else
                                    <img class="img" src="https://via.placeholder.com/66x50?text=NA" alt="NA">
                                @endif
                            </td>
                            <td style="font-weight:700;">{{ $d->item->name ?? 'NA' }}</td>
                            <td>{{ (int) $d->qty }}</td>
                            <td>{{ (int) $d->fine }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <span>Total Items: {{ $damages->count() }}</span>
                <span>Total Qty: {{ $totalQty }}</span>
                <span>Total Fine: Rs {{ (int) $totalFine }}</span>
            </div>

            <div class="foot">
                Website developed by M. Saif Ali | For website development inquiries: 0327-2000339
            </div>
        </div>
    </div>
</body>

</html>
