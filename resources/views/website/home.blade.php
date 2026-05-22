<!DOCTYPE html>
<html class="wide" lang="{{ app()->getLocale() }}">

<head>
    @yield('title')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" type="image/png" href="{{ asset('website/images/6.png') }}">
    <link rel="stylesheet" type="text/css"
        href="//fonts.googleapis.com/css?family=Poppins:300,300i,400,500,600,700,800,900,900i%7CRoboto:400%7CRubik:100,400,700">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('website/css/welfare-theme.css') }}">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- ✅ Put this in your <head> once (layout/app.blade.php) -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;700&family=Lateef&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                duration: 1200,
                once: true,
                offset: 120
            });
        });
    </script>

    <style>
        .ie-panel {
            display: none;
            background: #212121;
            padding: 10px 0;
            box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, .3);
            clear: both;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        html.ie-10 .ie-panel,
        html.lt-ie-10 .ie-panel {
            display: block;
        }
    </style>

    {{-- Layout styles: public/website/css/welfare-theme.css --}}


</head>

<body>
    <div class="welfare-strip">
        <i class="bi bi-heart-fill me-1"></i>
        {{ app()->getLocale() === 'ur' ? 'فروکہ برادرز ویلفیئر سوسائٹی — مل کر خدمت، مل کر بھلائی' : 'Farooka Brothers Welfare Society — Serving community together' }}
        &nbsp;·&nbsp;
        <a href="{{ route('website.donate') }}">{{ __('web.donation') }}</a>
    </div>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-light site-header__nav">
            <div class="container-fluid site-header__inner">

                <a class="navbar-brand site-header__brand" href="{{ route('website.index') }}">
                    <span class="site-header__logo-wrap">
                        <img src="{{ asset('website/images/7.png') }}" alt="FBWS Logo">
                    </span>
                    <span class="site-header__brand-text">
                        <strong>FBWS</strong>
                        <small>{{ app()->getLocale() === 'ur' ? 'ویلفیئر سوسائٹی' : 'Welfare Society' }}</small>
                    </span>
                </a>

                <div class="site-header__actions">
                    <div class="dropdown">
                        <button class="header-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-language"></i>
                            <span class="d-none d-sm-inline">{{ strtoupper(app()->getLocale()) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('lang.switch', 'en') }}">
                                    <span>🇬🇧</span><span>English</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('lang.switch', 'ur') }}">
                                    <span>🇵🇰</span><span>اردو</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="header-profile-btn" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Account menu">
                            <img src="{{ Auth::user() && Auth::user()->profile_photo
                                ? asset(Auth::user()->profile_photo)
                                : asset('website/images/profile.png') }}"
                                class="header-avatar" alt="Profile">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if (Auth::check())
                                <li>
                                    <a class="dropdown-item" href="{{ route('website.profile') }}">
                                        <i class="bi bi-person me-2"></i>{{ __('web.my_profile') }}
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('web.logout') }}
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item js-login-modal-trigger" href="#"
                                        data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('web.login') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <button class="navbar-toggler site-header__toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="site-header__toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse site-header__menu" id="navbarMain">
                    <ul class="navbar-nav site-header__links">

                    <li class="nav-item">
                        @auth
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                {{ __('web.dashboard') }}
                            </a>
                        @else
                            <a class="nav-link js-login-modal-trigger" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                {{ __('web.dashboard') }}
                            </a>
                        @endauth
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.index') ? 'active' : '' }}"
                            href="{{ route('website.index') }}">
                            {{ __('web.home') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.about') ? 'active' : '' }}"
                            href="{{ route('website.about') }}">
                            {{ __('web.about') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.team') ? 'active' : '' }}"
                            href="{{ route('website.team') }}">
                            {{ __('web.team') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.donate') ? 'active' : '' }}"
                            href="{{ route('website.donate') }}">
                            {{ __('web.donation') }}
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.item') ? 'active' : '' }}"
                            href="{{ route('website.item') }}">
                            {{ __('web.items') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.monthly-report') ? 'active' : '' }}"
                            href="{{ route('website.monthly-report') }}">
                            {{ __('web.monthly_report') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.event') ? 'active' : '' }}"
                            href="{{ route('website.event') }}">
                            {{ __('web.events') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.role') ? 'active' : '' }}"
                            href="{{ route('website.role') }}">
                            {{ __('web.condition') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.gallery') ? 'active' : '' }}"
                            href="{{ route('website.gallery') }}">
                            {{ __('web.gallery') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.help-center') ? 'active' : '' }}"
                            href="{{ route('website.help-center') }}">
                            {{ __('web.help_center') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.become-part') ? 'active' : '' }}"
                            href="{{ route('website.become-part') }}">
                            {{ __('web.become_part') }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('website.contact') ? 'active' : '' }}"
                            href="{{ route('website.contact') }}">
                            {{ __('web.contacts') }}
                        </a>
                    </li>

                </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- LOGIN MODAL -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content login-modal-card shadow-lg border-0 overflow-hidden">

                <div class="modal-header login-modal-header border-0 position-relative">
                    <div class="w-100 text-center">
                        <span class="login-modal-badge">FBWS Member Access</span>
                        <div class="login-modal-logo-wrap mx-auto">
                            <img src="{{ asset('website/images/7.png') }}" alt="FBWS Logo" class="login-modal-logo-main">
                        </div>
                    </div>

                    <button type="button" class="login-modal-close position-absolute end-0 top-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="modal-body login-modal-body px-4 pb-4">

                    <h4 class="text-center mb-2 fw-bold text-dark">Member Login</h4>
                    <p class="text-center text-muted mb-4">Use your FBWS ID card number and password to continue.</p>

                    @if ($errors->has('id_card') || $errors->has('password'))
                        <div class="alert alert-danger small mb-3 rounded-4" role="alert">
                            <div class="fw-semibold mb-1">Please correct the following:</div>
                            <ul class="mb-0 ps-3">
                                @error('id_card')
                                    <li><strong>ID Card:</strong> {{ $message }}</li>
                                @enderror
                                @error('password')
                                    <li><strong>Password:</strong> {{ $message }}</li>
                                @enderror
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        @if (request()->routeIs('website.payment'))
                            <input type="hidden" name="redirect_to" value="{{ route('website.payment') }}">
                        @endif

                        <!-- ID CARD -->
                        <div class="form-floating mb-3 login-floating">
                            <input type="text" class="form-control login-input @error('id_card') is-invalid @enderror"
                                id="id_card" name="id_card" value="{{ old('id_card') }}"
                                placeholder="Enter ID Card">

                            <label for="id_card">
                                <i class="bi bi-person-vcard me-1"></i>
                                ID Card Number
                            </label>

                            @error('id_card')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="form-floating mb-1 position-relative login-floating">
                            <input type="password" class="form-control login-input pe-5 @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password">

                            <label for="password">
                                <i class="bi bi-lock me-1"></i>
                                Password
                            </label>

                            <!-- Eye Icon -->
                            <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 login-eye"
                                id="togglePassword" style="cursor:pointer;"></i>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Live Password Preview -->
                        <small class="text-muted d-block mb-4 login-preview">
                            Typed Password: <span id="passwordPreview" class="fw-semibold text-primary"></span>
                        </small>

                        <button class="btn login-submit-btn w-100 py-3 fw-semibold rounded-4">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Login
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const toggle = document.getElementById("togglePassword");
            const password = document.getElementById("password");
            const preview = document.getElementById("passwordPreview");
            const loginTriggers = document.querySelectorAll(".js-login-modal-trigger");
            const navbarMain = document.getElementById("navbarMain");

            loginTriggers.forEach(function(trigger) {
                trigger.addEventListener("click", function() {
                    if (!navbarMain || typeof bootstrap === "undefined" || !bootstrap.Collapse) return;
                    if (window.innerWidth >= 992) return;

                    const collapse = bootstrap.Collapse.getInstance(navbarMain) ||
                        bootstrap.Collapse.getOrCreateInstance(navbarMain, {
                            toggle: false
                        });

                    if (navbarMain.classList.contains("show")) {
                        collapse.hide();
                    }
                });
            });

            if (navbarMain) {
                navbarMain.querySelectorAll(".nav-link").forEach(function(link) {
                    link.addEventListener("click", function() {
                        if (window.innerWidth >= 992) return;
                        if (link.classList.contains("js-login-modal-trigger")) return;
                        if (!navbarMain.classList.contains("show")) return;
                        if (typeof bootstrap === "undefined" || !bootstrap.Collapse) return;
                        var collapse = bootstrap.Collapse.getInstance(navbarMain);
                        if (collapse) collapse.hide();
                    });
                });
            }

            if (password) {
                password.addEventListener("input", function() {
                    preview.textContent = password.value;
                });
            }

            if (toggle) {
                toggle.addEventListener("click", function() {
                    const type = password.type === "password" ? "text" : "password";
                    password.type = type;
                    toggle.classList.toggle("bi-eye");
                    toggle.classList.toggle("bi-eye-slash");
                });
            }

        });
    </script>

    <div id="sitePreloader" class="site-preloader" aria-hidden="true">
        <div>
            <div class="site-preloader__ring">
                <img src="{{ asset('website/images/6.png') }}" alt="FBWS Logo" class="site-preloader__logo">
            </div>
            <div class="site-preloader__caption">FBWS</div>
        </div>
    </div>

    <div class="page">
        @yield('content')

        <!-- Page Footer-->
        <!-- Website Developed by Saif Ali Farooka | BSIT Student - Virtual University Sargodha -->

        <footer class="site-footer">
            <div class="container">

                <div class="row align-items-center text-center text-md-start">

                    <!-- Logo -->
                    <div class="col-md-3 mb-3 mb-md-0 text-center text-md-start">
                        <a href="{{ route('website.index') }}" class="site-footer__logo-link" aria-label="{{ __('web.home') }}">
                            <span class="site-footer__logo-wrap">
                                <img src="{{ asset('website/images/6.png') }}" alt="FBWS Logo" class="site-footer__logo">
                            </span>
                        </a>
                    </div>

                    <!-- Menu -->
                    <div class="col-md-6 mb-3 mb-md-0 text-center">

                        <ul class="list-inline mb-0 footer-links">

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.index') }}">
                                    {{ __('web.home') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.about') }}">
                                    {{ __('web.about') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.team') }}">
                                    {{ __('web.team') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.donate') }}">
                                    {{ __('web.donation') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.item') }}">
                                    {{ __('web.items') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.monthly-report') }}">
                                    {{ __('web.monthly_report') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.event') }}">
                                    {{ __('web.events') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.role') }}">
                                    {{ __('web.condition') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.become-part') }}">
                                    {{ __('web.become_part') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.gallery') }}">
                                    {{ __('web.gallery') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.help-center') }}">
                                    {{ __('web.help_center') }}
                                </a>
                            </li>

                            <li class="list-inline-item">
                                <a class="text-light text-decoration-none" href="{{ route('website.contact') }}">
                                    {{ __('web.contacts') }}
                                </a>
                            </li>

                        </ul>

                    </div>

                    <!-- Right Side -->
                    <div class="col-md-3 text-center text-md-end">
                        <small class="text-white-50">
                            {{ app()->getLocale() === 'ur' ? 'مل کر بھلائی، مل کر خدمت' : 'Together for welfare & community' }}
                        </small>
                    </div>

                </div>

                <!-- Legal Links -->
                <hr class="border-secondary my-3">

                <div class="row text-center">
                    <div class="col-md-12 mb-2 footer-legal">
                        <a class="me-3" href="{{ route('privacy.policy') }}">
                            Privacy Policy
                        </a>

                        <a class="me-3" href="{{ route('terms.page') }}">
                            Terms
                        </a>

                        <a href="{{ route('conditions.page') }}">
                            Conditions
                        </a>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center mt-2">

                    <p class="mb-0 small">
                        © {{ date('Y') }} Farooka Brothers Welfare Society. All rights reserved.
                    </p>

                    <!-- Developer Credit -->
                    <p class="mb-0 small site-footer__dev-credit mt-1">
                        Website developed by <strong>M. Saif Ali</strong> | For website development inquiries:
                        <strong>0327-2000339</strong>
                    </p>

                </div>

            </div>
        </footer>
    </div>

    <div class="snackbars" id="form-output-global"></div>

    @php
        $floatWa = \App\Support\Phone::toWhatsapp((string) env('ADMIN_WHATSAPP_NUMBER', '923012704423'));
        $floatWaLink = $floatWa ? 'https://wa.me/' . $floatWa : null;
    @endphp
    @if ($floatWaLink)
        <a href="{{ $floatWaLink }}" target="_blank" rel="noopener" class="floating-wa-btn"
            aria-label="Chat on WhatsApp">
            <span class="floating-wa-btn__icon"><i class="bi bi-whatsapp"></i></span>
            <span class="floating-wa-btn__text">WhatsApp</span>
        </a>
    @endif

    <script>
        (function() {
            function hideSitePreloader() {
                var el = document.getElementById('sitePreloader');
                if (!el) return;
                el.classList.add('is-hidden');
                window.setTimeout(function() {
                    if (el && el.parentNode) {
                        el.parentNode.removeChild(el);
                    }
                }, 500);
            }

            document.addEventListener('DOMContentLoaded', function() {
                window.setTimeout(hideSitePreloader, 350);
            });

            window.addEventListener('load', function() {
                window.setTimeout(hideSitePreloader, 150);
            });
        })();
    </script>

    <script src="{{ asset('website/js/core.min.js') }}"></script>
    <script src="{{ asset('website/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            if (!document.querySelector('.pagination')) {
                return;
            }

            var mobileMq = window.matchMedia('(max-width: 991px)');

            function desiredPerPage() {
                return mobileMq.matches ? 8 : 9;
            }

            function syncPerPage() {
                var target = desiredPerPage();
                var url = new URL(window.location.href);
                var current = parseInt(url.searchParams.get('per_page') || '9', 10);

                if (current === target) {
                    return;
                }

                url.searchParams.set('per_page', String(target));
                url.searchParams.set('page', '1');
                window.location.replace(url.toString());
            }

            syncPerPage();

            if (typeof mobileMq.addEventListener === 'function') {
                mobileMq.addEventListener('change', syncPerPage);
            } else if (typeof mobileMq.addListener === 'function') {
                mobileMq.addListener(syncPerPage);
            }
        })();
    </script>

    @if ($errors->has('id_card') || $errors->has('password') || request()->boolean('login_modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var el = document.getElementById('loginModal');
                if (el && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        </script>
    @endif

    @if (false)
        <script src="{{ asset('assets/js/AlertReceive.js') }}"></script>
    @endif

    {{-- Same-page image lightbox (overlay). Excludes nav/footer brand. Class no-img-newtab to opt out. --}}
    <div id="website-img-lightbox" class="website-img-lightbox" role="dialog" aria-modal="true" aria-hidden="true"
        hidden>
        <button type="button" class="website-img-lightbox__close" aria-label="Close">&times;</button>
        <div class="website-img-lightbox__backdrop" data-lightbox-close></div>
        <button type="button" class="website-img-lightbox__nav website-img-lightbox__nav--prev" data-lightbox-prev
            aria-label="Previous image">&#10094;</button>
        <button type="button" class="website-img-lightbox__nav website-img-lightbox__nav--next" data-lightbox-next
            aria-label="Next image">&#10095;</button>
        <div class="website-img-lightbox__toolbar">
            <button type="button" class="website-img-lightbox__tool" data-lightbox-zoom-in aria-label="Zoom in">
                <i class="bi bi-zoom-in"></i>
            </button>
            <button type="button" class="website-img-lightbox__tool" data-lightbox-zoom-out aria-label="Zoom out">
                <i class="bi bi-zoom-out"></i>
            </button>
            <button type="button" class="website-img-lightbox__tool" data-lightbox-zoom-reset aria-label="Reset zoom">
                100%
            </button>
        </div>
        <div class="website-img-lightbox__frame">
            <img class="website-img-lightbox__img" src="" alt="">
        </div>
    </div>

    <script>
        (function() {
            var lb = document.getElementById('website-img-lightbox');
            if (!lb) return;
            var lbImg = lb.querySelector('.website-img-lightbox__img');
            var closeBtn = lb.querySelector('.website-img-lightbox__close');
            var prevBtn = lb.querySelector('[data-lightbox-prev]');
            var nextBtn = lb.querySelector('[data-lightbox-next]');
            var zoomInBtn = lb.querySelector('[data-lightbox-zoom-in]');
            var zoomOutBtn = lb.querySelector('[data-lightbox-zoom-out]');
            var zoomResetBtn = lb.querySelector('[data-lightbox-zoom-reset]');
            var zoomLevel = 1;
            var minZoom = 1;
            var maxZoom = 3;
            var images = [];
            var currentIndex = -1;

            function getEligibleImages() {
                return Array.prototype.slice.call(document.querySelectorAll('.page img:not(.no-img-newtab)'))
                    .filter(function(img) {
                        if (!img || img.tagName !== 'IMG') return false;
                        if (img.closest('nav.navbar')) return false;
                        if (img.closest('footer a')) return false;
                        var src = img.currentSrc || img.src || img.getAttribute('src');
                        if (!src || String(src).indexOf('data:') === 0) return false;
                        return true;
                    });
            }

            function applyZoom() {
                lbImg.style.transform = 'scale(' + zoomLevel + ')';
            }

            function setZoom(value) {
                zoomLevel = Math.min(maxZoom, Math.max(minZoom, value));
                applyZoom();
            }

            function renderCurrentImage() {
                if (currentIndex < 0 || currentIndex >= images.length) return;
                var img = images[currentIndex];
                var src = img.currentSrc || img.src || img.getAttribute('src');
                lbImg.src = src;
                lbImg.alt = img.getAttribute('alt') || '';
                setZoom(1);
            }

            function openLightbox(index) {
                images = getEligibleImages();
                currentIndex = index;
                renderCurrentImage();
                lb.removeAttribute('hidden');
                lb.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function goTo(step) {
                if (!images.length) return;
                currentIndex = (currentIndex + step + images.length) % images.length;
                renderCurrentImage();
            }

            function closeLightbox() {
                lb.setAttribute('hidden', '');
                lb.setAttribute('aria-hidden', 'true');
                lbImg.removeAttribute('src');
                lbImg.alt = '';
                setZoom(1);
                document.body.style.overflow = '';
            }

            document.body.addEventListener('click', function(e) {
                var img = e.target.closest('img');
                if (!img || img.tagName !== 'IMG') return;
                if (img.classList.contains('no-img-newtab')) return;
                if (img.closest('nav.navbar')) return;
                if (img.closest('footer a')) return;
                if (img.closest('#website-img-lightbox')) return;
                var eligible = getEligibleImages();
                var index = eligible.indexOf(img);
                if (index === -1) return;

                e.preventDefault();
                e.stopPropagation();
                openLightbox(index);
            }, true);

            closeBtn.addEventListener('click', closeLightbox);
            prevBtn.addEventListener('click', function() {
                goTo(-1);
            });
            nextBtn.addEventListener('click', function() {
                goTo(1);
            });
            zoomInBtn.addEventListener('click', function() {
                setZoom(zoomLevel + 0.25);
            });
            zoomOutBtn.addEventListener('click', function() {
                setZoom(zoomLevel - 0.25);
            });
            zoomResetBtn.addEventListener('click', function() {
                setZoom(1);
            });
            lb.querySelectorAll('[data-lightbox-close]').forEach(function(el) {
                el.addEventListener('click', closeLightbox);
            });

            var frame = lb.querySelector('.website-img-lightbox__frame');
            if (frame) {
                frame.addEventListener('click', function(e) {
                    if (e.target === frame) closeLightbox();
                });
            }

            document.addEventListener('keydown', function(e) {
                if (lb.hasAttribute('hidden')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') goTo(-1);
                if (e.key === 'ArrowRight') goTo(1);
                if (e.key === '+' || e.key === '=') setZoom(zoomLevel + 0.25);
                if (e.key === '-') setZoom(zoomLevel - 0.25);
            });
        })();
    </script>
</body>

</html>


