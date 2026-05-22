@extends('website.home')

@section('title')
    <title>{{ __('events.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow"><i class="bi bi-calendar-event me-1"></i> {{ __('web.events') }}</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('events.page_heading') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('events.page_heading') }}</p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-4 site-grid-stretch">
                        @forelse ($events as $event)
                            @php
                                $evMedia = $event->displayMediaItems();
                                $first = $evMedia->first();
                            @endphp
                            <div class="col-6 col-md-6 col-lg-4">
                                <div class="site-content-card">
                                    <div class="site-content-card__media">
                                        @if (!$first)
                                            <img src="{{ asset('website/images/event.jpeg') }}" alt="{{ $event->name }}">
                                        @elseif ($first->type === 'video')
                                            <video controls playsinline preload="metadata" src="{{ asset($first->path) }}" title="{{ $event->name }}"></video>
                                        @else
                                            <img src="{{ asset($first->path) }}" alt="{{ $event->name }}">
                                        @endif
                                    </div>
                                    <div class="site-content-card__body text-center">
                                        <h5 class="site-section-title mb-2" style="font-size: 1.2rem;" lang="ur" dir="rtl">{{ $event->name }}</h5>
                                        <p class="site-section-copy mb-2">{{ optional($event->created_at)->format('d M Y') }}</p>
                                        <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><div class="site-empty" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('web.no_data_available') }}</div></div>
                        @endforelse
                    </div>

                    @if ($events->count() > 0)
                        <div class="d-flex justify-content-center mt-5">
                            {{ $events->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection


