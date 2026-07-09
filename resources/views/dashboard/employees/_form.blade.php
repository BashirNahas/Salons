@php($employee = $employee ?? null)

<div class="space-y-5">
    <x-input :label="__('Full Name')" name="name" :value="$employee?->name" required :placeholder="__('e.g. Ahmad Khalil')" />
    <x-input :label="__('Specialties')" name="specialties" :value="$employee?->specialties"
             :placeholder="__('e.g. Fades, Beard Trims, Classic Cuts')"
             :hint="__('What does this person specialize in? Shown to customers.')" />
    <x-textarea :label="__('Bio')" name="bio" :value="$employee?->bio" rows="3" optional
                :placeholder="__('A short introduction shown to customers…')" />
    <x-input :label="__('Display Order')" name="sort_order" type="number" min="0" :value="$employee?->sort_order ?? 0"
             class="max-w-[8rem]" :hint="__('Lower number appears first.')" />
    <x-checkbox :label="__('Active (visible to customers)')" name="is_active" :checked="$employee?->is_active ?? true" hidden />
</div>
