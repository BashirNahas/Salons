@extends('layouts.guest')

@section('title', __('Salon Login'))

@section('page-logo')
    @if (currentSalon()->logo)
        <img src="{{ currentSalon()->logoUrl() }}" alt="" class="mx-auto mb-4 h-14 w-14 rounded-2xl object-cover ring-1 ring-gray-950/10">
    @else
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-600 text-xl font-semibold text-white">
            {{ mb_strtoupper(mb_substr(currentSalon()->name, 0, 1)) }}
        </div>
    @endif
@endsection

@section('page-title', currentSalon()->name)
@section('page-subtitle', __('Sign in to manage your salon'))

@section('guest-content')
<form method="POST" action="{{ route('dashboard.login') }}" class="space-y-5">
    @csrf

    <x-input :label="__('Email')" name="email" type="email" required autofocus dir="ltr" />
    <x-input :label="__('Password')" name="password" type="password" required />

    <x-checkbox :label="__('Remember me')" name="remember" />

    <x-btn class="w-full">{{ __('Sign In') }}</x-btn>
</form>
@endsection
