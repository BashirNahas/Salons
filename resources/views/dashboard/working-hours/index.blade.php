@extends('layouts.dashboard')

@section('title', __('Working Hours'))
@section('page-title', __('Working Hours'))

@section('dashboard-content')
<div class="mx-auto max-w-2xl">
    <x-card>
        <p class="mb-6 text-sm text-gray-500">{{ __('Customers can only book within these hours.') }}</p>

        <form method="POST" action="{{ route('dashboard.working-hours.update') }}">
            @csrf
            @method('PUT')

            <div class="divide-y divide-gray-100">
                @foreach (\App\Models\WorkingHour::DAYS as $dayNumber => $dayName)
                    @php($wh = $existing->get($dayNumber))
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 py-3.5">
                        <div class="w-24 shrink-0 text-sm font-medium text-gray-900">{{ __($dayName) }}</div>
                        <label class="flex shrink-0 items-center gap-2 text-sm text-gray-500">
                            <input type="checkbox" name="days[{{ $dayNumber }}][is_closed]" value="1"
                                   class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-600"
                                   @checked(old("days.$dayNumber.is_closed", $wh?->is_closed ?? false))>
                            {{ __('Closed') }}
                        </label>
                        <div class="flex items-center gap-2" dir="ltr">
                            <input type="time" name="days[{{ $dayNumber }}][start_time]"
                                   value="{{ old("days.$dayNumber.start_time", $wh?->start_time?->format('H:i') ?? '09:00') }}"
                                   class="rounded-lg border-0 py-1.5 text-sm shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-600">
                            <span class="text-gray-400">–</span>
                            <input type="time" name="days[{{ $dayNumber }}][end_time]"
                                   value="{{ old("days.$dayNumber.end_time", $wh?->end_time?->format('H:i') ?? '18:00') }}"
                                   class="rounded-lg border-0 py-1.5 text-sm shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-600">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end border-t border-gray-100 pt-5">
                <x-btn>{{ __('Save Working Hours') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
