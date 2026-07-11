@php($recurringBooking = $recurringBooking ?? null)

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <x-input :label="__('Customer Name')" name="customer_name" :value="$recurringBooking?->customer_name" required />
    <x-phone-input :label="__('Phone')" name="customer_phone" :value="$recurringBooking?->customer_phone" required />

    <x-select :label="__('Service')" name="service_id" required>
        @unless ($recurringBooking)
            <option value="">{{ __('Select…') }}</option>
        @endunless
        @foreach ($services as $service)
            <option value="{{ $service->id }}" @selected(old('service_id', $recurringBooking?->service_id) == $service->id)>
                {{ $service->name }} ({{ $service->duration_minutes }} {{ __('min') }})
            </option>
        @endforeach
    </x-select>

    @if ($employees->isNotEmpty())
        <x-select :label="__('Staff')" name="employee_id">
            <option value="">{{ __('Any staff') }}</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected(old('employee_id', $recurringBooking?->employee_id) == $employee->id)>{{ $employee->name }}</option>
            @endforeach
        </x-select>
    @endif

    <x-select :label="__('Day of Week')" name="day_of_week" required>
        @foreach (\App\Models\WorkingHour::DAYS as $num => $label)
            <option value="{{ $num }}" @selected(old('day_of_week', $recurringBooking?->day_of_week) == $num)>{{ __($label) }}</option>
        @endforeach
    </x-select>

    <x-input :label="__('Time')" name="time" type="time" :value="$recurringBooking?->time?->format('H:i')" required />
    <x-input :label="__('Start Date')" name="start_date" type="date" :value="$recurringBooking?->start_date?->toDateString() ?? now()->toDateString()" required />
    <x-input :label="__('End Date')" name="end_date" type="date" :value="$recurringBooking?->end_date?->toDateString()" optional />
</div>

<div class="mt-5 space-y-5">
    <x-textarea :label="__('Notes')" name="notes" :value="$recurringBooking?->notes" rows="2" optional />
    <x-checkbox :label="__('Active')" name="is_active" :checked="$recurringBooking?->is_active ?? true"
                :hint="__('Pausing removes this customer\'s upcoming reserved appointments.')" />
</div>
