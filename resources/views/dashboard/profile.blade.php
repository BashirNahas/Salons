@extends('layouts.dashboard')

@section('title', 'Salon Profile')
@section('page-title', 'Salon Profile')

@section('dashboard-content')

<div class="mx-auto max-w-2xl">
    <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
        <p class="mb-5 text-sm text-gray-500">This information is displayed on your public booking page.</p>

        <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Logo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Salon Logo</label>
                <div class="flex items-center gap-4">
                    @if($salon->logo)
                        <img src="{{ asset('storage/'.$salon->logo) }}" alt="Current logo" class="h-14 w-14 rounded-xl object-cover ring-2 ring-brand-100">
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-brand-100 text-2xl font-bold text-brand-700">
                            {{ mb_strtoupper(mb_substr($salon->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <input type="file" name="logo" accept="image/*" class="text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-700 hover:file:bg-brand-100">
                        <p class="mt-1 text-xs text-gray-400">JPG, PNG up to 2 MB. Square images work best.</p>
                    </div>
                </div>
                @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-gray-100 pt-5">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Salon Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $salon->name) }}" required
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $salon->phone) }}"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                               placeholder="+963 ...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $salon->email) }}"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                               placeholder="salon@example.com">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                        <input type="text" name="address" value="{{ old('address', $salon->address) }}"
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                               placeholder="Street, City">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                                  placeholder="Tell customers what makes your salon special…">{{ old('description', $salon->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Instagram Handle</label>
                        <div class="flex rounded-xl border border-gray-300 shadow-sm focus-within:border-brand-500 focus-within:ring-1 focus-within:ring-brand-500">
                            <span class="flex items-center rounded-l-xl bg-gray-50 px-3 text-sm text-gray-500 border-r border-gray-300">@</span>
                            <input type="text" name="instagram" value="{{ old('instagram', $salon->instagram) }}"
                                   class="flex-1 rounded-r-xl border-0 focus:ring-0 text-sm"
                                   placeholder="yoursalon">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="submit" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Save Profile
                </button>
            </div>
        </form>
    </div>

    <div class="mt-4 rounded-xl bg-gray-50 px-5 py-4 text-sm text-gray-500 ring-1 ring-gray-100">
        <strong class="font-medium text-gray-700">Subdomain:</strong>
        <a href="{{ $salon->url() }}" target="_blank" class="ml-1 text-brand-600 hover:underline">{{ $salon->subdomain() }}</a>
        — This is your public booking URL. To change the subdomain, contact the platform admin.
    </div>
</div>

@endsection
