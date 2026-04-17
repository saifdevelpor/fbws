@extends('website.home')

@section('title')
    <title>{{ __('items.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow">FBWS Items</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('items.page_heading') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('items.page_heading') }}</p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-4 site-grid-stretch">
                        @foreach ($latestItems as $item)
                            <div class="col-md-6 col-lg-4">
                                <div class="site-content-card">
                                    <div class="site-content-card__media">
                                        <img src="{{ $item->image ? asset($item->image) : asset('assets/img/avatars/default_profile_imgavif.avif') }}" alt="{{ $item->name }}">
                                    </div>
                                    <div class="site-content-card__body text-center">
                                        <h5 class="site-section-title mb-2" style="font-size: 1.2rem;" lang="ur" dir="rtl">{{ $item->name }}</h5>
                                        <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('items.qty') }}: <strong>{{ $item->qty ?? 'N/A' }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        {{ $latestItems->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
