@extends('layouts.base')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title')</h1>
            <p class="mt-1 text-sm text-gray-500">@yield('page-subtitle')</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            @include('partials.flash')
            @yield('guest-content')
        </div>
    </div>
</div>
@endsection
