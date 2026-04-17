<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ur' ? 'rtl' : 'ltr' }}"
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    @yield('title')
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#F7721E">
    <link rel="apple-touch-icon" href="/pwa/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo3.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <style>
        /* Make cards feel premium */
        .card {
            border-radius: 1rem;
        }

        .table-as-cards {
            margin-top: 14px;
            padding: 0 16px 16px;
        }

        .table-as-cards .card-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
        }

        .table-as-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 14px;
        }

        .table-as-card {
            border: 1px solid #e7ebf1;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .05);
            padding: 16px;
            position: relative;
            overflow: hidden;
        }

        .table-as-card-actions {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 3;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #eef2f7;
            padding: 2px 4px;
        }

        .table-as-card-item {
            border: 1px solid #eef2f7;
            border-radius: 10px;
            background: #fafbfc;
            padding: 11px 13px;
            margin-bottom: 11px;
        }

        .table-as-card-item:last-child {
            margin-bottom: 0;
        }

        .table-as-card-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 3px;
            display: block;
            font-weight: 600;
        }

        .table-as-card-value {
            color: #111827;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.35;
            word-break: break-word;
            overflow-wrap: anywhere;
            max-width: 100%;
        }

        .table-as-card-value img,
        .table-as-card-value video,
        .table-as-card-value iframe {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 8px;
            object-fit: cover;
        }

        .table-as-card-value .btn,
        .table-as-card-value .badge {
            white-space: normal;
        }

        .table-as-card .dropdown-menu {
            max-width: min(260px, 92vw);
            white-space: normal;
            z-index: 10;
        }

        .table-as-card .dropdown-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding-top: 8px;
            padding-bottom: 8px;
            line-height: 1.25;
        }

        .table-as-cards-pagination {
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        .table-as-cards-pagination .btn {
            min-width: 92px;
        }

        /* Unified pagination design for all portal blades */
        .pagination {
            gap: 6px;
            margin-bottom: 0;
        }

        .page-item .page-link {
            border: 1px solid #e5e7eb;
            color: #374151;
            border-radius: 10px;
            min-width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            font-weight: 600;
            background: #fff;
            box-shadow: 0 4px 10px rgba(15, 23, 42, .04);
        }

        .page-item .page-link:hover {
            color: #F7721E;
            border-color: #F7721E;
            background: #fff7f2;
        }

        .page-item.active .page-link {
            background: #F7721E;
            border-color: #F7721E;
            color: #fff;
            box-shadow: 0 8px 16px rgba(247, 114, 30, .25);
        }

        .page-item.disabled .page-link {
            background: #f9fafb;
            color: #9ca3af;
            border-color: #eceff3;
            box-shadow: none;
        }

        @media (max-width: 576px) {
            .table-as-cards {
                padding: 0 10px 12px;
            }

            .table-as-cards-grid {
                grid-template-columns: 1fr;
            }

            .table-as-card {
                padding: 12px;
            }
        }

        pre {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New",
                monospace;
        }

        /* Portal image preview (lightbox-style, same tab) */
        .portal-previewable {
            cursor: zoom-in;
        }

        #portalImagePreviewModal .modal-dialog {
            max-width: min(96vw, 1100px);
        }

        #portalImagePreviewModal .modal-content {
            background: rgba(15, 23, 42, .96);
            border: 0;
        }

        .portal-preview-image {
            width: 100%;
            max-height: 82vh;
            object-fit: contain;
            display: block;
            background: #0b1220;
            border-radius: 10px;
        }
    </style>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    {{-- tabler Icon --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu menu-theme"
                style="background-color: #FEFEFF;">
                <div class="app-brand demo">
                    <a href="#" class="app-brand-link">
                        <span class="app-brand-logo demo"
                            style="display: flex; justify-content: center; align-items: center;">
                            <img src="{{ asset('/assets/logo3.png') }}" alt="Project logo" class="logo-image" />
                            <h6 class="logo-text">FBWS</h6>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="ti menu-toggle-icon d-none d-xl-block align-middle" style="color: black"></i>
                        <i class="ti ti-x d-block d-xl-none ti-md align-middle" style="color: black"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">

                    {{-- Dashboard --}}
                    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <i class="ti ti-dashboard me-2"></i>
                            <div>
                                {{ __('menu.dashboard') }}
                                <div class="menu-desc">{{ __('menu.dashboard_desc') }}</div>
                            </div>
                        </a>
                    </li>

                    {{-- Website --}}
                    <li class="menu-item {{ request()->routeIs('website.index') ? 'active' : '' }}">
                        <a href="{{ route('website.index') }}" class="menu-link">
                            <i class="ti ti-home me-2"></i>
                            <div>
                                {{ __('menu.website_home') }}
                                <div class="menu-desc">{{ __('menu.website_home_desc') }}</div>
                            </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                        <a href="{{ route('reports.monthly') }}" class="menu-link">
                            <i class="ti ti-chart-line me-2"></i>
                            <div>
                                {{ __('menu.monthly_reports') }}
                                <div class="menu-desc">{{ __('menu.monthly_reports_desc') }}</div>
                            </div>
                        </a>
                    </li>

                    {{-- Admin Only --}}
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li class="menu-item {{ request()->routeIs('user-management') ? 'active' : '' }}">
                            <a href="{{ route('user-management') }}" class="menu-link">
                                <i class="ti ti-users me-2"></i>
                                <div>
                                    {{ __('menu.user_management') }}
                                    <div class="menu-desc">{{ __('menu.user_management_desc') }}</div>
                                </div>
                            </a>
                        </li>

                        @php
                            $leadsLastSeenRaw = session('leads_last_seen_at');
                            $newLeadsQuery = \App\Models\Lead::where('status', 'new');
                            if (is_string($leadsLastSeenRaw) && $leadsLastSeenRaw !== '') {
                                $newLeadsQuery->where(
                                    'created_at',
                                    '>',
                                    \Carbon\Carbon::parse($leadsLastSeenRaw),
                                );
                            }
                            $newLeadsCount = $newLeadsQuery->count();
                        @endphp
                        <li class="menu-item {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                            <a href="{{ route('leads.index') }}" class="menu-link">
                                <i class="ti ti-user-plus me-2"></i>
                                <div>
                                    {{ __('menu.leads') }}
                                    @if ($newLeadsCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $newLeadsCount }}</span>
                                    @endif
                                    <div class="menu-desc">{{ __('menu.leads_desc') }}</div>
                                </div>
                            </a>
                        </li>
                    @endif

                    {{-- Items --}}
                    <li class="menu-item {{ request()->routeIs('items.index') ? 'active' : '' }}">
                        <a href="{{ route('items.index') }}" class="menu-link">
                            <i class="ti ti-list me-2"></i>
                            <div>
                                {{ __('menu.items_list') }}
                                <div class="menu-desc">{{ __('menu.items_list_desc') }}</div>
                            </div>
                        </a>
                    </li>

                    {{-- Payments --}}
                    <li class="menu-item {{ request()->routeIs('payments.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="ti ti-currency-dollar me-2"></i>
                            <div>
                                {{ __('menu.payments') }}
                                <div class="menu-desc">{{ __('menu.payments_desc') }}</div>
                            </div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('payments.index') ? 'active' : '' }}">
                                <a href="{{ route('payments.index') }}" class="menu-link">
                                    <div>
                                        {{ __('menu.monthly_payment') }}
                                        <div class="menu-desc">{{ __('menu.monthly_payment_desc') }}</div>
                                    </div>
                                </a>
                            </li>

                            <li class="menu-item {{ request()->routeIs('payments.history') ? 'active' : '' }}">
                                <a href="{{ route('payments.history') }}" class="menu-link">
                                    <div>
                                        {{ __('menu.payment_history') }}
                                        <div class="menu-desc">{{ __('menu.payment_history_desc') }}</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Welfare Fund --}}
                    <li class="menu-item {{ request()->routeIs('welfare.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="ti ti-currency-dollar me-2"></i>
                            <div>
                                {{ __('menu.welfare_fund') }}
                                <div class="menu-desc">{{ __('menu.welfare_fund_desc') }}</div>
                            </div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('welfare.index') ? 'active' : '' }}">
                                <a href="{{ route('welfare.index') }}" class="menu-link">
                                    <div>
                                        {{ __('menu.monthly_fund') }}
                                        <div class="menu-desc">{{ __('menu.monthly_fund_desc') }}</div>
                                    </div>
                                </a>
                            </li>

                            <li class="menu-item {{ request()->routeIs('welfare.history') ? 'active' : '' }}">
                                <a href="{{ route('welfare.history') }}" class="menu-link">
                                    <div>
                                        {{ __('menu.fund_history') }}
                                        <div class="menu-desc">{{ __('menu.fund_history_desc') }}</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Deliveries --}}
                    <li class="menu-item {{ request()->routeIs('deliveries.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="ti ti-truck-delivery me-2"></i>
                            <div>
                                {{ __('menu.deliveries') }}
                                <div class="menu-desc">{{ __('menu.deliveries_desc') }}</div>
                            </div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('deliveries.index') ? 'active' : '' }}">
                                <a href="{{ route('deliveries.index') }}" class="menu-link">
                                    <div>
                                        {{ __('menu.all_deliveries') }}
                                        <div class="menu-desc">{{ __('menu.all_deliveries_desc') }}</div>
                                    </div>
                                </a>
                            </li>

                            @if (auth()->check() && auth()->user()->role === 'admin')
                                <li class="menu-item {{ request()->routeIs('deliveries.create') ? 'active' : '' }}">
                                    <a href="{{ route('deliveries.create') }}" class="menu-link">
                                        <div>
                                            {{ __('menu.new_delivery') }}
                                            <div class="menu-desc">{{ __('menu.new_delivery_desc') }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    @php
                        $pendingOrders = \App\Models\Order::where('is_seen_admin', false)->count();
                    @endphp
                    <li class="menu-item {{ request()->routeIs('orders.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="ti ti-shopping-cart me-2"></i>
                            <div>
                                {{ __('menu.orders') }}
                                @if (auth()->check() && auth()->user()->role === 'admin' && $pendingOrders > 0)
                                    <span class="badge bg-danger ms-1">{{ $pendingOrders }}</span>
                                @endif
                                <div class="menu-desc">{{ __('menu.orders_desc') }}</div>
                            </div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                <a href="{{ route('orders.index') }}" class="menu-link">
                                    <div>
                                        {{ __('menu.all_orders') }}
                                        <div class="menu-desc">{{ __('menu.all_orders_desc') }}</div>
                                    </div>
                                </a>
                            </li>
                            @if (auth()->check() && auth()->user()->role === 'user')
                                <li class="menu-item {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                                    <a href="{{ route('orders.create') }}" class="menu-link">
                                        <div>
                                            {{ __('menu.new_order') }}
                                            <div class="menu-desc">{{ __('menu.new_order_desc') }}</div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    {{-- Events --}}
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li class="menu-item {{ request()->routeIs('event-list') ? 'active' : '' }}">
                            <a href="{{ route('event-list') }}" class="menu-link">
                                <i class="ti ti-calendar-event me-2"></i>
                                <div>
                                    {{ __('menu.events') }}
                                    <div class="menu-desc">{{ __('menu.events_desc') }}</div>
                                </div>
                            </a>
                        </li>
                    @endif

                    {{-- Complaints / Suggestions (Role Based Menu) --}}
                    @php
                        $role = strtolower(auth()->user()->role ?? '');
                        $isAdmin = $role === 'admin';
                        $isUser = $role === 'user';

                        // Active state for both user + admin routes
                        $complaintsActive =
                            request()->routeIs('complaints.create') ||
                            request()->routeIs('complaints.mine') ||
                            request()->routeIs('admin.complaints.index') ||
                            request()->routeIs('admin.complaints.show');
                    @endphp
                    @php
                        $unreadComplaints = \App\Models\Complaint::where('is_seen', false)->count();
                    @endphp

                    @if ($isUser || $isAdmin)
                        <li class="menu-item {{ $complaintsActive ? 'active' : '' }}">
                            {{-- USER --}}
                            @if ($isUser)
                                <a href="{{ route('complaints.mine') }}" class="menu-link">
                                    <i class="ti ti-message-report me-2"></i>
                                    <div>
                                        {{ __('menu.complaint') }}
                                        <div class="menu-desc">{{ __('menu.complaint_desc') }}</div>
                                    </div>
                                </a>
                            @endif

                            {{-- ADMIN --}}
                            @if ($isAdmin)
                                <a href="{{ route('admin.complaints.index') }}" class="menu-link">
                                    <i class="ti ti-message-report me-2"></i>

                                    <div>
                                        {{ __('menu.complaint') }}

                                        @if ($unreadComplaints > 0)
                                            <span class="badge bg-danger ms-2">
                                                {{ $unreadComplaints }}
                                            </span>
                                        @endif

                                        <div class="menu-desc">{{ __('menu.complaint_desc') }}</div>
                                    </div>
                                </a>
                            @endif
                        </li>
                    @endif

                    @php
                        $supportActive =
                            request()->routeIs('support-requests.create') ||
                            request()->routeIs('support-requests.mine') ||
                            request()->routeIs('admin.support-requests.index') ||
                            request()->routeIs('admin.support-requests.show');
                        $unseenSupportRequests = \App\Models\SupportRequest::where('is_seen_admin', false)->count();
                    @endphp

                    @if ($isUser || $isAdmin)
                        <li class="menu-item {{ $supportActive ? 'active' : '' }}">
                            @if ($isUser)
                                <a href="{{ route('support-requests.mine') }}" class="menu-link">
                                    <i class="ti ti-heart-handshake me-2"></i>
                                    <div>
                                        Support Requests
                                        <div class="menu-desc">Welfare help request status and new submission.</div>
                                    </div>
                                </a>
                            @endif

                            @if ($isAdmin)
                                <a href="{{ route('admin.support-requests.index') }}" class="menu-link">
                                    <i class="ti ti-heart-handshake me-2"></i>
                                    <div>
                                        Support Desk
                                        @if ($unseenSupportRequests > 0)
                                            <span class="badge bg-danger ms-2">{{ $unseenSupportRequests }}</span>
                                        @endif
                                        <div class="menu-desc">Review welfare assistance cases from members.</div>
                                    </div>
                                </a>
                            @endif
                        </li>
                    @endif
                    <li class="menu-item {{ request()->routeIs('account.membership-card') || request()->routeIs('account.membership-card.user') ? 'active' : '' }}">
                        <a href="{{ route('account.membership-card') }}" class="menu-link">
                            <i class="ti ti-id-badge-2 me-2"></i>
                            <div>
                                {{ __('menu.e_id_card') }}
                                <div class="menu-desc">{{ __('menu.e_id_card_desc') }}</div>
                            </div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('damages-list') ? 'active' : '' }}">
                        <a href="{{ route('damages-list') }}" class="menu-link">
                            <i class="ti ti-cash me-2"></i>
                            <div>
                                {{ __('menu.damages_fine') }}
                                <div class="menu-desc">{{ __('menu.damages_fine_desc') }}</div>
                            </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <a href="{{ route('gallery.index') }}" class="menu-link">
                            <i class="ti ti-photo me-2"></i>
                            <div>
                                {{ __('menu.gallery') }}
                                <div class="menu-desc">{{ __('menu.gallery_desc') }}</div>
                            </div>
                        </a>
                    </li>

                    {{-- Admin Only --}}
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li class="menu-item {{ request()->routeIs('audit.logs') ? 'active' : '' }}">
                            <a href="{{ route('audit.logs') }}" class="menu-link">
                                <i class="ti ti-history me-2"></i>
                                <div>
                                    {{ __('menu.audit_logs') }}
                                    <div class="menu-desc">{{ __('menu.audit_logs_desc') }}</div>
                                </div>
                            </a>
                        </li>
                    @endif

                </ul>
            </aside>

            <!-- Custom CSS -->
            <style>
                .menu-item.active>a {
                    background-color: #F7721E !important;
                    color: #fff !important;
                }

                .menu-item.active>a i {
                    color: #fff !important;
                }

                .menu-desc {
                    font-size: 11px;
                    color: #9aa0b7;
                    margin-top: -2px;
                }

                .menu-link div {
                    line-height: 1.2;
                }

                .app-brand-logo.demo {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 180px;
                    height: 68px;
                }

                .app-brand-logo.demo img.logo-image {
                    width: 50px;
                    height: auto;
                    object-fit: contain;
                }

                .app-brand-logo.demo h6.logo-text {
                    margin-left: 10px;
                    font-size: 1.5rem;
                    font-weight: bold;
                    color: #333;
                }

                .dark-style .menu .app-brand.demo {
                    height: 120px;
                }

                .light-style .menu .app-brand.demo {
                    height: 80px;
                }

                .menu-item a {
                    color: #000;
                    display: flex;
                    align-items: center;
                    transition: all 0.3s ease;
                }

                .menu-item a i {
                    color: #000;
                }

                .menu-item a:hover {
                    background-color: #F7721E !important;
                    color: #fff !important;
                }

                .menu-item a:hover i {
                    color: #fff !important;
                }
            </style>

            <!-- Page content -->
            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">

                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="ti ti-menu-2 ti-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">
                                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0"
                                    href="javascript:void(0);">
                                    <span class="d-none d-md-inline-block text-muted fw-normal">
                                        {{ __('menu.brand_title') }}
                                    </span>
                                </a>
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            {{-- Language Switch --}}
                            <li class="nav-item dropdown me-2">

                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    <i class="ti ti-language fs-5"></i>
                                    {{ strtoupper(app()->getLocale()) }}

                                </a>

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
                            </li>

                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user() && Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('assets/img/avatars/5.png') }}"
                                            alt="User Avatar" class="rounded-circle"
                                            style="width:40px;height:40px;object-fit:cover;" />
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item mt-0">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-online">
                                                    <img src="{{ Auth::user() && Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('assets/img/avatars/5.png') }}"
                                                        alt="User Avatar" class="rounded-circle"
                                                        style="width:40px;height:40px;object-fit:cover;" />
                                                </div>
                                                <div class="user-info">
                                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider my-1 mx-n2"></div>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile') }}">
                                            <i class="ti ti-user me-3 ti-md"></i>
                                            <span class="align-middle">{{ __('menu.my_profile') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('account.membership-card') }}">
                                            <i class="ti ti-id-badge-2 me-3 ti-md"></i>
                                            <span class="align-middle">{{ __('menu.download_e_card') }}</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('login-history.index') }}">
                                            <i class="ti ti-history me-3 ti-md"></i>
                                            <span class="align-middle">Login History</span>
                                        </a>
                                    </li>

                                    @if (strtolower(trim((string) (Auth::user()->role ?? ''))) === 'admin')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('login-history.admin') }}">
                                                <i class="ti ti-users me-3 ti-md"></i>
                                                <span class="align-middle">Users Login History</span>
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a class="dropdown-item" href="{{ route('account.devices') }}">
                                            <i class="ti ti-devices me-3 ti-md"></i>
                                            <span class="align-middle">Account Login Devices</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('password.edit') }}">
                                            <i class="ti ti-lock me-3 ti-md"></i>
                                            <span class="align-middle">Change Password</span>
                                        </a>
                                    </li>

                                    <div class="d-grid px-2 pt-2 pb-1">
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>

                                        <button class="btn btn-sm d-flex" type="button"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            style="background: #F7721E; color:#fff">
                                            <small class="align-middle">{{ __('menu.logout') }}</small>
                                            <i class="ti ti-logout ms-2 ti-14px"></i>
                                        </button>
                                    </div>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                            </div>
                        </div>
                    </footer>

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= CORE JS ================= -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- ================= DATATABLES ================= -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>


    <!-- ================= FORM VALIDATION ================= -->
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>

    <!-- ================= PAGE JS ================= -->
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>


    <!-- ================= CUSTOM INIT ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectors = ['#myTable', '#myTable1', '#myTable2', '#myTable3', '.card-datatable .table', 'table.table'];
            var tables = document.querySelectorAll(selectors.join(','));
            if (!tables.length) return;

            function hasServerPagination(table) {
                var wrapper = table.closest('.card, .card-datatable, .table-responsive, .container-xxl, .modal-body');
                if (!wrapper) return false;
                return !!wrapper.querySelector('.pagination');
            }

            function createCard(table, row, headers) {
                var card = document.createElement('div');
                card.className = 'table-as-card';

                var cells = Array.from(row.children);
                var actionCell = null;
                if (cells.length) {
                    var lastCell = cells[cells.length - 1];
                    var hasActionContent = !!lastCell.querySelector('.dropdown, .btn, button, a, form');
                    if (hasActionContent) actionCell = lastCell;
                }

                if (actionCell) {
                    var actions = document.createElement('div');
                    actions.className = 'table-as-card-actions';
                    actions.innerHTML = actionCell.innerHTML;
                    card.appendChild(actions);
                    card.style.paddingTop = '44px';
                    cells = cells.slice(0, -1);
                    headers = headers.slice(0, -1);
                }

                cells.forEach(function(cell, idx) {
                    var label = (headers[idx] || ('Field ' + (idx + 1))).trim();
                    if (!cell.innerHTML.trim()) return;

                    var item = document.createElement('div');
                    item.className = 'table-as-card-item';

                    var labelEl = document.createElement('span');
                    labelEl.className = 'table-as-card-label';
                    labelEl.textContent = label;

                    var valueEl = document.createElement('div');
                    valueEl.className = 'table-as-card-value';
                    valueEl.innerHTML = cell.innerHTML;

                    item.appendChild(labelEl);
                    item.appendChild(valueEl);
                    card.appendChild(item);
                });

                return card;
            }

            tables.forEach(function(table, tableIndex) {
                if (table.dataset.cardsConverted === '1') return;
                if (!table.tBodies || !table.tBodies.length || table.tBodies[0].rows.length === 0) return;
                if (table.closest('.no-card-convert')) return;
                if (table.closest('.modal')) return;
                if (table.querySelector('#ovItemsTbody, #dvItemsTbody')) return;

                table.dataset.cardsConverted = '1';

                var headers = Array.from(table.querySelectorAll('thead th')).map(function(th) {
                    return th.textContent.replace(/\s+/g, ' ').trim() || 'Field';
                });
                if (!headers.length) {
                    var colCount = table.tBodies[0].rows[0].children.length;
                    headers = Array.from({
                        length: colCount
                    }, function(_, i) {
                        return 'Field ' + (i + 1);
                    });
                }

                var rows = Array.from(table.tBodies[0].rows).filter(function(row) {
                    return row.children.length > 0;
                });
                if (!rows.length) return;

                var wrapper = document.createElement('div');
                wrapper.className = 'table-as-cards';
                wrapper.dataset.tableCards = String(tableIndex);

                var summary = document.createElement('div');
                summary.className = 'card-summary';
                summary.innerHTML = '<span><i class="ti ti-layout-grid me-1"></i>Card View</span><span>' + rows.length + ' records</span>';
                wrapper.appendChild(summary);

                var grid = document.createElement('div');
                grid.className = 'table-as-cards-grid';
                wrapper.appendChild(grid);

                var cards = rows.map(function(row) {
                    return createCard(table, row, headers);
                });

                cards.forEach(function(card) {
                    grid.appendChild(card);
                });

                var useClientPagination = !hasServerPagination(table);
                if (useClientPagination && cards.length > 8) {
                    var pageSize = 8;
                    var currentPage = 1;
                    var totalPages = Math.ceil(cards.length / pageSize);

                    var pager = document.createElement('div');
                    pager.className = 'table-as-cards-pagination';
                    var info = document.createElement('span');
                    var prev = document.createElement('button');
                    prev.className = 'btn btn-outline-secondary btn-sm';
                    prev.type = 'button';
                    prev.innerHTML = '<i class="ti ti-chevron-left"></i> Previous';
                    var next = document.createElement('button');
                    next.className = 'btn btn-outline-secondary btn-sm';
                    next.type = 'button';
                    next.innerHTML = 'Next <i class="ti ti-chevron-right"></i>';

                    function renderPage() {
                        cards.forEach(function(card, idx) {
                            var start = (currentPage - 1) * pageSize;
                            var end = start + pageSize;
                            card.style.display = idx >= start && idx < end ? '' : 'none';
                        });
                        info.textContent = 'Page ' + currentPage + ' of ' + totalPages;
                        prev.disabled = currentPage === 1;
                        next.disabled = currentPage === totalPages;
                    }

                    prev.addEventListener('click', function() {
                        if (currentPage > 1) {
                            currentPage -= 1;
                            renderPage();
                        }
                    });
                    next.addEventListener('click', function() {
                        if (currentPage < totalPages) {
                            currentPage += 1;
                            renderPage();
                        }
                    });

                    pager.appendChild(prev);
                    pager.appendChild(info);
                    pager.appendChild(next);
                    wrapper.appendChild(pager);
                    renderPage();
                }

                table.style.display = 'none';
                table.parentNode.insertBefore(wrapper, table.nextSibling);
            });
        });
    </script>

    {{-- Portal-wide image preview modal --}}
    <div class="modal fade" id="portalImagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <img id="portalPreviewImage" class="portal-preview-image" src="" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('portalImagePreviewModal');
            var previewImg = document.getElementById('portalPreviewImage');
            if (!modalEl || !previewImg || typeof bootstrap === 'undefined') return;

            var modal = new bootstrap.Modal(modalEl);

            function isSmallUiImage(img) {
                var rect = img.getBoundingClientRect();
                var w = rect.width || img.width || 0;
                var h = rect.height || img.height || 0;
                return w <= 48 && h <= 48;
            }

            function canPreview(img) {
                if (!img || !(img instanceof HTMLImageElement)) return false;
                if (!img.src || img.src.startsWith('data:')) return false;
                if (img.closest('[data-no-preview], .no-preview, .navbar, .menu, .dropdown-menu')) return false;
                if (isSmallUiImage(img)) return false;
                return true;
            }

            document.addEventListener('click', function(e) {
                var img = e.target.closest('img');
                if (!img || !canPreview(img)) return;

                var link = img.closest('a[href]');
                if (link && link.getAttribute('target') === '_blank') {
                    e.preventDefault();
                } else if (link && link.getAttribute('href') && link.getAttribute('href') !== 'javascript:void(0);') {
                    // For normal image links, keep navigation behavior unchanged
                    return;
                }

                previewImg.src = img.currentSrc || img.src;
                previewImg.alt = img.alt || 'Preview';
                modal.show();
            });

            modalEl.addEventListener('hidden.bs.modal', function() {
                previewImg.src = '';
            });

            document.querySelectorAll('.container-xxl img').forEach(function(img) {
                if (canPreview(img)) img.classList.add('portal-previewable');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
