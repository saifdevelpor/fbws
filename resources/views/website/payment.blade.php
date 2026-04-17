@extends('website.home')

@section('title')
    <title>{{ __('payment.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
    @endphp

    <style>
        .payment-page {
            --payment-primary: #0f4c81;
            --payment-primary-deep: #0a2d4b;
            --payment-accent: #f4b321;
            --payment-ink: #13263b;
            --payment-muted: #5f7187;
            --payment-line: rgba(19, 38, 59, 0.1);
            --payment-shadow: 0 24px 55px rgba(13, 39, 66, 0.12);
            --payment-soft: #f4f9ff;
            --payment-alert: #fff7e5;
        }

        .payment-shell {
            position: relative;
            overflow: hidden;
            padding: 14px 0 88px;
        }

        .payment-shell::before,
        .payment-shell::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .payment-shell::before {
            width: 320px;
            height: 320px;
            top: -60px;
            right: -80px;
            background: radial-gradient(circle, rgba(244, 179, 33, 0.2), transparent 70%);
        }

        .payment-shell::after {
            width: 300px;
            height: 300px;
            bottom: 40px;
            left: -100px;
            background: radial-gradient(circle, rgba(15, 76, 129, 0.16), transparent 72%);
        }

        .payment-hero {
            position: relative;
            overflow: hidden;
            padding: 38px;
            border-radius: 34px;
            margin-bottom: 28px;
            color: #fff;
            background:
                linear-gradient(135deg, rgba(10, 45, 75, 0.95), rgba(15, 76, 129, 0.88)),
                url('{{ asset('website/images/banner1.jpeg') }}') center/cover no-repeat;
            box-shadow: 0 28px 65px rgba(10, 45, 75, 0.22);
        }

        .payment-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(244, 179, 33, 0.24), transparent 24%),
                linear-gradient(120deg, rgba(255, 255, 255, 0.04), transparent 58%);
        }

        .payment-hero > * {
            position: relative;
            z-index: 1;
        }

        .payment-badge,
        .payment-kicker {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .payment-badge {
            background: rgba(255, 255, 255, 0.12);
        }

        .payment-kicker {
            background: rgba(15, 76, 129, 0.08);
            color: var(--payment-primary);
        }

        .payment-badge::before,
        .payment-kicker::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--payment-accent);
            box-shadow: 0 0 0 6px rgba(244, 179, 33, 0.18);
        }

        .payment-title {
            font-size: clamp(2rem, 4vw, 3.7rem);
            line-height: 1.1;
            font-weight: 900;
            margin: 20px 0 16px;
            color: #fff;
        }

        .payment-title[lang="ur"],
        .payment-heading[lang="ur"],
        .payment-card-title[lang="ur"] {
            line-height: 1.65;
        }

        .payment-copy,
        .payment-text {
            white-space: pre-line;
        }

        .payment-copy {
            max-width: 700px;
            font-size: 1.05rem;
            line-height: 1.95;
            color: rgba(255, 255, 255, 0.86);
        }

        .payment-copy[lang="ur"],
        .payment-text[lang="ur"] {
            line-height: 2.1;
        }

        .payment-stat-grid,
        .payment-grid-stretch {
            display: flex;
            flex-wrap: wrap;
        }

        .payment-stat-grid > div,
        .payment-grid-stretch > div {
            display: flex;
        }

        .payment-stat-card,
        .payment-panel,
        .payment-method-card,
        .payment-step-card,
        .payment-help-card {
            width: 100%;
            border-radius: 28px;
            box-shadow: var(--payment-shadow);
        }

        .payment-stat-card {
            padding: 24px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
            height: 100%;
        }

        .payment-stat-card strong {
            display: block;
            font-size: clamp(1.8rem, 3vw, 2.7rem);
            margin-bottom: 10px;
            color: #fff;
        }

        .payment-stat-card span {
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.8;
        }

        .payment-panel,
        .payment-help-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid var(--payment-line);
            overflow: hidden;
        }

        .payment-panel-header,
        .payment-panel-body,
        .payment-help-card {
            padding: 28px;
        }

        .payment-heading {
            margin: 16px 0 10px;
            font-size: clamp(1.5rem, 2.4vw, 2.35rem);
            color: var(--payment-ink);
            font-weight: 800;
        }

        .payment-text {
            margin: 0;
            color: var(--payment-muted);
            line-height: 1.9;
        }

        .payment-notice-card {
            border-radius: 26px;
            padding: 24px;
            background: linear-gradient(135deg, var(--payment-alert), #fffdf6);
            border: 1px solid rgba(244, 179, 33, 0.28);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
        }

        .payment-notice-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            background: linear-gradient(135deg, #f4b321, #ffd56f);
            color: var(--payment-primary-deep);
            font-size: 1.7rem;
            box-shadow: 0 18px 34px rgba(244, 179, 33, 0.22);
        }

        .payment-note-list {
            display: grid;
            gap: 14px;
            margin-top: 22px;
        }

        .payment-note-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.68);
            border: 1px solid rgba(19, 38, 59, 0.08);
            color: var(--payment-ink);
        }

        .payment-note-item i {
            color: var(--payment-primary);
            font-size: 1.1rem;
            margin-top: 4px;
        }

        .payment-method-card,
        .payment-step-card {
            padding: 24px;
            background: #fff;
            border: 1px solid var(--payment-line);
            height: 100%;
        }

        .payment-method-top {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .payment-method-logo {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #fff, #f3f8fe);
            border: 1px solid rgba(19, 38, 59, 0.08);
        }

        .payment-method-logo img {
            max-width: 42px;
            max-height: 42px;
            object-fit: contain;
        }

        .payment-method-chip {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(15, 76, 129, 0.08);
            color: var(--payment-primary);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .payment-card-title {
            margin: 10px 0 8px;
            color: var(--payment-ink);
            font-size: 1.28rem;
            font-weight: 800;
        }

        .payment-detail-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px dashed rgba(19, 38, 59, 0.1);
            color: var(--payment-muted);
        }

        .payment-detail-row:last-child {
            border-bottom: 0;
        }

        .payment-detail-row strong {
            color: var(--payment-ink);
            text-align: right;
        }

        .payment-copy-pill {
            margin-top: 18px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(15, 76, 129, 0.08);
            color: var(--payment-primary);
            font-weight: 700;
            border: 1px solid rgba(15, 76, 129, 0.12);
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .payment-copy-pill:hover,
        .payment-copy-pill:focus {
            transform: translateY(-1px);
            background: rgba(15, 76, 129, 0.14);
            color: var(--payment-primary-deep);
        }

        .payment-copy-pill.is-copied {
            background: rgba(37, 167, 84, 0.12);
            color: #1d6f3a;
        }

        .payment-copy-feedback {
            margin-top: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            color: #1d6f3a;
            min-height: 22px;
            opacity: 0;
            transform: translateY(-2px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .payment-copy-feedback.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .payment-step-icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            background: linear-gradient(135deg, rgba(15, 76, 129, 0.12), rgba(244, 179, 33, 0.18));
            color: var(--payment-primary);
            font-size: 1.4rem;
        }

        .payment-primary-btn,
        .payment-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 54px;
            padding: 0 24px;
            border: 0;
            text-decoration: none;
            border-radius: 999px;
            font-weight: 800;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .payment-primary-btn {
            color: #fff;
            background: linear-gradient(135deg, var(--payment-primary), #1b75bb);
            box-shadow: 0 18px 34px rgba(15, 76, 129, 0.25);
        }

        .payment-secondary-btn {
            color: var(--payment-primary-deep);
            background: #F9C449;
            border: 1px solid rgba(15, 76, 129, 0.14);
        }

        .payment-primary-btn:hover,
        .payment-secondary-btn:hover {
            transform: translateY(-2px);
        }

        .payment-primary-btn:hover {
            color: #fff;
        }

        .payment-secondary-btn:hover {
            color: #fff;
        }

        .payment-access-modal .modal-content {
            border: 0;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(10, 45, 75, 0.24);
            background: linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
        }

        .payment-access-top {
            padding: 30px 28px 18px;
            text-align: center;
            color: #fff;
            background:
                linear-gradient(135deg, rgba(10, 45, 75, 0.96), rgba(15, 76, 129, 0.9)),
                url('{{ asset('website/images/banner1.jpeg') }}') center/cover no-repeat;
        }

        .payment-access-icon {
            width: 94px;
            height: 94px;
            margin: 16px auto 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.92);
            color: var(--payment-primary);
            font-size: 2rem;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
        }

        .payment-access-body {
            padding: 28px;
            text-align: center;
        }

        .payment-access-body p {
            color: var(--payment-muted);
            line-height: 1.9;
        }

        .payment-access-actions {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        .payment-access-actions #openExistingLoginModal,
        .payment-access-actions #openExistingLoginModal i {
            color: #000 !important;
        }

        .payment-access-actions #openExistingLoginModal {
            opacity: 1;
        }

        .payment-access-actions #openExistingLoginModal:hover,
        .payment-access-actions #openExistingLoginModal:focus {
            color: #000 !important;
        }

        @media (max-width: 991px) {
            .payment-hero,
            .payment-panel-header,
            .payment-panel-body,
            .payment-help-card {
                padding: 24px;
            }
        }

        @media (max-width: 767px) {
            .payment-shell {
                padding-bottom: 72px;
            }

            .payment-hero,
            .payment-panel-header,
            .payment-panel-body,
            .payment-help-card,
            .payment-method-card,
            .payment-step-card,
            .payment-access-body,
            .payment-notice-card {
                padding: 18px;
            }

            .payment-hero {
                border-radius: 28px;
            }

            .payment-access-actions {
                flex-direction: column;
            }

            .payment-access-actions a,
            .payment-access-actions button,
            .payment-primary-btn,
            .payment-secondary-btn {
                width: 100%;
            }

            .payment-detail-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .payment-detail-row strong {
                text-align: left;
            }
        }
    </style>

    <div class="payment-page">
        <div class="payment-shell">
            <div class="container">
                <section class="payment-hero">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <span class="payment-badge">{{ __('payment.hero_badge') }}</span>
                            <h1 class="payment-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.hero_title') }}</h1>
                            <p class="payment-copy" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.hero_text') }}</p>
                            <div class="d-flex flex-wrap gap-3 mt-4">
                                <a href="{{ route('website.contact') }}" class="payment-primary-btn">
                                    <i class="bi bi-telephone"></i>
                                    {{ __('payment.contact_admin') }}
                                </a>
                                <a href="{{ route('website.become-part') }}" class="payment-secondary-btn">
                                    <i class="bi bi-people"></i>
                                    {{ __('payment.hero_secondary') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row g-3 payment-stat-grid">
                                <div class="col-sm-6">
                                    <div class="payment-stat-card">
                                        <strong>{{ $memberCount }}</strong>
                                        <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.stat_members') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="payment-stat-card">
                                        <strong>Rs {{ number_format($currentMonthCollection) }}</strong>
                                        <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.stat_collection') }}</span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="payment-stat-card">
                                        <strong>{{ __('payment.stat_safe_title') }}</strong>
                                        <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.stat_safe_text') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7">
                        <section class="payment-panel h-100">
                            <div class="payment-panel-header pb-0">
                                <span class="payment-kicker">{{ __('payment.notice_badge') }}</span>
                                <h2 class="payment-heading" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_title') }}</h2>
                                <p class="payment-text" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_text') }}</p>
                            </div>
                            <div class="payment-panel-body pt-4">
                                <div class="payment-notice-card">
                                    <span class="payment-notice-icon"><i class="bi bi-shield-exclamation"></i></span>
                                    <div class="payment-note-list">
                                        <div class="payment-note-item">
                                            <i class="bi bi-phone"></i>
                                            <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_tip_1') }}</span>
                                        </div>
                                        <div class="payment-note-item">
                                            <i class="bi bi-check2-circle"></i>
                                            <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_tip_2') }}</span>
                                        </div>
                                        <div class="payment-note-item">
                                            <i class="bi bi-headset"></i>
                                            <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.notice_tip_3') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-5">
                        <div class="payment-help-card h-100">
                            <span class="payment-kicker">{{ __('payment.steps_badge') }}</span>
                            <h2 class="payment-heading" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.help_title') }}</h2>
                            <p class="payment-text mb-4" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.help_text') }}</p>
                            <a href="{{ route('website.contact') }}" class="payment-primary-btn">
                                <i class="bi bi-chat-dots"></i>
                                {{ __('payment.contact_admin') }}
                            </a>
                        </div>
                    </div>
                </div>

                <section class="mt-4">
                    <div class="payment-panel">
                        <div class="payment-panel-header pb-2">
                            <span class="payment-kicker">{{ __('payment.accounts_badge') }}</span>
                            <h2 class="payment-heading" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.accounts_title') }}</h2>
                            <p class="payment-text" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.accounts_text') }}</p>
                        </div>
                        <div class="payment-panel-body">
                            <div class="row g-4 payment-grid-stretch">
                                @foreach ($paymentChannels as $channel)
                                    <div class="col-md-6 col-xl-3">
                                        <div class="payment-method-card">
                                            <div class="payment-method-top">
                                                <span class="payment-method-logo">
                                                    <img src="{{ asset($channel['logo']) }}" alt="{{ $channel['name'] }}">
                                                </span>
                                                <div>
                                                    <span class="payment-method-chip">{{ $channel['type'] }}</span>
                                                    <h3 class="payment-card-title">{{ $channel['name'] }}</h3>
                                                </div>
                                            </div>
                                            <div class="payment-detail-row">
                                                <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.account_number') }}</span>
                                                <strong>{{ $channel['account'] }}</strong>
                                            </div>
                                            <div class="payment-detail-row">
                                                <span lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.account_holder') }}</span>
                                                <strong>{{ $channel['holder'] }}</strong>
                                            </div>
                                            <button
                                                type="button"
                                                class="payment-copy-pill js-payment-copy"
                                                data-account="{{ $channel['account'] }}"
                                                data-copy-label="{{ __('payment.copy_label') }}"
                                                data-copied-label="{{ $locale === 'ur' ? 'اکاؤنٹ نمبر کاپی ہو گیا' : __('payment.copied_label') }}"
                                                data-success-message="{{ $locale === 'ur' ? 'نمبر کامیابی سے کاپی ہو گیا' : __('payment.copy_success_message') }}"
                                                aria-label="{{ __('payment.copy_label') }}"
                                            >
                                                <i class="bi bi-copy"></i>
                                                <span class="js-payment-copy-label" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.copy_label') }}</span>
                                            </button>
                                            <div class="payment-copy-feedback js-payment-copy-feedback" lang="{{ $locale }}" dir="{{ $dir }}"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mt-4">
                    <div class="row g-4 payment-grid-stretch">
                        <div class="col-md-4">
                            <div class="payment-step-card">
                                <span class="payment-step-icon"><i class="bi bi-phone"></i></span>
                                <h3 class="payment-card-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_1_title') }}</h3>
                                <p class="payment-text mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_1_text') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-step-card">
                                <span class="payment-step-icon"><i class="bi bi-credit-card"></i></span>
                                <h3 class="payment-card-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_2_title') }}</h3>
                                <p class="payment-text mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_2_text') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-step-card">
                                <span class="payment-step-icon"><i class="bi bi-receipt"></i></span>
                                <h3 class="payment-card-title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_3_title') }}</h3>
                                <p class="payment-text mb-0" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.step_3_text') }}</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @guest
        <div class="modal fade payment-access-modal" id="paymentAccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="payment-access-top">
                        <span class="payment-badge">{{ __('payment.guest_popup_badge') }}</span>
                        <div class="payment-access-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>
                    <div class="payment-access-body">
                        <h3 class="payment-heading mb-3" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.login_required_title') }}</h3>
                        <p lang="{{ $locale }}" dir="{{ $dir }}">{{ __('payment.login_required_text') }}</p>
                        <div class="payment-access-actions">
                            <button type="button" class="payment-primary-btn" id="openExistingLoginModal">
                                <i class="bi bi-box-arrow-in-right"></i>
                                {{ __('payment.login_button') }}
                            </button>
                            <a href="{{ route('website.become-part') }}" class="payment-secondary-btn">
                                <i class="bi bi-person-plus"></i>
                                {{ __('payment.become_part_button') }}
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
                        var accessModal = bootstrap.Modal.getOrCreateInstance(accessModalEl);
                        var loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                        accessModal.hide();
                        setTimeout(function() {
                            loginModal.show();
                        }, 220);
                    });
                }
            });
        </script>
    @endguest

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var copyButtons = document.querySelectorAll('.js-payment-copy');

            function showCopyState(button, isSuccess) {
                var label = button.querySelector('.js-payment-copy-label');
                var feedback = button.parentElement.querySelector('.js-payment-copy-feedback');
                var defaultLabel = button.getAttribute('data-copy-label');
                var copiedLabel = button.getAttribute('data-copied-label');
                var successMessage = button.getAttribute('data-success-message');

                if (isSuccess) {
                    button.classList.add('is-copied');
                    if (label) {
                        label.textContent = copiedLabel;
                    }
                    if (feedback) {
                        feedback.textContent = successMessage;
                        feedback.classList.add('is-visible');
                    }

                    window.setTimeout(function() {
                        button.classList.remove('is-copied');
                        if (label) {
                            label.textContent = defaultLabel;
                        }
                        if (feedback) {
                            feedback.classList.remove('is-visible');
                            feedback.textContent = '';
                        }
                    }, 1800);
                }
            }

            function fallbackCopy(text) {
                var textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.setAttribute('readonly', '');
                textArea.style.position = 'fixed';
                textArea.style.top = '-9999px';
                textArea.style.left = '-9999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                textArea.setSelectionRange(0, textArea.value.length);
                var copied = document.execCommand('copy');
                document.body.removeChild(textArea);
                return copied;
            }

            copyButtons.forEach(function(button) {
                button.addEventListener('click', async function() {
                    var accountNumber = button.getAttribute('data-account');

                    try {
                        var copied = false;

                        if (navigator.clipboard && window.isSecureContext) {
                            await navigator.clipboard.writeText(accountNumber);
                            copied = true;
                        } else {
                            copied = fallbackCopy(accountNumber);
                        }

                        if (copied) {
                            showCopyState(button, true);
                        }
                    } catch (error) {
                        var fallbackWorked = fallbackCopy(accountNumber);
                        if (fallbackWorked) {
                            showCopyState(button, true);
                        } else {
                            console.error('Unable to copy account number.', error);
                        }
                    }
                });
            });
        });
    </script>
@endsection
