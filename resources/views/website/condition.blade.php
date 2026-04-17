@extends('website.home')

@section('title')
    <title>{{ __('rules.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow">FBWS Rules</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('rules.title') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('rules.intro') }}</p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="text-center mb-4">
                        <span class="site-chip">{{ __('rules.breadcrumb_title') }}</span>
                    </div>
                    <ol class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}" style="padding-{{ $dir === 'rtl' ? 'right' : 'left' }}: 22px;">
                        @foreach (__('rules.rules') as $rule)
                            <li class="mb-2">{{ $rule }}</li>
                        @endforeach
                    </ol>
                </div>
            </section>
        </div>
    </div>
@endsection
