@extends('layouts.dashboard')

@section('title', 'Add Staff Member')
@section('page-title', 'Add Staff Member')

@section('dashboard-content')

<div class="mx-auto max-w-xl">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <form method="POST" action="{{ route('dashboard.employees.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                       placeholder="e.g. Ahmad Khalil">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Specialties</label>
                <input type="text" name="specialties" value="{{ old('specialties') }}"
                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                       placeholder="e.g. Fades, Beard Trims, Classic Cuts">
                <p class="mt-1 text-xs text-gray-400">What services does this person specialize in?</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                          placeholder="A short introduction shown to customers…">{{ old('bio') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Display Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-24 rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <p class="mt-1 text-xs text-gray-400">Lower number appears first.</p>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible to customers)</label>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('dashboard.employees.index') }}" class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Add Staff Member
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
