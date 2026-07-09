@php($service = $service ?? null)

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <x-input :label="__('Service Name')" name="name" :value="$service?->name" required class="sm:col-span-2" :placeholder="__('e.g. Haircut')" />
    <x-input :label="__('Duration (minutes)')" name="duration_minutes" type="number" min="5" step="5" :value="$service?->duration_minutes ?? 30" required />
    <x-input :label="__('Price')" name="price" type="number" min="0" step="0.01" :value="$service?->price" optional />

    @if ($service)
        <div class="sm:col-span-2">
            <x-checkbox :label="__('Active (bookable by customers)')" name="is_active" :checked="$service->is_active" hidden />
        </div>
    @endif
</div>
