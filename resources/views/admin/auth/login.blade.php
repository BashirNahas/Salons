@extends('layouts.guest')

@section('title', __('Super Admin Login'))
@section('page-title', __('Super Admin'))
@section('page-subtitle', __('Sign in to manage all salons'))

@section('guest-content')
<form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
    @csrf

    <x-input :label="__('Email')" name="email" type="email" required autofocus dir="ltr" />
    <x-input :label="__('Password')" name="password" type="password" required />

    <x-checkbox :label="__('Remember me')" name="remember" />

    <x-btn variant="dark" class="w-full">{{ __('Sign In') }}</x-btn>
</form>
@endsection
