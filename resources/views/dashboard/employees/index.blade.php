@extends('layouts.dashboard')

@section('title', 'Staff / Barbers')
@section('page-title', 'Staff / Barbers')

@section('dashboard-content')

<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-gray-500">Manage the barbers and staff members at your salon.</p>
    <a href="{{ route('dashboard.employees.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Staff Member
    </a>
</div>

@if($employees->isEmpty())
    <div class="rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-500">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <p class="mt-4 font-semibold text-gray-700">No staff members yet</p>
        <p class="mt-1 text-sm text-gray-400">Add your first barber or staff member to let customers choose them when booking.</p>
        <a href="{{ route('dashboard.employees.create') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
            Add First Staff Member
        </a>
    </div>
@else
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($employees as $employee)
            <div class="flex items-start gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
                @if($employee->avatar_url)
                    <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="h-14 w-14 shrink-0 rounded-full object-cover ring-2 ring-brand-100">
                @else
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-lg font-bold text-white">
                        {{ $employee->initials() }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $employee->name }}</h3>
                            @if($employee->specialties)
                                <p class="mt-0.5 text-xs text-gray-500">{{ $employee->specialties }}</p>
                            @endif
                        </div>
                        <span @class([
                            'shrink-0 rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-green-100 text-green-700' => $employee->is_active,
                            'bg-gray-100 text-gray-500' => !$employee->is_active,
                        ])>{{ $employee->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    @if($employee->bio)
                        <p class="mt-1.5 text-sm text-gray-500 line-clamp-2">{{ $employee->bio }}</p>
                    @endif
                    <div class="mt-3 flex gap-3">
                        <a href="{{ route('dashboard.employees.edit', $employee) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Edit</a>
                        <form method="POST" action="{{ route('dashboard.employees.destroy', $employee) }}" onsubmit="return confirm('Remove {{ addslashes($employee->name) }} from your team?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
