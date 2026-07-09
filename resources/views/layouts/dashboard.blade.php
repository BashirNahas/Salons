@extends('layouts.base')

@php
    $navSections = [
        __('Main') => [
            ['route' => 'dashboard.home', 'pattern' => 'dashboard.home', 'label' => __('Overview'),
             'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'dashboard.bookings.index', 'pattern' => 'dashboard.bookings.*', 'label' => __('Appointments'),
             'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['route' => 'dashboard.recurring-bookings.index', 'pattern' => 'dashboard.recurring-bookings.*', 'label' => __('Regular Customers'),
             'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
        ],
        __('Manage') => [
            ['route' => 'dashboard.services.index', 'pattern' => 'dashboard.services.*', 'label' => __('Services'),
             'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['route' => 'dashboard.employees.index', 'pattern' => 'dashboard.employees.*', 'label' => __('Staff'),
             'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ],
        __('Schedule') => [
            ['route' => 'dashboard.working-hours.index', 'pattern' => 'dashboard.working-hours.*', 'label' => __('Working Hours'),
             'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['route' => 'dashboard.blocked-dates.index', 'pattern' => 'dashboard.blocked-dates.*', 'label' => __('Days Off'),
             'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
        ],
        __('Settings') => [
            ['route' => 'dashboard.profile.edit', 'pattern' => 'dashboard.profile.*', 'label' => __('Salon Profile'),
             'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
        ],
    ];
@endphp

@section('content')
<div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-gray-50">
    {{-- Mobile backdrop --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-gray-950/40 backdrop-blur-sm md:hidden"
         x-transition.opacity></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : 'max-md:ltr:-translate-x-full max-md:rtl:translate-x-full'"
           class="fixed inset-y-0 start-0 z-40 flex w-64 shrink-0 flex-col border-e border-gray-200 bg-white transition-transform duration-300 ease-in-out md:relative">

        <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4">
            @if (currentSalon()->logo)
                <img src="{{ currentSalon()->logoUrl() }}" alt="" class="h-9 w-9 shrink-0 rounded-xl object-cover ring-1 ring-gray-950/10">
            @else
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-sm font-semibold text-white">
                    {{ mb_strtoupper(mb_substr(currentSalon()->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-gray-900">{{ currentSalon()->name }}</p>
                <p class="text-xs text-gray-400">{{ __('Salon Dashboard') }}</p>
            </div>
        </div>

        <nav class="flex-1 space-y-5 overflow-y-auto px-3 py-5">
            @foreach ($navSections as $section => $items)
                <div>
                    <p class="px-3 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-gray-400">{{ $section }}</p>
                    <div class="space-y-0.5">
                        @foreach ($items as $item)
                            <a href="{{ route($item['route']) }}" @class([
                                'group flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-brand-50 text-brand-700' => request()->routeIs($item['pattern']),
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! request()->routeIs($item['pattern']),
                            ])>
                                <svg @class(['h-[18px] w-[18px] shrink-0', 'text-brand-600' => request()->routeIs($item['pattern']), 'text-gray-400 group-hover:text-gray-500' => ! request()->routeIs($item['pattern'])])
                                     fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div>
                <a href="{{ currentSalon()->url() }}" target="_blank"
                   class="group flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">
                    <svg class="h-[18px] w-[18px] shrink-0 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    {{ __('View Public Page') }}
                </a>
            </div>
        </nav>

        <div class="border-t border-gray-100 p-3">
            <div class="flex items-center gap-2.5 rounded-lg px-2 py-1.5">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-600">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-medium text-gray-900">{{ auth()->user()->name ?? '' }}</p>
                    <p class="truncate text-[11px] text-gray-400">{{ __('Salon Owner') }}</p>
                </div>
                <form method="POST" action="{{ route('dashboard.logout') }}">
                    @csrf
                    <button type="submit" title="{{ __('Log out') }}"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">
                        <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main column --}}
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center justify-between gap-3 border-b border-gray-200 bg-white/90 px-4 py-3 backdrop-blur md:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button @click="sidebarOpen = true" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 md:hidden" aria-label="{{ __('Open menu') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="truncate text-base font-semibold tracking-tight text-gray-900 md:text-lg">@yield('page-title', __('Dashboard'))</h1>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <span class="hidden text-xs text-gray-400 lg:block">{{ now()->translatedFormat(app()->getLocale() === 'ar' ? 'l، j F Y' : 'l, M j, Y') }}</span>
                <x-lang-switcher />
            </div>
        </header>

        <main class="flex-1 px-4 py-6 md:px-8 md:py-8">
            <div class="mx-auto max-w-6xl">
                @include('partials.flash')
                @yield('dashboard-content')
            </div>
        </main>
    </div>
</div>

@push('head')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none!important}</style>
@endpush
@endsection
