@extends('layouts.dashboard')

@section('title', 'Working Hours')
@section('page-title', 'Working Hours')

@section('dashboard-content')
<div class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
    <form method="POST" action="{{ route('dashboard.working-hours.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            @foreach (\App\Models\WorkingHour::DAYS as $dayNumber => $dayName)
                @php($wh = $existing->get($dayNumber))
                <div class="flex flex-wrap items-center gap-3 rounded-lg border border-gray-200 p-3">
                    <div class="w-28 font-medium">{{ $dayName }}</div>
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="days[{{ $dayNumber }}][is_closed]" value="1"
                               class="rounded border-gray-300 text-brand-600 closed-toggle"
                               {{ old("days.$dayNumber.is_closed", $wh?->is_closed ?? false) ? 'checked' : '' }}>
                        Closed
                    </label>
                    <input type="time" name="days[{{ $dayNumber }}][start_time]"
                           value="{{ old("days.$dayNumber.start_time", $wh?->start_time?->format('H:i') ?? '09:00') }}"
                           class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    <span class="text-gray-400">to</span>
                    <input type="time" name="days[{{ $dayNumber }}][end_time]"
                           value="{{ old("days.$dayNumber.end_time", $wh?->end_time?->format('H:i') ?? '18:00') }}"
                           class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Save Working Hours</button>
        </div>
    </form>
</div>
@endsection
