@extends('website.home')

@section('title')
    <title>{{ __('about.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
        $missions = [
            ['icon' => 'fa-solid fa-earth-asia', 't' => 'about.mission_1_title', 'p' => 'about.mission_1_text'],
            ['icon' => 'fa-solid fa-umbrella', 't' => 'about.mission_2_title', 'p' => 'about.mission_2_text'],
            ['icon' => 'fa-solid fa-baby', 't' => 'about.mission_3_title', 'p' => 'about.mission_3_text'],
            ['icon' => 'fa-solid fa-sun', 't' => 'about.mission_4_title', 'p' => 'about.mission_4_text'],
            ['icon' => 'fa-solid fa-leaf', 't' => 'about.mission_5_title', 'p' => 'about.mission_5_text'],
            ['icon' => 'fa-solid fa-share-nodes', 't' => 'about.mission_6_title', 'p' => 'about.mission_6_text'],
        ];
    @endphp

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .about-summary-card h3,
        .about-summary-card p {
            color: #111827 !important;
        }

        .about-progress-wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        .about-progress-bar {
            height: 14px;
            background: rgba(255, 255, 255, .18);
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, .18);
        }

        .about-progress-fill {
            height: 100%;
            width: 0;
            max-width: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 45%, #f97316 100%);
            box-shadow: 0 8px 18px rgba(249, 115, 22, .35);
        }

        .about-progress-label {
            color: #fff;
            font-weight: 600;
        }
    </style>

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow">FBWS Story</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.breadcrumb_title') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.about_para_1') }}</p>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-5">
                            <div class="site-content-card">
                                <div class="site-content-card__media media-fit">
                                    <img src="{{ asset('website/images/6.png') }}" alt="FBWS Logo">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <span class="site-chip mb-3">{{ __('about.about_tagline') }}</span>
                            <h2 class="site-section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.about_heading') }}</h2>
                            <p class="site-section-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.about_para_1') }}</p>
                            <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.about_para_2') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="site-panel soft-panel mb-4">
                <div class="site-panel-body">
                    <div class="text-center mb-4">
                        <span class="site-chip">{{ __('about.mission_section_title') }}</span>
                        <h2 class="site-section-title mt-3" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.mission_section_title') }}</h2>
                    </div>
                    <div class="row g-4 site-grid-stretch">
                        @foreach ($missions as $mission)
                            <div class="col-md-6 col-lg-4">
                                <div class="site-content-card">
                                    <div class="site-content-card__body text-center">
                                        <div class="mb-3" style="font-size: 2rem; color: #1b75bb;"><i class="{{ $mission['icon'] }}"></i></div>
                                        <h4 class="site-section-title mb-2" style="font-size: 1.2rem;" lang="{{ $locale }}" dir="{{ $dir }}">{{ __($mission['t']) }}</h4>
                                        <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __($mission['p']) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="site-page-hero">
                <div class="text-center">
                    <span class="site-page-hero__eyebrow">{{ __('about.monthly_summary') }}</span>
                    <h2 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('about.monthly_summary') }}</h2>
                </div>
                <div class="row g-4 mt-2 site-grid-stretch">
                    <div class="col-md-6 col-lg-3"><div class="site-content-card about-summary-card"><div class="site-content-card__body text-center"><h3>{{ $totalUsers }}</h3><p class="mb-0">{{ __('about.total_users') }}</p></div></div></div>
                    <div class="col-md-6 col-lg-3"><div class="site-content-card about-summary-card"><div class="site-content-card__body text-center"><h3>Rs {{ number_format($perUserMonthly) }}</h3><p class="mb-0">{{ __('about.per_user_monthly') }}</p></div></div></div>
                    <div class="col-md-6 col-lg-3"><div class="site-content-card about-summary-card"><div class="site-content-card__body text-center"><h3>Rs {{ number_format($expectedMonthly) }}</h3><p class="mb-0">{{ __('about.expected_monthly') }}</p></div></div></div>
                    <div class="col-md-6 col-lg-3"><div class="site-content-card about-summary-card"><div class="site-content-card__body text-center"><h3>Rs {{ number_format($monthlyCollected) }}</h3><p class="mb-0">{{ __('about.collected_this_month') }}</p></div></div></div>
                </div>
                <div class="about-progress-wrap mt-4">
                    <div class="about-progress-bar">
                        <div class="about-progress-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                    <p class="about-progress-label text-center mt-3 mb-0">{{ __('about.collection_progress') }}: <strong>{{ $progress }}%</strong></p>
                </div>
            </section>
        </div>
    </div>
@endsection
