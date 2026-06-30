@extends('layouts.dashboard')

@section('title', 'Overview')
@section('page-title', 'Overview')

@section('dashboard-content')
<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <p class="text-sm text-gray-500">Pending</p>
        <p class="mt-1 text-3xl font-bold">{{ $pendingCount }}</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <p class="text-sm text-gray-500">Approved</p>
        <p class="mt-1 text-3xl font-bold">{{ $approvedCount }}</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
        <p class="text-sm text-gray-500">Total Bookings</p>
        <p class="mt-1 text-3xl font-bold">{{ $totalBookings }}</p>
    </div>
</div>

<div class="mt-6 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
    <h2 class="mb-3 font-semibold">Upcoming Approved Bookings</h2>
    <ul class="divide-y divide-gray-100 text-sm">
        @forelse ($upcoming as $booking)
            <li class="flex items-center justify-between py-2">
                <div>
                    <span class="font-medium">{{ $booking->customer_name }}</span>
                    <span class="text-gray-500"> &middot; {{ $booking->service->name }}</span>
                </div>
                <span class="text-gray-500">{{ $booking->datetime->format('M j, Y H:i') }}</span>
            </li>
        @empty
            <li class="py-2 text-gray-400">No upcoming bookings.</li>
        @endforelse
    </ul>
</div>
@endsection
