@extends('layouts.public')

@section('title', $salon->name)

@section('public-content')

{{-- Hero --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950 px-6 py-12 text-white shadow-lg sm:px-10 sm:py-16">
    <div class="hero-dot-grid absolute inset-0 opacity-10" aria-hidden="true"></div>
    <div class="relative flex flex-col gap-8 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            @if ($salon->logo)
                <img src="{{ $salon->logoUrl() }}" alt="{{ $salon->name }}" class="mb-5 h-16 w-16 rounded-2xl object-cover ring-2 ring-white/30">
            @endif
            <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">{{ $salon->name }}</h1>

            <div class="mt-3 space-y-1.5">
                @if ($salon->address)
                    <p class="flex items-center gap-2 text-sm text-brand-100/90">
                        <svg aria-hidden="true" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $salon->address }}
                    </p>
                @endif
                @if ($salon->phone)
                    <p class="flex items-center gap-2 text-sm text-brand-100/90">
                        <svg aria-hidden="true" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $salon->phone }}" dir="ltr" class="hover:text-white">{{ $salon->phone }}</a>
                    </p>
                @endif
            </div>

            @if ($salon->description)
                <p class="mt-5 max-w-lg text-sm leading-6 text-brand-100/80">{{ $salon->description }}</p>
            @endif
        </div>

        <div class="shrink-0">
            <a href="{{ route('public.booking.create') }}"
               class="inline-flex items-center gap-2 rounded-2xl bg-white px-7 py-3.5 text-base font-semibold text-brand-700 shadow-lg transition hover:bg-brand-50">
                <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('Book Appointment') }}
            </a>
        </div>
    </div>
</div>

{{-- Services --}}
<section class="mt-12">
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-xl font-semibold tracking-tight text-gray-900">{{ __('Services') }}</h2>
        <a href="{{ route('public.booking.create') }}" class="flex items-center gap-1 text-sm font-medium text-brand-600 hover:text-brand-700">
            {{ __('Book now') }}
            <svg aria-hidden="true" class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if ($services->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white py-12 text-center text-sm text-gray-400">
            {{ __('No services listed yet.') }}
        </div>
    @else
        <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <a href="{{ route('public.booking.create', ['service' => $service->id]) }}"
                   class="group flex flex-col rounded-2xl bg-white p-5 shadow-card ring-1 ring-gray-950/5 transition hover:-translate-y-0.5 hover:shadow-md hover:ring-brand-200">
                    <div class="flex items-start justify-between">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        @if ($service->price !== null)
                            <span class="rounded-full bg-brand-50 px-2.5 py-1 text-sm font-semibold tabular-nums text-brand-700">{{ number_format($service->price, 2) }}</span>
                        @endif
                    </div>
                    <h3 class="mt-3.5 text-sm font-semibold text-gray-900 group-hover:text-brand-700">{{ $service->name }}</h3>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $service->duration_minutes }} {{ __('min') }}</p>
                    <div class="mt-4 flex items-center gap-1 text-sm font-medium text-brand-600">
                        {{ __('Book this service') }}
                        <svg aria-hidden="true" class="h-4 w-4 transition-transform group-hover:translate-x-1 rtl:rotate-180 rtl:group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>

{{-- Staff --}}
@if ($employees->isNotEmpty())
    <section class="mt-12">
        <h2 class="mb-5 text-xl font-semibold tracking-tight text-gray-900">{{ __('Our Team') }}</h2>
        <div class="grid grid-cols-2 gap-3.5 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($employees as $employee)
                <div class="flex flex-col items-center rounded-2xl bg-white p-5 text-center shadow-card ring-1 ring-gray-950/5">
                    @if ($employee->avatar_url)
                        <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-brand-100">
                    @else
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-lg font-semibold text-white ring-2 ring-brand-100">
                            {{ $employee->initials() }}
                        </div>
                    @endif
                    <h3 class="mt-3 text-sm font-semibold text-gray-900">{{ $employee->name }}</h3>
                    @if ($employee->specialties)
                        <p class="mt-1 text-xs text-gray-500">{{ $employee->specialties }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif

{{-- CTA --}}
<div class="mt-12 rounded-3xl bg-brand-50 px-6 py-10 text-center">
    <h3 class="text-lg font-semibold tracking-tight text-brand-900">{{ __('Ready to book?') }}</h3>
    <p class="mx-auto mt-1 max-w-md text-sm text-brand-700">{{ __("Choose your service, pick your preferred time, and we'll confirm shortly.") }}</p>
    <a href="{{ route('public.booking.create') }}"
       class="mt-5 inline-flex items-center gap-2 rounded-full bg-brand-600 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
        {{ __('Book an Appointment') }}
        <svg aria-hidden="true" class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
</div>

@endsection
