@php($salon = $salon ?? null)

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <x-input :label="__('Salon Name')" name="name" :value="$salon?->name" required />

    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Subdomain (slug)') }} <span class="text-red-500">*</span></label>
        <div dir="ltr" class="flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-brand-600">
            <input type="text" name="slug" value="{{ old('slug', $salon?->slug) }}" required
                   class="w-full rounded-s-lg border-0 bg-transparent py-2.5 text-sm focus:ring-0">
            <span class="flex items-center rounded-e-lg border-s border-gray-200 bg-gray-50 px-3 text-sm text-gray-500">.{{ config('tenancy.central_domain') }}</span>
        </div>
        @error('slug') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
    </div>

    <x-phone-input :label="__('Phone')" name="phone" :value="$salon?->phone" />
    <x-input :label="__('Address')" name="address" :value="$salon?->address" />
    <x-textarea :label="__('Description')" name="description" :value="$salon?->description" rows="3" class="sm:col-span-2" />
</div>

<div class="mt-6 border-t border-gray-100 pt-6">
    <h3 class="mb-1 text-sm font-semibold text-gray-900">{{ __('Subscription') }}</h3>
    <p class="mb-4 text-xs text-gray-400">{{ __('Leave the end date empty for an unlimited subscription. After the end date, the salon is automatically disabled.') }}</p>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <x-input :label="__('Subscription Start')" name="subscription_starts_at" type="date" :value="$salon?->subscription_starts_at?->toDateString()" optional />
        <x-input :label="__('Subscription End')" name="subscription_ends_at" type="date" :value="$salon?->subscription_ends_at?->toDateString()" optional />
    </div>

    @if ($salon)
        <div class="mt-5">
            <x-checkbox :label="__('Salon is ON (reachable by owner and customers)')" name="is_active" :checked="$salon->is_active" hidden
                        :hint="__('Turning this off makes the salon immediately unavailable, regardless of the subscription dates.')" />
        </div>
    @endif
</div>

<div class="mt-6 border-t border-gray-100 pt-6">
    <h3 class="mb-4 text-sm font-semibold text-gray-900">{{ __('Owner Account') }}</h3>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <x-input :label="__('Owner Name')" name="owner_name" :value="$salon?->owner->name" required />
        <x-input :label="__('Owner Email')" name="owner_email" type="email" :value="$salon?->owner->email" required dir="ltr" />
        <x-input :label="__('Password')" name="owner_password" type="password" autocomplete="new-password" class="sm:col-span-2"
                 :hint="$salon ? __('Leave blank to keep the current password.') : __('Leave blank to generate a secure password automatically.')" />
    </div>
</div>
