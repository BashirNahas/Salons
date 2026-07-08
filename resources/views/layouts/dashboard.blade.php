@extends('layouts.base')

@section('content')
<div x-data="{ sidebarOpen: false }" class="flex min-h-screen bg-gray-50">
    {{-- Mobile backdrop --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/50 md:hidden" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="fixed inset-y-0 left-0 z-40 flex w-64 shrink-0 flex-col bg-brand-950 text-brand-100 transition-transform duration-300 ease-in-out md:relative md:inset-auto md:translate-x-0">

        <div class="flex items-center gap-3 border-b border-brand-800 px-5 py-4">
            @if(currentSalon()->logo)
                <img src="{{ currentSalon()->logoUrl() }}" alt="" class="h-9 w-9 rounded-full object-cover ring-2 ring-brand-600">
            @else
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">
                    {{ mb_strtoupper(mb_substr(currentSalon()->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-white">{{ currentSalon()->name }}</p>
                <p class="text-xs text-brand-400">Salon Dashboard</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            <p class="px-3 pb-1 pt-2 text-xs font-semibold uppercase tracking-wider text-brand-500">Main</p>

            <a href="{{ route('dashboard.home') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.home'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.home')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Overview
            </a>

            <a href="{{ route('dashboard.bookings.index') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.bookings.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.bookings.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Appointments
            </a>

            <p class="px-3 pb-1 pt-4 text-xs font-semibold uppercase tracking-wider text-brand-500">Manage</p>

            <a href="{{ route('dashboard.services.index') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.services.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.services.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Services
            </a>

            <a href="{{ route('dashboard.employees.index') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.employees.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.employees.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Staff / Barbers
            </a>

            <a href="{{ route('dashboard.recurring-bookings.index') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.recurring-bookings.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.recurring-bookings.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Regular Customers
            </a>

            <p class="px-3 pb-1 pt-4 text-xs font-semibold uppercase tracking-wider text-brand-500">Schedule</p>

            <a href="{{ route('dashboard.working-hours.index') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.working-hours.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.working-hours.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Working Hours
            </a>

            <a href="{{ route('dashboard.blocked-dates.index') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.blocked-dates.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.blocked-dates.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Blocked Dates
            </a>

            <p class="px-3 pb-1 pt-4 text-xs font-semibold uppercase tracking-wider text-brand-500">Settings</p>

            <a href="{{ route('dashboard.profile.edit') }}" @class(['flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors', 'bg-brand-700 text-white shadow-sm' => request()->routeIs('dashboard.profile.*'), 'text-brand-300 hover:bg-brand-800 hover:text-white' => !request()->routeIs('dashboard.profile.*')])>
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Salon Profile
            </a>

            <a href="{{ currentSalon()->url() }}" target="_blank" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-sm font-medium text-brand-300 hover:bg-brand-800 hover:text-white transition-colors">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Public Page
            </a>
        </nav>

        <div class="border-t border-brand-800 px-3 py-4">
            <div class="mb-2 flex items-center gap-2.5 px-2">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs font-bold text-white">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-xs font-medium text-white">{{ auth()->user()->name ?? '' }}</p>
                    <p class="truncate text-xs text-brand-500">Salon Owner</p>
                </div>
            </div>
            <form method="POST" action="{{ route('dashboard.logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-brand-400 hover:bg-brand-800 hover:text-white transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex items-center justify-between border-b bg-white px-4 py-3 shadow-sm md:px-6">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 md:hidden" aria-label="Open menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>
            <span class="hidden text-sm text-gray-500 sm:block">{{ now()->format('l, M j Y') }}</span>
        </header>
        <main class="flex-1 p-4 md:p-6">
            @include('partials.flash')
            @yield('dashboard-content')
        </main>
    </div>
</div>

@push('head')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
