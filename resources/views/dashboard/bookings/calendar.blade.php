@extends('layouts.dashboard')

@section('title', 'Calendar')
@section('page-title', 'Calendar')

@section('dashboard-content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('dashboard.bookings.calendar', ['month' => $month->clone()->subMonth()->format('Y-m-01')]) }}"
       class="rounded-lg border px-3 py-1.5 text-sm hover:bg-gray-50">&larr; Prev</a>
    <h2 class="text-lg font-semibold">{{ $month->format('F Y') }}</h2>
    <a href="{{ route('dashboard.bookings.calendar', ['month' => $month->clone()->addMonth()->format('Y-m-01')]) }}"
       class="rounded-lg border px-3 py-1.5 text-sm hover:bg-gray-50">Next &rarr;</a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
    <div class="grid grid-cols-7 bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="py-2">{{ $day }}</div>
        @endforeach
    </div>
    <div class="divide-y divide-gray-100">
        @foreach ($weeks as $week)
            <div class="grid grid-cols-7 divide-x divide-gray-100">
                @foreach ($week as $day)
                    @php($dayBookings = $bookings->get($day->format('Y-m-d'), collect()))
                    <div class="min-h-[110px] p-2 align-top text-xs {{ $day->month !== $month->month ? 'bg-gray-50 text-gray-300' : '' }}">
                        <div class="mb-1 font-semibold {{ $day->isToday() ? 'text-brand-600' : '' }}">{{ $day->day }}</div>
                        @foreach ($dayBookings->take(3) as $booking)
                            <div @class([
                                'mb-1 truncate rounded px-1 py-0.5',
                                'bg-yellow-100 text-yellow-700' => $booking->status === 'pending',
                                'bg-green-100 text-green-700' => $booking->status === 'approved',
                            ]) title="{{ $booking->customer_name }} - {{ $booking->service->name }}">
                                {{ $booking->datetime->format('H:i') }} {{ $booking->customer_name }}
                            </div>
                        @endforeach
                        @if ($dayBookings->count() > 3)
                            <div class="text-gray-400">+{{ $dayBookings->count() - 3 }} more</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endsection
