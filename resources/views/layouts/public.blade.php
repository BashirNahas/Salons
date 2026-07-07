@extends('layouts.base')

@section('content')
<div class="min-h-screen" style="background:#fafafa;">
    <header class="sticky top-0 z-30 border-b border-gray-100 bg-white/95 backdrop-blur-sm">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ route('public.salon.show') }}" class="flex items-center gap-2.5">
                @if(currentSalon()->logo)
                    <img src="{{ currentSalon()->logoUrl() }}" alt="{{ currentSalon()->name }}" class="h-8 w-8 rounded-full object-cover">
                @else
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white">
                        {{ mb_strtoupper(mb_substr(currentSalon()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="text-base font-bold text-gray-900">{{ currentSalon()->name }}</span>
            </a>
            <div class="flex items-center gap-3">
                <div class="flex items-center rounded-full bg-gray-100 p-0.5 text-xs font-medium">
                    <a href="{{ route('public.locale.update', 'en') }}"
                       class="rounded-full px-2.5 py-1 transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' }}">EN</a>
                    <a href="{{ route('public.locale.update', 'ar') }}"
                       class="rounded-full px-2.5 py-1 transition-colors {{ app()->getLocale() === 'ar' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' }}">عربي</a>
                </div>
                <a href="{{ route('public.booking.create') }}" class="rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    {{ __('Book Now') }}
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-10">
        @include('partials.flash')
        @yield('public-content')
    </main>

    <footer class="border-t border-gray-100 bg-white py-8 mt-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="flex flex-col items-center gap-2 text-center sm:flex-row sm:justify-between sm:text-left">
                <div>
                    <p class="font-semibold text-gray-800">{{ currentSalon()->name }}</p>
                    @if(currentSalon()->address)
                        <p class="text-sm text-gray-500">{{ currentSalon()->address }}</p>
                    @endif
                </div>
                <div class="text-sm text-gray-400">
                    @if(currentSalon()->phone)
                        <a href="tel:{{ currentSalon()->phone }}" class="hover:text-brand-600">{{ currentSalon()->phone }}</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
