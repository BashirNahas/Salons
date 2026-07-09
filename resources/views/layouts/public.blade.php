@extends('layouts.base')

@section('content')
<div class="flex min-h-screen flex-col bg-gray-50">
    <header class="sticky top-0 z-30 border-b border-gray-950/5 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
            <a href="{{ route('public.salon.show') }}" class="flex min-w-0 items-center gap-2.5">
                @if (currentSalon()->logo)
                    <img src="{{ currentSalon()->logoUrl() }}" alt="{{ currentSalon()->name }}" class="h-9 w-9 shrink-0 rounded-xl object-cover ring-1 ring-gray-950/10">
                @else
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-sm font-semibold text-white">
                        {{ mb_strtoupper(mb_substr(currentSalon()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="truncate text-sm font-semibold tracking-tight text-gray-900 sm:text-base">{{ currentSalon()->name }}</span>
            </a>
            <div class="flex shrink-0 items-center gap-2.5">
                <x-lang-switcher />
                <x-btn href="{{ route('public.booking.create') }}" size="sm" class="!rounded-full !px-4">{{ __('Book Now') }}</x-btn>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-6 sm:px-6 sm:py-10">
        @include('partials.flash')
        @yield('public-content')
    </main>

    <footer class="mt-12 border-t border-gray-950/5 bg-white py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="flex flex-col items-center gap-2 text-center sm:flex-row sm:justify-between sm:text-start">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ currentSalon()->name }}</p>
                    @if (currentSalon()->address)
                        <p class="mt-0.5 text-sm text-gray-500">{{ currentSalon()->address }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-sm text-gray-400">
                    @if (currentSalon()->instagram)
                        <a href="https://instagram.com/{{ currentSalon()->instagram }}" target="_blank" rel="noopener" class="transition hover:text-brand-600">Instagram</a>
                    @endif
                    @if (currentSalon()->phone)
                        <a href="tel:{{ currentSalon()->phone }}" dir="ltr" class="transition hover:text-brand-600">{{ currentSalon()->phone }}</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection
