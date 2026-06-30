@extends('layouts.base')

@section('content')
<div class="flex min-h-screen">
    <aside class="hidden w-64 shrink-0 flex-col bg-brand-900 text-brand-100 md:flex">
        <div class="px-6 py-5 text-lg font-bold text-white">{{ currentSalon()->name }}</div>
        <nav class="flex-1 space-y-1 px-3">
            <a href="{{ route('dashboard.home') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.home') ? 'bg-brand-800 text-white' : 'hover:bg-brand-800' }}">Overview</a>
            <a href="{{ route('dashboard.bookings.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.bookings.index') ? 'bg-brand-800 text-white' : 'hover:bg-brand-800' }}">Bookings (List)</a>
            <a href="{{ route('dashboard.bookings.calendar') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.bookings.calendar') ? 'bg-brand-800 text-white' : 'hover:bg-brand-800' }}">Bookings (Calendar)</a>
            <a href="{{ route('dashboard.services.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.services.*') ? 'bg-brand-800 text-white' : 'hover:bg-brand-800' }}">Services</a>
            <a href="{{ route('dashboard.working-hours.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.working-hours.*') ? 'bg-brand-800 text-white' : 'hover:bg-brand-800' }}">Working Hours</a>
            <a href="{{ route('dashboard.blocked-dates.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard.blocked-dates.*') ? 'bg-brand-800 text-white' : 'hover:bg-brand-800' }}">Blocked Dates</a>
            <a href="{{ currentSalon()->url() }}" target="_blank" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-brand-800">View Public Page ↗</a>
        </nav>
        <form method="POST" action="{{ route('dashboard.logout') }}" class="px-3 pb-5">
            @csrf
            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-brand-100 hover:bg-brand-800">Log out</button>
        </form>
    </aside>

    <div class="flex-1">
        <header class="flex items-center justify-between border-b bg-white px-6 py-4">
            <h1 class="text-xl font-semibold">@yield('page-title', 'Dashboard')</h1>
            <span class="text-sm text-gray-500">{{ auth()->user()->name ?? '' }}</span>
        </header>
        <main class="p-6">
            @include('partials.flash')
            @yield('dashboard-content')
        </main>
    </div>
</div>
@endsection
