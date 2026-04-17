<!DOCTYPE html>
<html class="wide" lang="{{ app()->getLocale() }}">

<head>
    @yield('title')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" type="image/png" href="{{ asset('website/images/6.png') }}">
    <link rel="stylesheet" type="text/css"
        href="//fonts.googleapis.com/css?family=Poppins:300,300i,400,500,600,700,800,900,900i%7CRoboto:400%7CRubik:100,400,700">

    <link rel="stylesheet" href="{{ asset('website/css/bootstrap.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('website/css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}">
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

    <style>
        /* Force content visible (temp fix) */
        html,
        body,
        .page {
            opacity: 1 !important;
            visibility: visible !important;
        }

        /* WOW / animations often hide content before init */
        .wow,
        .wow-outer,
        [class*="wow"] {
            visibility: visible !important;
            opacity: 1 !important;
            animation: none !important;
        }

        /* If any preloader/overlay is blocking */
        .preloader,
        .rd-navbar-wrap .rd-navbar-preloader,
        .overlay,
        .page-loader {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
    </style>

    <style>
        @media (max-width: 768px) {}
    </style>

    {{-- ✅ NEW: User dropdown in header (right side) --}}


</head>

<body>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .site-preloader {
            position: fixed;
            inset: 0;
            z-index: 5000;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top, rgba(252, 183, 20, 0.18), transparent 34%),
                linear-gradient(145deg, rgba(9, 28, 48, 0.98), rgba(16, 36, 62, 0.96));
            transition: opacity .4s ease, visibility .4s ease;
        }

        .site-preloader.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .site-preloader__ring {
            position: relative;
            width: 152px;
            height: 152px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(252, 183, 20, 0.95), rgba(247, 114, 30, 0.92));
            box-shadow:
                0 0 0 14px rgba(255, 255, 255, 0.06),
                0 28px 58px rgba(3, 10, 18, 0.35);
            animation: sitePreloaderPulse 1.8s ease-in-out infinite;
        }

        .site-preloader__ring::before {
            content: "";
            position: absolute;
            inset: -16px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.22);
            animation: sitePreloaderSpin 3s linear infinite;
        }

        .site-preloader__logo {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            background: #fff;
            padding: 12px;
            object-fit: contain;
            box-shadow: 0 12px 26px rgba(16, 36, 62, 0.18);
        }

        .site-preloader__caption {
            margin-top: 24px;
            text-align: center;
            color: rgba(255, 255, 255, 0.92);
            font-family: var(--font-en);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        @keyframes sitePreloaderPulse {
            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.04);
            }
        }

        @keyframes sitePreloaderSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .page {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
        }

        .page footer {
            margin-top: auto !important;
        }

        :root {
            --font-en: "Plus Jakarta Sans", "Inter", "Segoe UI", Roboto, Arial, sans-serif;
        }

        /* Urdu Font */
        :lang(ur) {
            font-family: 'Noto Nastaliq Urdu', 'Lateef', serif !important;
        }

        /* Keep icon fonts intact in Urdu mode */
        .bi,
        [class^="bi-"],
        [class*=" bi-"] {
            font-family: "bootstrap-icons" !important;
            font-style: normal;
            font-weight: normal;
            line-height: 1;
        }

        .fa,
        .fas,
        .far,
        .fab,
        [class^="fa-"],
        [class*=" fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }

        .ti,
        [class^="ti-"],
        [class*=" ti-"] {
            font-family: "tabler-icons" !important;
            font-style: normal;
            font-weight: normal;
            line-height: 1;
        }

        /* English Font */
        :lang(en) {
            font-family: var(--font-en) !important;
        }

        /* Headings English */
        h1:lang(en),
        h2:lang(en),
        h3:lang(en) {
            font-family: var(--font-en) !important;
            font-weight: 700;
        }

        /* Paragraph English */
        p:lang(en),
        span:lang(en),
        li:lang(en) {
            font-family: var(--font-en) !important;
        }

        /* Global English override for consistent professional typography */
        html[lang="en"] body,
        html[lang="en"] h1,
        html[lang="en"] h2,
        html[lang="en"] h3,
        html[lang="en"] h4,
        html[lang="en"] h5,
        html[lang="en"] h6,
        html[lang="en"] p,
        html[lang="en"] span,
        html[lang="en"] a,
        html[lang="en"] li,
        html[lang="en"] button,
        html[lang="en"] input,
        html[lang="en"] select,
        html[lang="en"] textarea,
        html[lang="en"] label {
            font-family: var(--font-en) !important;
        }

        /* Keep Urdu-tagged dynamic content in Urdu font even on English pages */
        html[lang="en"] [lang="ur"],
        html[lang="en"] [lang="ur"] * {
            font-family: 'Noto Nastaliq Urdu', 'Lateef', serif !important;
        }

        /* Unified pagination design for website blades */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 0;
            padding-left: 0;
        }

        .page-item .page-link {
            border: 1px solid rgba(16, 36, 62, 0.14);
            color: #10243e;
            border-radius: 12px;
            min-width: 46px;
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            font-weight: 700;
            line-height: 1;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(16, 36, 62, 0.08);
            white-space: nowrap;
        }

        .page-item .page-link:hover {
            color: #10243e;
            border-color: #FCB714;
            background: #fff5d6;
        }

        .page-item.active .page-link {
            background: #FCB714;
            border-color: #FCB714;
            color: #1f2937;
            box-shadow: 0 12px 24px rgba(252, 183, 20, 0.28);
        }

        .page-item.disabled .page-link {
            opacity: .65;
            color: #7b8794;
            background: #f5f7fa;
            border-color: rgba(16, 36, 62, 0.08);
            box-shadow: none;
        }

        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            min-width: 110px;
        }

        @media (max-width: 767px) {
            .pagination {
                gap: 6px;
            }

            .page-item .page-link {
                min-width: 42px;
                min-height: 42px;
                padding: 0 12px;
                border-radius: 10px;
                font-size: 14px;
            }

            .page-item:first-child .page-link,
            .page-item:last-child .page-link {
                min-width: 96px;
            }
        }

        body {
            background: linear-gradient(180deg, #f7fbff 0%, #ffffff 40%, #f7fbff 100%);
            color: #10243e;
        }

        ::selection {
            background: rgba(252, 183, 20, 0.88);
            color: #10243e;
        }

        ::-moz-selection {
            background: rgba(252, 183, 20, 0.88);
            color: #10243e;
        }

        .site-header {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 3000 !important;
            width: 100%;
            background: rgba(9, 28, 48, 0.92) !important;
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 40px rgba(5, 16, 28, 0.18);
            transform: none !important;
        }

        .page {
            padding-top: 96px;
        }

        .site-header .navbar-brand img {
            height: 54px !important;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.18));
        }

        .site-header__inner {
            max-width: 1720px;
            padding-left: 28px;
            padding-right: 28px;
            gap: 14px;
        }

        .site-header .navbar-brand,
        .site-header .site-header__actions {
            flex-shrink: 0;
        }

        .site-header .navbar-collapse {
            flex: 1 1 auto;
            min-width: 0;
        }

        .site-header .navbar-nav {
            gap: 6px;
        }

        .site-header .nav-link {
            color: rgba(255, 255, 255, 0.84) !important;
            font-weight: 600;
            font-size: 0.98rem;
            margin: 0;
            padding: 11px 11px !important;
            border-radius: 999px;
            text-align: center;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        .site-header .nav-link:hover,
        .site-header .nav-link:focus {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        .site-header .nav-link.active {
            color: #10243e !important;
            background: linear-gradient(135deg, #fcb714, #ffd56f);
            box-shadow: 0 10px 20px rgba(252, 183, 20, 0.28);
        }

        .site-header .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: none !important;
        }

        .header-action-btn {
            min-height: 42px;
            border-radius: 999px !important;
            padding: 0 14px !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            background: rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
            font-weight: 700;
        }

        .header-action-btn:hover,
        .header-action-btn:focus {
            background: rgba(255, 255, 255, 0.16) !important;
            color: #fff !important;
        }

        .header-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .site-footer {
            background:
                radial-gradient(circle at top left, rgba(252, 183, 20, 0.18), transparent 20%),
                linear-gradient(135deg, #091a2c 0%, #0f2b47 55%, #12385c 100%);
            color: #eaf1f8;
            padding: 48px 0 28px;
            margin-top: 24px;
        }

        .site-footer .footer-links {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .site-footer .footer-links a {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            display: inline-block;
        }

        .site-footer .footer-links a:hover {
            color: #10243e;
            background: #fcb714;
        }

        .site-footer .footer-legal a {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
        }

        .site-footer .footer-legal a:hover {
            color: #fcb714;
        }

        .site-page-shell {
            padding: 16px 0 88px;
        }

        .site-page-hero {
            position: relative;
            overflow: hidden;
            border-radius: 34px;
            padding: 54px 36px;
            margin-bottom: 34px;
            background:
                linear-gradient(135deg, rgba(9, 26, 44, 0.92), rgba(18, 56, 92, 0.88)),
                url('{{ asset('website/images/banner1.jpeg') }}') center/cover no-repeat;
            color: #fff;
            box-shadow: 0 24px 60px rgba(12, 33, 56, 0.18);
            text-align: center;
        }

        .site-page-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(252, 183, 20, 0.22), transparent 24%),
                radial-gradient(circle at bottom left, rgba(255, 255, 255, 0.08), transparent 30%);
            pointer-events: none;
        }

        .site-page-hero > * {
            position: relative;
            z-index: 1;
        }

        .site-page-hero__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .site-page-hero__eyebrow::before {
            content: "";
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fcb714;
            box-shadow: 0 0 0 5px rgba(252, 183, 20, 0.18);
        }

        .site-page-hero__title {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 900;
            line-height: 1.12;
            margin: 18px 0 12px;
            color: #fff;
        }

        .site-page-hero__title[lang="ur"] {
            line-height: 1.65;
        }

        .site-page-hero__copy {
            max-width: 760px;
            margin: 0 auto;
            color: rgba(255, 255, 255, 0.82);
            font-size: 1.03rem;
            line-height: 1.9;
            white-space: pre-line;
        }

        .site-page-hero__copy[lang="ur"] {
            line-height: 2.1;
        }

        .site-panel {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(16, 36, 62, 0.08);
            border-radius: 28px;
            box-shadow: 0 24px 50px rgba(16, 36, 62, 0.08);
        }

        .site-panel.soft-panel {
            background: linear-gradient(180deg, #fbfdff 0%, #f4f8fc 100%);
        }

        .site-panel-body {
            padding: 30px;
        }

        .site-section-title {
            font-size: clamp(1.6rem, 2.5vw, 2.5rem);
            font-weight: 800;
            line-height: 1.2;
            color: #10243e;
            margin-bottom: 12px;
        }

        .site-section-title[lang="ur"] {
            line-height: 1.7;
        }

        .site-section-copy {
            color: #5f7187;
            line-height: 1.85;
            white-space: pre-line;
        }

        .site-section-copy[lang="ur"] {
            line-height: 2.1;
        }

        .site-grid-stretch {
            display: flex;
            flex-wrap: wrap;
        }

        .site-grid-stretch > div {
            display: flex;
        }

        .site-content-card {
            width: 100%;
            background: #fff;
            border: 1px solid rgba(16, 36, 62, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 38px rgba(16, 36, 62, 0.08);
            overflow: hidden;
            height: 100%;
        }

        .site-content-card__media {
            min-height: 230px;
            background: linear-gradient(180deg, #edf5ff 0%, #f8fbff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .site-content-card__media img,
        .site-content-card__media video,
        .site-content-card__media iframe {
            width: 100%;
            height: 230px;
            object-fit: cover;
            display: block;
            border: 0;
        }

        .site-content-card__media.media-fit img,
        .site-content-card__media.media-fit video {
            object-fit: contain;
            padding: 16px;
        }

        .site-content-card__body {
            padding: 24px;
        }

        .donation-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 10px;
            min-height: 100%;
            padding: 34px 28px;
        }

        .donation-card .donation-logo {
            width: 90px;
            height: 90px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #ffffff 0%, #f7faff 100%);
            border: 1px solid rgba(16, 36, 62, 0.08);
            box-shadow: 0 12px 26px rgba(16, 36, 62, 0.08);
            margin: 0 auto 12px;
        }

        .donation-card .donation-logo img {
            max-width: 60px;
            max-height: 60px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .donation-card .donation-label,
        .donation-card .card-title,
        .donation-card .card-copy,
        .donation-card .card-meta {
            text-align: center;
            width: 100%;
        }

        .donation-card .card-title {
            margin-bottom: 0 !important;
        }

        .donation-card .card-copy,
        .donation-card .card-meta {
            margin-bottom: 0;
        }

        .site-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(27, 117, 187, 0.1);
            color: #12527f;
            font-size: 13px;
            font-weight: 700;
        }

        .site-primary-btn,
        .site-secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 52px;
            padding: 0 22px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            border: none;
        }

        .site-primary-btn {
            background: linear-gradient(135deg, #1b75bb, #2c91dc);
            color: #fff;
            box-shadow: 0 16px 32px rgba(27, 117, 187, 0.22);
        }

        .site-secondary-btn {
            background: linear-gradient(135deg, #fcb714, #ffd56f);
            color: #10243e;
        }

        .site-empty {
            width: min(100%, 560px);
            margin: 0 auto;
            min-height: 180px;
            padding: 32px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 24px;
            background: linear-gradient(180deg, #fbfdff 0%, #f5f8fb 100%);
            border: 1px dashed rgba(16, 36, 62, 0.18);
            color: #5f7187;
            font-size: 1.05rem;
            font-weight: 700;
        }

        @media (max-width: 991px) {
            .site-header__inner {
                max-width: 100%;
                padding-left: 14px;
                padding-right: 14px;
                gap: 10px;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
            }

            .site-header .navbar-brand {
                order: 1;
                margin-right: 0;
            }

            .site-header .navbar-toggler {
                order: 2;
                margin-left: auto;
            }

            .site-header .site-header__actions {
                order: 3;
                margin-left: 12px !important;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .site-header .navbar-collapse {
                order: 4;
                width: 100%;
                flex-basis: 100%;
                flex-grow: 0;
                flex-shrink: 0;
                margin-top: 14px;
                padding: 16px;
                border-radius: 22px;
                background: rgba(255, 255, 255, 0.06);
                min-width: 100%;
            }

            .site-header .navbar-collapse.show,
            .site-header .navbar-collapse.collapsing {
                display: block;
            }

            .site-header .navbar-nav {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .site-header .nav-item {
                width: 100%;
            }

            .site-header .nav-link {
                width: 100%;
                white-space: normal;
                padding: 12px 16px !important;
            }

            .page {
                padding-top: 90px;
            }

            .site-page-hero {
                padding: 42px 24px;
                border-radius: 28px;
            }

            .site-panel-body {
                padding: 24px;
            }
        }

        @media (max-width: 767px) {
            .site-page-shell {
                padding-bottom: 72px;
            }

            .site-header .site-header__actions {
                width: auto;
                justify-content: flex-end;
            }

            .site-page-hero {
                background:
                    linear-gradient(135deg, rgba(9, 26, 44, 0.94), rgba(18, 56, 92, 0.88)),
                    url('{{ asset('website/images/mobile1.jpeg') }}') center/cover no-repeat;
            }

            .site-content-card__media,
            .site-content-card__media img,
            .site-content-card__media video,
            .site-content-card__media iframe {
                min-height: 210px;
                height: 210px;
            }
        }

        @media (min-width: 992px) and (max-width: 1399px) {
            .site-header__inner {
                padding-left: 20px;
                padding-right: 20px;
            }

            .site-header .navbar-collapse {
                flex: 1 1 auto;
                min-width: 0;
            }

            .site-header .navbar-nav {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: nowrap;
                gap: 2px;
                width: 100%;
            }

            .site-header .nav-item {
                flex: 0 0 auto;
            }

            .site-header .nav-link {
                font-size: 0.9rem;
                padding: 10px 8px !important;
                white-space: nowrap;
            }

            .header-action-btn {
                min-height: 40px;
                padding: 0 12px !important;
            }
        }

        @media (min-width: 1400px) {
            .site-header .navbar-collapse {
                flex: 1 1 auto;
                min-width: 0;
            }

            .site-header .navbar-nav {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: nowrap;
                gap: 6px;
                width: 100%;
            }

            .site-header .nav-item {
                flex: 0 0 auto;
            }

            .site-header .nav-link {
                white-space: nowrap;
            }
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top site-header">
        <div class="container-fluid site-header__inner">

            <!-- Logo LEFT -->
            <a class="navbar-brand" href="{{ route('website.index') }}">
                <img src="{{ asset('website/images/7.png') }}" alt="Logo"
                    style="height:50px;width:auto;object-fit:contain;">
            </a>

            <!-- Mobile toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- CENTER MENU -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarMain">
                <ul class="navbar-nav">

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

            <!-- RIGHT SIDE (Language + User Icon) -->
            <div class="d-flex align-items-center gap-2 ms-lg-3 site-header__actions">

                <!-- Language Switch -->
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle d-flex align-items-center header-action-btn"
                        data-bs-toggle="dropdown">

                        <i class="fa-solid fa-language me-2"></i>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ route('lang.switch', 'en') }}">
                                🇬🇧 <span class="ms-2">English</span>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ route('lang.switch', 'ur') }}">
                                🇵🇰 <span class="ms-2">اردو</span>
                            </a>
                        </li>

                    </ul>
                </div>

                <!-- User Icon -->
                <div class="dropdown">
                    <button class="btn p-0 border-0 bg-transparent dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user() && Auth::user()->profile_photo
                            ? asset(Auth::user()->profile_photo)
                            : asset('website/images/profile.png') }}"
                            class="header-avatar">
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        @if (Auth::check())
                            <li>
                                <a class="dropdown-item" href="{{ route('website.profile') }}">
                                    {{ __('web.my_profile') }}
                                </a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item">{{ __('web.logout') }}</button>
                                </form>
                            </li>
                        @else
                            <li>
                                <a class="dropdown-item js-login-modal-trigger" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    {{ __('web.login') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </nav>
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

                    <button type="button" class="btn-close login-modal-close position-absolute end-0 top-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close">
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

    <!-- Optional: Custom Styles -->
    <style>
        #loginModal .modal-dialog {
            max-width: 680px;
        }

        .login-modal-card {
            border-radius: 32px !important;
            background:
                radial-gradient(circle at top right, rgba(252, 183, 20, 0.18), transparent 18%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 30px 80px rgba(12, 33, 56, 0.26) !important;
        }

        .login-modal-header {
            padding: 28px 28px 18px;
            background:
                linear-gradient(135deg, rgba(9, 26, 44, 0.96), rgba(18, 56, 92, 0.9)),
                url('{{ asset('website/images/banner1.jpeg') }}') center/cover no-repeat;
        }

        .login-modal-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .login-modal-logo-wrap {
            width: 118px;
            height: 118px;
            margin-top: 18px;
            border-radius: 50%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.22);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-modal-logo-main {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        .login-modal-close {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            padding: 10px;
            opacity: 1;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.14);
        }

        .login-modal-close:hover {
            background-color: #ffffff;
        }

        .login-modal-body {
            padding-top: 26px;
            padding-left: 34px !important;
            padding-right: 34px !important;
            padding-bottom: 34px !important;
        }

        .login-floating .form-label {
            color: #5f7187;
            font-weight: 600;
        }

        .login-input {
            min-height: 70px;
            border-radius: 18px !important;
            border: 1px solid rgba(16, 36, 62, 0.14);
            background: linear-gradient(180deg, #f8fbff 0%, #eef5fd 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        .login-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(27, 117, 187, 0.16);
            border-color: #1b75bb;
            background: #fff;
        }

        .login-eye {
            color: #5f7187;
            font-size: 18px;
        }

        .login-preview {
            padding-left: 4px;
        }

        .login-submit-btn {
            background: linear-gradient(135deg, #1b75bb, #2c91dc);
            border: none;
            color: #fff;
            font-size: 1.05rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            box-shadow: 0 18px 34px rgba(27, 117, 187, 0.24);
        }

        .login-submit-btn:hover {
            color: #fff;
            background: linear-gradient(135deg, #16639f, #247cbd);
        }

        @media (max-width: 767px) {
            .login-modal-header {
                padding: 24px 20px 16px;
            }

            .login-modal-body {
                padding-left: 20px !important;
                padding-right: 20px !important;
                padding-bottom: 24px !important;
            }

            .login-modal-logo-wrap {
                width: 96px;
                height: 96px;
            }

            .login-input {
                min-height: 64px;
            }
        }
    </style>

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
                        <a href="{{ route('website.index') }}">
                            <img src="{{ asset('website/images/6.png') }}"
                                style="height:45px;width:auto;object-fit:contain;">
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
                            Helping community together
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
                    <p class="mb-0 small text-secondary mt-1">
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

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

    <style>
        .floating-wa-btn {
            position: fixed;
            left: 18px;
            bottom: 18px;
            min-width: 66px;
            height: 64px;
            padding: 8px 18px 8px 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #10243e 0%, #173a63 56%, #f7721e 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            z-index: 2050;
            border: 1px solid rgba(255, 255, 255, .85);
            box-shadow: 0 18px 38px rgba(16, 36, 62, .26);
            text-decoration: none;
            transition: transform .22s ease, box-shadow .22s ease, filter .22s ease;
            overflow: hidden;
        }

        .floating-wa-btn::before {
            content: "";
            position: absolute;
            inset: 1px;
            border-radius: inherit;
            background: linear-gradient(135deg, rgba(255, 255, 255, .12), rgba(255, 255, 255, .03));
            pointer-events: none;
        }

        .floating-wa-btn__icon,
        .floating-wa-btn__text {
            position: relative;
            z-index: 1;
        }

        .floating-wa-btn__icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fcb714 0%, #f7721e 100%);
            color: #10243e;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .45), 0 8px 18px rgba(252, 183, 20, .28);
            font-size: 26px;
        }

        .floating-wa-btn__text {
            font-family: var(--font-en);
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .02em;
            white-space: nowrap;
        }

        .floating-wa-btn:hover {
            color: #fff;
            transform: translateY(-3px) scale(1.01);
            filter: brightness(1.04);
            box-shadow: 0 24px 46px rgba(16, 36, 62, .32);
        }

        @media (max-width: 576px) {
            .floating-wa-btn {
                left: 14px;
                bottom: 14px;
                width: 60px;
                min-width: 60px;
                height: 60px;
                padding: 0;
                justify-content: center;
                gap: 0;
                border-radius: 50%;
            }

            .floating-wa-btn__icon {
                width: 44px;
                height: 44px;
                font-size: 24px;
            }

            .floating-wa-btn__text {
                display: none;
            }
        }

        .page img:not(.no-img-newtab) {
            cursor: zoom-in;
        }

        nav.navbar img,
        footer a img,
        .no-img-newtab {
            cursor: inherit;
        }

        nav.navbar .dropdown img {
            cursor: pointer;
        }

        .website-img-lightbox {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            box-sizing: border-box;
        }

        .website-img-lightbox[hidden] {
            display: none !important;
        }

        .website-img-lightbox__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(4px);
        }

        .website-img-lightbox__frame {
            position: relative;
            z-index: 1;
            max-width: min(96vw, 1200px);
            max-height: 90vh;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .website-img-lightbox__img {
            max-width: 100%;
            max-height: 90vh;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
            cursor: default;
            transition: transform 0.2s ease;
            transform-origin: center center;
        }

        .website-img-lightbox__close {
            position: absolute;
            top: 12px;
            right: 16px;
            z-index: 2;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }

        .website-img-lightbox__close:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .website-img-lightbox__nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 26px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .website-img-lightbox__nav:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .website-img-lightbox__nav--prev {
            left: 16px;
        }

        .website-img-lightbox__nav--next {
            right: 16px;
        }

        .website-img-lightbox__toolbar {
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
        }

        .website-img-lightbox__tool {
            min-width: 42px;
            height: 34px;
            border: none;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .website-img-lightbox__tool:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 768px) {
            .website-img-lightbox__toolbar {
                top: 60px;
            }

            .website-img-lightbox__nav {
                width: 38px;
                height: 38px;
                font-size: 22px;
            }

            .website-img-lightbox__nav--prev {
                left: 10px;
            }

            .website-img-lightbox__nav--next {
                right: 10px;
            }
        }
    </style>
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


