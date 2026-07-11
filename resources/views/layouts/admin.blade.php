@extends('layouts.base')

@php
    $adminNav = [
        ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => __('Analytics'),
         'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
        ['route' => 'admin.salons.index', 'pattern' => 'admin.salons.*', 'label' => __('Salons'),
         'icon' => 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z'],
        ['route' => 'admin.bookings.index', 'pattern' => 'admin.bookings.*', 'label' => __('All Bookings'),
         'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ];
@endphp

@section('content')
<div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-gray-50">
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-gray-950/40 backdrop-blur-sm md:hidden" x-transition.opacity></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : 'max-md:ltr:-translate-x-full max-md:rtl:translate-x-full'"
           class="fixed inset-y-0 start-0 z-40 flex w-64 shrink-0 flex-col border-e border-gray-800 bg-gray-950 transition-transform duration-300 ease-in-out md:relative">

        <div class="flex items-center gap-3 border-b border-gray-800 px-5 py-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-sm font-semibold text-white">S</div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">{{ config('app.name') }}</p>
                <p class="text-xs text-gray-500">{{ __('Super Admin') }}</p>
            </div>
        </div>

        <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-5">
            @foreach ($adminNav as $item)
                <a href="{{ route($item['route']) }}" @class([
                    'group flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition',
                    'bg-white/10 text-white' => request()->routeIs($item['pattern']),
                    'text-gray-400 hover:bg-white/5 hover:text-white' => ! request()->routeIs($item['pattern']),
                ])>
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="border-t border-gray-800 p-3">
            <div class="flex items-center gap-2.5 rounded-lg px-2 py-1.5">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/10 text-xs font-semibold text-white">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-medium text-white">{{ auth()->user()->name ?? '' }}</p>
                    <p class="truncate text-[11px] text-gray-500">{{ __('Super Admin') }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" title="{{ __('Log out') }}"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center justify-between gap-3 border-b border-gray-200 bg-white/90 px-4 py-3 backdrop-blur md:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button @click="sidebarOpen = true" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 md:hidden" aria-label="{{ __('Open menu') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="truncate text-base font-semibold tracking-tight text-gray-900 md:text-lg">@yield('page-title', __('Super Admin'))</h1>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <x-lang-switcher />
            </div>
        </header>

        <main class="flex-1 px-4 py-6 md:px-8 md:py-8">
            <div class="mx-auto max-w-6xl">
                @include('partials.flash')
                @yield('admin-content')
            </div>
        </main>
    </div>
</div>

@push('head')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak]{display:none!important}</style>
@endpush
@endsection
