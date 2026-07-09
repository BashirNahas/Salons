@extends('layouts.dashboard')

@section('title', __('Salon Profile'))
@section('page-title', __('Salon Profile'))

@section('dashboard-content')

<div class="mx-auto max-w-2xl">
    <x-card>
        <p class="mb-6 text-sm text-gray-500">{{ __('This information is displayed on your public booking page.') }}</p>

        <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Logo --}}
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">{{ __('Salon Logo') }}</label>
                <div class="flex items-center gap-4">
                    @if ($salon->logo)
                        <img src="{{ $salon->logoUrl() }}" alt="" class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-gray-950/10">
                    @else
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-xl font-semibold text-brand-600">
                            {{ mb_strtoupper(mb_substr($salon->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <input type="file" name="logo" accept="image/*"
                               class="block w-full text-sm text-gray-600 file:me-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-brand-700 hover:file:bg-brand-100">
                        <p class="mt-1 text-xs text-gray-400">{{ __('JPG or PNG up to 2 MB. Square images work best.') }}</p>
                        @error('logo') <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 border-t border-gray-100 pt-6 sm:grid-cols-2">
                <x-input :label="__('Salon Name')" name="name" :value="$salon->name" required class="sm:col-span-2" />
                <x-phone-input :label="__('Phone')" name="phone" :value="$salon->phone" />
                <x-input :label="__('Email')" name="email" type="email" :value="$salon->email" dir="ltr" placeholder="salon@example.com" />
                <x-input :label="__('Address')" name="address" :value="$salon->address" class="sm:col-span-2" :placeholder="__('Street, City')" />
                <x-textarea :label="__('Description')" name="description" :value="$salon->description" rows="4" class="sm:col-span-2"
                            :placeholder="__('Tell customers what makes your salon special…')" />

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Instagram Handle') }}</label>
                    <div dir="ltr" class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-brand-600">
                        <span class="flex items-center rounded-s-lg border-e border-gray-200 bg-gray-50 px-3 text-sm text-gray-500">@</span>
                        <input type="text" name="instagram" value="{{ old('instagram', $salon->instagram) }}"
                               class="w-full rounded-e-lg border-0 bg-transparent py-2.5 text-sm focus:ring-0" placeholder="yoursalon">
                    </div>
                    @error('instagram') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Brand Color') }}</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="brand_color" value="{{ old('brand_color', $salon->brand_color ?? '#db2777') }}"
                               class="h-10 w-14 shrink-0 cursor-pointer rounded-lg border border-gray-300 p-1">
                        <p class="text-xs leading-5 text-gray-400">{{ __('Used on your public booking page only.') }}</p>
                    </div>
                    @error('brand_color') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-5">
                <x-btn>{{ __('Save Profile') }}</x-btn>
            </div>
        </form>
    </x-card>

    <div class="mt-4 rounded-2xl bg-gray-100/80 px-5 py-4 text-sm text-gray-500">
        <span class="font-medium text-gray-700">{{ __('Your booking page') }}:</span>
        <a href="{{ $salon->url() }}" target="_blank" dir="ltr" class="ms-1 font-medium text-brand-600 hover:underline">{{ $salon->subdomain() }}</a>
    </div>
</div>

@endsection
