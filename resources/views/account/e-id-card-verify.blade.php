@extends('website.home')

@section('title')
<title>Member Verification | FBWS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&display=swap" rel="stylesheet">
@endsection

@section('content')
@php
    $photo = $cardUser->profile_photo ? asset($cardUser->profile_photo) : asset('assets/img/avatars/5.png');
    $memberId = 'FBWS-' . str_pad((string) ($cardUser->id ?? 0), 4, '0', STR_PAD_LEFT);
    $roleLabel = ucfirst((string) ($cardUser->role ?? 'Member'));
    $positionLabel = $cardUser->position ?: 'Member';
    $phoneNumber = $cardUser->phone_number ?: 'Not Provided';
    $joinedOn = $cardUser->created_at ? $cardUser->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA';
@endphp

<style>
    .verify-shell {
        padding: 40px 0 60px;
    }

    .verify-page {
        max-width: 1220px;
        margin: 0 auto;
    }

    .verify-hero {
        border-radius: 30px;
        padding: 28px 30px;
        background:
            radial-gradient(circle at top right, rgba(247, 114, 30, .18), transparent 22%),
            linear-gradient(135deg, #ffffff 0%, #fff8f2 42%, #eef6ff 100%);
        border: 1px solid rgba(27, 117, 187, .12);
        box-shadow: 0 22px 48px rgba(15, 23, 42, .08);
        margin-bottom: 18px;
    }

    .verify-hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: wrap;
    }

    .verify-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(22, 163, 74, .1);
        color: #15803d;
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .verify-hero h1 {
        margin: 12px 0 4px;
        font-size: 2rem;
        font-weight: 900;
        color: #122033;
        letter-spacing: -.03em;
    }

    .verify-hero p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .verify-code {
        padding: 12px 16px;
        border-radius: 16px;
        background: #fff;
        border: 1px solid #e5edf6;
        font-size: 14px;
        font-weight: 800;
        color: #122033;
    }

    .verify-summary {
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .verify-stat {
        border-radius: 20px;
        background: rgba(255, 255, 255, .84);
        border: 1px solid #e8eef5;
        padding: 16px 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, .04);
    }

    .verify-stat span {
        display: block;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .verify-stat strong {
        display: block;
        color: #122033;
        font-size: 1.35rem;
        font-weight: 900;
    }

    .verify-layout {
        display: grid;
        grid-template-columns: 310px minmax(0, 1fr);
        gap: 18px;
        align-items: start;
    }

    .verify-profile {
        border-radius: 28px;
        background: #fff;
        border: 1px solid #e8eef5;
        padding: 22px 18px;
        text-align: center;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .05);
        position: sticky;
        top: 24px;
    }

    .verify-photo {
        width: 150px;
        height: 180px;
        margin: 0 auto 16px;
        padding: 6px;
        border-radius: 20px;
        background: linear-gradient(180deg, #e6f7ff 0%, #fff 100%);
        border: 1px solid rgba(18, 32, 51, .12);
    }

    .verify-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    .verify-name {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 900;
        color: #122033;
        font-family: "Noto Nastaliq Urdu", "Segoe UI", serif;
        direction: rtl;
        line-height: 1.8;
    }

    .verify-sub {
        margin-top: 6px;
        color: #475569;
        font-size: 15px;
        font-weight: 800;
    }

    .verify-chip-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        margin-top: 14px;
    }

    .verify-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e6edf5;
        color: #122033;
        font-size: 12px;
        font-weight: 800;
    }

    .verify-stack {
        margin-top: 16px;
        display: grid;
        gap: 10px;
        text-align: left;
    }

    .verify-box {
        border-radius: 18px;
        background: #fff;
        border: 1px solid #e8eef5;
        padding: 16px 18px;
        min-height: 92px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, .04);
    }

    .verify-box span {
        display: block;
        margin-bottom: 6px;
        font-size: 11px;
        color: #64748b;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .verify-box strong {
        display: block;
        color: #122033;
        font-size: 1rem;
        line-height: 1.55;
        word-break: break-word;
    }

    .verify-box strong.urdu {
        font-family: "Noto Nastaliq Urdu", "Segoe UI", serif;
        direction: rtl;
        line-height: 1.8;
    }

    .verify-sections {
        display: grid;
        gap: 18px;
    }

    .verify-panel {
        border-radius: 28px;
        background: #fff;
        border: 1px solid #e8eef5;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .05);
        overflow: hidden;
    }

    .verify-panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid #ecf1f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .verify-panel-head h3 {
        margin: 0;
        color: #122033;
        font-size: 1.05rem;
        font-weight: 900;
    }

    .verify-panel-head small {
        color: #64748b;
        font-weight: 700;
    }

    .verify-table {
        width: 100%;
        border-collapse: collapse;
    }

    .verify-table th,
    .verify-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #eef3f8;
        vertical-align: top;
        text-align: left;
        font-size: 14px;
        color: #122033;
    }

    .verify-table th {
        color: #64748b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 800;
        background: #f9fbfd;
    }

    .verify-table tr:last-child td {
        border-bottom: 0;
    }

    .verify-inline-badge {
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

    .verify-inline-badge.warn {
        background: rgba(245, 158, 11, .12);
        color: #b45309;
    }

    .verify-inline-badge.good {
        background: rgba(22, 163, 74, .12);
        color: #15803d;
    }

    .verify-empty {
        padding: 32px 20px;
        text-align: center;
        color: #64748b;
        font-weight: 700;
    }

    @media (max-width: 1100px) {
        .verify-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .verify-layout {
            grid-template-columns: 1fr;
        }

        .verify-profile {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .verify-summary {
            grid-template-columns: 1fr;
        }

        .verify-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>

<div class="verify-shell">
    <div class="container">
        <div class="verify-page">
            <section class="verify-hero">
                <div class="verify-hero-top">
                    <div>
                        <div class="verify-badge">
                            <i class="ti ti-shield-check"></i>
                            Live Verification
                        </div>
                        <h1>FBWS Member Complete Record</h1>
                        <p>Yeh page member ka real-time data, activity aur record history show karta hai.</p>
                    </div>
                    <div class="verify-code">{{ $memberId }}</div>
                </div>

                <div class="verify-summary">
                    <div class="verify-stat">
                        <span>Total Payments</span>
                        <strong>{{ number_format((float) ($stats['payments_total'] ?? 0), 0) }}</strong>
                    </div>
                    <div class="verify-stat">
                        <span>Paid Months</span>
                        <strong>{{ $stats['paid_months_count'] ?? 0 }}</strong>
                    </div>
                    <div class="verify-stat">
                        <span>Total Orders</span>
                        <strong>{{ $stats['orders_count'] ?? 0 }}</strong>
                    </div>
                    <div class="verify-stat">
                        <span>Total Deliveries</span>
                        <strong>{{ $stats['deliveries_count'] ?? 0 }}</strong>
                    </div>
                </div>
            </section>

            <div class="verify-layout">
                <aside class="verify-profile">
                    <div class="verify-photo">
                        <img src="{{ $photo }}" alt="{{ $cardUser->name }}">
                    </div>
                    <h2 class="verify-name">{{ $cardUser->name ?? 'NA' }}</h2>
                    <div class="verify-sub">{{ $positionLabel }}</div>

                    <div class="verify-chip-row">
                        <span class="verify-chip"><i class="ti ti-user"></i>{{ $roleLabel }}</span>
                        <span class="verify-chip"><i class="ti ti-id-badge-2"></i>{{ $cardUser->id_card ?? 'Not Assigned' }}</span>
                    </div>

                    <div class="verify-stack">
                        <div class="verify-box">
                            <span>Phone Number</span>
                            <strong>{{ $phoneNumber }}</strong>
                        </div>
                        <div class="verify-box">
                            <span>Email</span>
                            <strong>{{ $cardUser->email ?? 'NA' }}</strong>
                        </div>
                        <div class="verify-box">
                            <span>Joined On</span>
                            <strong>{{ $joinedOn }}</strong>
                        </div>
                        <div class="verify-box">
                            <span>Address</span>
                            <strong class="urdu">{{ $cardUser->address ?? 'Address not provided' }}</strong>
                        </div>
                    </div>
                </aside>

                <div class="verify-sections">
                    <section class="verify-panel">
                        <div class="verify-panel-head">
                            <div>
                                <h3>Record Summary</h3>
                                <small>Member ki overall activity ka quick snapshot</small>
                            </div>
                        </div>
                        <div style="padding:18px 22px;">
                            <div class="verify-summary" style="margin-top:0;">
                                <div class="verify-stat">
                                    <span>Complaints</span>
                                    <strong>{{ $stats['complaints_count'] ?? 0 }}</strong>
                                </div>
                                <div class="verify-stat">
                                    <span>Suggestions</span>
                                    <strong>{{ $stats['suggestions_count'] ?? 0 }}</strong>
                                </div>
                                <div class="verify-stat">
                                    <span>Support Requests</span>
                                    <strong>{{ $stats['support_count'] ?? 0 }}</strong>
                                </div>
                                <div class="verify-stat">
                                    <span>Payment Entries</span>
                                    <strong>{{ $stats['payments_count'] ?? 0 }}</strong>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="verify-panel">
                        <div class="verify-panel-head">
                            <div>
                                <h3>Latest Payments</h3>
                                <small>Recent fund/payment records</small>
                            </div>
                        </div>
                        @if ($payments->count())
                            <table class="verify-table">
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
                        @else
                            <div class="verify-empty">No payment history found.</div>
                        @endif
                    </section>

                    <section class="verify-panel">
                        <div class="verify-panel-head">
                            <div>
                                <h3>Latest Orders</h3>
                                <small>User ne kitne order request kiye aur unka status</small>
                            </div>
                        </div>
                        @if ($orders->count())
                            <table class="verify-table">
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
                                            <td><span class="verify-inline-badge">{{ ucfirst((string) ($order->status ?? 'Pending')) }}</span></td>
                                            <td>{{ $order->items->sum('qty') }}</td>
                                            <td>{{ optional($order->created_at)->format('d M Y') ?? 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="verify-empty">No order history found.</div>
                        @endif
                    </section>

                    <section class="verify-panel">
                        <div class="verify-panel-head">
                            <div>
                                <h3>Latest Deliveries</h3>
                                <small>Delivery record jo is member se related hai</small>
                            </div>
                        </div>
                        @if ($deliveries->count())
                            <table class="verify-table">
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
                        @else
                            <div class="verify-empty">No delivery history found.</div>
                        @endif
                    </section>

                    <section class="verify-panel">
                        <div class="verify-panel-head">
                            <div>
                                <h3>Complaints & Suggestions</h3>
                                <small>Member ke complaints ya suggestions</small>
                            </div>
                        </div>
                        @if ($complaints->count())
                            <table class="verify-table">
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
                                                <span class="verify-inline-badge {{ strtolower((string) ($complaint->status ?? '')) === 'resolved' ? 'good' : 'warn' }}">
                                                    {{ ucfirst((string) ($complaint->status ?? 'Pending')) }}
                                                </span>
                                            </td>
                                            <td>{{ optional($complaint->created_at)->format('d M Y') ?? 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="verify-empty">No complaints or suggestions found.</div>
                        @endif
                    </section>

                    <section class="verify-panel">
                        <div class="verify-panel-head">
                            <div>
                                <h3>Support Requests</h3>
                                <small>Welfare/support related requests</small>
                            </div>
                        </div>
                        @if ($supportRequests->count())
                            <table class="verify-table">
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
                                                <span class="verify-inline-badge {{ strtolower((string) ($support->status ?? '')) === 'approved' || strtolower((string) ($support->status ?? '')) === 'resolved' ? 'good' : 'warn' }}">
                                                    {{ ucfirst((string) ($support->status ?? 'Pending')) }}
                                                </span>
                                            </td>
                                            <td>{{ $support->amount_needed ? 'Rs ' . number_format((float) $support->amount_needed, 0) : 'NA' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="verify-empty">No support request history found.</div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
