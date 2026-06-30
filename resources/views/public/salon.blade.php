@extends('layouts.public')

@section('title', $salon->name)

@section('public-content')
<div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200">
    <h1 class="text-3xl font-bold text-gray-900">{{ $salon->name }}</h1>
    @if ($salon->address)
        <p class="mt-1 text-gray-500">{{ $salon->address }}</p>
    @endif
    @if ($salon->phone)
        <p class="text-gray-500">{{ $salon->phone }}</p>
    @endif
    @if ($salon->description)
        <p class="mt-4 text-gray-700">{{ $salon->description }}</p>
    @endif

    <a href="{{ route('public.booking.create') }}" class="mt-6 inline-block rounded-full bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">
        Book an Appointment
    </a>
</div>

<div class="mt-8">
    <h2 class="mb-4 text-xl font-semibold text-gray-900">Our Services</h2>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        @forelse ($services as $service)
            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">{{ $service->name }}</h3>
                    @if ($service->price !== null)
                        <span class="font-semibold text-brand-600">{{ number_format($service->price, 2) }}</span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500">{{ $service->duration_minutes }} minutes</p>
                <a href="{{ route('public.booking.create', ['service' => $service->id]) }}" class="mt-3 inline-block text-sm font-medium text-brand-600 hover:underline">
                    Book this service &rarr;
                </a>
            </div>
        @empty
            <p class="text-gray-400">No services available yet.</p>
        @endforelse
    </div>
</div>
@endsection
