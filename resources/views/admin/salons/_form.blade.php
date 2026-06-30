@php($salon = $salon ?? null)

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-gray-700">Salon Name</label>
        <input type="text" name="name" value="{{ old('name', $salon?->name) }}" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Subdomain (slug)</label>
        <div class="mt-1 flex rounded-lg shadow-sm">
            <input type="text" name="slug" value="{{ old('slug', $salon?->slug) }}" required
                   class="w-full rounded-l-lg border-gray-300 focus:border-brand-500 focus:ring-brand-500">
            <span class="inline-flex items-center rounded-r-lg border border-l-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">.{{ config('tenancy.central_domain') }}</span>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $salon?->phone) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Address</label>
        <input type="text" name="address" value="{{ old('address', $salon?->address) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="3"
                  class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('description', $salon?->description) }}</textarea>
    </div>

    @if ($salon)
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-brand-600" {{ old('is_active', $salon->is_active) ? 'checked' : '' }}>
                Active (visible &amp; bookable)
            </label>
        </div>
    @endif
</div>

<hr class="my-6 border-gray-200">

<h3 class="mb-3 text-sm font-semibold text-gray-700">Owner Account</h3>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-gray-700">Owner Name</label>
        <input type="text" name="owner_name" value="{{ old('owner_name', $salon?->owner->name) }}" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Owner Email</label>
        <input type="email" name="owner_email" value="{{ old('owner_email', $salon?->owner->email) }}" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">
            Password {{ $salon ? '(leave blank to keep current)' : '(leave blank to auto-generate)' }}
        </label>
        <input type="password" name="owner_password" autocomplete="new-password"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
</div>
