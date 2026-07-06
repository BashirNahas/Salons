@extends('layouts.public')

@section('title', $salon->name . ' — Book Online')

@section('public-content')

{{-- Hero --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950 px-6 py-12 text-white shadow-lg sm:px-10 sm:py-16">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 70% 50%, white 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="relative">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                @if($salon->logo)
                    <img src="{{ $salon->logoUrl() }}" alt="{{ $salon->name }}" class="mb-4 h-16 w-16 rounded-2xl object-cover ring-2 ring-white/30">
                @endif
                <h1 class="text-3xl font-bold sm:text-4xl">{{ $salon->name }}</h1>
                @if($salon->address)
                    <p class="mt-2 flex items-center gap-1.5 text-brand-200">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $salon->address }}
                    </p>
                @endif
                @if($salon->phone)
                    <p class="mt-1 flex items-center gap-1.5 text-brand-200">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $salon->phone }}" class="hover:text-white">{{ $salon->phone }}</a>
                    </p>
                @endif
                @if($salon->description)
                    <p class="mt-4 max-w-lg text-brand-100/80 leading-relaxed">{{ $salon->description }}</p>
                @endif
            </div>
            <div class="shrink-0">
                <a href="{{ route('public.booking.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white px-7 py-3.5 text-base font-bold text-brand-700 shadow-lg hover:bg-brand-50 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Book Appointment
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Services --}}
<section class="mt-10">
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Services</h2>
        <a href="{{ route('public.booking.create') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Book now &rarr;</a>
    </div>

    @if($services->isEmpty())
        <div class="rounded-xl border border-dashed border-gray-200 py-10 text-center text-gray-400">
            No services listed yet.
        </div>
    @else
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <a href="{{ route('public.booking.create', ['service' => $service->id]) }}" class="group relative flex flex-col rounded-xl border border-gray-100 bg-white p-5 shadow-sm transition-all hover:border-brand-200 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        @if($service->price !== null)
                            <span class="rounded-full bg-brand-50 px-2.5 py-1 text-sm font-bold text-brand-700">
                                {{ number_format($service->price, 2) }}
                            </span>
                        @endif
                    </div>
                    <h3 class="mt-3 font-semibold text-gray-900 group-hover:text-brand-700">{{ $service->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $service->duration_minutes }} min</p>
                    <div class="mt-4 flex items-center gap-1 text-sm font-medium text-brand-600">
                        Book this service
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>

{{-- Staff --}}
@if($employees->isNotEmpty())
<section class="mt-10">
    <h2 class="mb-5 text-xl font-bold text-gray-900">Our Team</h2>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @foreach($employees as $employee)
            <div class="flex flex-col items-center rounded-xl border border-gray-100 bg-white p-5 shadow-sm text-center">
                @if($employee->avatar_url)
                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-brand-100">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-lg font-bold text-white ring-2 ring-brand-100">
                        {{ $employee->initials() }}
                    </div>
                @endif
                <h3 class="mt-3 font-semibold text-gray-900">{{ $employee->name }}</h3>
                @if($employee->specialties)
                    <p class="mt-1 text-xs text-gray-500">{{ $employee->specialties }}</p>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- CTA --}}
<div class="mt-10 rounded-2xl bg-brand-50 px-6 py-8 text-center">
    <h3 class="text-lg font-bold text-brand-900">Ready to book?</h3>
    <p class="mt-1 text-sm text-brand-700">Choose your service, pick your preferred time, and we'll confirm shortly.</p>
    <a href="{{ route('public.booking.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-brand-700 transition-colors">
        Book an Appointment
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
</div>

@endsection
