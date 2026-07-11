@extends('layouts.dashboard')

@section('title', __('Staff'))
@section('page-title', __('Staff'))

@section('dashboard-content')

<div class="mb-5 flex items-center justify-between gap-3">
    <p class="text-sm text-gray-500">{{ __('The barbers and staff members customers can choose when booking.') }}</p>
    <x-btn href="{{ route('dashboard.employees.create') }}" size="sm" class="shrink-0">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Staff Member') }}
    </x-btn>
</div>

@if ($employees->isEmpty())
    <x-empty-state :title="__('No staff members yet')" :description="__('Add your first barber or staff member to let customers choose them when booking.')">
        <x-slot:icon>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </x-slot:icon>
        <x-slot:action>
            <x-btn href="{{ route('dashboard.employees.create') }}" size="sm">{{ __('Add Staff Member') }}</x-btn>
        </x-slot:action>
    </x-empty-state>
@else
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($employees as $employee)
            <x-card class="!p-5">
                <div class="flex items-start gap-4">
                    @if ($employee->avatar_url)
                        <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="h-12 w-12 shrink-0 rounded-full object-cover ring-1 ring-gray-950/10">
                    @else
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-base font-semibold text-white">
                            {{ $employee->initials() }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-semibold text-gray-900">{{ $employee->name }}</h3>
                                @if ($employee->specialties)
                                    <p class="mt-0.5 truncate text-xs text-gray-500">{{ $employee->specialties }}</p>
                                @endif
                            </div>
                            <x-badge :tone="$employee->is_active ? 'success' : 'neutral'">{{ $employee->is_active ? __('Active') : __('Inactive') }}</x-badge>
                        </div>
                        @if ($employee->bio)
                            <p class="mt-2 line-clamp-2 text-xs leading-5 text-gray-500">{{ $employee->bio }}</p>
                        @endif
                        <div class="mt-3 flex items-center gap-1">
                            <x-btn href="{{ route('dashboard.employees.edit', $employee) }}" variant="ghost" size="sm">{{ __('Edit') }}</x-btn>
                            <form method="POST" action="{{ route('dashboard.employees.destroy', $employee) }}" onsubmit="return confirm(@js(__('Remove :name from your team?', ['name' => $employee->name])));">
                                @csrf
                                @method('DELETE')
                                <x-btn variant="ghost" size="sm" class="!text-red-600">{{ __('Remove') }}</x-btn>
                            </form>
                        </div>
                    </div>
                </div>
            </x-card>
        @endforeach
    </div>
@endif

@endsection
