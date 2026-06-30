@extends('layouts.base')

@section('content')
<div class="min-h-screen bg-gray-50">
    <header class="border-b bg-white">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4">
            <a href="{{ route('public.salon.show') }}" class="text-lg font-bold text-brand-700">{{ currentSalon()->name }}</a>
            <a href="{{ route('public.booking.create') }}" class="rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Book Now</a>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-4 py-8">
        @include('partials.flash')
        @yield('public-content')
    </main>

    <footer class="border-t bg-white py-6 text-center text-xs text-gray-400">
        Powered by Salons System
    </footer>
</div>
@endsection
