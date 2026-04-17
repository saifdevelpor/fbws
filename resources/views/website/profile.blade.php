@extends('website.home')

@section('title')
    <title>{{ __('profile.page_title') }}</title>
@endsection

@section('content')
    @php
        $verificationUrl = route('account.membership-card.verify', \Illuminate\Support\Facades\Crypt::encryptString((string) $user->id));
        $qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=0&data=' . urlencode($verificationUrl);
    @endphp
    <style>
        .website-profile-qr {
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 28px;
            padding: 1.25rem;
            background: linear-gradient(180deg, #ffffff, #f7fbff);
            text-align: center;
            box-shadow: 0 16px 32px rgba(15, 23, 42, .06);
        }

        .website-profile-qr img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            background: #fff;
            border-radius: 22px;
            padding: 10px;
            border: 1px solid rgba(15, 23, 42, .08);
        }
    </style>
    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow">My Profile</span>
                <h1 class="site-page-hero__title">{{ __('profile.profile_details') }}</h1>
                <p class="site-page-hero__copy">{{ __('profile.profile_subtitle') }}</p>
            </section>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="site-content-card">
                        <div class="site-content-card__body text-center">
                            <div class="mx-auto mb-3" style="width: 150px; height: 150px; border-radius: 50%; padding: 6px; background: rgba(27,117,187,.14);">
                                <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/avatars/default_profile_imgavif.avif') }}" alt="User Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            </div>
                            <h4 class="site-section-title mb-2" style="font-size: 1.35rem;" lang="ur" dir="rtl">{{ $user->name }}</h4>
                            <p class="site-section-copy mb-2">{{ str_replace('Gernal Secretary', 'General Secretary', $user->position ?? __('profile.member')) }}</p>
                            <span class="site-chip mb-4">{{ $user->role ?? __('profile.user') }}</span>
                            <div class="d-grid">
                                <a href="{{ route('website.index') }}" class="site-primary-btn">{{ __('profile.back_to_home') }}</a>
                            </div>
                            <div class="website-profile-qr mt-4">
                                <div class="small text-uppercase fw-bold text-muted mb-2">Profile QR</div>
                                <img src="{{ $qrImageUrl }}" alt="Profile Verification QR">
                                <h6 class="mt-3 mb-1">Scan to Verify</h6>
                                <p class="site-section-copy mb-3">E ID-Card wala same verification page yahan bhi linked hai.</p>
                                <a href="{{ $verificationUrl }}" target="_blank" class="site-primary-btn">Open Verification</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="site-panel soft-panel">
                        <div class="site-panel-body">
                            <div class="row g-3">
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.name') }}</strong><div class="site-section-copy mt-2" lang="ur" dir="rtl">{{ $user->name }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.position') }}</strong><div class="site-section-copy mt-2">{{ str_replace('Gernal Secretary', 'General Secretary', $user->position ?? __('profile.not_provided')) }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.role') }}</strong><div class="site-section-copy mt-2">{{ $user->role ?? __('profile.not_provided') }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.id_card') }}</strong><div class="site-section-copy mt-2">{{ $user->id_card ?? __('profile.not_provided') }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.phone') }}</strong><div class="site-section-copy mt-2">{{ $user->phone_number ?? __('profile.not_provided') }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.email') }}</strong><div class="site-section-copy mt-2">{{ $user->email ?? __('profile.not_provided') }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.joined') }}</strong><div class="site-section-copy mt-2">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</div></div></div></div>
                                <div class="col-md-6"><div class="site-content-card"><div class="site-content-card__body"><strong>{{ __('profile.address') }}</strong><div class="site-section-copy mt-2">{{ $user->address ?? __('profile.not_provided') }}</div></div></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
