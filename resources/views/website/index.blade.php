@extends('website.home')

@section('title')
    <title>{{ __('home.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="landing-home">
        {{-- Hero --}}
        <section class="hero-shell">
            <div class="container">
                <div class="hero-banner">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7 text-center text-lg-start">
                            <img src="{{ asset('website/images/6.png') }}" alt="FBWS" class="hero-logo">
                            <h1 class="hero-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.hero_title') }}</h1>
                            <p class="hero-copy mx-auto mx-lg-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.hero_text') }}</p>
                            <div class="hero-actions justify-content-center justify-content-lg-start">
                                <a href="{{ route('website.about') }}" class="btn-main"><i class="bi bi-info-circle me-1"></i>{{ __('home.about_us') }}</a>
                                <a href="{{ route('website.donate') }}" class="btn-ghost"><i class="bi bi-heart me-1"></i>{{ __('home.donation') }}</a>
                            </div>
                            <div class="welfare-quick-links">
                                <a href="{{ route('website.become-part') }}"><i class="bi bi-person-plus"></i>{{ __('web.become_part') }}</a>
                                <a href="{{ route('website.payment') }}"><i class="bi bi-credit-card"></i>{{ __('web.donation') }}</a>
                                <a href="{{ route('website.event') }}"><i class="bi bi-calendar-event"></i>{{ __('web.events') }}</a>
                                <a href="{{ route('website.contact') }}"><i class="bi bi-telephone"></i>{{ __('web.contacts') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-card text-center">
                                        <strong>{{ $totalUsers }}</strong>
                                        <span>{{ __('home.total_users') }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card text-center">
                                        <strong>Rs {{ number_format($monthlyCollected) }}</strong>
                                        <span>{{ __('home.collected_this_month') }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="progress-box">
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong style="color:var(--w-ink);">{{ __('home.monthly_summary') }}</strong>
                                            <span style="color:var(--w-primary);font-weight:700;">{{ $progress }}%</span>
                                        </div>
                                        <div class="progress-track">
                                            <div class="progress-fill" style="width: {{ $progress }}%;"></div>
                                        </div>
                                        <small class="d-block mt-2 text-muted">{{ __('home.expected_monthly') }}: Rs {{ number_format($expectedMonthly) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Leaders --}}
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.our_leaders') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.our_leaders') }}</h2>
                    <p class="section-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.leaders_text') }}</p>
                </div>
                <div class="row g-3 g-md-4 grid-stretch">
                    @forelse ($leaders as $leader)
                        @php
                            $displayPosition = str_replace('Gernal Secretary', 'General Secretary', $leader->position);
                        @endphp
                        <div class="col-6 col-lg-3">
                            <div class="surface-card member-card">
                                <div class="member-card__avatar">
                                    <img alt="{{ $leader->name }}" src="{{ $leader->profile_photo ? asset($leader->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}">
                                </div>
                                <div class="member-card__body">
                                    <h5 class="card-title" lang="ur" dir="rtl">{{ $leader->name }}</h5>
                                    <span class="member-card__badge leader-badge">{{ $displayPosition }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="site-empty" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('web.no_data_available') }}</div></div>
                    @endforelse
                </div>
                @if ($leaders->count() > 0)
                    <div class="text-center mt-4"><a href="{{ route('website.team') }}" class="site-primary-btn">{{ __('home.view_all') }}</a></div>
                @endif
            </div>
        </section>

        {{-- Latest members (4) --}}
        <section class="section-block alt">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_users') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.latest_users') }}</h2>
                    <p class="section-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.latest_users_sub') }}</p>
                </div>
                <div class="row g-3 g-md-4 grid-stretch">
                    @forelse ($latestUsers as $user)
                        <div class="col-6 col-lg-3">
                            <div class="surface-card member-card">
                                <div class="member-card__avatar">
                                    <img alt="{{ $user->name }}" src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}">
                                </div>
                                <div class="member-card__body">
                                    <h5 class="card-title" lang="ur" dir="rtl">{{ $user->name }}</h5>
                                    <span class="member-card__badge"><i class="bi bi-person-check"></i> {{ app()->getLocale() === 'ur' ? 'رکن' : 'Member' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="site-empty">{{ __('web.no_data_available') }}</div></div>
                    @endforelse
                </div>
                @if ($latestUsers->count() > 0)
                    <div class="text-center mt-4">
                        <a href="{{ route('website.team') }}" class="site-primary-btn">
                            <i class="bi bi-people me-1"></i>{{ __('home.view_all') }}
                        </a>
                    </div>
                @endif
            </div>
        </section>

        {{-- Events --}}
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_events') }}</span>
                    <h2 class="section-title" lang="ur" dir="rtl">{{ __('home.latest_events') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    @forelse ($latestEvents as $event)
                        @php $evFirst = $event->displayMediaItems()->first(); @endphp
                        <div class="col-6 col-md-6 col-lg-4">
                            <div class="surface-card">
                                <div class="media-frame">
                                    @if (!$evFirst)
                                        <img loading="lazy" alt="{{ $event->name }}" src="{{ asset('website/images/event.jpeg') }}">
                                    @elseif ($evFirst->type === 'video')
                                        <video loading="lazy" muted playsinline preload="metadata" src="{{ asset($evFirst->path) }}"></video>
                                    @else
                                        <img loading="lazy" alt="{{ $event->name }}" src="{{ asset($evFirst->path) }}">
                                    @endif
                                </div>
                                <div class="card-stack">
                                    <h5 class="card-title text-clamp-2" lang="ur" dir="rtl">{{ $event->name }}</h5>
                                    <p class="card-meta">{{ optional($event->created_at)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="site-empty">{{ __('web.no_data_available') }}</div></div>
                    @endforelse
                </div>
                @if ($latestEvents->count() > 0)
                    <div class="text-center mt-4"><a href="{{ route('website.event') }}" class="site-primary-btn">{{ __('home.view_all') }}</a></div>
                @endif
            </div>
        </section>

        {{-- Donation channels --}}
        <section class="section-block alt">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.donate_via') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.donate_via') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    <div class="col-6 col-md-6 col-lg-3"><div class="surface-card donation-card text-center"><span class="donation-logo"><img src="{{ asset('website/images/jazzcash.png') }}" alt="JazzCash"></span><span class="donation-label">JazzCash</span><p class="card-copy mb-0">03012704423</p></div></div>
                    <div class="col-6 col-md-6 col-lg-3"><div class="surface-card donation-card text-center"><span class="donation-logo"><img src="{{ asset('website/images/easypaisa.png') }}" alt="EasyPaisa"></span><span class="donation-label">EasyPaisa</span><p class="card-copy mb-0">03012704423</p></div></div>
                    <div class="col-6 col-md-6 col-lg-3"><div class="surface-card donation-card text-center"><span class="donation-logo"><img src="{{ asset('website/images/bop.png') }}" alt="BOP"></span><span class="donation-label">BOP</span><p class="card-copy mb-0">6300342619100011</p></div></div>
                    <div class="col-6 col-md-6 col-lg-3"><div class="surface-card donation-card text-center"><span class="donation-logo"><img src="{{ asset('website/images/hbl.png') }}" alt="HBL"></span><span class="donation-label">HBL</span><p class="card-copy mb-0">0017207905946003</p></div></div>
                </div>
            </div>
        </section>

        {{-- Items (4) --}}
        <section class="section-block">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_items') }}</span>
                    <h2 class="section-title" lang="ur" dir="rtl">{{ __('home.latest_items') }}</h2>
                    <p class="section-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.latest_items_sub') }}</p>
                </div>
                <div class="row g-3 g-md-4 grid-stretch">
                    @forelse ($latestItems as $item)
                        <div class="col-6 col-lg-3">
                            <div class="surface-card item-card">
                                <div class="item-card__media">
                                    <img alt="{{ $item->name }}" loading="lazy"
                                        src="{{ $item->image ? asset($item->image) : asset('assets/img/avatars/defualt_profile_imgavif.avif') }}">
                                </div>
                                <div class="item-card__body">
                                    <h5 class="item-card__title" lang="ur" dir="rtl">{{ $item->name }}</h5>
                                    <p class="item-card__qty" lang="{{ $locale }}" dir="{{ $dir }}">
                                        <i class="bi bi-box-seam me-1"></i>{{ __('home.qty') }}: <strong>{{ $item->qty ?? 'N/A' }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="site-empty">{{ __('web.no_data_available') }}</div></div>
                    @endforelse
                </div>
                @if ($latestItems->count() > 0)
                    <div class="text-center mt-4">
                        <a href="{{ route('website.item') }}" class="site-primary-btn">
                            <i class="bi bi-box-seam me-1"></i>{{ __('home.view_all') }}
                        </a>
                    </div>
                @endif
            </div>
        </section>

        {{-- Gallery --}}
        <section class="section-block alt">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.latest_gallery') }}</span>
                    <h2 class="section-title" lang="ur" dir="rtl">{{ __('home.latest_gallery') }}</h2>
                </div>
                <div class="row g-4 grid-stretch">
                    @forelse ($latestGallery as $img)
                        <div class="col-6 col-md-6 col-lg-4">
                            <div class="surface-card">
                                <div class="media-frame">
                                    @if ($img->isVideo())
                                        <video controls playsinline preload="metadata" src="{{ $img->publicUrl() }}"></video>
                                    @else
                                        <img loading="lazy" alt="{{ $img->title ?? __('home.gallery_image') }}" src="{{ $img->publicUrl() }}">
                                    @endif
                                </div>
                                <div class="card-stack">
                                    <h5 class="card-title text-clamp-2" lang="ur" dir="rtl">{{ $img->title ?? __('home.gallery_image') }}</h5>
                                    <p class="card-meta">{{ $img->created_at?->format('d M, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><div class="site-empty">{{ __('web.no_data_available') }}</div></div>
                    @endforelse
                </div>
                @if ($latestGallery->count() > 0)
                    <div class="text-center mt-4"><a href="{{ route('website.gallery') }}" class="site-primary-btn">{{ __('home.view_full_gallery') }}</a></div>
                @endif
            </div>
        </section>

        {{-- Monthly summary --}}
        <section class="section-block dark">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">{{ __('home.monthly_summary') }}</span>
                    <h2 class="section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('home.monthly_summary') }}</h2>
                </div>
                <div class="row g-3 grid-stretch">
                    <div class="col-6 col-lg-3"><div class="summary-card"><h3>{{ $totalUsers }}</h3><p>{{ __('home.total_users') }}</p></div></div>
                    <div class="col-6 col-lg-3"><div class="summary-card"><h3>Rs {{ number_format($perUserMonthly) }}</h3><p>{{ __('home.per_user_monthly') }}</p></div></div>
                    <div class="col-6 col-lg-3"><div class="summary-card"><h3>Rs {{ number_format($expectedMonthly) }}</h3><p>{{ __('home.expected_monthly') }}</p></div></div>
                    <div class="col-6 col-lg-3"><div class="summary-card"><h3>Rs {{ number_format($monthlyCollected) }}</h3><p>{{ __('home.collected_this_month') }}</p></div></div>
                </div>
                <div class="summary-track">
                    <div class="summary-fill" style="width: {{ $progress }}%;"></div>
                </div>
                <p class="summary-label">{{ __('home.collection_progress') }}: <strong>{{ $progress }}%</strong></p>
            </div>
        </section>
    </div>
@endsection
