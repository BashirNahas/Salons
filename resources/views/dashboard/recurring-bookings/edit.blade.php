@extends('layouts.dashboard')

@section('title', __('Edit Regular Customer'))
@section('page-title', __('Edit Regular Customer'))

@section('dashboard-content')
<div class="mx-auto max-w-xl">
    <x-card>
        <p class="mb-6 text-sm text-gray-500">{{ __('Changing the day, time, service, or staff will regenerate all upcoming appointments for this customer.') }}</p>

        <form method="POST" action="{{ route('dashboard.recurring-bookings.update', $recurringBooking) }}">
            @csrf
            @method('PUT')
            @include('dashboard.recurring-bookings._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.recurring-bookings.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Save Changes') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
