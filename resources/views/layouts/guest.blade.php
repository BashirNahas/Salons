@extends('layouts.base')

@section('content')
<div class="flex min-h-screen flex-col items-center justify-center bg-gray-50 px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            @hasSection('page-logo')
                @yield('page-logo')
            @endif
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">@yield('page-title')</h1>
            <p class="mt-1.5 text-sm text-gray-500">@yield('page-subtitle')</p>
        </div>
        <div class="rounded-2xl bg-white p-7 shadow-card ring-1 ring-gray-950/5">
            @include('partials.flash')
            @yield('guest-content')
        </div>
        <div class="mt-6 flex justify-center">
            <x-lang-switcher />
        </div>
    </div>
</div>
@endsection
