@extends('website.home')

@php
    $locale = app()->getLocale();
    $dir = $locale === 'ur' ? 'rtl' : 'ltr';
@endphp

@section('title')
    <title>{{ __('help_center.page_title') }} | FBWS</title>
@endsection

@section('content')
    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow"><i class="bi bi-question-circle me-1"></i> {{ __('web.help_center') }}</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('help_center.hero_title') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('help_center.hero_intro') }}</p>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4 site-grid-stretch">
                        <div class="col-6 col-md-4"><div class="site-content-card"><div class="site-content-card__body"><h5 class="site-section-title" style="font-size:1.1rem;">{{ __('help_center.cat_donations_title') }}</h5><p class="site-section-copy mb-0">{{ __('help_center.cat_donations_desc') }}</p></div></div></div>
                        <div class="col-6 col-md-4"><div class="site-content-card"><div class="site-content-card__body"><h5 class="site-section-title" style="font-size:1.1rem;">{{ __('help_center.cat_membership_title') }}</h5><p class="site-section-copy mb-0">{{ __('help_center.cat_membership_desc') }}</p></div></div></div>
                        <div class="col-6 col-md-4"><div class="site-content-card"><div class="site-content-card__body"><h5 class="site-section-title" style="font-size:1.1rem;">{{ __('help_center.cat_support_title') }}</h5><p class="site-section-copy mb-0">{{ __('help_center.cat_support_desc') }}</p></div></div></div>
                    </div>
                </div>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <h2 class="site-section-title">{{ __('help_center.faq_heading') }}</h2>
                            <p class="site-section-copy">{{ __('help_center.faq_sub') }}</p>
                            <div class="accordion" id="helpCenterAccordion">
                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="faq{{ $i }}h">
                                            <button class="accordion-button {{ $i === 1 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}" aria-expanded="{{ $i === 1 ? 'true' : 'false' }}" aria-controls="faq{{ $i }}">
                                                {{ __("help_center.faq{$i}_q") }}
                                            </button>
                                        </h2>
                                        <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}" aria-labelledby="faq{{ $i }}h" data-bs-parent="#helpCenterAccordion">
                                            <div class="accordion-body">
                                                @if ($i === 5)
                                                    {{ __('help_center.faq5_before_contact') }}<a href="{{ route('website.contact') }}">{{ __('help_center.faq5_link_contact') }}</a>{{ __('help_center.faq5_between') }}<a href="{{ route('complaints.create') }}">{{ __('help_center.faq5_link_complaint') }}</a>{{ __('help_center.faq5_after') }}
                                                @else
                                                    {!! __("help_center.faq{$i}_a") !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="site-content-card">
                                <div class="site-content-card__body">
                                    <h5 class="site-section-title" style="font-size:1.15rem;">{{ __('help_center.quick_heading') }}</h5>
                                    <div class="d-grid gap-2">
                                        <a class="site-primary-btn" href="{{ route('website.contact') }}">{{ __('help_center.link_contact') }}</a>
                                        <a class="site-primary-btn" href="{{ route('complaints.create') }}">{{ __('help_center.link_complaint') }}</a>
                                        <a class="site-primary-btn" href="{{ route('website.become-part') }}">{{ __('help_center.link_become_part') }}</a>
                                        <a class="site-secondary-btn" href="{{ route('website.donate') }}">{{ __('help_center.link_donate') }}</a>
                                    </div>
                                    <hr>
                                    <h6 class="site-section-title" style="font-size:1rem;">{{ __('help_center.timeline_heading') }}</h6>
                                    <ul class="site-section-copy mb-0">
                                        <li>{{ __('help_center.timeline_1') }}</li>
                                        <li>{{ __('help_center.timeline_2') }}</li>
                                        <li>{{ __('help_center.timeline_3') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
