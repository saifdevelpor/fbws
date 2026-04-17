@extends('home')

@section('title')
<title>Dashboard | FBWS</title>
@endsection

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #f1f5f9;
    --card: #ffffff;
    --text: #0f172a;
    --muted: #64748b;
    --line: #e2e8f0;
    --shadow: 0 12px 40px rgba(15, 23, 42, .08);
    --r: 18px;
    --accent: #F7721E;
    --blue: #2563eb;
    --green: #16a34a;
    --red: #dc2626;
}

body {
    background: linear-gradient(165deg, #f8fafc 0%, #eef2ff 40%, #f1f5f9 100%);
    background-attachment: fixed;
}

.page-wrap {
    padding: 12px 16px 28px;
    max-width: 1600px;
    margin: 0 auto;
}

.dash-hero {
    position: relative;
    overflow: hidden;
    border-radius: var(--r);
    padding: 22px 24px;
    margin-bottom: 18px;
    background: linear-gradient(135deg, #fff 0%, #fff8f3 45%, #ffe8d6 100%);
    border: 1px solid rgba(247, 114, 30, .18);
    box-shadow: var(--shadow);
}

.dash-hero::before {
    content: '';
    position: absolute;
    right: -40px;
    top: -60px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(247, 114, 30, .22) 0%, transparent 70%);
    pointer-events: none;
}

.dash-hero-inner {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(320px, .9fr);
    align-items: end;
    gap: 24px;
}

.dash-hero-copy {
    max-width: 760px;
}

.dash-hero-brand {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
    padding: 12px 16px;
    border-radius: 20px;
    background: rgba(255, 255, 255, .82);
    border: 1px solid rgba(226, 232, 240, .92);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
}

.dash-hero-brand img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border-radius: 16px;
    background: #fff;
    padding: 6px;
    border: 1px solid rgba(226, 232, 240, .92);
}

.dash-hero-brand-copy {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.dash-hero-brand-copy strong {
    font-size: 14px;
    font-weight: 900;
    color: var(--text);
    letter-spacing: -.01em;
}

.dash-hero-brand-copy span {
    font-size: 12px;
    color: var(--muted);
    font-weight: 600;
}

.dash-hero-side {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 14px;
}

.dash-summary-card {
    margin-left: auto;
    width: min(100%, 390px);
    padding: 16px 18px;
    border-radius: 20px;
    border: 1px solid rgba(148, 163, 184, .22);
    background: linear-gradient(180deg, rgba(255, 255, 255, .94) 0%, rgba(255, 248, 243, .98) 100%);
    box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
}

.dash-summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 12px;
}

.dash-summary-top span {
    font-size: 12px;
    font-weight: 800;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
}

.dash-summary-clock {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--accent);
    background: rgba(247, 114, 30, .1);
    border: 1px solid rgba(247, 114, 30, .16);
    font-size: 18px;
}

.dash-summary-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.dash-summary-stat {
    border-radius: 16px;
    padding: 12px 13px;
    background: rgba(255, 255, 255, .82);
    border: 1px solid rgba(226, 232, 240, .95);
}

.dash-summary-stat small {
    display: block;
    font-size: 11px;
    color: var(--muted);
    margin-bottom: 4px;
    font-weight: 700;
}

.dash-summary-stat strong {
    display: block;
    color: var(--text);
    font-size: 15px;
    font-weight: 900;
}

.dash-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
    background: rgba(247, 114, 30, .12);
    color: #c2410c;
    border: 1px solid rgba(247, 114, 30, .2);
}

.dash-role-badge.admin {
    background: rgba(37, 99, 235, .1);
    color: #1d4ed8;
    border-color: rgba(37, 99, 235, .22);
}

.dash-hero h2 {
    margin: 8px 0 4px;
    font-weight: 900;
    font-size: 1.5rem;
    color: var(--text);
    letter-spacing: -0.02em;
}

.dash-name-urdu {
    font-family: 'Noto Nastaliq Urdu', serif;
    direction: rtl;
    display: inline-block;
    line-height: 1.75;
}

.dash-hero .lead {
    font-size: 14px;
    color: var(--muted);
    margin: 0;
    max-width: 520px;
}

.dash-quick-links {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    justify-content: flex-end;
}

.dash-quick-links a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    color: var(--text);
    background: #fff;
    border: 1px solid var(--line);
    box-shadow: 0 4px 12px rgba(15, 23, 42, .05);
    transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
}

.dash-quick-links a:hover {
    transform: translateY(-2px);
    border-color: rgba(247, 114, 30, .35);
    box-shadow: 0 8px 20px rgba(247, 114, 30, .12);
    color: var(--accent);
}

.dash-quick-links a i {
    font-size: 16px;
    opacity: .85;
}

.action-center-card {
    background: linear-gradient(135deg, #ffffff 0%, #fffaf5 100%);
    border: 1px solid rgba(247, 114, 30, .16);
}

.action-center-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
}

.action-tile {
    display: block;
    text-decoration: none;
    color: var(--text);
    border: 1px solid rgba(226, 232, 240, .95);
    border-radius: 16px;
    padding: 14px 15px;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 10px 24px rgba(15, 23, 42, .05);
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.action-tile:hover {
    transform: translateY(-3px);
    border-color: rgba(247, 114, 30, .32);
    box-shadow: 0 18px 32px rgba(247, 114, 30, .10);
    color: var(--text);
}

.action-tile-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
}

.action-tile-ico {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(247, 114, 30, .10);
    color: var(--accent);
    font-size: 20px;
    border: 1px solid rgba(247, 114, 30, .14);
    flex-shrink: 0;
}

.action-tile-status {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .03em;
    text-transform: uppercase;
    padding: 5px 9px;
    border-radius: 999px;
}

.action-tile-status.good {
    background: rgba(22, 163, 74, .12);
    color: #15803d;
}

.action-tile-status.warn {
    background: rgba(245, 158, 11, .12);
    color: #b45309;
}

.action-tile-status.danger {
    background: rgba(239, 68, 68, .12);
    color: #b91c1c;
}

.action-tile h6 {
    margin: 0;
    font-size: 15px;
    font-weight: 900;
    color: var(--text);
}

.action-tile p {
    margin: 6px 0 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.55;
}

.action-note-card {
    background: linear-gradient(135deg, #ffffff 0%, #fffaf5 100%);
    color: var(--text);
    border: 1px solid rgba(247, 114, 30, .14);
}

.action-note-card .section-title h5,
.action-note-card .section-title small,
.action-note-card p,
.action-note-card li,
.action-note-card .text-muted {
    color: var(--muted) !important;
}

.action-note-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 12px;
}

.action-note-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 14px;
    background: #fff;
    border: 1px solid rgba(226, 232, 240, .95);
    box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
}

.action-note-item i {
    font-size: 18px;
    color: #fbbf24;
    margin-top: 1px;
}

.action-note-item strong {
    display: block;
    color: var(--text);
    font-size: 13px;
}

.action-note-item span {
    display: block;
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.breadcrumb-mini {
    font-size: 12px;
    color: var(--muted);
}

.breadcrumb-mini b {
    color: var(--text);
}

.pill {
    padding: 8px 12px;
    border: 1px solid var(--line);
    border-radius: 12px;
    background: #fff;
    font-size: 12px;
    font-weight: 800;
    color: var(--text);
}

.pill b {
    color: var(--accent);
}

.cardx {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    padding: 18px;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.cardx::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent), #ffb366);
    opacity: 0;
    transition: opacity .25s ease;
}

.cardx:hover::before {
    opacity: 1;
}

.dash-user-highlight {
    border-color: rgba(247, 114, 30, .28);
    background: linear-gradient(180deg, #fff 0%, #fffaf5 100%);
}

.dash-user-highlight::before {
    opacity: 1;
}

.dash-panel {
    border-radius: var(--r);
    border: 1px solid var(--line);
    box-shadow: var(--shadow);
    overflow: hidden;
    background: var(--card);
}

.dash-panel .card-body {
    padding: 20px;
}

.stat {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}

.stat .title {
    font-size: 12px;
    color: var(--muted);
    font-weight: 800;
}

.stat .value {
    font-size: 22px;
    font-weight: 900;
    color: var(--text);
    margin: 6px 0 0;
}

.stat .sub {
    font-size: 12px;
    color: var(--muted);
    margin-top: 8px;
}

.ico {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, #eff6ff, #dbeafe);
    color: #1d4ed8;
    font-weight: 900;
    font-size: 13px;
    border: 1px solid rgba(37, 99, 235, .12);
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .1);
}

.ico i {
    font-size: 22px;
    line-height: 1;
}

.ico--accent {
    background: linear-gradient(145deg, #fff4e8, #ffe0c2);
    color: #c2410c;
    border-color: rgba(247, 114, 30, .2);
    box-shadow: 0 6px 16px rgba(247, 114, 30, .15);
}

.ico--orange {
    background: linear-gradient(145deg, #fff7ed, #ffedd5);
    color: #ea580c;
    border-color: rgba(234, 88, 12, .2);
    box-shadow: 0 6px 16px rgba(234, 88, 12, .12);
}

.ico--blue {
    background: linear-gradient(145deg, #eff6ff, #dbeafe);
    color: #1d4ed8;
    border-color: rgba(37, 99, 235, .15);
    box-shadow: 0 6px 16px rgba(37, 99, 235, .12);
}

.ico--muted {
    background: linear-gradient(145deg, #f8fafc, #f1f5f9);
    color: #64748b;
    border-color: rgba(100, 116, 139, .15);
    box-shadow: 0 4px 12px rgba(15, 23, 42, .06);
}

.ico--success {
    background: linear-gradient(145deg, #ecfdf5, #d1fae5);
    color: #15803d;
    border-color: rgba(22, 163, 74, .18);
    box-shadow: 0 6px 16px rgba(22, 163, 74, .1);
}

.ico--danger {
    background: linear-gradient(145deg, #fef2f2, #fee2e2);
    color: #b91c1c;
    border-color: rgba(220, 38, 38, .18);
    box-shadow: 0 6px 16px rgba(220, 38, 38, .1);
}

.progress-mini {
    height: 6px;
    border-radius: 999px;
    background: #eef2f7;
    overflow: hidden;
    margin-top: 10px;
}

.progress-mini>span {
    display: block;
    height: 100%;
    border-radius: 999px;
    background: var(--accent);
}

.section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
}

.section-title h5 {
    margin: 0;
    font-weight: 900;
    color: var(--text);
    font-size: 15px;
}

.section-title small {
    color: var(--muted);
}

.chart-box {
    height: 310px;
    position: relative;
    border-radius: 16px;
    background:
        linear-gradient(180deg, rgba(248, 250, 252, .9) 0%, rgba(255, 255, 255, .98) 100%);
    border: 1px dashed rgba(148, 163, 184, .18);
    padding: 8px;
}

.chart-box canvas {
    width: 100% !important;
    height: 100% !important;
}

.blue-card {
    background: linear-gradient(145deg, #1e40af 0%, #2563eb 45%, #3b82f6 100%);
    color: #fff;
    border: none;
    box-shadow: 0 16px 40px rgba(37, 99, 235, .35);
}

.blue-card .muted {
    color: rgba(255, 255, 255, .75);
    font-size: 12px;
    font-weight: 700;
}

.blue-card .big {
    font-size: 26px;
    font-weight: 900;
    margin: 6px 0 0;
}

.blue-card .mini-line {
    height: 120px;
    margin-top: 12px;
}

.blue-card .mini-line canvas {
    height: 120px !important;
}

.list-mini {
    margin-top: 12px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.list-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 14px;
    background: rgba(255, 255, 255, .12);
    border: 1px solid rgba(255, 255, 255, .14);
}

.list-row .l {
    display: flex;
    align-items: center;
    gap: 10px;
}

.tag-ico {
    width: 34px;
    height: 34px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, .2);
    font-weight: 900;
}

.list-row .name {
    font-weight: 900;
    font-size: 13px;
}

.list-row .sub {
    font-size: 11px;
    color: rgba(255, 255, 255, .75);
    margin-top: 2px;
}

.list-row .amt {
    font-weight: 900;
    font-size: 13px;
}

.spark {
    height: 60px;
}

.spark canvas {
    height: 60px !important;
}

.table thead th {
    font-size: 12px;
    color: #334155;
    background: #f8fafc;
    border-bottom: 1px solid var(--line) !important;
}

.table tbody td {
    border-bottom: 1px solid var(--line);
    vertical-align: middle;
}

.dash-click {
    cursor: pointer;
    transition: .25s ease;
}

.dash-click:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 36px rgba(15, 23, 42, .12);
    border-color: rgba(247, 114, 30, .35);
}

.section-title h5 {
    font-size: 1.05rem;
    letter-spacing: -0.02em;
}

@media (max-width: 576px) {
    .dash-hero {
        padding: 18px 16px;
    }

    .dash-hero-brand {
        width: 100%;
        justify-content: flex-start;
    }

    .dash-hero h2 {
        font-size: 1.25rem;
    }

    .dash-summary-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 991px) {
    .dash-hero-inner {
        grid-template-columns: 1fr;
    }

    .dash-summary-card {
        width: 100%;
        margin-left: 0;
    }

    .dash-quick-links {
        justify-content: flex-start;
    }
}
</style>

<div class="page-wrap">

    {{-- HERO --}}
    <div class="dash-hero">
        <div class="dash-hero-inner">
            <div class="dash-hero-copy">
                <div class="dash-hero-brand">
                    <img src="{{ asset('website/images/6.png') }}" alt="FBWS Logo">
                    <div class="dash-hero-brand-copy">
                        <strong>Farooka Brothers Welfare Society</strong>
                        <span>Community support dashboard</span>
                    </div>
                </div>
                <div class="breadcrumb-mini">Home / <b>Dashboard</b></div>
                <span class="dash-role-badge {{ $isAdmin ? 'admin' : '' }}">
                    <i class="ti {{ $isAdmin ? 'ti-shield-check' : 'ti-user' }}"></i>
                    {{ $isAdmin ? 'Administrator' : 'Member' }}
                </span>
                <h2>Welcome back, <span class="dash-name-urdu">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span></h2>
                <p class="lead">Overview of welfare, payments, orders, and activity for <strong>{{ $monthName }}
                        {{ $yearNum }}</strong>. All figures use {{ config('app.timezone') }} time where applicable.</p>
            </div>
            <div class="dash-hero-side">
                <div class="dash-summary-card">
                    <div class="dash-summary-top">
                        <span>Current period</span>
                        <div class="dash-summary-clock"><i class="ti ti-clock-hour-4"></i></div>
                    </div>
                    <div class="dash-summary-grid">
                        <div class="dash-summary-stat">
                            <small>Month</small>
                            <strong>{{ $monthName }} {{ $yearNum }}</strong>
                        </div>
                        <div class="dash-summary-stat">
                            <small>Live time</small>
                            <strong id="dashboardCurrentTime">{{ optional($now)->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</strong>
                        </div>
                    </div>
                </div>
                <div class="dash-quick-links">
                    <a href="{{ route('dashboard') }}"><i class="ti ti-layout-dashboard"></i> Dashboard</a>
                    <a href="{{ route('reports.monthly') }}"><i class="ti ti-chart-line"></i> Monthly reports</a>
                    <a href="{{ route('account.membership-card') }}"><i class="ti ti-id-badge-2"></i> E ID-Card</a>
                    @if ($isAdmin)
                    <a href="{{ route('payments.index') }}"><i class="ti ti-credit-card"></i> Payments</a>
                    <a href="{{ route('orders.index') }}"><i class="ti ti-shopping-cart"></i> Orders</a>
                    <a href="{{ route('admin.support-requests.index') }}"><i class="ti ti-heart-handshake"></i> Support cases</a>
                    @else
                    <a href="{{ route('payments.history') }}"><i class="ti ti-history"></i> My payments</a>
                    <a href="{{ route('orders.index') }}"><i class="ti ti-shopping-cart"></i> My orders</a>
                    <a href="{{ route('support-requests.mine') }}"><i class="ti ti-heart-handshake"></i> My support</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($isAdmin && (int) ($pendingOrderRequests ?? 0) > 0)
    <div class="alert alert-warning border-0 d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 rounded-3 shadow-sm"
        style="background: linear-gradient(90deg, #fffbeb, #fef3c7); border-left: 4px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="rounded-circle d-inline-flex align-items-center justify-content-center"
                style="width:40px;height:40px;background:rgba(245,158,11,.25);"><i
                    class="ti ti-bell-ringing text-warning"></i></span>
            <div><strong>{{ (int) $pendingOrderRequests }}</strong> new order request(s) need attention.</div>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-dark rounded-pill px-3">View orders</a>
    </div>
    @endif
    @if ($isAdmin && (int) ($newSupportRequestCount ?? 0) > 0)
    <div class="alert alert-danger border-0 d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 rounded-3 shadow-sm"
        style="background: linear-gradient(90deg, #fff1f2, #ffe4e6); border-left: 4px solid #e11d48 !important;">
        <div class="d-flex align-items-center gap-2">
            <span class="rounded-circle d-inline-flex align-items-center justify-content-center"
                style="width:40px;height:40px;background:rgba(225,29,72,.14);"><i
                    class="ti ti-heart-handshake text-danger"></i></span>
            <div><strong>{{ (int) $newSupportRequestCount }}</strong> new support request(s) need welfare review.</div>
        </div>
        <a href="{{ route('admin.support-requests.index') }}" class="btn btn-sm btn-dark rounded-pill px-3">Open support desk</a>
    </div>
    @endif

    <div class="row g-3 mb-1">
        <div class="col-12 col-xl-8">
            <div class="cardx action-center-card">
                <div class="section-title">
                    <div>
                        <h5>{{ $isAdmin ? 'Action Center' : 'My Account Snapshot' }}</h5>
                        <small>{{ $isAdmin ? 'Important items that may need attention today.' : 'A quick view of your account activity and next steps.' }}</small>
                    </div>
                </div>

                <div class="action-center-grid">
                    @if ($isAdmin)
                    <a href="{{ route('leads.index') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-user-plus"></i></div>
                            <span class="action-tile-status {{ (int) ($newLeadCount ?? 0) > 0 ? 'danger' : 'good' }}">{{ (int) ($newLeadCount ?? 0) > 0 ? 'Review' : 'Clear' }}</span>
                        </div>
                        <h6>{{ (int) ($newLeadCount ?? 0) }} New Leads</h6>
                        <p>New member requests waiting for admin review.</p>
                    </a>

                    <a href="{{ route('orders.index') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-shopping-bag-plus"></i></div>
                            <span class="action-tile-status {{ (int) ($pendingOrderRequests ?? 0) > 0 ? 'warn' : 'good' }}">{{ (int) ($pendingOrderRequests ?? 0) > 0 ? 'Pending' : 'Stable' }}</span>
                        </div>
                        <h6>{{ (int) ($pendingOrderRequests ?? 0) }} New Requests</h6>
                        <p>Fresh order requests not yet seen in the admin panel.</p>
                    </a>

                    <a href="{{ route('admin.complaints.index') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-message-report"></i></div>
                            <span class="action-tile-status {{ (int) ($openComplaintCount ?? 0) > 0 ? 'warn' : 'good' }}">{{ (int) ($openComplaintCount ?? 0) > 0 ? 'Open' : 'Clear' }}</span>
                        </div>
                        <h6>{{ (int) ($openComplaintCount ?? 0) }} Open Complaints</h6>
                        <p>Complaint cases still marked new or in progress.</p>
                    </a>

                    <a href="{{ route('admin.support-requests.index') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-heart-handshake"></i></div>
                            <span class="action-tile-status {{ (int) ($openSupportRequestCount ?? 0) > 0 ? 'danger' : 'good' }}">{{ (int) ($openSupportRequestCount ?? 0) > 0 ? 'Active' : 'Clear' }}</span>
                        </div>
                        <h6>{{ (int) ($openSupportRequestCount ?? 0) }} Support Cases</h6>
                        <p>Financial, ration, medical, and emergency help requests under review.</p>
                    </a>

                    <a href="{{ route('payments.index') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-credit-card-off"></i></div>
                            <span class="action-tile-status {{ (int) ($unpaidCount ?? 0) > 0 ? 'danger' : 'good' }}">{{ (int) ($unpaidCount ?? 0) > 0 ? 'Due' : 'Done' }}</span>
                        </div>
                        <h6>{{ (int) ($unpaidCount ?? 0) }} Unpaid Members</h6>
                        <p>Members whose current month payment is still pending.</p>
                    </a>
                    @else
                    <a href="{{ route('payments.history') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-wallet"></i></div>
                            <span class="action-tile-status {{ (float) ($userThisMonthContribution ?? 0) > 0 ? 'good' : 'danger' }}">{{ (float) ($userThisMonthContribution ?? 0) > 0 ? 'Paid' : 'Due' }}</span>
                        </div>
                        <h6>{{ $userPaymentStatus ?? 'Payment status' }}</h6>
                        <p>
                            @if ((float) ($userThisMonthContribution ?? 0) > 0)
                            Your latest month contribution is recorded.
                            @else
                            Current month payment is still waiting in your account.
                            @endif
                        </p>
                    </a>

                    <a href="{{ route('orders.index') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-package"></i></div>
                            <span class="action-tile-status {{ (int) ($activeOrderRequestCount ?? 0) > 0 ? 'warn' : 'good' }}">{{ (int) ($activeOrderRequestCount ?? 0) > 0 ? 'Active' : 'Clear' }}</span>
                        </div>
                        <h6>{{ (int) ($activeOrderRequestCount ?? 0) }} Active Requests</h6>
                        <p>Your pending or confirmed item requests are shown here.</p>
                    </a>

                    <a href="{{ route('complaints.mine') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-help-circle"></i></div>
                            <span class="action-tile-status {{ (int) ($openComplaintCount ?? 0) > 0 ? 'warn' : 'good' }}">{{ (int) ($openComplaintCount ?? 0) > 0 ? 'Open' : 'Settled' }}</span>
                        </div>
                        <h6>{{ (int) ($openComplaintCount ?? 0) }} Open Complaints</h6>
                        <p>Track complaint cases that still need an update from admin.</p>
                    </a>

                    <a href="{{ route('support-requests.mine') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-heart-handshake"></i></div>
                            <span class="action-tile-status {{ (int) ($openSupportRequestCount ?? 0) > 0 ? 'warn' : 'good' }}">{{ (int) ($openSupportRequestCount ?? 0) > 0 ? 'Active' : 'Settled' }}</span>
                        </div>
                        <h6>{{ (int) ($openSupportRequestCount ?? 0) }} My Support Cases</h6>
                        <p>Check admin review on your welfare assistance requests.</p>
                    </a>

                    <a href="{{ route('account.devices') }}" class="action-tile">
                        <div class="action-tile-top">
                            <div class="action-tile-ico"><i class="ti ti-devices"></i></div>
                            <span class="action-tile-status {{ (int) ($userDeviceCount ?? 0) > 1 ? 'warn' : 'good' }}">{{ (int) ($userDeviceCount ?? 0) > 1 ? 'Multiple' : 'Secure' }}</span>
                        </div>
                        <h6>{{ (int) ($userDeviceCount ?? 0) }} Login Devices</h6>
                        <p>Review the devices where your account has recently been used.</p>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="cardx action-note-card h-100">
                <div class="section-title">
                    <div>
                        <h5>{{ $isAdmin ? 'Portal Health' : 'Helpful Summary' }}</h5>
                        <small>{{ $isAdmin ? 'A quick operational overview for today.' : 'Your recent account highlights in one place.' }}</small>
                    </div>
                </div>

                <div class="action-note-list">
                    @if ($isAdmin)
                    <div class="action-note-item">
                        <i class="ti ti-users-group"></i>
                        <div>
                            <strong>{{ (int) ($totalMembers ?? 0) }} registered members</strong>
                            <span>Total members currently available in the portal.</span>
                        </div>
                    </div>
                    <div class="action-note-item">
                        <i class="ti ti-shopping-cart-check"></i>
                        <div>
                            <strong>{{ (int) ($activeOrderRequestCount ?? 0) }} active order requests</strong>
                            <span>Includes request statuses that are still pending or confirmed.</span>
                        </div>
                    </div>
                    <div class="action-note-item">
                        <i class="ti ti-heart-handshake"></i>
                        <div>
                            <strong>{{ (int) ($openSupportRequestCount ?? 0) }} support cases need follow-up</strong>
                            <span>Medical, ration, and emergency support requests currently visible in the support desk.</span>
                        </div>
                    </div>
                    <div class="action-note-item">
                        <i class="ti ti-photo"></i>
                        <div>
                            <strong>{{ (int) array_sum($galleryCounts ?? []) }} gallery uploads in last 6 months</strong>
                            <span>Community media activity snapshot from the recent months.</span>
                        </div>
                    </div>
                    @else
                    <div class="action-note-item">
                        <i class="ti ti-calendar-dollar"></i>
                        <div>
                            <strong>Current month contribution: Rs {{ number_format((float) ($userThisMonthContribution ?? 0), 0) }}</strong>
                            <span>{{ $userLastPaymentLabel ? 'Last payment recorded on ' . $userLastPaymentLabel . '.' : 'No payment has been recorded for you yet.' }}</span>
                        </div>
                    </div>
                    <div class="action-note-item">
                        <i class="ti ti-history-toggle"></i>
                        <div>
                            <strong>{{ (int) ($loginThisMonthCount ?? 0) }} logins this month</strong>
                            <span>Your recent access history stays available for account review.</span>
                        </div>
                    </div>
                    <div class="action-note-item">
                        <i class="ti ti-heart-handshake"></i>
                        <div>
                            <strong>{{ (int) ($openSupportRequestCount ?? 0) }} support requests in progress</strong>
                            <span>Your welfare assistance requests and their latest admin review status appear here.</span>
                        </div>
                    </div>
                    <div class="action-note-item">
                        <i class="ti ti-mail-opened"></i>
                        <div>
                            <strong>{{ (int) ($thisMonthOrderRequests ?? 0) }} requests made this month</strong>
                            <span>Item or welfare requests you submitted in the current month.</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- TOP STATS --}}
    <div class="row g-3">
        {{-- Admin Only: Users Cards --}}

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#allUsersModal">
                <div class="stat">
                    <div>
                        <div class="title">Total Users</div>
                        <div class="value">{{ (int) ($totalMembers ?? 0) }}</div>
                        <div class="sub">All registered users</div>
                        <div class="progress-mini"><span style="width:70%"></span></div>
                    </div>
                    <div class="ico">U</div>
                </div>
            </div>
        </div>
        @if ($isAdmin)
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#paidUsersModal"
                style="border-color:rgba(34,197,94,.25)">
                <div class="stat">
                    <div>
                        <div class="title">Paid Users ({{ $monthName }})</div>
                        <div class="value" style="color:#22c55e">{{ (int) ($paidCount ?? 0) }}</div>
                        <div class="sub">Paid in current month</div>
                        <div class="progress-mini"><span style="width:62%"></span></div>
                    </div>
                    <div class="ico">P</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#unpaidUsersModal"
                style="border-color:rgba(239,68,68,.25)">
                <div class="stat">
                    <div>
                        <div class="title">Unpaid Users ({{ $monthName }})</div>
                        <div class="value" style="color:#ef4444">{{ (int) ($unpaidCount ?? 0) }}</div>
                        <div class="sub">Not paid in current month</div>
                        <div class="progress-mini"><span style="width:58%;background:#ef4444"></span></div>
                    </div>
                    <div class="ico">UP</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Login History Cards --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#loginThisMonthModal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            Users Logged In ({{ $monthName }})
                            @else
                            My Logins ({{ $monthName }})
                            @endif
                        </div>
                        <div class="value">{{ (int) ($loginThisMonthCount ?? 0) }}</div>
                        <div class="sub">
                            @if ($isAdmin)
                            Unique users logged in this month
                            @else
                            Total logins this month
                            @endif
                        </div>
                        <div class="progress-mini"><span style="width:66%"></span></div>
                    </div>
                    <div class="ico">LH</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#loginLast6Modal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            Users Logged In (Last 6 Months)
                            @else
                            My Logins (Last 6 Months)
                            @endif
                        </div>
                        <div class="value">{{ (int) ($loginLast6Count ?? 0) }}</div>
                        <div class="sub">
                            @if ($isAdmin)
                            Unique users logged in (last 6 months)
                            @else
                            Total logins (last 6 months)
                            @endif
                        </div>
                        <div class="progress-mini"><span style="width:72%"></span></div>
                    </div>
                    <div class="ico">6M</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx">
                <div class="stat">
                    <div>
                        <div class="title">previous Balance</div>
                        <div class="value">Rs {{ number_format((float) ($wMonth->opening_balance ?? 0), 0) }}</div>
                        <div class="sub">Start of {{ $monthName }}</div>
                        <div class="progress-mini"><span style="width:55%"></span></div>
                    </div>
                    <div class="ico">PB</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#cmPaymentsModal">
                <div class="stat">
                    <div>
                        <div class="title">Monthly Income</div>
                        <div class="value">Rs {{ number_format((float) ($wMonth->total_received ?? 0), 0) }}</div>
                        <div class="sub">Payments + Add Amount</div>
                        <div class="progress-mini"><span style="width:72%"></span></div>
                    </div>
                    <div class="ico">+</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#cmExpensesModal">
                <div class="stat">
                    <div>
                        <div class="title">Monthly Expense</div>
                        <div class="value">Rs {{ number_format((float) ($wMonth->total_used ?? 0), 0) }}</div>
                        <div class="sub">Used in {{ $monthName }}</div>
                        <div class="progress-mini"><span style="width:38%;background:#ef4444"></span></div>
                    </div>
                    <div class="ico">-</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx" style="border-color:rgba(247,114,30,.25)">
                <div class="stat">
                    <div>
                        <div class="title">Remaining Balance</div>
                        <div class="value" style="color:var(--accent)">
                            Rs {{ number_format((float) ($wMonth->closing_balance ?? 0), 0) }}
                        </div>
                        <div class="sub">Available funds</div>
                        <div class="progress-mini"><span style="width:64%"></span></div>
                    </div>
                    <div class="ico">Rs</div>
                </div>
            </div>
        </div>

        {{-- Complaints --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#cmComplaintsModal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            This Month Complaints (All)
                            @else
                            My Complaints (This Month)
                            @endif
                        </div>
                        <div class="value">{{ (int) ($currentMonthComplaints ?? 0) }}</div>
                        <div class="sub">Total Complaints (All time): {{ (int) ($totalComplaintsAllTime ?? 0) }}
                        </div>
                        <div class="progress-mini"><span style="width:65%"></span></div>
                    </div>
                    <div class="ico">C</div>
                </div>
            </div>
        </div>

        {{-- Damages --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#cmDamagesModal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            This Month Damages (All)
                            @else
                            My Damages (This Month)
                            @endif
                        </div>
                        <div class="value">{{ (int) ($currentMonthDamages ?? 0) }}</div>
                        <div class="sub">Total Damages (All time): {{ (int) ($totalDamagesAllTime ?? 0) }}</div>
                        <div class="progress-mini"><span style="width:60%"></span></div>
                    </div>
                    <div class="ico">D</div>
                </div>
            </div>
        </div>

        {{-- Suggestions --}}
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#cmSuggestionsModal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            This Month Suggestions (All)
                            @else
                            My Suggestions (This Month)
                            @endif
                        </div>
                        <div class="value">{{ (int) ($currentMonthSuggestions ?? 0) }}</div>
                        <div class="sub">Total Suggestions (All time): {{ (int) ($totalSuggestionsAllTime ?? 0) }}
                        </div>
                        <div class="progress-mini"><span style="width:55%"></span></div>
                    </div>
                    <div class="ico">S</div>
                </div>
            </div>
        </div>
        @if ($isAdmin)
        <!-- Total Orders -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#deliveryOrdersModal">
                <div class="stat">
                    <div>
                        <div class="title">Total Orders</div>
                        <div class="value">
                            {{ (int) ($totalOrders ?? 0) }}
                        </div>
                        <div class="sub">All delivery orders</div>
                        <div class="progress-mini">
                            <span style="width:70%"></span>
                        </div>
                    </div>
                    <div class="ico">TO</div>
                </div>
            </div>
        </div>

        <!-- This Month Orders -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#currentMonthModal"
                style="cursor:pointer;">

                <div class="stat">
                    <div>
                        <div class="title">Current Month Orders</div>

                        <div class="value">
                            {{ (int) ($currentMonthCount ?? 0) }}
                        </div>

                        <div class="sub">
                            {{ $monthName }} Orders
                        </div>

                        <div class="progress-mini">
                            <span style="width:75%"></span>
                        </div>
                    </div>

                    <div class="ico">CM</div>
                </div>
            </div>
        </div>
        @endif

        <!-- Total / My Order Requests -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#orderRequestsModal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            Total Order Requests
                            @else
                            My Order Requests
                            @endif
                        </div>
                        <div class="value">{{ (int) ($totalOrderRequests ?? 0) }}</div>
                        <div class="sub">All time</div>
                        <div class="progress-mini"><span style="width:68%"></span></div>
                    </div>
                    <div class="ico {{ $isAdmin ? '' : 'ico--orange' }}">
                        @if ($isAdmin)
                        OR
                        @else
                        <i class="ti ti-shopping-cart"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month / My This Month Requests -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="cardx dash-click" data-bs-toggle="modal" data-bs-target="#currentMonthOrderRequestsModal">
                <div class="stat">
                    <div>
                        <div class="title">
                            @if ($isAdmin)
                            This Month Order Requests
                            @else
                            My This Month Requests
                            @endif
                        </div>
                        <div class="value">{{ (int) ($thisMonthOrderRequests ?? 0) }}</div>
                        <div class="sub">{{ $monthName }} requests</div>
                        <div class="progress-mini"><span style="width:72%"></span></div>
                    </div>
                    <div class="ico {{ $isAdmin ? '' : 'ico--accent' }}">
                        @if ($isAdmin)
                        MR
                        @else
                        <i class="ti ti-calendar-event"></i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!$isAdmin)
    <div class="row g-3 mt-1">
        <div class="col-12 col-md-4">
            <div class="cardx dash-user-highlight">
                <div class="stat">
                    <div>
                        <div class="title">My payments ({{ $monthName }})</div>
                        <div class="value" style="color:var(--accent)">Rs
                            {{ number_format((float) ($userThisMonthContribution ?? 0), 0) }}</div>
                        <div class="sub">Contributed this month</div>
                    </div>
                    <div class="ico ico--accent"><i class="ti ti-currency-rupee"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="cardx">
                <div class="stat">
                    <div>
                        <div class="title">My total payments</div>
                        <div class="value">Rs {{ number_format((float) ($userTotalContribution ?? 0), 0) }}</div>
                        <div class="sub">All time membership payments</div>
                    </div>
                    <div class="ico ico--blue"><i class="ti ti-wallet"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="cardx">
                <div class="stat">
                    <div>
                        <div class="title">Last payment</div>
                        <div class="value" style="font-size:1rem;line-height:1.35;">
                            @if ($userLastPayment)
                            Rs {{ number_format((float) $userLastPayment->amount, 0) }}
                            <div class="sub mt-1">
                                {{ $userLastPayment->month ?? '—' }}
                                @if ($userLastPayment->created_at)
                                · {{ $userLastPayment->created_at->timezone(config('app.timezone'))->format('d M Y') }}
                                @endif
                            </div>
                            @else
                            <span class="sub">No payment recorded</span>
                            @endif
                        </div>
                    </div>
                    <div class="ico ico--muted"><i class="ti ti-receipt"></i></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MAIN GRID --}}
    <div class="row g-3 mt-1">
        <div class="col-12 col-xl-8">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>Payment Record</h5>
                        <small>Income vs Expense (Last 6 months)</small>
                    </div>
                </div>
                <div class="chart-box">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-12 col-md-3">
                        <div class="pill w-100">Payments: <b>Rs
                                {{ number_format((float) ($paymentsTotal ?? 0), 0) }}</b></div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="pill w-100">Add Amount: <b>Rs
                                {{ number_format((float) ($incomeTotal ?? 0), 0) }}</b></div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="pill w-100">Expense: <b>Rs
                                {{ number_format((float) ($wMonth->total_used ?? 0), 0) }}</b></div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="pill w-100">Remaining: <b>Rs
                                {{ number_format((float) ($wMonth->closing_balance ?? 0), 0) }}</b></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="cardx blue-card">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="muted">Total Fund</div>
                        <div class="big">Rs {{ number_format((float) ($wMonth->total_received ?? 0), 0) }}</div>
                    </div>
                    <div class="pill"
                        style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.18);color:#fff">
                        {{ $monthName }}
                    </div>
                </div>

                <div class="mini-line">
                    <canvas id="salesMiniChart"></canvas>
                </div>

                <div class="list-mini">
                    <div class="list-row">
                        <div class="l">
                            <div class="tag-ico">P</div>
                            <div>
                                <div class="name">Paid Members</div>
                                <div class="sub">{{ $monthName }}</div>
                            </div>
                        </div>
                        <div class="amt">{{ (int) ($paidCount ?? 0) }}</div>
                    </div>

                    <div class="list-row">
                        <div class="l">
                            <div class="tag-ico">U</div>
                            <div>
                                <div class="name">Unpaid Members</div>
                                <div class="sub">{{ $monthName }}</div>
                            </div>
                        </div>
                        <div class="amt">{{ (int) ($unpaidCount ?? 0) }}</div>
                    </div>

                    <div class="list-row">
                        <div class="l">
                            <div class="tag-ico">M</div>
                            <div>
                                <div class="name">Total Members</div>
                                <div class="sub">All time</div>
                            </div>
                        </div>
                        <div class="amt">{{ (int) ($totalMembers ?? 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SPARK CARDS --}}
    <div class="row g-3 mt-1">
        <div class="col-12 col-lg-4">
            <div class="cardx">
                <div class="section-title">
                    <h5>Currently Payment</h5>
                    <small>Trend</small>
                </div>
                <div class="spark"><canvas id="spark1"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="cardx">
                <div class="section-title">
                    <h5>This month Payment</h5>
                    <small>Trend</small>
                </div>
                <div class="spark"><canvas id="spark2"></canvas></div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="cardx">
                <div class="section-title">
                    <h5>All Payment</h5>
                    <small>Trend</small>
                </div>
                <div class="spark"><canvas id="spark3"></canvas></div>
            </div>
        </div>
    </div>

    {{-- DONUT + LATEST PAYMENTS --}}
    <div class="row g-3 mt-1">

        <!-- Chart Section -->
        <div class="col-12 col-xl-4">
            <div class="card dash-panel shadow-sm h-100">
                <div class="card-body">

                    <div class="mb-3">
                        <h5 class="mb-0">
                            @if ($isAdmin)
                            Paid vs Unpaid
                            @else
                            My Contribution
                            @endif
                        </h5>
                        <small class="text-muted">{{ $monthName }} overview</small>
                    </div>

                    <div class="ratio ratio-1x1">
                        <canvas id="paidUnpaidChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

        <!-- Latest Payments -->
        <div class="col-12 col-xl-8">
            <div class="card dash-panel shadow-sm h-100">
                <div class="card-body">

                    <!-- Header -->
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                        <div>
                            <h5 class="mb-0">Latest Payments</h5>
                            <small class="text-muted">Recent membership payments</small>
                        </div>

                        <div class="d-grid d-sm-block">
                            @if (auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('payments.index') }}" class="btn btn-sm text-white px-3"
                                style="background:#F7721E;">
                                View All Payments
                            </a>
                            @else
                            <a href="{{ route('payments.history') }}" class="btn btn-sm text-white px-3"
                                style="background:#F7721E;">
                                View My Payment History
                            </a>
                            @endif
                        </div>
                    </div>

                    @if ($lastPayments->count())
                    <!-- ✅ Mobile View (Cards) -->
                    <div class="d-md-none">
                        <div class="list-group list-group-flush">
                            @foreach ($lastPayments as $i => $p)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $p->user?->profile_photo ? asset($p->user->profile_photo) : asset('assets/img/avatars/1.png') }}"
                                            class="rounded-circle" width="40" height="40" style="object-fit:cover;"
                                            alt="avatar">
                                        <div>
                                            <div class="fw-semibold">{{ $p->user?->name ?? 'NA' }}</div>
                                            <div class="text-muted small">
                                                {{ $p->month ?? 'NA' }}
                                                @if ($p->created_at)
                                                •
                                                {{ $p->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <span class="badge bg-success fs-6">
                                            Rs {{ (int) $p->amount }}
                                        </span>
                                        <div class="text-muted small mt-1">
                                            Receipt: {{ $p->receipt_number ?? 'NA' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2 d-flex align-items-center justify-content-between">
                                    <div class="small text-muted">#{{ $i + 1 }}</div>

                                    <div class="d-flex align-items-center gap-2">
                                        @if ($p->picture)
                                        <a href="{{ asset($p->picture) }}" target="_blank"
                                            class="btn btn-outline-secondary btn-sm">
                                            View Picture
                                        </a>
                                        @else
                                        <span class="badge text-bg-light">No Picture</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ✅ Desktop / Tablet View (Table) -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th class="text-nowrap">#</th>
                                        <th class="text-start">User</th>
                                        <th class="text-nowrap">Amount</th>
                                        <th class="text-nowrap">Month</th>
                                        <th class="text-nowrap d-none d-lg-table-cell">Receipt #</th>
                                        <th class="text-nowrap d-none d-lg-table-cell">Picture</th>
                                        <th class="text-nowrap d-none d-xl-table-cell">Created At (PK)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lastPayments as $i => $p)
                                    <tr class="text-center">
                                        <td class="text-nowrap">{{ $i + 1 }}</td>

                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $p->user?->profile_photo ? asset($p->user->profile_photo) : asset('assets/img/avatars/1.png') }}"
                                                    class="rounded-circle me-2" width="32" height="32"
                                                    style="object-fit:cover;" alt="avatar">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">{{ $p->user?->name ?? 'NA' }}</span>
                                                    <small class="text-muted d-lg-none">
                                                        Receipt: {{ $p->receipt_number ?? 'NA' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-nowrap">
                                            <span class="badge bg-success">
                                                Rs {{ number_format((float) $p->amount, 2) }}
                                            </span>
                                        </td>

                                        <td class="text-nowrap">{{ $p->month ?? 'NA' }}</td>

                                        <td class="text-nowrap d-none d-lg-table-cell">
                                            {{ $p->receipt_number ?? 'NA' }}
                                        </td>

                                        <td class="d-none d-lg-table-cell">
                                            @if ($p->picture)
                                            <a href="{{ asset($p->picture) }}" target="_blank" class="d-inline-block">
                                                <img src="{{ asset($p->picture) }}" class="rounded" width="44"
                                                    height="44" style="object-fit:cover;" alt="receipt">
                                            </a>
                                            @else
                                            <span class="badge text-bg-light">NA</span>
                                            @endif
                                        </td>

                                        <td class="text-nowrap d-none d-xl-table-cell">
                                            {{ $p->created_at ? $p->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="text-muted">No payments found.</div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Complaints/Suggestions Chart --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>Complaints & Suggestions</h5>
                        <small>
                            @if ($isAdmin)
                            All complaints & suggestions (Last 6 months)
                            @else
                            My complaints & suggestions (Last 6 months)
                            @endif
                        </small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="complaintSuggestionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Damages Chart --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>Damages</h5>
                        <small>
                            @if ($isAdmin)
                            All damages (Last 6 months)
                            @else
                            My damages (Last 6 months)
                            @endif
                        </small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="damageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ORDERS TABLE (keep yours) --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card dash-panel shadow-sm">
                <div class="card-body">

                    <!-- Title -->
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                        <div>
                            <h5 class="mb-0">
                                @if ($isAdmin)
                                Last 5 Delivery Orders (All)
                                @else
                                My Last 5 Delivery Orders
                                @endif
                            </h5>
                            <small class="text-muted">Latest delivery orders</small>
                        </div>

                        <div class="d-grid d-sm-block">
                            @if (auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('deliveries.index') }}" class="btn btn-sm text-white px-3"
                                style="background:#F7721E;">
                                View All Delivery orders
                            </a>
                            @else
                            <a href="{{ route('deliveries.index') }}" class="btn btn-sm text-white px-3"
                                style="background:#F7721E;">
                                View My Delivery Orders
                            </a>
                            @endif
                        </div>
                    </div>

                    @if ($lastOrders->count())
                    <!-- ✅ Mobile View (Cards) -->
                    <div class="d-md-none">
                        <div class="list-group list-group-flush">
                            @foreach ($lastOrders as $order)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold">
                                            #{{ $loop->iteration }} — {{ $order->user?->name ?? 'N/A' }}
                                        </div>

                                        <div class="text-muted small mt-1">
                                            Delivery: {{ $order->delivery_date }}
                                            @if ($order->created_at)
                                            •
                                            {{ \Carbon\Carbon::parse($order->created_at)->setTimezone(config('app.timezone'))->format('h:i A') }}
                                            @endif
                                        </div>

                                        <div class="small mt-2">
                                            <span class="text-muted">Notes:</span>
                                            <span class="text-dark">
                                                {{ \Illuminate\Support\Str::words($order->notes ?? '-', 12, '...') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="badge text-white px-3 py-2" style="background:#F7721E;">
                                            {{ $order->items->sum('qty') }} Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ✅ Desktop / Tablet View (Table) -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th class="text-nowrap">#</th>
                                        <th class="text-start">User</th>
                                        <th class="text-nowrap">Delivery Date</th>
                                        <th class="text-nowrap">Time</th>
                                        <th class="text-nowrap">Total Items</th>
                                        <th class="text-start d-none d-lg-table-cell">Notes</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($lastOrders as $order)
                                    <tr class="text-center">
                                        <td class="text-nowrap">{{ $loop->iteration }}</td>

                                        <td class="text-start">
                                            {{ $order->user?->name ?? 'N/A' }}
                                        </td>

                                        <td class="text-nowrap">{{ $order->delivery_date }}</td>

                                        <td class="text-nowrap">
                                            {{ \Carbon\Carbon::parse($order->created_at)->setTimezone(config('app.timezone'))->format('h:i A') }}
                                        </td>

                                        <td class="text-nowrap">
                                            <span class="badge text-white" style="background:#F7721E;">
                                                {{ $order->items->sum('qty') }}
                                            </span>
                                        </td>

                                        <td class="text-start d-none d-lg-table-cell">
                                            {{ \Illuminate\Support\Str::words($order->notes ?? '-', 10, '...') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="text-muted">No orders found.</div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- ORDER REQUESTS TABLE --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card dash-panel shadow-sm">
                <div class="card-body">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                        <div>
                            <h5 class="mb-0">
                                @if ($isAdmin)
                                Latest 5 Order Requests (All)
                                @else
                                My Latest 5 Order Requests
                                @endif
                            </h5>
                            <small class="text-muted">Latest order request records</small>
                        </div>

                        <div class="d-grid d-sm-block">
                            @if (auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('orders.index') }}" class="btn btn-sm text-white px-3"
                                style="background:#F7721E;">
                                View All New Orders
                            </a>
                            @else
                            <a href="{{ route('orders.index') }}" class="btn btn-sm text-white px-3"
                                style="background:#F7721E;">
                                View My Orders
                            </a>
                            @endif
                        </div>
                    </div>

                    @if (($latestOrderRequests ?? collect())->count())
                    <!-- ✅ Mobile View (Cards) -->
                    <div class="d-md-none">
                        <div class="list-group list-group-flush">
                            @foreach ($latestOrderRequests as $order)
                            @php
                            $orderPayload = [
                            'id' => $order->id,
                            'user' => $order->user?->name ?? 'NA',
                            'status' => ucfirst($order->status ?? 'pending'),
                            'notes' => $order->notes ?? '-',
                            'created_at' => $order->created_at
                            ? $order->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A')
                            : 'NA',
                            'items' => $order->items
                            ->map(function ($it) {
                            return [
                            'name' => $it->item?->name ?? 'NA',
                            'qty' => (int) ($it->qty ?? 0),
                            'image' => $it->item?->image ? asset($it->item->image) : null,
                            ];
                            })
                            ->values()
                            ->all(),
                            ];
                            @endphp
                            <div class="list-group-item px-0 dash-click js-order-request-detail"
                                data-order='@json($orderPayload)' data-bs-toggle="modal"
                                data-bs-target="#orderRequestDetailModal">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold">
                                            #{{ $loop->iteration }} - {{ $order->user?->name ?? 'N/A' }}
                                        </div>

                                        <div class="text-muted small mt-1">
                                            Status: {{ ucfirst($order->status ?? 'pending') }}
                                            @if ($order->created_at)
                                            •
                                            {{ $order->created_at->timezone(config('app.timezone'))->format('h:i A') }}
                                            @endif
                                        </div>

                                        <div class="small mt-2">
                                            <span class="text-muted">Notes:</span>
                                            <span class="text-dark">
                                                {{ \Illuminate\Support\Str::words($order->notes ?? '-', 12, '...') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="badge text-white px-3 py-2" style="background:#F7721E;">
                                            {{ $order->items->sum('qty') }} Items
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- ✅ Desktop / Tablet View (Table) -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th class="text-nowrap">#</th>
                                        <th class="text-start">User</th>
                                        <th class="text-nowrap">Status</th>
                                        <th class="text-nowrap">Total Items</th>
                                        <th class="text-start d-none d-lg-table-cell">Notes</th>
                                        <th class="text-nowrap">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestOrderRequests as $order)
                                    @php
                                    $orderPayload = [
                                    'id' => $order->id,
                                    'user' => $order->user?->name ?? 'NA',
                                    'status' => ucfirst($order->status ?? 'pending'),
                                    'notes' => $order->notes ?? '-',
                                    'created_at' => $order->created_at
                                    ? $order->created_at
                                    ->timezone(config('app.timezone'))
                                    ->format('d M Y, h:i A')
                                    : 'NA',
                                    'items' => $order->items
                                    ->map(function ($it) {
                                    return [
                                    'name' => $it->item?->name ?? 'NA',
                                    'qty' => (int) ($it->qty ?? 0),
                                    'image' => $it->item?->image
                                    ? asset($it->item->image)
                                    : null,
                                    ];
                                    })
                                    ->values()
                                    ->all(),
                                    ];
                                    @endphp
                                    <tr class="text-center dash-click js-order-request-detail"
                                        data-order='@json($orderPayload)' data-bs-toggle="modal"
                                        data-bs-target="#orderRequestDetailModal">
                                        <td class="text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="text-start">{{ $order->user?->name ?? 'N/A' }}</td>
                                        <td class="text-nowrap">{{ ucfirst($order->status ?? 'pending') }}</td>
                                        <td class="text-nowrap">
                                            <span class="badge text-white" style="background:#F7721E;">
                                                {{ $order->items->sum('qty') }}
                                            </span>
                                        </td>
                                        <td class="text-start d-none d-lg-table-cell">
                                            {{ \Illuminate\Support\Str::words($order->notes ?? '-', 10, '...') }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ $order->created_at ? $order->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="text-muted">No order requests found.</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ORDER REQUESTS CHART --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>
                            @if ($isAdmin)
                            Order Requests Trend
                            @else
                            My Order Requests Trend
                            @endif
                        </h5>
                        <small>Last 6 months request counts</small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="orderRequestChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
            CURRENT MONTH POPUPS
        ========================= --}}
    {{-- Order Requests Modal --}}
    <div class="modal fade" id="orderRequestsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isAdmin)
                        All Order Requests — Total: {{ (int) ($allOrderRequests ?? collect())->count() }}
                        @else
                        My Order Requests — Total: {{ (int) ($allOrderRequests ?? collect())->count() }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if (($allOrderRequests ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No order requests found</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allOrderRequests as $i => $order)
                                @php
                                $orderDetailPayload = [
                                'id' => $order->id,
                                'user' => $order->user?->name ?? 'NA',
                                'status' => ucfirst($order->status ?? 'pending'),
                                'notes' => $order->notes ?? '-',
                                'created_at' => $order->created_at
                                ? $order->created_at
                                ->timezone(config('app.timezone'))
                                ->format('d M Y, h:i A')
                                : 'NA',
                                'items' => $order->items
                                ->map(function ($it) {
                                return [
                                'name' => $it->item?->name ?? 'NA',
                                'qty' => (int) ($it->qty ?? 0),
                                'image' => $it->item?->image
                                ? asset($it->item->image)
                                : null,
                                ];
                                })
                                ->values()
                                ->all(),
                                ];
                                @endphp
                                <tr class="js-order-request-detail" data-order='@json($orderDetailPayload)'
                                    data-bs-toggle="modal" data-bs-target="#orderRequestDetailModal"
                                    style="cursor:pointer;">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $order->user?->name ?? 'NA' }}</td>
                                    <td>{{ ucfirst($order->status ?? 'pending') }}</td>
                                    <td>{{ $order->items->sum('qty') }}</td>
                                    <td>{{ $order->created_at ? $order->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Current Month Order Requests Modal --}}
    <div class="modal fade" id="currentMonthOrderRequestsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $monthName }} Order Requests</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if (($currentMonthOrderRequests ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No order requests found this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currentMonthOrderRequests as $i => $order)
                                @php
                                $monthOrderDetailPayload = [
                                'id' => $order->id,
                                'user' => $order->user?->name ?? 'NA',
                                'status' => ucfirst($order->status ?? 'pending'),
                                'notes' => $order->notes ?? '-',
                                'created_at' => $order->created_at
                                ? $order->created_at
                                ->timezone(config('app.timezone'))
                                ->format('d M Y, h:i A')
                                : 'NA',
                                'items' => $order->items
                                ->map(function ($it) {
                                return [
                                'name' => $it->item?->name ?? 'NA',
                                'qty' => (int) ($it->qty ?? 0),
                                'image' => $it->item?->image
                                ? asset($it->item->image)
                                : null,
                                ];
                                })
                                ->values()
                                ->all(),
                                ];
                                @endphp
                                <tr class="js-order-request-detail" data-order='@json($monthOrderDetailPayload)'
                                    data-bs-toggle="modal" data-bs-target="#orderRequestDetailModal"
                                    style="cursor:pointer;">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $order->user?->name ?? 'NA' }}</td>
                                    <td>{{ ucfirst($order->status ?? 'pending') }}</td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d M Y') : 'NA' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Order Request Detail Modal --}}
    <div class="modal fade" id="orderRequestDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="ordTitle">Order Request Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">User</div>
                                <div class="fw-bold" id="ordUser">NA</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Status</div>
                                <div class="fw-bold" id="ordStatus">NA</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Created At</div>
                                <div class="fw-bold" id="ordCreated">NA</div>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded p-3 mb-3">
                        <div class="text-muted small">Notes</div>
                        <div id="ordNotes">-</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody id="ordItemsBody">
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No items found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->check() && auth()->user()->role === 'admin')
    {{-- Delivery Orders Modal --}}
    <div class="modal fade" id="deliveryOrdersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Delivery Orders — Total: {{ (int) ($deliveryOrders ?? collect())->count() }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    @if (($deliveryOrders ?? collect())->count() === 0)
                    <div class="text-center py-4">
                        <strong>No delivery orders found</strong>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Delivery Date</th>
                                    <th>Delivery Time</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($deliveryOrders as $i => $order)
                                @php
                                $userPhoto = $order->user?->profile_photo
                                ? asset($order->user->profile_photo)
                                : asset('assets/img/avatars/1.png');

                                $creatorPhoto = $order->creator?->profile_photo
                                ? asset($order->creator->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                @endphp

                                <tr>
                                    <td data-label="#">
                                        <span class="val">{{ $i + 1 }}</span>
                                    </td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $userPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $order->user?->name ?? 'NA' }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Delivery Date">
                                        <span class="val">
                                            {{ $order->delivery_date ?? (Carbon::parse($order->delivery_date)->format('d M Y') ?? 'NA') }}
                                        </span>
                                    </td>

                                    <td data-label="Delivery Time">
                                        <span class="val">
                                            {{ $order->delivery_time ?? (Carbon::parse($order->delivery_time)->timezone(config('app.timezone'))->format('h:i A') ?? 'NA') }}
                                        </span>
                                    </td>

                                    <td data-label="Created By">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $creatorPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $order->creator?->name ?? 'NA' }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Created At">
                                        <span class="val">
                                            {{ $order->created_at ? $order->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Current Month Orders Modal -->
    <div class="modal fade" id="currentMonthModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $monthName }} Orders
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Member</th>
                                <th>Created By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($currentMonthOrders as $index => $order)
                            @php
                            $userPhoto = $order->user?->profile_photo
                            ? asset($order->user->profile_photo)
                            : asset('assets/img/avatars/1.png');

                            $creatorPhoto = $order->creator?->profile_photo
                            ? asset($order->creator->profile_photo)
                            : asset('assets/img/avatars/1.png');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->id }}</td>
                                <td data-label="User">
                                    <span class="val">
                                        <span class="mob-user">
                                            <img src="{{ $userPhoto }}" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover">
                                            <span>{{ $order->user?->name ?? 'NA' }}</span>
                                        </span>
                                    </span>
                                </td>
                                <td data-label="Created By">
                                    <span class="val">
                                        <span class="mob-user">
                                            <img src="{{ $creatorPhoto }}" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover">
                                            <span>{{ $order->creator?->name ?? 'NA' }}</span>
                                        </span>
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    No orders found this month
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
    {{-- Payments Modal --}}
    <div class="modal fade" id="cmPaymentsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">Current Month Payments ({{ $monthName }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($cmPayments ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No data available in this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cmPayments as $i => $p)
                                @php
                                $d =
                                $p->date ??
                                ($p->created_at ? $p->created_at->toDateString() : null);
                                $photo = $p->user?->profile_photo
                                ? asset($p->user->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                $name = $p->user?->name ?? 'NA';
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $photo }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $name }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Amount"><span class="val">Rs
                                            {{ number_format((float) $p->amount, 0) }}</span></td>

                                    <td data-label="Date">
                                        <span
                                            class="val">{{ $d ? \Carbon\Carbon::parse($d)->format('d M Y') : 'NA' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- All Users Modal --}}
    <div class="modal fade" id="allUsersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">All Users (Total: {{ (int) ($allUsers ?? collect())->count() }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($allUsers ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No users found</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allUsers as $i => $u)
                                @php
                                $photo = $u->profile_photo
                                ? asset($u->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $photo }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $u->name ?? 'NA' }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Email"><span class="val">{{ $u->email ?? 'NA' }}</span></td>
                                    <td data-label="Role"><span class="val">{{ $u->role ?? 'user' }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Paid Users Modal --}}
    <div class="modal fade" id="paidUsersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Paid Users ({{ $monthName }}) — Total:
                        {{ (int) ($paidUsers ?? collect())->count() }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($paidUsers ?? collect())->count() === 0)
                    <div class="text-center py-4">
                        <strong>No paid users found in this month</strong>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($paidUsers as $i => $p)
                                @php
                                $u = $p->user ?? null;
                                $photo = $u?->profile_photo
                                ? asset($u->profile_photo)
                                : asset('assets/img/avatars/1.png');

                                $d =
                                $p->date ??
                                ($p->created_at ? $p->created_at->toDateString() : null);
                                $dateFormatted = $d ? \Carbon\Carbon::parse($d)->format('d M Y') : 'NA';
                                @endphp

                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $photo }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $u?->name ?? 'NA' }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Email">
                                        <span class="val">{{ $u?->email ?? 'NA' }}</span>
                                    </td>

                                    <td data-label="Payment">
                                        <span class="val">
                                            Rs {{ number_format((float) ($p->amount ?? 0), 0) }}
                                        </span>
                                    </td>

                                    <td data-label="Date">
                                        <span class="val">{{ $dateFormatted }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Unpaid Users Modal --}}
    <div class="modal fade" id="unpaidUsersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">Unpaid Users ({{ $monthName }}) — Total:
                        {{ (int) ($unpaidUsers ?? collect())->count() }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($unpaidUsers ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No unpaid users found in this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unpaidUsers as $i => $u)
                                @php
                                $photo = $u->profile_photo
                                ? asset($u->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $photo }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $u->name ?? 'NA' }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Email"><span class="val">{{ $u->email ?? 'NA' }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Expenses Modal --}}
    <div class="modal fade" id="cmExpensesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">Current Month Expenses ({{ $monthName }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($cmExpenses ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No data available in this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title / Note</th>
                                    <th>Amount</th>
                                    <th>Added By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cmExpenses as $i => $e)
                                @php
                                $creatorPhoto = $e->creator?->profile_photo
                                ? asset($e->creator->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                $creatorName = $e->creator?->name ?? 'NA';
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="Title/Note">
                                        <span class="val">{{ $e->title ?? ($e->notes ?? 'Expense') }}</span>
                                    </td>

                                    <td data-label="Amount">
                                        <span class="val">Rs
                                            {{ number_format((float) $e->amount, 0) }}</span>
                                    </td>

                                    <td data-label="Added By">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $creatorPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $creatorName }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Date">
                                        <span class="val">
                                            {{ $e->created_at ? $e->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Complaints Modal --}}
    <div class="modal fade" id="cmComplaintsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">Current Month Complaints ({{ $monthName }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($cmComplaints ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No data available in this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cmComplaints as $i => $c)
                                @php
                                $userPhoto = $c->user?->profile_photo
                                ? asset($c->user->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                $userName = $c->user?->name ?? ($c->is_anonymous ? 'Anonymous' : 'NA');
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $userPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $userName }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Subject">
                                        <span class="val">{{ $c->subject ?? ($c->message ?? 'Complaint') }}</span>
                                    </td>

                                    <td data-label="Date">
                                        <span class="val">
                                            {{ $c->created_at ? $c->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Damages Modal --}}
    <div class="modal fade" id="cmDamagesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">Current Month Damages ({{ $monthName }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($cmDamages ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No data available in this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Detail</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cmDamages as $i => $d)
                                @php
                                $damagePhoto = $d->user?->profile_photo
                                ? asset($d->user->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                $damageName = $d->user?->name ?? 'NA';
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $damagePhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $damageName }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Detail">
                                        <span
                                            class="val">{{ $d->detail ?? ($d->reason ?? ($d->notes ?? 'Damage')) }}</span>
                                    </td>

                                    <td data-label="Date">
                                        <span class="val">
                                            {{ $d->created_at ? $d->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Suggestions Modal --}}
    <div class="modal fade" id="cmSuggestionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title">Current Month Suggestions ({{ $monthName }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if (($cmSuggestions ?? collect())->count() === 0)
                    <div class="text-center py-4"><strong>No data available in this month</strong></div>
                    @else
                    <div class="table-responsive">
                        <table class="table mob-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Suggestion</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cmSuggestions as $i => $s)
                                @php
                                $suggestionPhoto = $s->user?->profile_photo
                                ? asset($s->user->profile_photo)
                                : asset('assets/img/avatars/1.png');
                                $suggestionName =
                                $s->user?->name ?? ($s->is_anonymous ? 'Anonymous' : 'NA');
                                @endphp
                                <tr>
                                    <td data-label="#"><span class="val">{{ $i + 1 }}</span></td>

                                    <td data-label="User">
                                        <span class="val">
                                            <span class="mob-user">
                                                <img src="{{ $suggestionPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover">
                                                <span>{{ $suggestionName }}</span>
                                            </span>
                                        </span>
                                    </td>

                                    <td data-label="Suggestion">
                                        <span class="val">{{ $s->subject ?? ($s->message ?? 'Suggestion') }}</span>
                                    </td>

                                    <td data-label="Date">
                                        <span class="val">
                                            {{ $s->created_at ? $s->created_at->timezone(config('app.timezone'))->format('d M Y, h:i A') : 'NA' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    <style>
    /* ===== Responsive Modals + Tables (Mobile) ===== */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 10px;
        }

        .modal-content {
            border-radius: 14px !important;
        }

        .modal-header .modal-title {
            font-size: 14px;
            font-weight: 800;
        }

        /* Table -> Mobile Card Style */
        .table-responsive {
            overflow: visible !important;
        }

        .mob-table thead {
            display: none;
        }

        .mob-table,
        .mob-table tbody,
        .mob-table tr,
        .mob-table td {
            display: block;
            width: 100%;
        }

        .mob-table tr {
            border: 1px solid #e9eef6;
            border-radius: 14px;
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .04);
        }

        .mob-table td {
            border: none !important;
            padding: 6px 0 !important;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .mob-table td::before {
            content: attr(data-label);
            font-weight: 800;
            color: #64748b;
            font-size: 12px;
            flex: 0 0 42%;
        }

        .mob-table td .val {
            text-align: right;
            font-weight: 700;
            color: #0f172a;
            flex: 1;
            word-break: break-word;
        }

        /* user row */
        .mob-user {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-end;
        }

        .mob-user img {
            width: 34px !important;
            height: 34px !important;
        }
    }
    </style>

    {{-- LOGIN ANALYTICS --}}
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>
                            @if ($isAdmin)
                            Users Login (Last 12 months)
                            @else
                            My Login Trend (Last 12 months)
                            @endif
                        </h5>
                        <small>{{ $loginChartDatasetLabel ?? '' }} per month</small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="loginMonthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Login This Month Modal --}}
    <div class="modal fade" id="loginThisMonthModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isAdmin)
                        Users Logged In — {{ $monthName }}
                        @else
                        My Logins — {{ $monthName }}
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mob-table">
                            <thead class="table-light">
                                <tr>
                                    @if ($isAdmin)
                                    <th>User</th>
                                    <th>Login Count</th>
                                    @endif
                                    <th>Date</th>
                                    <th>Time ({{ config('app.timezone') }})</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($loginThisMonthRows ?? collect()) as $row)
                                @php
                                $ip = $row->ip_address ?? '—';
                                $userPhoto = $row->user?->profile_photo
                                ? asset($row->user->profile_photo)
                                : asset('assets/img/avatars/5.png');
                                @endphp
                                <tr>
                                    @if ($isAdmin)
                                    <td data-label="User">
                                        <div class="val">
                                            <div class="mob-user">
                                                <img src="{{ $userPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover;" alt="avatar">
                                                <div class="text-end">
                                                    <div class="fw-semibold">{{ $row->user?->name ?? 'NA' }}</div>
                                                    <div class="text-muted small">{{ $row->user?->id_card ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Login Count"><span class="val">{{ (int) ($row->login_count ?? 1) }} times</span></td>
                                    @endif
                                    <td data-label="Date"><span class="val login-local-date"
                                            data-login-at="{{ optional($row->logged_in_at)->toIso8601String() }}">{{ $row->karachi_date ?? '—' }}</span>
                                    </td>
                                    <td data-label="Time"><span class="val login-local-time"
                                            data-login-at="{{ optional($row->logged_in_at)->toIso8601String() }}">{{ $row->karachi_time ?? '—' }}</span>
                                    </td>
                                    <td data-label="IP"><span class="val">{{ $ip }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 5 : 3 }}" class="text-center text-muted py-4">
                                        No records found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Login Last 6 Months Modal --}}
    <div class="modal fade" id="loginLast6Modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isAdmin)
                        Users Logged In — Last 6 Months
                        @else
                        My Logins — Last 6 Months
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mob-table">
                            <thead class="table-light">
                                <tr>
                                    @if ($isAdmin)
                                    <th>User</th>
                                    <th>Login Count</th>
                                    @endif
                                    <th>Date</th>
                                    <th>Time ({{ config('app.timezone') }})</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (($loginLast6Rows ?? collect()) as $row)
                                @php
                                $ip = $row->ip_address ?? '—';
                                $userPhoto = $row->user?->profile_photo
                                ? asset($row->user->profile_photo)
                                : asset('assets/img/avatars/5.png');
                                @endphp
                                <tr>
                                    @if ($isAdmin)
                                    <td data-label="User">
                                        <div class="val">
                                            <div class="mob-user">
                                                <img src="{{ $userPhoto }}" class="rounded-circle"
                                                    style="width:32px;height:32px;object-fit:cover;" alt="avatar">
                                                <div class="text-end">
                                                    <div class="fw-semibold">{{ $row->user?->name ?? 'NA' }}</div>
                                                    <div class="text-muted small">{{ $row->user?->id_card ?? '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Login Count"><span class="val">{{ (int) ($row->login_count ?? 1) }} times</span></td>
                                    @endif
                                    <td data-label="Date"><span class="val login-local-date"
                                            data-login-at="{{ optional($row->logged_in_at)->toIso8601String() }}">{{ $row->karachi_date ?? '—' }}</span>
                                    </td>
                                    <td data-label="Time"><span class="val login-local-time"
                                            data-login-at="{{ optional($row->logged_in_at)->toIso8601String() }}">{{ $row->karachi_time ?? '—' }}</span>
                                    </td>
                                    <td data-label="IP"><span class="val">{{ $ip }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 5 : 3 }}" class="text-center text-muted py-4">
                                        No records found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>Leads</h5>
                        <small>All leads submitted in the last 6 months</small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="leadChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>Events</h5>
                        <small>All events created in the last 6 months</small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="eventChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="cardx">
                <div class="section-title">
                    <div>
                        <h5>Gallery Uploads</h5>
                        <small>
                            @if ($isAdmin)
                            All gallery uploads in the last 6 months
                            @else
                            My gallery uploads in the last 6 months
                            @endif
                        </small>
                    </div>
                </div>
                <div class="chart-box" style="height:320px">
                    <canvas id="galleryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div> {{-- page-wrap end --}}

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    var el = document.getElementById('dashboardCurrentTime');
    if (!el) return;

    function pad(n) {
        return n < 10 ? '0' + n : String(n);
    }

    function formatLocalNow() {
        var d = new Date();
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var hh = d.getHours();
        var ampm = hh >= 12 ? 'PM' : 'AM';
        hh = hh % 12 || 12;
        return pad(d.getDate()) + ' ' + months[d.getMonth()] + ' ' + d.getFullYear() + ', ' + pad(hh) + ':' + pad(d
            .getMinutes()) + ' ' + ampm;
    }

    function renderCurrentTime() {
        el.textContent = formatLocalNow();
    }

    renderCurrentTime();
    setInterval(renderCurrentTime, 30000);
})();

(function() {
    var serverNowStr = @json(optional($now)->toIso8601String());
    if (!serverNowStr) return;

    var serverNow = new Date(serverNowStr);
    var clientNow = new Date();
    var driftMs = clientNow.getTime() - serverNow.getTime();
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    function pad(n) {
        return n < 10 ? '0' + n : String(n);
    }

    document.querySelectorAll('.login-local-time, .login-local-date').forEach(function(el) {
        var raw = el.getAttribute('data-login-at');
        if (!raw) return;
        var dt = new Date(raw);
        if (isNaN(dt.getTime())) return;

        // Align login time display with user's local/browser clock
        var fixed = new Date(dt.getTime() + driftMs);
        if (el.classList.contains('login-local-date')) {
            el.textContent = pad(fixed.getDate()) + ' ' + months[fixed.getMonth()] + ' ' + fixed
                .getFullYear();
        } else {
            var hh = fixed.getHours();
            var ampm = hh >= 12 ? 'PM' : 'AM';
            hh = hh % 12 || 12;
            el.textContent = pad(hh) + ':' + pad(fixed.getMinutes()) + ' ' + ampm;
        }
    });
})();

// ✅ Base chart data
const labels = @json($labels ?? []);
const incomeData = @json($incomeData ?? []);
const expenseData = @json($expenseData ?? []);

// ✅ Spark data
const spark1Labels = @json($spark1Labels ?? []);
const spark1Data = @json($spark1Data ?? []);

const spark2Labels = @json($spark2Labels ?? []);
const spark2Data = @json($spark2Data ?? []);

const spark3Labels = @json($spark3Labels ?? []);
const spark3Data = @json($spark3Data ?? []);

// ✅ Login chart data
const loginChartLabels = @json($loginChartLabels ?? []);
const loginChartData = @json($loginChartData ?? []);
const loginChartDatasetLabel = @json($loginChartDatasetLabel ?? 'Logins');

function buildRecentMonthLabels(count) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const now = new Date();
    const out = [];

    for (let i = count - 1; i >= 0; i--) {
        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        out.push(months[d.getMonth()]);
    }

    return out;
}

function prepareChartSeries(rawLabels, rawSeries, fallbackCount) {
    const labels = Array.isArray(rawLabels) && rawLabels.length ? rawLabels : buildRecentMonthLabels(fallbackCount || 6);

    return {
        labels,
        series: rawSeries.map(function(series) {
            const source = Array.isArray(series) ? series : [];
            return labels.map(function(_, index) {
                const value = Number(source[index] ?? 0);
                return Number.isFinite(value) ? value : 0;
            });
        })
    };
}

function datasetsHaveValues(datasets) {
    return (datasets || []).some(function(dataset) {
        return (dataset.data || []).some(function(value) {
            return Math.abs(Number(value) || 0) > 0;
        });
    });
}

const emptyStateChartPlugin = {
    id: 'emptyStateChartPlugin',
    afterDraw(chart, args, pluginOptions) {
        if (!pluginOptions || pluginOptions.enabled === false || datasetsHaveValues(chart.data.datasets)) {
            return;
        }

        const ctx = chart.ctx;
        const area = chart.chartArea;

        if (!area) {
            return;
        }

        const centerX = (area.left + area.right) / 2;
        const centerY = (area.top + area.bottom) / 2;
        const badgeY = centerY - 26;
        const isBlueCard = !!(chart.canvas && chart.canvas.closest('.blue-card'));
        const badgeFill = isBlueCard ? 'rgba(255, 255, 255, 0.14)' : 'rgba(247, 114, 30, 0.12)';
        const badgeText = isBlueCard ? '#fbbf24' : '#f7721e';
        const titleColor = isBlueCard ? '#ffffff' : '#0f172a';
        const subtitleColor = isBlueCard ? 'rgba(255, 255, 255, 0.78)' : '#64748b';

        ctx.save();

        ctx.fillStyle = badgeFill;
        ctx.beginPath();
        ctx.arc(centerX, badgeY, 22, 0, Math.PI * 2);
        ctx.fill();

        ctx.fillStyle = badgeText;
        ctx.font = '700 22px "Public Sans", sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('0', centerX, badgeY + 1);

        ctx.fillStyle = titleColor;
        ctx.font = '700 16px "Public Sans", sans-serif';
        ctx.fillText(pluginOptions.title || 'No activity yet', centerX, centerY + 18);

        ctx.fillStyle = subtitleColor;
        ctx.font = '500 12px "Public Sans", sans-serif';
        ctx.fillText(pluginOptions.subtitle || 'Data will appear here automatically.', centerX, centerY + 40);

        ctx.restore();
    }
};

Chart.register(emptyStateChartPlugin);

function emptyStateOptions(title, subtitle) {
    return {
        emptyStateChartPlugin: {
            title: title,
            subtitle: subtitle
        }
    };
}

// =========================
// Main Income/Expense Chart
// =========================
const elIncome = document.getElementById('incomeExpenseChart');
if (elIncome) {
    const incomeExpenseSeries = prepareChartSeries(labels, [incomeData, expenseData], 6);
    new Chart(elIncome, {
        type: 'bar',
        data: {
            labels: incomeExpenseSeries.labels,
            datasets: [{
                label: 'Income',
                data: incomeExpenseSeries.series[0],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 26,
                backgroundColor: 'rgba(247, 114, 30, .88)'
            }, {
                label: 'Expense',
                data: incomeExpenseSeries.series[1],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 26,
                backgroundColor: 'rgba(71, 85, 105, .75)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No payment activity yet', 'Income and expense trend will show here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Right Mini Line Chart
// =========================
const elMini = document.getElementById('salesMiniChart');
if (elMini) {
    const miniSeries = prepareChartSeries(labels, [incomeData], 6);
    new Chart(elMini, {
        type: 'line',
        data: {
            labels: miniSeries.labels,
            datasets: [{
                data: miniSeries.series[0],
                tension: .35,
                pointRadius: 0,
                borderWidth: 2,
                borderColor: 'rgba(255,255,255,.95)',
                backgroundColor: 'rgba(255,255,255,.12)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                ...emptyStateOptions('No fund trend yet', 'Monthly fund movement will appear here.')
            },
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            }
        }
    });
}

// =========================
// Login Monthly Chart (Last 12 months)
// =========================
const elLogin = document.getElementById('loginMonthlyChart');
if (elLogin) {
    const loginSeries = prepareChartSeries(loginChartLabels, [loginChartData], 12);
    new Chart(elLogin, {
        type: 'bar',
        data: {
            labels: loginSeries.labels,
            datasets: [{
                label: loginChartDatasetLabel,
                data: loginSeries.series[0],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 28,
                backgroundColor: 'rgba(247,114,30,.85)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No login activity yet', 'Monthly login graph will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Spark Options
// =========================
const sparkOpt = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        x: {
            display: false
        },
        y: {
            display: false
        }
    },
    elements: {
        line: {
            tension: .35
        },
        point: {
            radius: 0
        }
    }
};

// Spark 1
const elS1 = document.getElementById('spark1');
if (elS1) {
    const sparkSeries1 = prepareChartSeries(spark1Labels, [spark1Data], 6);
    new Chart(elS1, {
        type: 'line',
        data: {
            labels: sparkSeries1.labels,
            datasets: [{
                data: sparkSeries1.series[0],
                borderWidth: 2,
                borderColor: 'rgba(247, 114, 30, .95)',
                backgroundColor: 'rgba(247, 114, 30, .12)',
                fill: true
            }]
        },
        options: sparkOpt
    });
}

// Spark 2
const elS2 = document.getElementById('spark2');
if (elS2) {
    const sparkSeries2 = prepareChartSeries(spark2Labels, [spark2Data], 6);
    new Chart(elS2, {
        type: 'line',
        data: {
            labels: sparkSeries2.labels,
            datasets: [{
                data: sparkSeries2.series[0],
                borderWidth: 2,
                borderColor: 'rgba(37, 99, 235, .95)',
                backgroundColor: 'rgba(37, 99, 235, .1)',
                fill: true
            }]
        },
        options: sparkOpt
    });
}

// Spark 3
const elS3 = document.getElementById('spark3');
if (elS3) {
    const sparkSeries3 = prepareChartSeries(spark3Labels, [spark3Data], 6);
    new Chart(elS3, {
        type: 'line',
        data: {
            labels: sparkSeries3.labels,
            datasets: [{
                data: sparkSeries3.series[0],
                borderWidth: 2,
                borderColor: 'rgba(22, 163, 74, .95)',
                backgroundColor: 'rgba(22, 163, 74, .1)',
                fill: true
            }]
        },
        options: sparkOpt
    });
}

// =========================
// Doughnut Chart
// =========================
const ctx2 = document.getElementById('paidUnpaidChart');
if (ctx2) {
    @if($isAdmin)
    const paid = @json((int) ($paidCount ?? 0));
    const unpaid = @json((int) ($unpaidCount ?? 0));
    const paidUnpaidHasData = paid > 0 || unpaid > 0;
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Unpaid'],
            datasets: [{
                data: paidUnpaidHasData ? [paid, unpaid] : [1, 1],
                borderWidth: 2,
                cutout: '72%',
                backgroundColor: paidUnpaidHasData ? ['rgba(34, 197, 94, .88)', 'rgba(248, 113, 113, .85)'] : ['rgba(34, 197, 94, .18)', 'rgba(248, 113, 113, .18)'],
                borderColor: ['#fff', '#fff']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                ...emptyStateOptions('No payment split yet', 'Paid and unpaid members will appear here.')
            }
        }
    });
    @else
    const thisMonth = @json((float) ($userThisMonthContribution ?? 0));
    const total = @json((float) ($userTotalContribution ?? 0));
    const previous = Math.max(0, total - thisMonth);
    const contributionHasData = thisMonth > 0 || previous > 0;
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['This Month', 'Previous'],
            datasets: [{
                data: contributionHasData ? [thisMonth, previous] : [1, 1],
                borderWidth: 2,
                cutout: '72%',
                backgroundColor: contributionHasData ? ['rgba(247, 114, 30, .9)', 'rgba(148, 163, 184, .65)'] : ['rgba(247, 114, 30, .18)', 'rgba(148, 163, 184, .24)'],
                borderColor: ['#fff', '#fff']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                ...emptyStateOptions('No contribution yet', 'Your monthly contribution split will show here.')
            }
        }
    });
    @endif
}

// =========================
// Complaints/Suggestions Chart
// =========================
const complaintCounts = @json($complaintCounts ?? []);
const suggestionCounts = @json($suggestionCounts ?? []);
const elCS = document.getElementById('complaintSuggestionChart');
if (elCS) {
    const complaintSuggestionSeries = prepareChartSeries(labels, [complaintCounts, suggestionCounts], 6);
    new Chart(elCS, {
        type: 'bar',
        data: {
            labels: complaintSuggestionSeries.labels,
            datasets: [{
                label: 'Complaints',
                data: complaintSuggestionSeries.series[0],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 26,
                backgroundColor: 'rgba(220, 38, 38, .78)'
            }, {
                label: 'Suggestions',
                data: complaintSuggestionSeries.series[1],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 26,
                backgroundColor: 'rgba(37, 99, 235, .78)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No complaints or suggestions yet', 'Monthly feedback activity will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Damages Chart
// =========================
const damageCounts = @json($damageCounts ?? []);
const elDmg = document.getElementById('damageChart');
if (elDmg) {
    const damageSeries = prepareChartSeries(labels, [damageCounts], 6);
    new Chart(elDmg, {
        type: 'bar',
        data: {
            labels: damageSeries.labels,
            datasets: [{
                label: 'Damages',
                data: damageSeries.series[0],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 26,
                backgroundColor: 'rgba(234, 88, 12, .82)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No damage records yet', 'Monthly damage entries will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Order Requests Chart
// =========================
const orderRequestLabels = @json($orderRequestLabels ?? []);
const orderRequestCounts = @json($orderRequestCounts ?? []);
const elOrderReq = document.getElementById('orderRequestChart');
if (elOrderReq) {
    const orderRequestSeries = prepareChartSeries(orderRequestLabels, [orderRequestCounts], 6);
    new Chart(elOrderReq, {
        type: 'line',
        data: {
            labels: orderRequestSeries.labels,
            datasets: [{
                label: 'Order Requests',
                data: orderRequestSeries.series[0],
                borderWidth: 3,
                borderColor: 'rgba(247, 114, 30, .95)',
                backgroundColor: 'rgba(247, 114, 30, .15)',
                pointBackgroundColor: 'rgba(247, 114, 30, 1)',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: .35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No order requests yet', 'Order request trend will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Leads Chart
// =========================
const leadCounts = @json($leadCounts ?? []);
const elLead = document.getElementById('leadChart');
if (elLead) {
    const leadSeries = prepareChartSeries(labels, [leadCounts], 6);
    new Chart(elLead, {
        type: 'bar',
        data: {
            labels: leadSeries.labels,
            datasets: [{
                label: 'Leads',
                data: leadSeries.series[0],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 30,
                backgroundColor: 'rgba(59, 130, 246, .82)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No leads yet', 'Lead submissions graph will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Events Chart
// =========================
const eventCounts = @json($eventCounts ?? []);
const elEvent = document.getElementById('eventChart');
if (elEvent) {
    const eventSeries = prepareChartSeries(labels, [eventCounts], 6);
    new Chart(elEvent, {
        type: 'line',
        data: {
            labels: eventSeries.labels,
            datasets: [{
                label: 'Events',
                data: eventSeries.series[0],
                borderWidth: 3,
                borderColor: 'rgba(16, 185, 129, .95)',
                backgroundColor: 'rgba(16, 185, 129, .16)',
                pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: .35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No events yet', 'Event activity graph will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Gallery Chart
// =========================
const galleryCounts = @json($galleryCounts ?? []);
const elGallery = document.getElementById('galleryChart');
if (elGallery) {
    const gallerySeries = prepareChartSeries(labels, [galleryCounts], 6);
    new Chart(elGallery, {
        type: 'bar',
        data: {
            labels: gallerySeries.labels,
            datasets: [{
                label: 'Gallery Uploads',
                data: gallerySeries.series[0],
                borderWidth: 0,
                borderRadius: 10,
                maxBarThickness: 30,
                backgroundColor: 'rgba(99, 102, 241, .82)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                ...emptyStateOptions('No gallery uploads yet', 'Upload activity will appear here.')
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(148,163,184,.25)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// =========================
// Order Request Detail Modal
// =========================
document.addEventListener('click', function(e) {
    const row = e.target.closest('.js-order-request-detail');
    if (!row) return;
    const data = row.dataset.order ? JSON.parse(row.dataset.order) : null;
    if (!data) return;

    document.getElementById('ordTitle').textContent = `Order Request #${data.id}`;
    document.getElementById('ordUser').textContent = data.user || 'NA';
    document.getElementById('ordStatus').textContent = data.status || 'NA';
    document.getElementById('ordCreated').textContent = data.created_at || 'NA';
    document.getElementById('ordNotes').textContent = data.notes || '-';

    const tbody = document.getElementById('ordItemsBody');
    const items = data.items || [];
    if (items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No items found.</td></tr>';
        return;
    }

    tbody.innerHTML = items.map((it, i) => {
        const img = it.image ?
            `<img src="${it.image}" style="width:60px;height:45px;object-fit:cover;border-radius:8px;">` :
            'NA';
        return `<tr><td>${i + 1}</td><td>${img}</td><td>${it.name ?? 'NA'}</td><td>${it.qty ?? 0}</td></tr>`;
    }).join('');
});
</script>
@endsection

