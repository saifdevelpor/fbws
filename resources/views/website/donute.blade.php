@extends('website.home')

@section('title')
    <title>{{ __('donation.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow"><i class="bi bi-heart me-1"></i> {{ __('web.donation') }}</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('donation.members_with_payments') }}</h1>
                <p class="site-page-hero__copy mt-3" lang="{{ $locale }}" dir="{{ $dir }}">
                    {{ __('payment.hero_text') }}
                </p>
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3 mt-4">
                    <a href="{{ route('website.payment') }}" class="site-primary-btn">{{ __('donation.open_payment_page') }}</a>
                    <a href="{{ route('website.become-part') }}" class="site-secondary-btn">{{ __('donation.become_part') }}</a>
                </div>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-8">
                            <span class="site-chip">{{ __('donation.payment_access_badge') }}</span>
                            <h2 class="site-section-title mt-3 mb-2" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('donation.payment_access_title') }}</h2>
                            <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">
                                {{ __('donation.payment_access_text') }}
                            </p>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex flex-column flex-sm-row flex-lg-column gap-3">
                                <a href="{{ route('website.payment') }}" class="site-primary-btn justify-content-center">{{ __('donation.open_payment_page') }}</a>
                                <a href="{{ route('website.become-part') }}" class="site-secondary-btn justify-content-center">{{ __('donation.become_part') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-4 site-grid-stretch">
                        @forelse ($payments as $payment)
                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="site-content-card">
                                    <div class="site-content-card__media media-fit">
                                        <img src="{{ $payment->user->profile_photo ? asset($payment->user->profile_photo) : asset('assets/img/avatars/default_profile_imgavif.avif') }}" alt="{{ $payment->user->name }}">
                                    </div>
                                    <div class="site-content-card__body text-center">
                                        <h5 class="site-section-title mb-2" style="font-size:1.2rem;" lang="ur" dir="rtl">{{ $payment->user->name }}</h5>
                                        <p class="site-section-copy mb-1" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('donation.month') }}: {{ $payment->month }}</p>
                                        <p class="site-section-copy mb-1">{{ __('donation.amount_paid') }}: <strong>PKR {{ number_format($payment->amount, 0) }}</strong></p>
                                        <p class="site-section-copy mb-0">{{ __('donation.payment_date') }}: {{ $payment->date }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><div class="site-empty" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('web.no_data_available') }}</div></div>
                        @endforelse
                    </div>

                    @if ($payments->count() > 0)
                        <div class="d-flex justify-content-center mt-5">
                            {{ $payments->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
