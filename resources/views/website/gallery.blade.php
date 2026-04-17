@extends('website.home')

@section('title')
    <title>{{ __('gallery.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow">FBWS Gallery</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('gallery.page_heading') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('gallery.page_heading') }}</p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-4 site-grid-stretch">
                        @forelse ($galleryImages as $img)
                            @php $imgUrl = $img->publicUrl(); @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="site-content-card">
                                    <div class="site-content-card__media">
                                        @if ($img->isVideo())
                                            <video controls playsinline preload="metadata" src="{{ $imgUrl }}" title="{{ $img->title ?? __('gallery.gallery_image') }}"></video>
                                        @else
                                            <img src="{{ $imgUrl }}" alt="{{ $img->title ?? __('gallery.gallery_image') }}" loading="lazy">
                                        @endif
                                    </div>
                                    <div class="site-content-card__body text-center">
                                        <h5 class="site-section-title mb-2" style="font-size: 1.2rem;" lang="ur" dir="rtl">{{ $img->title ?? __('gallery.gallery_image') }}</h5>
                                        <p class="site-section-copy mb-1">{{ $img->created_at?->format('d M, Y') }}</p>
                                        <p class="site-section-copy mb-0" lang="ur" dir="rtl">{{ $img->user->name ?? __('gallery.unknown_user') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><div class="site-empty" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('web.no_data_available') }}</div></div>
                        @endforelse
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        {{ $galleryImages->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection


