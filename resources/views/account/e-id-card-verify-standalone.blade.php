<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Verification | FBWS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(247, 114, 30, .16), transparent 20%),
                linear-gradient(135deg, #eef5ff 0%, #fff8f2 42%, #ffffff 100%);
            color: #122033;
        }

        .page-shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 36px 18px 60px;
        }

        .hero {
            border-radius: 30px;
            padding: 28px 30px;
            background: rgba(255, 255, 255, .9);
            border: 1px solid rgba(27, 117, 187, .12);
            box-shadow: 0 24px 50px rgba(15, 23, 42, .08);
            margin-bottom: 18px;
        }

        .hero-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(22, 163, 74, .12);
            color: #15803d;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .hero h1 {
            margin: 12px 0 6px;
            font-size: 2.1rem;
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .hero p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        .member-code {
            padding: 12px 16px;
            border-radius: 16px;
            background: #fff;
            border: 1px solid #e5edf6;
            font-size: 14px;
            font-weight: 800;
        }

        .summary-grid,
        .mini-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .summary-grid {
            margin-top: 18px;
        }

        .stat-card {
            border-radius: 20px;
            background: rgba(255, 255, 255, .84);
            border: 1px solid #e8eef5;
            padding: 16px 18px;
            box-shadow: 0 10px 22px rgba(15, 23, 42, .04);
        }

        .stat-card span {
            display: block;
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .stat-card strong {
            display: block;
            font-size: 1.3rem;
            font-weight: 900;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 310px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .profile-card,
        .panel {
            border-radius: 28px;
            background: #fff;
            border: 1px solid #e8eef5;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .05);
        }

        .profile-card {
            padding: 22px 18px;
            text-align: center;
            position: sticky;
            top: 20px;
        }

        .photo-wrap {
            width: 150px;
            height: 180px;
            margin: 0 auto 16px;
            padding: 6px;
            border-radius: 20px;
            background: linear-gradient(180deg, #e6f7ff 0%, #fff 100%);
            border: 1px solid rgba(18, 32, 51, .12);
        }

        .photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 14px;
        }

        .urdu-font {
            font-family: "Noto Nastaliq Urdu", "Segoe UI", serif;
            direction: rtl;
            line-height: 1.8;
        }

        .profile-name {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 900;
        }

        .profile-sub {
            margin-top: 6px;
            color: #475569;
            font-size: 15px;
            font-weight: 800;
        }

        .chip-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 14px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e6edf5;
            font-size: 12px;
            font-weight: 800;
        }

        .info-stack {
            margin-top: 16px;
            display: grid;
            gap: 10px;
            text-align: left;
        }

        .info-box {
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e8eef5;
            padding: 16px 18px;
            min-height: 90px;
            box-shadow: 0 10px 22px rgba(15, 23, 42, .04);
        }

        .info-box span {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            color: #64748b;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .info-box strong {
            display: block;
            font-size: 1rem;
            line-height: 1.55;
            word-break: break-word;
        }

        .sections {
            display: grid;
            gap: 18px;
        }

        .panel-head {
            padding: 18px 22px;
            border-bottom: 1px solid #ecf1f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .panel-head h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 900;
        }

        .panel-head small {
            color: #64748b;
            font-weight: 700;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 18px;
            border-bottom: 1px solid #eef3f8;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            background: #f9fbfd;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .inline-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            background: rgba(27, 117, 187, .08);
            color: #1d4ed8;
        }

        .inline-badge.warn {
            background: rgba(245, 158, 11, .12);
            color: #b45309;
        }

        .inline-badge.good {
            background: rgba(22, 163, 74, .12);
            color: #15803d;
        }

        .empty {
            padding: 32px 20px;
            text-align: center;
            color: #64748b;
            font-weight: 700;
        }

        .website-link-wrap {
            margin-top: 22px;
            text-align: center;
        }

        .website-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 22px;
            border-radius: 999px;
            background: linear-gradient(135deg, #f7721e, #d95e10);
            color: #fff;
            text-decoration: none;
            font-weight: 900;
            box-shadow: 0 16px 28px rgba(247, 114, 30, .22);
        }

        .website-link:hover {
            opacity: .95;
        }

        @media (max-width: 1100px) {
            .summary-grid,
            .mini-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .profile-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .summary-grid,
            .mini-summary {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 22px 18px;
            }

            .page-shell {
                padding: 24px 12px 44px;
            }
        }
    </style>
</head>

<body>
@php
    $photo = $cardUser->profile_photo ? asset($cardUser->profile_photo) : asset('assets/img/avatars/5.png');
    $memberId = 'FBWS-' . str_pad((string) ($cardUser->id ?? 0), 4, '0', STR_PAD_LEFT);
    $roleLabel = ucfirst((string) ($cardUser->role ?? 'Member'));
    $positionLabel = $cardUser->position ?: 'Member';
    $phoneNumber = $cardUser->phone_number ?: 'Not Provided';
    $joinedOn = $cardUser->created_at ? $cardUser->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA';
@endphp

    <div class="page-shell">
        <section class="hero">
            <div class="hero-top">
                <div>
                    <div class="status-badge">
                        <i class="ti ti-shield-check"></i>
                        Live Verification
                    </div>
                    <h1>FBWS Member Complete Record</h1>
                    <p>Yeh page member ka real-time data, activity aur complete portal history show karta hai.</p>
                </div>
                <div class="member-code">{{ $memberId }}</div>
            </div>

            <div class="summary-grid">
                <div class="stat-card">
                    <span>Total Payments</span>
                    <strong>{{ number_format((float) ($stats['payments_total'] ?? 0), 0) }}</strong>
                </div>
                <div class="stat-card">
                    <span>Paid Months</span>
                    <strong>{{ $stats['paid_months_count'] ?? 0 }}</strong>
                </div>
                <div class="stat-card">
                    <span>Total Orders</span>
                    <strong>{{ $stats['orders_count'] ?? 0 }}</strong>
                </div>
                <div class="stat-card">
                    <span>Total Deliveries</span>
                    <strong>{{ $stats['deliveries_count'] ?? 0 }}</strong>
                </div>
            </div>
        </section>

        <div class="content-grid">
            <aside class="profile-card">
                <div class="photo-wrap">
                    <img src="{{ $photo }}" alt="{{ $cardUser->name }}">
                </div>
                <h2 class="profile-name urdu-font">{{ $cardUser->name ?? 'NA' }}</h2>
                <div class="profile-sub">{{ $positionLabel }}</div>

                <div class="chip-row">
                    <span class="chip"><i class="ti ti-user"></i>{{ $roleLabel }}</span>
                    <span class="chip"><i class="ti ti-id-badge-2"></i>{{ $cardUser->id_card ?? 'Not Assigned' }}</span>
                </div>

                <div class="info-stack">
                    <div class="info-box">
                        <span>Phone Number</span>
                        <strong>{{ $phoneNumber }}</strong>
                    </div>
                    <div class="info-box">
                        <span>Email</span>
                        <strong>{{ $cardUser->email ?? 'NA' }}</strong>
                    </div>
                    <div class="info-box">
                        <span>Joined On</span>
                        <strong>{{ $joinedOn }}</strong>
                    </div>
                    <div class="info-box">
                        <span>Address</span>
                        <strong class="urdu-font">{{ $cardUser->address ?? 'Address not provided' }}</strong>
                    </div>
                </div>
            </aside>

            <div class="sections">
                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Record Summary</h3>
                            <small>Member ki overall portal activity ka quick snapshot</small>
                        </div>
                    </div>
                    <div style="padding:18px 22px;">
                        <div class="mini-summary">
                            <div class="stat-card">
                                <span>Complaints</span>
                                <strong>{{ $stats['complaints_count'] ?? 0 }}</strong>
                            </div>
                            <div class="stat-card">
                                <span>Suggestions</span>
                                <strong>{{ $stats['suggestions_count'] ?? 0 }}</strong>
                            </div>
                            <div class="stat-card">
                                <span>Support Requests</span>
                                <strong>{{ $stats['support_count'] ?? 0 }}</strong>
                            </div>
                            <div class="stat-card">
                                <span>Payment Entries</span>
                                <strong>{{ $stats['payments_count'] ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Latest Payments</h3>
                            <small>Recent fund/payment records</small>
                        </div>
                    </div>
                    @if ($payments->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->month ?? 'NA' }}</td>
                                            <td>Rs {{ number_format((float) $payment->amount, 0) }}</td>
                                            <td>{{ $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d M Y') : 'NA' }}</td>
                                            <td>{{ $payment->receipt_number ?? 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">No payment history found.</div>
                    @endif
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Latest Orders</h3>
                            <small>User ne kitne order request kiye aur unka status</small>
                        </div>
                    </div>
                    @if ($orders->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td><span class="inline-badge">{{ ucfirst((string) ($order->status ?? 'Pending')) }}</span></td>
                                            <td>{{ $order->items->sum('qty') }}</td>
                                            <td>{{ optional($order->created_at)->format('d M Y') ?? 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">No order history found.</div>
                    @endif
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Latest Deliveries</h3>
                            <small>Delivery record jo is member se related hai</small>
                        </div>
                    </div>
                    @if ($deliveries->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Delivery #</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deliveries as $delivery)
                                        <tr>
                                            <td>#{{ $delivery->id }}</td>
                                            <td>{{ $delivery->delivery_date ?? 'NA' }}</td>
                                            <td>{{ $delivery->delivery_time ? \Carbon\Carbon::parse($delivery->delivery_time)->format('h:i A') : 'NA' }}</td>
                                            <td>{{ $delivery->items->sum('qty') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">No delivery history found.</div>
                    @endif
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Complaints & Suggestions</h3>
                            <small>Member ke complaints ya suggestions</small>
                        </div>
                    </div>
                    @if ($complaints->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaints as $complaint)
                                        <tr>
                                            <td>{{ ucfirst((string) ($complaint->type ?? 'Complaint')) }}</td>
                                            <td>{{ $complaint->subject ?? 'NA' }}</td>
                                            <td>
                                                <span class="inline-badge {{ strtolower((string) ($complaint->status ?? '')) === 'resolved' ? 'good' : 'warn' }}">
                                                    {{ ucfirst((string) ($complaint->status ?? 'Pending')) }}
                                                </span>
                                            </td>
                                            <td>{{ optional($complaint->created_at)->format('d M Y') ?? 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">No complaints or suggestions found.</div>
                    @endif
                </section>

                <section class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>Support Requests</h3>
                            <small>Welfare/support related requests</small>
                        </div>
                    </div>
                    @if ($supportRequests->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supportRequests as $support)
                                        <tr>
                                            <td>{{ $support->title ?? 'NA' }}</td>
                                            <td>{{ $support->category ?? ($support->support_type ?? 'NA') }}</td>
                                            <td>
                                                <span class="inline-badge {{ strtolower((string) ($support->status ?? '')) === 'approved' || strtolower((string) ($support->status ?? '')) === 'resolved' ? 'good' : 'warn' }}">
                                                    {{ ucfirst((string) ($support->status ?? 'Pending')) }}
                                                </span>
                                            </td>
                                            <td>{{ $support->amount_needed ? 'Rs ' . number_format((float) $support->amount_needed, 0) : 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty">No support request history found.</div>
                    @endif
                </section>

                <div class="website-link-wrap">
                    <a href="{{ route('website.index') }}" class="website-link">
                        <i class="ti ti-world"></i>
                        Visit Our Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
