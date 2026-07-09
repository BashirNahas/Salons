@extends('layouts.dashboard')

@section('title', __('Edit Staff Member'))
@section('page-title', __('Edit Staff Member'))

@section('dashboard-content')
<div class="mx-auto max-w-xl">
    <x-card>
        <div class="mb-6 flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-lg font-semibold text-white">
                {{ $employee->initials() }}
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">{{ $employee->name }}</h2>
                <p class="text-xs text-gray-400">{{ __('Edit profile') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.employees.update', $employee) }}">
            @csrf
            @method('PUT')
            @include('dashboard.employees._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.employees.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Save Changes') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
