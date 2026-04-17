@extends('home')

@section('title')
    <title>Change Password | FBWS</title>
@endsection

@section('content')
    <div class="d-flex w-100 justify-content-center align-items-center px-2 py-4"
        style="min-height: calc(100vh - 12rem);">
        <div class="w-100" style="max-width: 42rem;">
            @include('login-history.partials.change-password')
        </div>
    </div>
@endsection
