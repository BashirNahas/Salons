@extends('layouts.base')

@section('title', config('app.name'))

@section('content')
<div class="flex min-h-screen flex-col items-center justify-center bg-gray-50 px-6 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-600 text-xl font-semibold text-white shadow-sm">S</div>
    <h1 class="mt-6 text-3xl font-semibold tracking-tight text-gray-900">{{ config('app.name') }}</h1>
    <p class="mt-3 max-w-md text-sm leading-6 text-gray-500">
        {{ __('Multi-tenant salon booking platform. Each salon has its own booking page at') }}
        <code dir="ltr" class="rounded-md bg-gray-100 px-1.5 py-0.5 text-xs">{slug}.{{ config('tenancy.central_domain') }}</code>
    </p>
    <div class="mt-7 flex items-center gap-3">
        <x-btn href="{{ route('admin.login') }}" variant="dark">{{ __('Super Admin Login') }}</x-btn>
        <x-lang-switcher />
    </div>
</div>
@endsection
