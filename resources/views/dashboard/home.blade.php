@extends('layouts.dashboard')

@section('title', __('Overview'))
@section('page-title', __('Overview'))

@section('dashboard-content')

{{-- Stats --}}
<div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
    <x-stat :label="__('Today')" :value="$todayCount" tone="brand">
        <x-slot:icon>
            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-slot:icon>
    </x-stat>
    <x-stat :label="__('Pending')" :value="$pendingCount" tone="amber">
        <x-slot:icon>
            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </x-slot:icon>
    </x-stat>
    <x-stat :label="__('Approved')" :value="$approvedCount" tone="emerald">
        <x-slot:icon>
            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </x-slot:icon>
    </x-stat>
    <x-stat :label="__('Total')" :value="$totalBookings" tone="violet">
        <x-slot:icon>
            <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </x-slot:icon>
    </x-stat>
</div>

{{-- Pending alert --}}
@if ($pendingCount > 0)
    <div class="mt-5 flex flex-wrap items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-3.5">
        <svg aria-hidden="true" class="h-5 w-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L9.13 3.378c.866-1.5 3.032-1.5 3.898 0l6.276 12.748zM12 15.75h.007v.008H12v-.008z"/></svg>
        <p class="flex-1 text-sm font-medium text-amber-800">
            {{ trans_choice('{1}One booking is waiting for your approval.|[2,*]:count bookings are waiting for your approval.', $pendingCount, ['count' => $pendingCount]) }}
        </p>
        <x-btn href="{{ route('dashboard.bookings.index', ['status' => 'pending']) }}" size="sm" variant="secondary">{{ __('Review') }}</x-btn>
    </div>
@endif

<div class="mt-6 grid grid-cols-1 gap-5 xl:grid-cols-2">
    {{-- Today's schedule --}}
    <x-card :padding="false">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-gray-900">{{ __("Today's Schedule") }}</h2>
            <span class="text-xs text-gray-400">{{ now()->translatedFormat(app()->getLocale() === 'ar' ? 'l، j F' : 'l, M j') }}</span>
        </div>
        @forelse ($todayBookings as $booking)
            <div class="flex items-start gap-4 border-b border-gray-50 px-5 py-3.5 last:border-0">
                <div class="w-12 shrink-0 pt-0.5">
                    <p dir="ltr" class="text-sm font-semibold tabular-nums text-gray-900">{{ $booking->datetime->format('H:i') }}</p>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</p>
                    <p class="mt-0.5 text-sm text-gray-500">
                        {{ $booking->service->name }}@if ($booking->employee) · <span class="text-brand-600">{{ $booking->employee->name }}</span>@endif
                    </p>
                </div>
                <x-badge :tone="['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$booking->status] ?? 'neutral'">
                    {{ __(ucfirst($booking->status)) }}
                </x-badge>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400">{{ __('No appointments scheduled for today.') }}</div>
        @endforelse
    </x-card>

    {{-- Upcoming confirmed --}}
    <x-card :padding="false">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-gray-900">{{ __('Upcoming Confirmed') }}</h2>
            <a href="{{ route('dashboard.bookings.index') }}" class="text-xs font-medium text-brand-600 hover:text-brand-700">{{ __('View all') }}</a>
        </div>
        @forelse ($upcoming as $booking)
            <div class="flex items-center justify-between gap-4 border-b border-gray-50 px-5 py-3.5 last:border-0">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</p>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $booking->service->name }}@if ($booking->employee) · {{ $booking->employee->name }}@endif</p>
                </div>
                <span class="shrink-0 text-xs tabular-nums text-gray-500">{{ $booking->datetime->translatedFormat(app()->getLocale() === 'ar' ? 'j M، H:i' : 'M j, H:i') }}</span>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400">{{ __('No upcoming confirmed bookings.') }}</div>
        @endforelse
    </x-card>
</div>

@endsection
