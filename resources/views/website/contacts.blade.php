@extends('website.home')

@section('title')
    <title>{{ __('contact.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell contact-page">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow"><i class="bi bi-envelope me-1"></i> {{ __('web.contacts') }}</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('contact.breadcrumb_title') }}</h1>
                <p class="site-page-hero__copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('contact.get_in_touch') }}</p>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <div class="row g-4 contact-info-grid">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="site-content-card contact-info-card">
                                <div class="site-content-card__body text-center">
                                    <div class="contact-info-card__icon" aria-hidden="true">
                                        <i class="fa-solid fa-phone"></i>
                                    </div>
                                    <p class="contact-info-card__value mb-2">
                                        <a href="tel:+923012704423" class="contact-info-card__link">+92 301 2704423</a>
                                    </p>
                                    <p class="contact-info-card__hint mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('contact.call_anytime') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="site-content-card contact-info-card">
                                <div class="site-content-card__body text-center">
                                    <div class="contact-info-card__icon" aria-hidden="true">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <p class="contact-info-card__value mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('contact.address_text') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="site-content-card contact-info-card">
                                <div class="site-content-card__body text-center">
                                    <div class="contact-info-card__icon" aria-hidden="true">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <p class="contact-info-card__value mb-2">
                                        <a href="mailto:farookabrotherswelfearsociety@gmail.com" class="contact-info-card__link contact-info-card__link--email">farookabrotherswelfearsociety@gmail.com</a>
                                    </p>
                                    <p class="contact-info-card__hint mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('contact.email_anytime') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="site-panel mb-4">
                <div class="site-panel-body p-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d383385.4696242331!2d72.67924736746895!3d32.083611535516184!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3918a1efc8b7a983%3A0x9f7391eae95f52a4!2sSargodha%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2sus!4v1676920240594!5m2!1sen!2sus" width="100%" height="420" style="border:0; border-radius: 28px;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    @if (session('contact_wa_links'))
                        <div class="alert alert-info text-center">
                            <h5 class="mb-2">Message submitted successfully</h5>
                            @foreach (session('contact_wa_links') as $i => $link)
                                <a href="{{ $link }}" target="_blank" class="site-primary-btn m-1">Send to Admin {{ $i + 1 }}</a>
                            @endforeach
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif

                    <div class="text-center mb-4">
                        <span class="site-chip">{{ __('contact.get_in_touch') }}</span>
                        <h2 class="site-section-title mt-3" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('contact.get_in_touch') }}</h2>
                    </div>

                    <form method="POST" action="{{ route('website.contact.submit') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6"><input class="form-control form-control-lg" type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('contact.your_name') }}" required></div>
                            <div class="col-md-6"><input class="form-control form-control-lg" type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('contact.email') }}" required></div>
                            <div class="col-md-6"><input class="form-control form-control-lg" type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ __('contact.phone') }}" required></div>
                            <div class="col-md-12"><textarea class="form-control form-control-lg" name="message" rows="6" placeholder="{{ __('contact.message') }}" required>{{ old('message') }}</textarea></div>
                            <div class="col-md-12 text-center"><button class="site-primary-btn" type="submit">{{ __('contact.send') }}</button></div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
