@extends('layouts.dashboard')

@section('title', 'Overview')
@section('page-title', 'Overview')

@section('dashboard-content')

{{-- Stats --}}
<div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Today</p>
                <p class="text-2xl font-bold text-gray-900">{{ $todayCount }}</p>
            </div>
        </div>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-50 text-yellow-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
            </div>
        </div>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Approved</p>
                <p class="text-2xl font-bold text-gray-900">{{ $approvedCount }}</p>
            </div>
        </div>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Pending alert --}}
@if($pendingCount > 0)
<div class="mt-4 flex items-center gap-3 rounded-xl bg-amber-50 px-5 py-4 ring-1 ring-amber-200">
    <svg class="h-5 w-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    <p class="flex-1 text-sm font-medium text-amber-800">
        <strong>{{ $pendingCount }}</strong> {{ Str::plural('booking', $pendingCount) }} waiting for your approval.
    </p>
    <a href="{{ route('dashboard.bookings.index', ['status' => 'pending']) }}" class="shrink-0 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-amber-700">Review</a>
</div>
@endif

{{-- Today --}}
<div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
        <h2 class="font-semibold text-gray-800">Today's Schedule</h2>
        <span class="text-sm text-gray-500">{{ now()->format('l, M j') }}</span>
    </div>
    @forelse($todayBookings as $booking)
        <div class="flex items-start gap-4 border-b border-gray-50 px-5 py-3.5 last:border-0">
            <div class="w-12 shrink-0">
                <p class="text-sm font-bold text-gray-900">{{ $booking->datetime->format('H:i') }}</p>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-medium text-gray-800">{{ $booking->customer_name }}</p>
                <p class="text-sm text-gray-500">
                    {{ $booking->service->name }}
                    @if($booking->employee) · <span class="text-brand-600">{{ $booking->employee->name }}</span>@endif
                </p>
            </div>
            <span @class([
                'shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize',
                'bg-yellow-100 text-yellow-700' => $booking->status === 'pending',
                'bg-green-100 text-green-700' => $booking->status === 'approved',
                'bg-red-100 text-red-700' => $booking->status === 'rejected',
            ])>{{ $booking->status }}</span>
        </div>
    @empty
        <div class="px-5 py-8 text-center text-sm text-gray-400">No appointments scheduled for today.</div>
    @endforelse
</div>

{{-- Upcoming confirmed --}}
<div class="mt-6 rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
        <h2 class="font-semibold text-gray-800">Upcoming Confirmed</h2>
        <a href="{{ route('dashboard.bookings.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all</a>
    </div>
    @forelse($upcoming as $booking)
        <div class="flex items-center justify-between gap-4 border-b border-gray-50 px-5 py-3.5 last:border-0">
            <div class="min-w-0">
                <p class="font-medium text-gray-800">{{ $booking->customer_name }}</p>
                <p class="text-sm text-gray-500">{{ $booking->service->name }}@if($booking->employee) · {{ $booking->employee->name }}@endif</p>
            </div>
            <span class="shrink-0 text-sm text-gray-500">{{ $booking->datetime->format('M j, H:i') }}</span>
        </div>
    @empty
        <div class="px-5 py-6 text-center text-sm text-gray-400">No upcoming confirmed bookings.</div>
    @endforelse
</div>

@endsection
