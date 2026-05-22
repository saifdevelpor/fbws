@extends('website.home')

@section('title')
    <title>{{ __('team.page_title') }}</title>
@endsection

@section('content')
    @php
        $locale = app()->getLocale();
        $dir = $locale === 'ur' ? 'rtl' : 'ltr';
        $badgeClass = [
            'Member' => 'member-badge--member',
            'Legal Advisor' => 'member-badge--legal',
            'General Secretary' => 'member-badge--secretary',
            'Gernal Secretary' => 'member-badge--secretary',
            'Finance Secretary' => 'member-badge--finance',
            'President' => 'member-badge--president',
        ];
    @endphp

    <div class="site-page-shell team-page">
        <div class="container">
            <section class="site-page-hero text-center">
                <span class="site-page-hero__eyebrow"><i class="bi bi-people me-1"></i> {{ __('web.team') }}</span>
                <h1 class="site-page-hero__title" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('team.our_members') }}</h1>
                <p class="site-page-hero__copy mb-0" lang="{{ $locale }}" dir="{{ $dir }}">
                    {{ $users->total() }} {{ app()->getLocale() === 'ur' ? 'کل ممبران' : 'total members' }}
                </p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    <div class="team-toolbar mb-4">
                        <div class="team-toolbar__info">
                            <i class="bi bi-grid-3x3-gap"></i>
                            <span lang="{{ $locale }}" dir="{{ $dir }}">
                                {{ app()->getLocale() === 'ur' ? 'صفحہ' : 'Page' }}
                                <strong>{{ $users->currentPage() }}</strong>
                                {{ app()->getLocale() === 'ur' ? 'از' : 'of' }}
                                <strong>{{ $users->lastPage() }}</strong>
                            </span>
                        </div>
                        <span class="team-toolbar__count">{{ $users->count() }} / {{ $users->total() }}</span>
                    </div>

                    <div class="row g-3 g-md-4 site-grid-stretch">
                        @forelse ($users as $user)
                            @php
                                $position = $user->position ?? 'Member';
                                $displayPosition = str_replace('Gernal Secretary', 'General Secretary', $position);
                                $badgeMod = $badgeClass[$position] ?? $badgeClass['Member'];
                            @endphp
                            <div class="col-6 col-md-4 col-lg-4">
                                <article class="site-content-card team-member-card">
                                    <div class="team-member-card__avatar">
                                        <img src="{{ $user->profile_photo ? asset($user->profile_photo) : asset('assets/img/avatars/default_profile_imgavif.avif') }}"
                                            alt="{{ $user->name }}" loading="lazy">
                                    </div>
                                    <div class="site-content-card__body text-center">
                                        <h5 class="team-member-card__name" lang="ur" dir="rtl">{{ $user->name }}</h5>
                                        <span class="member-badge {{ $badgeMod }}" lang="{{ $locale }}" dir="{{ $dir }}">{{ $displayPosition }}</span>
                                        <p class="team-member-card__meta mb-0">
                                            <i class="bi bi-calendar3 me-1"></i>{{ __('team.joined_on') }}: {{ $user->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="site-empty" lang="{{ $locale }}" dir="{{ $dir }}">{{ __('web.no_data_available') }}</div>
                            </div>
                        @endforelse
                    </div>

                    @if ($users->hasPages())
                        <div class="team-pagination mt-5" aria-label="Team pagination">
                            {{ $users->onEachSide(1)->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
