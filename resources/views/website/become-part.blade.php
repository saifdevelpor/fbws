@extends('website.home')

@section('title')
    <title>{{ __('part.page_title') }}</title>
@endsection

@section('content')
    <div class="site-page-shell">
        <div class="container">
            <section class="site-page-hero text-center text-lg-start">
                <span class="site-page-hero__eyebrow"><i class="bi bi-person-plus me-1"></i> {{ __('web.become_part') }}</span>
                <h1 class="site-page-hero__title" lang="ur" dir="rtl">{{ __('part.join_us') }}</h1>
                <p class="site-page-hero__copy" lang="ur" dir="rtl">{{ __('part.message_ph') }}</p>
            </section>

            <section class="site-panel soft-panel">
                <div class="site-panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif

                    @if (session('admin_wa_link'))
                        <div class="alert alert-info text-center">
                            <h5 class="mb-3">Your form was submitted successfully.</h5>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ session('admin_wa_link') }}" target="_blank" class="site-primary-btn">Send to Admin on WhatsApp</a>
                                @if (session('user_wa_link'))
                                    <a href="{{ session('user_wa_link') }}" target="_blank" class="site-secondary-btn">Open confirmation message</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <span class="site-chip">Membership Form</span>
                        <h2 class="site-section-title mt-3" lang="ur" dir="rtl">{{ __('part.join_us') }}</h2>
                    </div>

                    <form action="{{ route('website.become-part.submit') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6"><input type="text" class="form-control form-control-lg" name="name" value="{{ old('name') }}" placeholder="{{ __('part.full_name') }}" required></div>
                            <div class="col-md-6"><input type="text" class="form-control form-control-lg" name="father_name" value="{{ old('father_name') }}" placeholder="{{ __('part.father_name') }}" required></div>
                            <div class="col-md-6"><input type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" placeholder="{{ __('part.email') }}" required></div>
                            <div class="col-md-6"><input type="tel" class="form-control form-control-lg" name="phone" value="{{ old('phone') }}" placeholder="{{ __('part.phone') }}" required></div>
                            <div class="col-md-6"><textarea class="form-control form-control-lg" name="address" rows="5" placeholder="{{ __('part.address') }}" required>{{ old('address') }}</textarea></div>
                            <div class="col-md-6"><input type="text" class="form-control form-control-lg" name="id_card" value="{{ old('id_card') }}" placeholder="{{ __('part.id_card') }}" required></div>
                            <div class="col-md-12"><textarea class="form-control form-control-lg" name="message" rows="6" placeholder="{{ __('part.message') }}" required>{{ old('message') }}</textarea></div>
                            <div class="col-md-12 text-center"><button type="submit" class="site-primary-btn">{{ __('part.submit') }}</button></div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    @if (session('admin_wa_link'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.open(@json(session('admin_wa_link')), '_blank');
                @if (session('user_wa_link'))
                    setTimeout(function() {
                        window.open(@json(session('user_wa_link')), '_blank');
                    }, 400);
                @endif
            });
        </script>
    @endif
@endsection
