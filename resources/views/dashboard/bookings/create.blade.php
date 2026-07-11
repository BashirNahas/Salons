@extends('layouts.dashboard')

@section('title', __('Add Appointment'))
@section('page-title', __('Add Appointment'))

@section('dashboard-content')

<div class="mx-auto max-w-xl">
    <x-card>
        <p class="mb-6 text-sm text-gray-500">{{ __('For walk-ins or phone reservations. This appointment is confirmed immediately and blocks the slot from online booking.') }}</p>

        <form method="POST" action="{{ route('dashboard.bookings.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-input :label="__('Customer Name')" name="customer_name" required />
                <x-phone-input :label="__('Phone')" name="customer_phone" required />

                <x-select :label="__('Service')" name="service_id" required>
                    <option value="">{{ __('Select…') }}</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                            {{ $service->name }} ({{ $service->duration_minutes }} {{ __('min') }})
                        </option>
                    @endforeach
                </x-select>

                @if ($employees->isNotEmpty())
                    <x-select :label="__('Staff')" name="employee_id">
                        <option value="">{{ __('Any staff') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </x-select>
                @endif

                <x-input :label="__('Date')" name="date" type="date" required />
                <x-input :label="__('Time')" name="time" type="time" required />
            </div>

            <x-textarea :label="__('Notes')" name="notes" rows="2" optional />

            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.bookings.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Add Appointment') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>

@endsection
