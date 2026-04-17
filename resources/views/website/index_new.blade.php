@extends('website.home')

@section('title')
    <title>{{ __('home.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <style>
        .landing-home {
            --fbws-primary: #1b75bb;
            --fbws-primary-dark: #12527f;
            --fbws-accent: #f5b51f;
            --fbws-ink: #10243e;
            --fbws-muted: #5f7187;
            --fbws-surface: #ffffff;
            --fbws-border: rgba(16, 36, 62, 0.08);
            --fbws-shadow: 0 24px 60px rgba(16, 36, 62, 0.12);
            --fbws-radius: 28px;
            color: var(--fbws-ink);
            background:
                radial-gradient(circle at top left, rgba(27, 117, 187, 0.14), transparent 30%),
                radial-gradient(circle at top right, rgba(245, 181, 31, 0.18), transparent 24%),
                linear-gradient(180deg, #f8fbff 0%, #ffffff 38%, #f8fbff 100%);
        }

        .landing-home .section-shell {
            padding: 88px 0;
            position: relative;
        }

        .landing-home .section-shell.section-soft {
            background: linear-gradient(180deg, rgba(27, 117, 187, 0.05), rgba(255, 255, 255, 0.94));
        }

        .landing-home .section-shell.section-contrast {
            background: linear-gradient(135deg, #0f2238, #16375b 65%, #1d5c8d);
            color: #fff;
        }

        .landing-home .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-radius: 999px;
            background: rgba(27, 117, 187, 0.1);
            color: var(--fbws-primary-dark);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .landing-home .eyebrow::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--fbws-accent);
            box-shadow: 0 0 0 6px rgba(245, 181, 31, 0.18);
        }

        .landing-home .section-title {
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1.18;
            font-weight: 800;
            margin: 18px 0 14px;
            color: inherit;
        }

        .landing-home .section-title[lang="ur"] {
            line-height: 1.7;
        }

        .landing-home .section-copy {
            max-width: 720px;
            margin: 0 auto;
            font-size: 1.05rem;
            line-height: 1.9;
            color: var(--fbws-muted);
            white-space: pre-line;
        }

        .landing-home .section-copy[lang="ur"] {
            line-height: 2.15;
        }

        .landing-home .hero-shell {
            padding: 56px 0 96px;
        }

        .landing-home .hero-card {
            position: relative;
            overflow: hidden;
            border-radius: 36px;
            padding: 34px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.96), rgba(244, 249, 255, 0.92)),
                url('{{ asset('website/images/banner1.jpeg') }}') center/cover no-repeat;
            border: 1px solid rgba(255, 255, 255, 0.75);
            box-shadow: var(--fbws-shadow);
        }

        .landing-home .hero-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(120deg, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.9) 45%, rgba(16, 36, 62, 0.08) 100%);
            pointer-events: none;
        }

        .landing-home .hero-content,
        .landing-home .hero-panel {
            position: relative;
            z-index: 1;
        }

        .landing-home .hero-title {
            font-size: clamp(2.5rem, 5vw, 4.7rem);
            line-height: 1.05;
            font-weight: 900;
            color: var(--fbws-ink);
            margin: 18px 0 18px;
        }

        .landing-home .hero-title[lang="ur"] {
            line-height: 1.55;
        }

        .landing-home .hero-copy {
            font-size: 1.12rem;
            line-height: 1.95;
            color: #35506e;
            max-width: 640px;
            white-space: pre-line;
            margin-bottom: 30px;
        }

        .landing-home .hero-copy[lang="ur"] {
            line-height: 2.2;
        }

        .landing-home .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .landing-home .hero-stat-grid,
        .landing-home .grid-stretch {
            display: flex;
            flex-wrap: wrap;
        }

        .landing-home .hero-stat-grid > div,
        .landing-home .grid-stretch > div {
            display: flex;
        }

        .landing-home .hero-mini-card {
            width: 100%;
            border-radius: 24px;
            padding: 24px;
            background: rgba(10, 29, 49, 0.84);
            color: #fff;
            backdrop-filter: blur(8px);
            box-shadow: 0 20px 45px rgba(9, 26, 46, 0.28);
            height: 100%;
        }

        .landing-home .hero-mini-card strong {
            display: block;
            font-size: clamp(1.8rem, 3vw, 2.8rem);
            line-height: 1;
            margin-bottom: 10px;
            color: #fff;
        }

        .landing-home .hero-mini-card span {
            display: block;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .landing-home .hero-progress-card {
            width: 100%;
            border-radius: 28px;
            padding: 28px;
            background: linear-gradient(135deg, rgba(27, 117, 187, 0.16), rgba(245, 181, 31, 0.22));
            border: 1px solid rgba(27, 117, 187, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .landing-home .hero-progress-card .progress-track,
        .landing-home .summary-track {
            height: 14px;
            background: rgba(16, 36, 62, 0.08);
            border-radius: 999px;
            overflow: hidden;
        }

        .landing-home .hero-progress-card .progress-fill,
        .landing-home .summary-fill {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--fbws-primary), var(--fbws-accent));
        }

        .landing-home .btn-main,
        .landing-home .btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 54px;
            padding: 0 24px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .landing-home .btn-main {
            background: linear-gradient(135deg, var(--fbws-primary), #2c91dc);
            color: #fff;
            box-shadow: 0 16px 34px rgba(27, 117, 187, 0.28);
        }

        .landing-home .btn-ghost {
            color: var(--fbws-primary-dark);
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(27, 117, 187, 0.15);
        }

        .landing-home .btn-main:hover,
        .landing-home .btn-ghost:hover {
            transform: translateY(-2px);
        }

        .landing-home .btn-main:hover {
            color: #fff;
        }

        .landing-home .surface-card {
            width: 100%;
            background: var(--fbws-surface);
            border: 1px solid var(--fbws-border);
            border-radius: var(--fbws-radius);
            box-shadow: var(--fbws-shadow);
            overflow: hidden;
            height: 100%;
        }

        .landing-home .media-card {
            display: flex;
            flex-direction: column;
        }

        .landing-home .media-frame {
            position: relative;
            background: linear-gradient(180deg, #edf5ff, #f8fbff);
            min-height: 248px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .landing-home .media-frame img,
        .landing-home .media-frame video {
            width: 100%;
            height: 248px;
            object-fit: cover;
            display: block;
        }

        .landing-home .media-frame.media-fit img,
        .landing-home .media-frame.media-fit video {
            object-fit: contain;
            padding: 18px;
        }

        .landing-home .card-stack {
            padding: 24px 24px 26px;
            text-align: center;
        }

        .landing-home .card-title {
            margin: 0 0 10px;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--fbws-ink);
        }

        .landing-home .card-title[lang="ur"] {
            line-height: 1.95;
        }

        .landing-home .card-meta,
        .landing-home .card-copy {
            margin: 0;
            color: var(--fbws-muted);
            line-height: 1.85;
        }

        .landing-home .leader-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .02em;
            border: 1px solid transparent;
        }

        .landing-home .leader-badge.president {
            background: rgba(34, 197, 94, .14);
            color: #166534;
            border-color: rgba(34, 197, 94, .26);
        }

        .landing-home .leader-badge.general-secretary {
            background: rgba(59, 130, 246, .14);
            color: #1d4ed8;
            border-color: rgba(59, 130, 246, .24);
        }

        .landing-home .leader-badge.finance-secretary {
            background: rgba(245, 158, 11, .18);
            color: #b45309;
            border-color: rgba(245, 158, 11, .3);
        }

        .landing-home .leader-badge.legal-advisor {
            background: rgba(139, 92, 246, .14);
            color: #6d28d9;
            border-color: rgba(139, 92, 246, .24);
        }

        .landing-home .card-copy[lang="ur"],
        .landing-home .card-meta[lang="ur"] {
            line-height: 2.05;
        }

        .landing-home .text-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .landing-home .section-head {
            text-align: center;
            margin-bottom: 42px;
        }

        .landing-home .section-head.section-head-left {
            text-align: left;
        }

        .landing-home .donation-card {
            text-align: left;
            padding: 28px 24px;
            position: relative;
        }

        .landing-home .donation-card::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--fbws-primary), var(--fbws-accent));
        }

        .landing-home .donation-logo {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            background: #fff;
            border: 1px solid rgba(16, 36, 62, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 24px rgba(16, 36, 62, 0.08);
        }

        .landing-home .donation-logo img {
            max-width: 44px;
            max-height: 44px;
            object-fit: contain;
        }

        .landing-home .donation-label {
            display: block;
            margin-bottom: 4px;
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--fbws-primary);
        }

        .landing-home .summary-grid .summary-card {
            width: 100%;
            border-radius: 24px;
            padding: 26px 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(6px);
            text-align: center;
            height: 100%;
        }

        .landing-home .summary-grid .summary-card h3 {
            margin: 0 0 10px;
            color: #fff;
            font-size: clamp(1.7rem, 3vw, 2.6rem);
        }

        .landing-home .summary-grid .summary-card p {
            margin: 0;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.8;
        }

        .landing-home .summary-progress-wrap {
            max-width: 760px;
            margin: 20px auto 0;
        }

        .landing-home .summary-label {
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.82);
            font-weight: 600;
        }

        .landing-home .empty-state {
            padding: 32px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px dashed rgba(16, 36, 62, 0.18);
            color: var(--fbws-muted);
        }

        @media (max-width: 991px) {
            .landing-home .hero-shell {
                padding-top: 24px;
            }

            .landing-home .hero-card {
                padding: 24px;
            }

            .landing-home .section-shell {
                padding: 72px 0;
            }

            .landing-home .section-head.section-head-left {
                text-align: center;
            }
        }

        @media (max-width: 767px) {
            .landing-home .hero-card {
                border-radius: 28px;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(244, 249, 255, 0.95)),
                    url('{{ asset('website/images/mobile1.jpeg') }}') center top/cover no-repeat;
            }

            .landing-home .hero-title {
                font-size: clamp(2rem, 9vw, 3rem);
            }

            .landing-home .section-copy,
            .landing-home .hero-copy {
                font-size: 1rem;
            }

            .landing-home .media-frame,
            .landing-home .media-frame img,
            .landing-home .media-frame video {
                min-height: 220px;
                height: 220px;
            }

            .landing-home .card-stack,
            .landing-home .donation-card {
                padding: 22px 18px 24px;
            }
        }
    </style>

    <div class="landing-home">
        <section class="hero-shell">
            <div class="container">
                <div class="hero-card">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <div class="hero-content text-center text-lg-start">
                                <span class="eyebrow">FBWS Welfare Network</span>
                                <h1 class="hero-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.hero_title') }}</h1>
                                <p class="hero-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.hero_text') }}</p>
                                <div class="hero-actions justify-content-center justify-content-lg-start">
                                    <a href="{{ route('website.about') }}" class="btn-main">{{ __('home.about_us') }}</a>
                                    <a href="{{ route('website.donate') }}" class="btn-ghost">{{ __('home.donation') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="hero-panel">
                                <div class="row g-3 hero-stat-grid">
                                    <div class="col-sm-6">
                                        <div class="hero-mini-card">
                                            <strong>{{ $totalUsers }}</strong>
                                            <span>{{ __('home.total_users') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="hero-mini-card">
                                            <strong>Rs {{ number_format($monthlyCollected) }}</strong>
                                            <span>{{ __('home.collected_this_month') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="hero-progress-card">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <strong>{{ __('home.monthly_summary') }}</strong>
                                                <span>{{ $progress }}%</span>
                                            </div>
                                            <div class="progress-track">
                                                <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3 small">
                                                <span>{{ __('home.expected_monthly') }}</span>
                                                <strong>Rs {{ number_format($expectedMonthly) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-shell">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.our_leaders') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.our_leaders') }}</h2>
                    <p class="section-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.leaders_text') }}</p>
                </div>
                <div class="row g-4 grid-stretch">
                    @foreach ($leaders as $leader)
                        @php
                            $displayPosition = str_replace('Gernal Secretary', 'General Secretary', $leader->position);
                            $badgeClass = match ($displayPosition) {
                                'President' => 'president',
                                'General Secretary' => 'general-secretary',
                                'Finance Secretary' => 'finance-secretary',
                                'Legal Advisor' => 'legal-advisor',
                                default => 'general-secretary',
                            };
                        @endphp
                        <div class="col-md-6 col-lg-3" data-aos="fade-up">
                            <div class="surface-card media-card">
                                <div class="media-frame media-fit">
                                    <img alt="{{ $leader->name }}" src="{{ $leader->profile_photo ? asset($leader->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}">
                                </div>
                                <div class="card-stack">
                                    <h5 class="card-title" lang="ur" dir="rtl">{{ $leader->name }}</h5>
                                    <div class="leader-badge {{ $badgeClass }}" lang="{{ $locale }}" dir="{{ $dir }}">{{ $displayPosition }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('website.team') }}" class="btn-main">{{ __('home.view_all') }}</a>
                </div>
            </div>
        </section>

        <section class="section-shell section-soft">
            <div class="container">
                <div class="row g-4 align-items-end">
                    <div class="col-lg-4">
                        <div class="section-head section-head-left mb-0">
                            <span class="eyebrow">{{ __('home.latest_users') }}</span>
                            <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.latest_users') }}</h2>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row g-4 grid-stretch">
                            @foreach ($latestUsers as $user)
                                <div class="col-md-4" data-aos="zoom-in">
                                    <div class="surface-card media-card">
                                        <div class="media-frame media-fit">
                                            <img alt="{{ $user->name }}" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}">
                                        </div>
                                        <div class="card-stack">
                                            <h5 class="card-title" lang="ur" dir="rtl">{{ $user->name }}</h5>
                                            <p class="card-meta">{{ __('home.latest_users') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-shell">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_events') }}</span>
                    <h2 class="section-title" lang="ur" dir="rtl">{{ __('home.latest_events') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    @foreach ($latestEvents as $event)
                        @php
                            $evItems = $event->displayMediaItems();
                            $evFirst = $evItems->first();
                        @endphp
                        <div class="col-md-6 col-lg-4" data-aos="fade-up">
                            <div class="surface-card media-card">
                                <div class="media-frame">
                                    @if (!$evFirst)
                                        <img loading="lazy" alt="{{ $event->name }}" src="{{ asset('website/images/event.jpeg') }}">
                                    @elseif ($evFirst->type === 'video')
                                        <video loading="lazy" muted playsinline preload="metadata" src="{{ asset($evFirst->path) }}" title="{{ $event->name }}"></video>
                                    @else
                                        <img loading="lazy" alt="{{ $event->name }}" src="{{ asset($evFirst->path) }}">
                                    @endif
                                </div>
                                <div class="card-stack">
                                    <h5 class="card-title text-clamp-2" lang="ur" dir="rtl">{{ $event->name }}</h5>
                                    <p class="card-meta">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('website.event') }}" class="btn-main">{{ __('home.view_all') }}</a>
                </div>
            </div>
        </section>

        <section class="section-shell section-soft">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.donate_via') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.donate_via') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    <div class="col-md-6 col-lg-3"><div class="surface-card donation-card"><span class="donation-logo"><img src="{{ asset('website/images/jazzcash.png') }}" alt="JazzCash"></span><span class="donation-label">Mobile Wallet</span><h5 class="card-title mb-2">JazzCash</h5><p class="card-copy mb-1">Account: 03012704423</p><p class="card-meta">Muhammad Usama Arshad</p></div></div>
                    <div class="col-md-6 col-lg-3"><div class="surface-card donation-card"><span class="donation-logo"><img src="{{ asset('website/images/easypaisa.png') }}" alt="EasyPaisa"></span><span class="donation-label">Mobile Wallet</span><h5 class="card-title mb-2">EasyPaisa</h5><p class="card-copy mb-1">Account: 03012704423</p><p class="card-meta">Muhammad Usama Arshad</p></div></div>
                    <div class="col-md-6 col-lg-3"><div class="surface-card donation-card"><span class="donation-logo"><img src="{{ asset('website/images/bop.png') }}" alt="BOP Bank"></span><span class="donation-label">Bank Transfer</span><h5 class="card-title mb-2">BOP Bank</h5><p class="card-copy mb-1">Account: 6300342619100011</p><p class="card-meta">Muhammad Usama Arshad</p></div></div>
                    <div class="col-md-6 col-lg-3"><div class="surface-card donation-card"><span class="donation-logo"><img src="{{ asset('website/images/hbl.png') }}" alt="HBL Bank"></span><span class="donation-label">Bank Transfer</span><h5 class="card-title mb-2">HBL Bank</h5><p class="card-copy mb-1">Account: 0017207905946003</p><p class="card-meta">Muhammad Usama Arshad</p></div></div>
                </div>
            </div>
        </section>

        <section class="section-shell">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_items') }}</span>
                    <h2 class="section-title" lang="ur" dir="rtl">{{ __('home.latest_items') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    @foreach ($latestItems as $item)
                        <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                            <div class="surface-card media-card">
                                <div class="media-frame">
                                    <img alt="{{ $item->name }}" src="{{ $item->image ? asset($item->image) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}">
                                </div>
                                <div class="card-stack">
                                    <h5 class="card-title text-clamp-2" lang="ur" dir="rtl">{{ $item->name }}</h5>
                                    <p class="card-meta" lang="ur" dir="rtl">{{ __('home.qty') }}: {{ $item->qty ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('website.item') }}" class="btn-main">{{ __('home.view_all') }}</a>
                </div>
            </div>
        </section>

        <section class="section-shell section-soft">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_gallery') }}</span>
                    <h2 class="section-title" lang="ur" dir="rtl">{{ __('home.latest_gallery') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    @forelse ($latestGallery as $img)
                        <div class="col-md-6 col-lg-4" data-aos="fade-up">
                            <div class="surface-card media-card">
                                <div class="media-frame">
                                    @if ($img->isVideo())
                                        <video controls playsinline preload="metadata" src="{{ $img->publicUrl() }}" title="{{ $img->title ?? __('home.gallery_image') }}"></video>
                                    @else
                                        <img loading="lazy" alt="{{ $img->title ?? __('home.gallery_image') }}" src="{{ $img->publicUrl() }}">
                                    @endif
                                </div>
                                <div class="card-stack">
                                    <h5 class="card-title text-clamp-2" lang="ur" dir="rtl">{{ $img->title ?? __('home.gallery_image') }}</h5>
                                    <p class="card-meta">{{ $img->created_at?->format('d M, Y') }}</p>
                                    <p class="card-copy mt-2" lang="ur" dir="rtl">{{ $img->user->name ?? __('home.unknown_user') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state text-center" lang="ur" dir="rtl">{{ __('home.no_gallery') }}</div>
                        </div>
                    @endforelse
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('website.gallery') }}" class="btn-main">{{ __('home.view_full_gallery') }}</a>
                </div>
            </div>
        </section>

        <section class="section-shell section-contrast">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow" style="background: rgba(255,255,255,0.12); color: #fff;">{{ __('home.monthly_summary') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.monthly_summary') }}</h2>
                </div>
                <div class="row g-4 grid-stretch summary-grid">
                    <div class="col-md-6 col-lg-3"><div class="summary-card"><h3>{{ $totalUsers }}</h3><p>{{ __('home.total_users') }}</p></div></div>
                    <div class="col-md-6 col-lg-3"><div class="summary-card"><h3>Rs {{ number_format($perUserMonthly) }}</h3><p>{{ __('home.per_user_monthly') }}</p></div></div>
                    <div class="col-md-6 col-lg-3"><div class="summary-card"><h3>Rs {{ number_format($expectedMonthly) }}</h3><p>{{ __('home.expected_monthly') }}</p></div></div>
                    <div class="col-md-6 col-lg-3"><div class="summary-card"><h3>Rs {{ number_format($monthlyCollected) }}</h3><p>{{ __('home.collected_this_month') }}</p></div></div>
                </div>
                <div class="summary-progress-wrap">
                    <div class="summary-track">
                        <div class="summary-fill" style="width: {{ $progress }}%;"></div>
                    </div>
                    <p class="summary-label">{{ __('home.collection_progress') }}: <strong>{{ $progress }}%</strong></p>
                </div>
            </div>
        </section>
    </div>
@endsection
