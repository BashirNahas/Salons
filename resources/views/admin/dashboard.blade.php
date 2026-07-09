@extends('layouts.admin')

@section('title', __('Analytics'))
@section('page-title', __('Analytics'))

@section('admin-content')

<div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:gap-4">
    <x-stat :label="__('Total Salons')" :value="$totalSalons" tone="brand">
        <x-slot:icon>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35"/></svg>
        </x-slot:icon>
    </x-stat>
    <x-stat :label="__('Total Bookings')" :value="$totalBookings" tone="violet">
        <x-slot:icon>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </x-slot:icon>
    </x-stat>
    <x-stat :label="__('Pending Bookings')" :value="$bookingsByStatus['pending'] ?? 0" tone="amber">
        <x-slot:icon>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-slot:icon>
    </x-stat>
</div>

<div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
    <x-card :padding="false">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-gray-900">{{ __('Bookings per Salon (top 10)') }}</h2>
        </div>
        @forelse ($bookingsPerSalon as $salon)
            <div class="flex items-center justify-between gap-4 border-b border-gray-50 px-5 py-3 last:border-0">
                <span class="text-sm text-gray-700">{{ $salon->name }}</span>
                <span class="text-sm font-semibold tabular-nums text-gray-900">{{ $salon->bookings_count }}</span>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400">{{ __('No salons yet.') }}</div>
        @endforelse
    </x-card>

    <x-card :padding="false">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-gray-900">{{ __('Recent Bookings') }}</h2>
        </div>
        @forelse ($recentBookings as $booking)
            <div class="border-b border-gray-50 px-5 py-3 last:border-0">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</span>
                    <x-badge :tone="['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$booking->status] ?? 'neutral'">
                        {{ __(ucfirst($booking->status)) }}
                    </x-badge>
                </div>
                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $booking->salon->name }} · {{ $booking->service->name }} · {{ $booking->datetime->translatedFormat(app()->getLocale() === 'ar' ? 'j M Y، H:i' : 'M j, Y H:i') }}
                </p>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400">{{ __('No bookings yet.') }}</div>
        @endforelse
    </x-card>
</div>

@endsection
