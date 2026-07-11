@extends('layouts.dashboard')

@section('title', __('Add Regular Customer'))
@section('page-title', __('Add Regular Customer'))

@section('dashboard-content')
<div class="mx-auto max-w-xl">
    <x-card>
        <p class="mb-6 text-sm text-gray-500">{{ __('Reserve a standing weekly slot for a customer (e.g. every Saturday at 10:00). Upcoming appointments are created automatically and blocked from online booking.') }}</p>

        <form method="POST" action="{{ route('dashboard.recurring-bookings.store') }}">
            @csrf
            @include('dashboard.recurring-bookings._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.recurring-bookings.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Add Regular Customer') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
