@extends('website.home')

@section('title')
    <title>{{ __('monthly_report.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $isUrdu = $locale === 'ur';
        $dir = $isUrdu ? 'rtl' : 'ltr';

        $months = __('monthly_report.months');

        $copy = [
            'eyebrow' => __('monthly_report.eyebrow'),
            'title' => __('monthly_report.title'),
            'intro' => __('monthly_report.intro'),
            'snapshot' => __('monthly_report.snapshot'),
            'snapshot_body' => __('monthly_report.snapshot_body'),
            'month' => __('monthly_report.month'),
            'year' => __('monthly_report.year'),
            'apply' => __('monthly_report.apply'),
            'progress' => __('monthly_report.progress'),
            'trend' => __('monthly_report.trend'),
            'trend_sub' => __('monthly_report.trend_sub'),
            'health' => __('monthly_report.health'),
            'health_sub' => __('monthly_report.health_sub'),
            'highlights' => __('monthly_report.highlights'),
            'service_desk' => __('monthly_report.service_desk'),
            'operations' => __('monthly_report.operations'),
            'recent_payments' => __('monthly_report.recent_payments'),
            'recent_orders' => __('monthly_report.recent_orders'),
            'support_desk' => __('monthly_report.support_desk'),
            'no_payments' => __('monthly_report.no_payments'),
            'no_orders' => __('monthly_report.no_orders'),
            'no_support' => __('monthly_report.no_support'),
            'member' => __('monthly_report.member'),
        ];

        $summary = $payload['summary'] ?? [];
        $trend = $payload['trend'] ?? [];
        $highlights = $payload['highlights'] ?? [];
        $monthLabel = $months[$month] ?? $month;
        $currentMonthNumber = now()->month;
        $currentMonthLabel = ($months[$currentMonthNumber] ?? $currentMonthNumber) . ' ' . now()->year;
        $heroMetrics = [
            [
                'label' => __('monthly_report.current_month'),
                'value' => $currentMonthLabel,
            ],
            [
                'label' => __('monthly_report.selected_period'),
                'value' => $monthLabel . ' ' . $year,
            ],
            [
                'label' => __('monthly_report.report_progress'),
                'value' => (int) ($summary['progress'] ?? 0) . '%',
            ],
            [
                'label' => __('monthly_report.support_cases'),
                'value' => (int) ($summary['support_count'] ?? 0),
            ],
        ];

        $stats = [
            ['icon' => 'fa-solid fa-sack-dollar', 'label' => __('monthly_report.collected_this_month'), 'value' => 'Rs ' . number_format((float) ($summary['collected_monthly'] ?? 0), 0)],
            ['icon' => 'fa-solid fa-hand-holding-heart', 'label' => __('monthly_report.support_requests'), 'value' => (int) ($summary['support_count'] ?? 0)],
            ['icon' => 'fa-solid fa-chart-pie', 'label' => __('monthly_report.welfare_net'), 'value' => 'Rs ' . number_format((float) ($summary['welfare_net'] ?? 0), 0)],
            ['icon' => 'fa-solid fa-cart-shopping', 'label' => __('monthly_report.orders_deliveries'), 'value' => (int) ($summary['orders_count'] ?? 0) . ' / ' . (int) ($summary['deliveries_count'] ?? 0)],
            ['icon' => 'fa-solid fa-circle-exclamation', 'label' => __('monthly_report.complaints'), 'value' => (int) ($summary['complaints_count'] ?? 0)],
            ['icon' => 'fa-solid fa-users', 'label' => __('monthly_report.members_leads'), 'value' => (int) ($summary['total_members'] ?? 0) . ' / ' . (int) ($summary['leads_count'] ?? 0)],
        ];

        $healthBars = [
            ['label' => __('monthly_report.expected_monthly'), 'value' => (float) ($summary['expected_monthly'] ?? 0), 'color' => '#fcb714'],
            ['label' => __('monthly_report.collected'), 'value' => (float) ($summary['collected_monthly'] ?? 0), 'color' => '#f7721e'],
            ['label' => __('monthly_report.welfare_income'), 'value' => (float) ($summary['welfare_income'] ?? 0), 'color' => '#0ea5e9'],
            ['label' => __('monthly_report.welfare_expense'), 'value' => (float) ($summary['welfare_expense'] ?? 0), 'color' => '#ef4444'],
        ];

        $highlightColumns = [
            [
                'title' => $copy['highlights'],
                'items' => [
                    ['title' => (int) ($summary['events_count'] ?? 0) . ' ' . __('monthly_report.events'), 'sub' => __('monthly_report.events_sub')],
                    ['title' => (int) ($summary['gallery_count'] ?? 0) . ' ' . __('monthly_report.gallery_uploads'), 'sub' => __('monthly_report.gallery_sub')],
                    ['title' => (int) ($summary['damages_count'] ?? 0) . ' ' . __('monthly_report.damage_entries'), 'sub' => __('monthly_report.damage_sub')],
                ],
            ],
            [
                'title' => $copy['service_desk'],
                'items' => [
                    ['title' => (int) ($summary['support_count'] ?? 0) . ' ' . __('monthly_report.support_cases'), 'sub' => __('monthly_report.support_cases_sub')],
                    ['title' => (int) ($summary['complaints_count'] ?? 0) . ' ' . __('monthly_report.complaints'), 'sub' => __('monthly_report.complaints_sub')],
                    ['title' => (int) ($summary['leads_count'] ?? 0) . ' ' . __('monthly_report.membership_leads'), 'sub' => __('monthly_report.membership_leads_sub')],
                ],
            ],
            [
                'title' => $copy['operations'],
                'items' => [
                    ['title' => (int) ($summary['orders_count'] ?? 0) . ' ' . __('monthly_report.orders'), 'sub' => __('monthly_report.orders_sub')],
                    ['title' => (int) ($summary['deliveries_count'] ?? 0) . ' ' . __('monthly_report.deliveries'), 'sub' => __('monthly_report.deliveries_sub')],
                    ['title' => (int) ($summary['total_members'] ?? 0) . ' ' . __('monthly_report.active_members'), 'sub' => __('monthly_report.active_members_sub')],
                ],
            ],
        ];
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero report-hero text-center text-lg-start">
                <div class="report-hero__grid">
                    <div class="report-hero__content">
                        <span class="site-page-hero__eyebrow">{{ $copy['eyebrow'] }}</span>
                        <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['title'] }}</h1>
                        <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['intro'] }}</p>

                        <div class="report-hero__metrics {{ $isUrdu ? 'report-hero__metrics--rtl' : '' }}">
                            @foreach ($heroMetrics as $metric)
                                <div class="report-hero__metric">
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ $metric['label'] }}</span>
                                    <strong>{{ $metric['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="report-hero__aside">
                        <div class="report-hero__summary-card">
                            <div class="report-hero__summary-head">
                                <div>
                                    <span class="report-hero__summary-label">{{ $copy['snapshot'] }}</span>
                                    <h2 class="report-hero__summary-title">{{ $monthLabel }} {{ $year }}</h2>
                                </div>
                                <div class="report-hero__summary-icon">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                            </div>

                            <div class="report-hero__summary-list">
                                <div class="report-hero__summary-row">
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('monthly_report.collected_amount') }}</span>
                                    <strong>Rs {{ number_format((float) ($summary['collected_monthly'] ?? 0), 0) }}</strong>
                                </div>
                                <div class="report-hero__summary-row">
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('monthly_report.welfare_net') }}</span>
                                    <strong>Rs {{ number_format((float) ($summary['welfare_net'] ?? 0), 0) }}</strong>
                                </div>
                                <div class="report-hero__summary-row">
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('monthly_report.active_members') }}</span>
                                    <strong>{{ (int) ($summary['total_members'] ?? 0) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="site-panel soft-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <div class="d-flex flex-wrap gap-2 mb-3 {{ $isUrdu ? 'justify-content-lg-end' : '' }}">
                                <span class="site-chip">{{ $monthLabel }} {{ $year }}</span>
                                <span class="site-chip">{{ $copy['progress'] }} {{ (int) ($summary['progress'] ?? 0) }}%</span>
                            </div>
                            <h2 class="site-section-title mb-2" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['snapshot'] }}</h2>
                            <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['snapshot_body'] }}</p>
                        </div>
                        <div class="col-lg-5">
                            <form method="GET" action="{{ route('website.monthly-report') }}" class="site-content-card report-filter-card">
                                <div class="site-content-card__body report-filter-card__body">
                                    <input type="hidden" name="filter" value="1">
                                    <div class="report-filter-card__header">
                                        <span class="report-filter-card__eyebrow">{{ $copy['snapshot'] }}</span>
                                        <h3 class="report-filter-card__title">{{ $monthLabel }} {{ $year }}</h3>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold report-filter-card__label">{{ $copy['month'] }}</label>
                                            <select name="month" class="form-select report-filter-card__select" lang="{{ $locale }}" dir="{{ $dir }}">
                                                @foreach ($months as $num => $labels)
                                                    <option value="{{ $num }}" @selected((int) $month === (int) $num)>{{ $labels }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold report-filter-card__label">{{ $copy['year'] }}</label>
                                            <select name="year" class="form-select report-filter-card__select" lang="{{ $locale }}" dir="{{ $dir }}">
                                                @foreach ($yearOptions as $option)
                                                    <option value="{{ $option }}" @selected((int) $year === (int) $option)>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 d-flex flex-wrap gap-2 {{ $isUrdu ? 'justify-content-end' : '' }}">
                                            <button class="btn-main border-0 report-filter-card__button">{{ $copy['apply'] }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-4">
                <div class="row g-4 grid-stretch">
                    @foreach ($stats as $stat)
                        <div class="col-md-6 col-xl-4">
                            <div class="site-content-card report-stat-card">
                                <div class="site-content-card__body">
                                    <div class="report-stat-top">
                                        <div class="report-stat-icon"><i class="{{ $stat['icon'] }}"></i></div>
                                        <div class="report-stat-value">{{ $stat['value'] }}</div>
                                    </div>
                                    <h3 class="report-stat-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ $stat['label'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="site-content-card h-100">
                                <div class="site-content-card__body">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                        <div>
                                            <h3 class="site-section-title mb-1" style="font-size:1.55rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['trend'] }}</h3>
                                            <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['trend_sub'] }}</p>
                                        </div>
                                    </div>
                                    <div style="height:320px;">
                                        <canvas id="publicMonthlyTrendChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="site-content-card h-100">
                                <div class="site-content-card__body">
                                    <h3 class="site-section-title mb-2" style="font-size:1.4rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['health'] }}</h3>
                                    <p class="site-section-copy mb-4" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['health_sub'] }}</p>

                                    @foreach ($healthBars as $bar)
                                        @php
                                            $base = max(1, (float) ($summary['expected_monthly'] ?? 1), (float) ($summary['welfare_income'] ?? 1), (float) ($summary['welfare_expense'] ?? 1));
                                            $pct = min(100, (int) round(($bar['value'] / $base) * 100));
                                        @endphp
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between small fw-semibold mb-1">
                                                <span lang="{{ $locale }}" dir="{{ $dir }}">{{ $bar['label'] }}</span>
                                                <span>Rs {{ number_format($bar['value'], 0) }}</span>
                                            </div>
                                            <div class="progress" style="height:10px;border-radius:999px;">
                                                <div class="progress-bar" style="width:{{ $pct }}%; background:{{ $bar['color'] }};"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4">
                        @foreach ($highlightColumns as $column)
                            <div class="col-lg-4">
                                <div class="site-content-card h-100">
                                    <div class="site-content-card__body">
                                        <h3 class="site-section-title mb-2" style="font-size:1.35rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ $column['title'] }}</h3>
                                        <div class="report-mini-list">
                                            @foreach ($column['items'] as $item)
                                                <div class="report-mini-item">
                                                    <strong lang="{{ $locale }}" dir="{{ $dir }}">{{ $item['title'] }}</strong>
                                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ $item['sub'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="site-panel">
                <div class="site-panel-body">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="site-content-card h-100">
                                <div class="site-content-card__body">
                                    <h3 class="site-section-title mb-3" style="font-size:1.25rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['recent_payments'] }}</h3>
                                    <div class="report-mini-list">
                                        @forelse (($highlights['payments'] ?? collect()) as $row)
                                            <div class="report-mini-item">
                                                <strong>{{ $row->user?->name ?? $copy['member'] }} - Rs {{ number_format((float) $row->amount, 0) }}</strong>
                                                <span>{{ $row->date }} | {{ $row->month ?? $copy['month'] }}</span>
                                            </div>
                                        @empty
                                            <div class="report-mini-item"><span lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['no_payments'] }}</span></div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="site-content-card h-100">
                                <div class="site-content-card__body">
                                    <h3 class="site-section-title mb-3" style="font-size:1.25rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['recent_orders'] }}</h3>
                                    <div class="report-mini-list">
                                        @forelse (($highlights['orders'] ?? collect()) as $row)
                                            <div class="report-mini-item">
                                                <strong>#{{ $row->id }} - {{ $row->user?->name ?? $copy['member'] }}</strong>
                                                <span>{{ ucfirst((string) $row->status) }} | {{ $row->created_at?->format('d M Y') }}</span>
                                            </div>
                                        @empty
                                            <div class="report-mini-item"><span lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['no_orders'] }}</span></div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="site-content-card h-100">
                                <div class="site-content-card__body">
                                    <h3 class="site-section-title mb-3" style="font-size:1.25rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['support_desk'] }}</h3>
                                    <div class="report-mini-list">
                                        @forelse (($highlights['support'] ?? collect()) as $row)
                                            <div class="report-mini-item">
                                                <strong>{{ $row->title }}</strong>
                                                <span>{{ ucfirst((string) $row->priority) }} / {{ ucfirst(str_replace('_', ' ', (string) $row->status)) }} | {{ $row->created_at?->format('d M Y') }}</span>
                                            </div>
                                        @empty
                                            <div class="report-mini-item"><span lang="{{ $locale }}" dir="{{ $dir }}">{{ $copy['no_support'] }}</span></div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <style>
        .report-hero {
            text-align: left;
            padding: 34px 34px 30px;
        }
        .report-hero__grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(300px, 0.85fr);
            gap: 24px;
            align-items: stretch;
        }
        .report-hero__content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }
        .report-hero__metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 26px;
        }
        .report-hero__metrics--rtl {
            direction: rtl;
        }
        .report-hero__metric {
            padding: 16px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
        }
        .report-hero__metric span {
            display: block;
            color: rgba(255, 255, 255, 0.72);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-bottom: 8px;
        }
        .report-hero__metric strong {
            display: block;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 800;
            line-height: 1.35;
        }
        .report-hero__aside {
            display: flex;
            align-items: stretch;
        }
        .report-hero__summary-card {
            width: 100%;
            padding: 22px;
            border-radius: 28px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 24px 50px rgba(6, 19, 34, 0.18);
            backdrop-filter: blur(12px);
        }
        .report-hero__summary-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }
        .report-hero__summary-label {
            display: inline-block;
            color: rgba(255, 255, 255, 0.72);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 8px;
        }
        .report-hero__summary-title {
            color: #fff;
            font-size: 1.55rem;
            font-weight: 900;
            margin: 0;
            line-height: 1.2;
        }
        .report-hero__summary-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fcb714, #f7721e);
            color: #10243e;
            font-size: 22px;
            box-shadow: 0 18px 34px rgba(252, 183, 20, 0.3);
            flex-shrink: 0;
        }
        .report-hero__summary-list {
            display: grid;
            gap: 12px;
        }
        .report-hero__summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .report-hero__summary-row span {
            color: rgba(255, 255, 255, 0.76);
            font-size: 13px;
            font-weight: 700;
        }
        .report-hero__summary-row strong {
            color: #fff;
            font-size: 1rem;
            font-weight: 900;
            text-align: right;
        }
        .report-stat-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, #ffffff 0%, #fffaf4 100%);
        }
        .report-filter-card {
            background: linear-gradient(180deg, #ffffff 0%, #fffaf6 100%);
            border: 1px solid rgba(16, 36, 62, 0.08);
            box-shadow: 0 24px 46px rgba(16, 36, 62, 0.08);
        }
        .report-filter-card__body {
            padding: 24px;
        }
        .report-filter-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(16, 36, 62, 0.08);
        }
        .report-filter-card__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(252, 183, 20, 0.12);
            color: #d66a13;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .report-filter-card__title {
            margin: 0;
            color: #10243e;
            font-size: 1.2rem;
            font-weight: 900;
            line-height: 1.2;
            text-align: right;
        }
        .report-filter-card__label {
            font-size: 13px;
            color: #5f7187;
            margin-bottom: 8px;
        }
        .report-filter-card__select {
            min-height: 56px;
            padding: 12px 18px;
            border-radius: 16px;
            border: 1px solid rgba(16, 36, 62, 0.12);
            background-color: #fff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
            color: #10243e;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.4;
        }
        .report-filter-card__select:focus {
            border-color: rgba(27, 117, 187, 0.45);
            box-shadow: 0 0 0 4px rgba(27, 117, 187, 0.12);
        }
        .report-filter-card__select[lang="ur"] {
            font-family: var(--font-ur), serif;
            font-size: 1.35rem;
            line-height: 1.8;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .report-filter-card__button {
            min-height: 48px;
            padding: 0 24px;
            border-radius: 14px;
            font-weight: 800;
            box-shadow: 0 14px 24px rgba(247, 114, 30, 0.24);
        }
        .report-stat-card::before {
            content: "";
            position: absolute;
            right: -28px;
            top: -28px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(252, 183, 20, 0.18), transparent 70%);
        }
        .report-stat-top {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }
        .report-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(252, 183, 20, 0.18), rgba(247, 114, 30, 0.14));
            color: #f7721e;
            font-size: 24px;
            border: 1px solid rgba(247, 114, 30, 0.18);
        }
        .report-stat-value {
            font-size: 1.5rem;
            font-weight: 900;
            color: #10243e;
            text-align: right;
        }
        .report-stat-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #10243e;
            margin-bottom: 6px;
        }
        .report-mini-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .report-mini-item {
            padding: 14px 16px;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid rgba(16, 36, 62, 0.08);
            box-shadow: 0 14px 30px rgba(16, 36, 62, 0.06);
        }
        .report-mini-item strong {
            display: block;
            color: #10243e;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .report-mini-item span {
            display: block;
            color: #5f7187;
            font-size: 13px;
            line-height: 1.7;
        }
        @media (max-width: 991.98px) {
            .report-hero {
                text-align: center;
                padding: 28px 22px 24px;
            }
            .report-hero__grid {
                grid-template-columns: 1fr;
            }
            .report-hero__metrics {
                grid-template-columns: 1fr;
            }
            .report-hero__summary-head,
            .report-hero__summary-row {
                text-align: left;
            }
        }
        @media (max-width: 575.98px) {
            .report-hero {
                padding: 24px 18px 20px;
                border-radius: 26px;
            }
            .report-hero__metric,
            .report-hero__summary-card,
            .report-hero__summary-row {
                border-radius: 18px;
            }
            .report-hero__summary-head {
                flex-direction: column;
                align-items: flex-start;
            }
            .report-hero__summary-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .report-hero__summary-row strong {
                text-align: left;
            }
            .report-filter-card__body {
                padding: 18px;
            }
            .report-filter-card__header {
                flex-direction: column;
                align-items: flex-start;
            }
            .report-filter-card__title {
                text-align: left;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const publicPayload = @json($payload);
        const reportLocale = @json($locale);
        const trend = publicPayload.trend || { labels: [], payments: [], orders: [], support: [] };
        const ctx = document.getElementById('publicMonthlyTrendChart');
        if (ctx) {
            const g = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
            g.addColorStop(0, 'rgba(247,114,30,0.28)');
            g.addColorStop(1, 'rgba(247,114,30,0.02)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trend.labels || [],
                    datasets: [{
                        label: @json(__('monthly_report.recent_payments')),
                        data: trend.payments || [],
                        borderColor: '#f7721e',
                        backgroundColor: g,
                        fill: true,
                        tension: .35,
                        yAxisID: 'y'
                    }, {
                        label: @json(__('monthly_report.orders')),
                        data: trend.orders || [],
                        borderColor: '#1b75bb',
                        backgroundColor: 'rgba(27,117,187,.08)',
                        tension: .35,
                        yAxisID: 'y1'
                    }, {
                        label: @json(__('monthly_report.support_requests')),
                        data: trend.support || [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,.08)',
                        tension: .35,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: { beginAtZero: true, position: 'left' },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
                    }
                }
            });
        }
    </script>
@endsection
