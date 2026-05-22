@extends('website.home')

@section('title')
    <title>{{ __('payment.page_title') }}</title>
    <link rel="stylesheet" href="{{ asset('website/css/payment-page.css') }}">
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <div class="site-page-shell payment-page">
        <div class="container">

            {{-- Hero --}}
            <section class="site-page-hero">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <span class="site-page-hero__eyebrow"><i class="bi bi-credit-card me-1"></i> {{ __('payment.hero_badge') }}</span>
                        <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.hero_title') }}</h1>
                        <p class="site-page-hero__copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.hero_text') }}</p>
                        <div class="site-page-hero__actions payment-hero-actions">
                            <a href="{{ route('website.contact') }}" class="site-primary-btn">
                                <i class="bi bi-telephone"></i> {{ __('payment.contact_admin') }}
                            </a>
                            <a href="{{ route('website.become-part') }}" class="site-secondary-btn">
                                <i class="bi bi-people"></i> {{ __('payment.hero_secondary') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row g-2 g-md-3 payment-stat-grid">
                            <div class="col-6 col-lg-6">
                                <div class="payment-stat-card text-center">
                                    <strong>{{ $memberCount }}</strong>
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.stat_members') }}</span>
                                </div>
                            </div>
                            <div class="col-6 col-lg-6">
                                <div class="payment-stat-card text-center">
                                    <strong class="payment-stat-amount">Rs {{ number_format($currentMonthCollection) }}</strong>
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.stat_collection') }}</span>
                                </div>
                            </div>
                            <div class="col-12 col-lg-12">
                                <div class="payment-stat-card text-center">
                                    <strong>{{ __('payment.stat_safe_title') }}</strong>
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.stat_safe_text') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Notice + Help --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <section class="site-panel h-100">
                        <div class="site-panel-body">
                            <span class="site-chip mb-3">{{ __('payment.notice_badge') }}</span>
                            <h2 class="site-section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_title') }}</h2>
                            <p class="site-section-copy mb-4" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_text') }}</p>
                            <div class="payment-notice-list">
                                <div class="payment-notice-item">
                                    <i class="bi bi-phone"></i>
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_tip_1') }}</span>
                                </div>
                                <div class="payment-notice-item">
                                    <i class="bi bi-check2-circle"></i>
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_tip_2') }}</span>
                                </div>
                                <div class="payment-notice-item">
                                    <i class="bi bi-headset"></i>
                                    <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_tip_3') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('website.contact') }}" class="site-primary-btn payment-mobile-contact d-lg-none">
                                <i class="bi bi-chat-dots"></i> {{ __('payment.contact_admin') }}
                            </a>
                        </div>
                    </section>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <section class="site-panel soft-panel h-100">
                        <div class="site-panel-body d-flex flex-column h-100">
                            <span class="site-chip mb-3">{{ __('payment.steps_badge') }}</span>
                            <h2 class="site-section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.help_title') }}</h2>
                            <p class="site-section-copy mb-4" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.help_text') }}</p>
                            <a href="{{ route('website.contact') }}" class="site-primary-btn mt-auto align-self-start">
                                <i class="bi bi-chat-dots"></i> {{ __('payment.contact_admin') }}
                            </a>
                        </div>
                    </section>
                </div>
            </div>

            {{-- Payment accounts --}}
            <section class="site-panel mb-4">
                <div class="site-panel-body">
                    <span class="site-chip mb-3">{{ __('payment.accounts_badge') }}</span>
                    <h2 class="site-section-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.accounts_title') }}</h2>
                    <p class="site-section-copy mb-4" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.accounts_text') }}</p>
                    <div class="row g-3 g-md-4 payment-channels-grid">
                        @foreach ($paymentChannels as $channel)
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="site-content-card payment-channel-card">
                                    <div class="payment-channel-top">
                                        <span class="payment-channel-logo">
                                            <img src="{{ asset($channel['logo']) }}" alt="{{ $channel['name'] }}">
                                        </span>
                                        <div class="payment-channel-meta">
                                            <span class="payment-channel-type">{{ $channel['type'] }}</span>
                                            <h3 class="payment-channel-name">{{ $channel['name'] }}</h3>
                                        </div>
                                    </div>
                                    <div class="payment-detail-row payment-detail-row--account">
                                        <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.account_number') }}</span>
                                        <strong class="payment-account-number" dir="ltr">{{ $channel['account'] }}</strong>
                                    </div>
                                    <div class="payment-detail-row payment-detail-row--holder">
                                        <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.account_holder') }}</span>
                                        <strong class="payment-holder-name" lang="ur" dir="rtl">{{ $channel['holder'] }}</strong>
                                    </div>
                                    <button type="button" class="payment-copy-btn js-payment-copy"
                                        data-account="{{ $channel['account'] }}"
                                        data-copy-label="{{ __('payment.copy_label') }}"
                                        data-copied-label="{{ $locale === 'ur' ? 'اکاؤنٹ نمبر کاپی ہو گیا' : __('payment.copied_label') }}"
                                        data-success-message="{{ $locale === 'ur' ? 'نمبر کامیابی سے کاپی ہو گیا' : __('payment.copy_success_message') }}">
                                        <i class="bi bi-copy"></i>
                                        <span class="js-payment-copy-label" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.copy_label') }}</span>
                                    </button>
                                    <div class="payment-copy-feedback js-payment-copy-feedback" lang="{{ $locale }}" dir="{{ $dir }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Steps --}}
            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <span class="site-chip mb-3">{{ __('payment.steps_badge') }}</span>
                    <h2 class="site-section-title mb-4" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.help_title') }}</h2>
                    <div class="row g-3 payment-steps-grid">
                        <div class="col-12 col-md-4">
                            <div class="site-content-card payment-step-card">
                                <span class="step-icon"><i class="bi bi-phone"></i></span>
                                <div class="payment-step-body">
                                    <h3 lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_1_title') }}</h3>
                                    <p lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_1_text') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="site-content-card payment-step-card">
                                <span class="step-icon"><i class="bi bi-credit-card"></i></span>
                                <div class="payment-step-body">
                                    <h3 lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_2_title') }}</h3>
                                    <p lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_2_text') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="site-content-card payment-step-card">
                                <span class="step-icon"><i class="bi bi-receipt"></i></span>
                                <div class="payment-step-body">
                                    <h3 lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_3_title') }}</h3>
                                    <p lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_3_text') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    @guest
        <div class="modal fade payment-access-modal" id="paymentAccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="payment-access-top">
                        <span class="site-page-hero__eyebrow d-inline-block mb-0">{{ __('payment.guest_popup_badge') }}</span>
                        <div class="payment-access-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <div class="payment-access-body">
                        <h3 class="site-section-title mb-3" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.login_required_title') }}</h3>
                        <p class="site-section-copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.login_required_text') }}</p>
                        <div class="payment-access-actions">
                            <button type="button" class="site-primary-btn" id="openExistingLoginModal">
                                <i class="bi bi-box-arrow-in-right"></i> {{ __('payment.login_button') }}
                            </button>
                            <a href="{{ route('website.become-part') }}" class="site-secondary-btn">
                                <i class="bi bi-person-plus"></i> {{ __('payment.become_part_button') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var accessModalEl = document.getElementById('paymentAccessModal');
                var openLoginBtn = document.getElementById('openExistingLoginModal');
                var loginModalEl = document.getElementById('loginModal');

                if (accessModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(accessModalEl).show();
                }

                if (openLoginBtn && accessModalEl && loginModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    openLoginBtn.addEventListener('click', function() {
                        bootstrap.Modal.getOrCreateInstance(accessModalEl).hide();
                        setTimeout(function() {
                            bootstrap.Modal.getOrCreateInstance(loginModalEl).show();
                        }, 220);
                    });
                }
            });
        </script>
    @endguest

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-payment-copy').forEach(function(button) {
                button.addEventListener('click', async function() {
                    var accountNumber = button.getAttribute('data-account');
                    var label = button.querySelector('.js-payment-copy-label');
                    var feedback = button.parentElement.querySelector('.js-payment-copy-feedback');
                    var defaultLabel = button.getAttribute('data-copy-label');
                    var copiedLabel = button.getAttribute('data-copied-label');
                    var successMessage = button.getAttribute('data-success-message');

                    function showSuccess() {
                        button.classList.add('is-copied');
                        if (label) label.textContent = copiedLabel;
                        if (feedback) {
                            feedback.textContent = successMessage;
                            feedback.classList.add('is-visible');
                        }
                        setTimeout(function() {
                            button.classList.remove('is-copied');
                            if (label) label.textContent = defaultLabel;
                            if (feedback) {
                                feedback.classList.remove('is-visible');
                                feedback.textContent = '';
                            }
                        }, 1800);
                    }

                    try {
                        var copied = false;
                        if (navigator.clipboard && window.isSecureContext) {
                            await navigator.clipboard.writeText(accountNumber);
                            copied = true;
                        } else {
                            var ta = document.createElement('textarea');
                            ta.value = accountNumber;
                            ta.style.position = 'fixed';
                            ta.style.left = '-9999px';
                            document.body.appendChild(ta);
                            ta.select();
                            copied = document.execCommand('copy');
                            document.body.removeChild(ta);
                        }
                        if (copied) showSuccess();
                    } catch (e) {
                        var ta = document.createElement('textarea');
                        ta.value = accountNumber;
                        ta.style.position = 'fixed';
                        ta.style.left = '-9999px';
                        document.body.appendChild(ta);
                        ta.select();
                        if (document.execCommand('copy')) showSuccess();
                        document.body.removeChild(ta);
                    }
                });
            });
        });
    </script>
@endsection
