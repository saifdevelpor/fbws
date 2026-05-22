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
                <span class="site-page-hero__eyebrow"><i class="bi bi-box-seam me-1"></i> {{ __('web.items') }}</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('items.page_heading') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('items.page_heading') }}</p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-3 g-md-4 site-grid-stretch">
                        @forelse ($latestItems as $item)
                            <div class="col-6 col-md-4 col-lg-4">
                                <article class="site-content-card item-card">
                                    <div class="item-card__media">
                                        <img loading="lazy" src="{{ $item->image ? asset($item->image) : asset('assets/img/avatars/default_profile_imgavif.avif') }}" alt="{{ $item->name }}">
                                    </div>
                                    <div class="item-card__body">
                                        <h5 class="item-card__title" lang="ur" dir="rtl">{{ $item->name }}</h5>
                                        <p class="item-card__qty mb-0" lang="{{ $locale }}" dir="{{ $dir }}">
                                            <i class="bi bi-box-seam me-1"></i>{{ __('items.qty') }}: <strong>{{ $item->qty ?? 'N/A' }}</strong>
                                        </p>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12"><div class="site-empty" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('web.no_data_available') }}</div></div>
                        @endforelse
                    </div>

                    @if ($latestItems->count() > 0)
                        <div class="d-flex justify-content-center mt-5">
                            {{ $latestItems->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection


