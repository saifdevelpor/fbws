@extends('website.home')

@section('title')
    <title>{{ __('team.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
        $badges = [
            'Member' => 'rgba(27,117,187,.10)|#12527f',
            'Legal Advisor' => 'rgba(16,185,129,.12)|#047857',
            'General Secretary' => 'rgba(245,181,31,.18)|#9a6700',
            'Finance Secretary' => 'rgba(139,92,246,.12)|#6d28d9',
            'President' => 'rgba(239,68,68,.12)|#b91c1c',
        ];
    @endphp

    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow">FBWS Team</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('team.our_members') }}</h1>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="row g-4 site-grid-stretch">
                        @foreach ($users as $user)
                            @php
                                $position = $user->position ?? 'Member';
                                [$bg, $color] = explode('|', $badges[$position] ?? $badges['Member']);
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="site-content-card">
                                    <div class="site-content-card__media media-fit">
                                        <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/avatars/default_profile_imgavif.avif') }}" alt="{{ $user->name }}">
                                    </div>
                                    <div class="site-content-card__body text-center">
                                        <h5 class="site-section-title mb-2" style="font-size: 1.2rem;" lang="ur" dir="rtl">{{ $user->name }}</h5>
                                        <span class="site-chip mb-3" style="background: {{ $bg }}; color: {{ $color }};">{{ $position }}</span>
                                        <p class="site-section-copy mb-0">{{ __('team.joined_on') }}: {{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-5">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
