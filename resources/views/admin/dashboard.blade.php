@extends('layouts.admin')

@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('admin-content')
<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <p class="text-sm text-gray-500">Total Salons</p>
        <p class="mt-1 text-3xl font-bold">{{ $totalSalons }}</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <p class="text-sm text-gray-500">Total Bookings</p>
        <p class="mt-1 text-3xl font-bold">{{ $totalBookings }}</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <p class="text-sm text-gray-500">Pending Bookings</p>
        <p class="mt-1 text-3xl font-bold">{{ $bookingsByStatus['pending'] ?? 0 }}</p>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <h2 class="mb-3 font-semibold">Bookings per Salon (top 10)</h2>
        <ul class="divide-y divide-gray-100 text-sm">
            @forelse ($bookingsPerSalon as $salon)
                <li class="flex items-center justify-between py-2">
                    <span>{{ $salon->name }}</span>
                    <span class="font-semibold">{{ $salon->bookings_count }}</span>
                </li>
            @empty
                <li class="py-2 text-gray-400">No salons yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <h2 class="mb-3 font-semibold">Recent Bookings</h2>
        <ul class="divide-y divide-gray-100 text-sm">
            @forelse ($recentBookings as $booking)
                <li class="py-2">
                    <div class="flex items-center justify-between">
                        <span class="font-medium">{{ $booking->customer_name }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs capitalize">{{ $booking->status }}</span>
                    </div>
                    <p class="text-gray-500">{{ $booking->salon->name }} &middot; {{ $booking->service->name }} &middot; {{ $booking->datetime->format('M j, Y H:i') }}</p>
                </li>
            @empty
                <li class="py-2 text-gray-400">No bookings yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
