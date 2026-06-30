@extends('layouts.base')

@section('content')
<div class="flex min-h-screen">
    <aside class="hidden w-64 shrink-0 flex-col bg-gray-900 text-gray-200 md:flex">
        <div class="px-6 py-5 text-lg font-bold text-white">Salons System</div>
        <nav class="flex-1 space-y-1 px-3">
            <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">Analytics</a>
            <a href="{{ route('admin.salons.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.salons.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">Salons</a>
            <a href="{{ route('admin.bookings.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">All Bookings</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="px-3 pb-5">
            @csrf
            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-gray-300 hover:bg-gray-800">Log out</button>
        </form>
    </aside>

    <div class="flex-1">
        <header class="flex items-center justify-between border-b bg-white px-6 py-4">
            <h1 class="text-xl font-semibold">@yield('page-title', 'Super Admin')</h1>
            <span class="text-sm text-gray-500">{{ auth()->user()->name ?? '' }}</span>
        </header>
        <main class="p-6">
            @include('partials.flash')
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection
