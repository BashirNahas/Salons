@extends('layouts.dashboard')

@section('title', 'Add Regular Customer')
@section('page-title', 'Add Regular Customer')

@section('dashboard-content')

<div class="mx-auto max-w-xl">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <p class="mb-5 text-sm text-gray-500">Reserve a standing weekly slot for a customer (e.g. every Saturday at 10:00). Upcoming appointments are created automatically and blocked from online booking.</p>

        <form method="POST" action="{{ route('dashboard.recurring-bookings.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('customer_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                    <input type="tel" dir="ltr" name="customer_phone" value="{{ old('customer_phone') }}" required
                           class="w-full rounded-xl border-gray-300 text-left shadow-sm focus:border-brand-500 focus:ring-brand-500"
                           placeholder="+963 ...">
                    @error('customer_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Service <span class="text-red-500">*</span></label>
                    <select name="service_id" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Select…</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ $service->duration_minutes }} min)
                            </option>
                        @endforeach
                    </select>
                    @error('service_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                @if($employees->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Staff</label>
                        <select name="employee_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                            <option value="">Any staff</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Day of Week <span class="text-red-500">*</span></label>
                    <select name="day_of_week" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @foreach(\App\Models\WorkingHour::DAYS as $num => $label)
                            <option value="{{ $num }}" {{ old('day_of_week') == $num ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Time <span class="text-red-500">*</span></label>
                    <input type="time" name="time" value="{{ old('time') }}" required
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" required
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @error('end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('dashboard.recurring-bookings.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Add Regular Customer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
