@extends('layouts.dashboard')

@section('title', __('Calendar'))
@section('page-title', __('Calendar'))

@section('dashboard-content')

<div class="mb-5 flex items-center justify-between">
    <x-btn href="{{ route('dashboard.bookings.calendar', ['month' => $month->clone()->subMonth()->format('Y-m-01')]) }}" variant="secondary" size="sm">
        <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        {{ __('Previous') }}
    </x-btn>
    <h2 class="text-base font-semibold tracking-tight text-gray-900">{{ $month->translatedFormat('F Y') }}</h2>
    <x-btn href="{{ route('dashboard.bookings.calendar', ['month' => $month->clone()->addMonth()->format('Y-m-01')]) }}" variant="secondary" size="sm">
        {{ __('Next') }}
        <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </x-btn>
</div>

<x-card :padding="false" class="overflow-x-auto">
    <div class="min-w-[640px]">
        <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50/60 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-400">
            @foreach (\App\Models\WorkingHour::DAYS as $dayName)
                <div class="py-2.5">{{ __($dayName) }}</div>
            @endforeach
        </div>
        <div class="divide-y divide-gray-100">
            @foreach ($weeks as $week)
                <div class="grid grid-cols-7 divide-x divide-gray-100 rtl:divide-x-reverse">
                    @foreach ($week as $day)
                        @php($dayBookings = $bookings->get($day->format('Y-m-d'), collect()))
                        <div class="min-h-[104px] p-2 text-xs {{ $day->month !== $month->month ? 'bg-gray-50/60 text-gray-300' : '' }}">
                            <div class="mb-1.5 flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold {{ $day->isToday() ? 'bg-brand-600 text-white' : 'text-gray-500' }}">{{ $day->day }}</div>
                            @foreach ($dayBookings->take(3) as $booking)
                                <div @class([
                                    'mb-1 truncate rounded-md px-1.5 py-0.5 text-[11px] font-medium',
                                    'bg-amber-50 text-amber-700' => $booking->status === 'pending',
                                    'bg-emerald-50 text-emerald-700' => $booking->status === 'approved',
                                ]) title="{{ $booking->customer_name }} — {{ $booking->service->name }}">
                                    <span dir="ltr">{{ $booking->datetime->format('H:i') }}</span> {{ $booking->customer_name }}
                                </div>
                            @endforeach
                            @if ($dayBookings->count() > 3)
                                <div class="text-[11px] text-gray-400">{{ __('+:count more', ['count' => $dayBookings->count() - 3]) }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-card>

@endsection
