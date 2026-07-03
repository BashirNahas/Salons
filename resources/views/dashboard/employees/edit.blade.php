@extends('layouts.dashboard')

@section('title', 'Edit Staff Member')
@section('page-title', 'Edit Staff Member')

@section('dashboard-content')

<div class="mx-auto max-w-xl">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <div class="mb-5 flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-xl font-bold text-white">
                {{ $employee->initials() }}
            </div>
            <div>
                <h2 class="font-semibold text-gray-800">{{ $employee->name }}</h2>
                <p class="text-sm text-gray-500">Edit profile</p>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.employees.update', $employee) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Specialties</label>
                <input type="text" name="specialties" value="{{ old('specialties', $employee->specialties) }}"
                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                       placeholder="e.g. Fades, Beard Trims, Classic Cuts">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('bio', $employee->bio) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Display Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $employee->sort_order) }}" min="0"
                       class="w-24 rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible to customers)</label>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('dashboard.employees.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
