@props([
    'label' => null,
    'name',
    'value' => null,
    'rows' => 3,
    'required' => false,
    'optional' => false,
    'hint' => null,
])

<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)<span class="text-red-500">*</span>@endif
            @if ($optional)<span class="font-normal text-gray-400">({{ __('optional') }})</span>@endif
        </label>
    @endif
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if ($required) required @endif
        {{ $attributes->except('class')->merge(['class' => 'block w-full rounded-lg border-0 py-2.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-600']) }}
    >{{ old($name, $value) }}</textarea>
    @if ($hint)
        <p class="mt-1.5 text-xs text-gray-400">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
