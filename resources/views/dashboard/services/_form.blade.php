@php($service = $service ?? null)

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Service Name</label>
        <input type="text" name="name" value="{{ old('name', $service?->name) }}" required placeholder="e.g. Haircut"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
        <input type="number" name="duration_minutes" min="5" step="5" value="{{ old('duration_minutes', $service?->duration_minutes ?? 30) }}" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Price (optional)</label>
        <input type="number" name="price" min="0" step="0.01" value="{{ old('price', $service?->price) }}"
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>

    @if ($service)
        <div class="sm:col-span-2">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-brand-600" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                Active (bookable by customers)
            </label>
        </div>
    @endif
</div>
