@props([
    'label' => null,
    'name' => 'customer_phone',
    'value' => null,
    'required' => false,
])

{{--
    Phone numbers are inherently left-to-right data. type="tel" gets a
    global LTR/bidi-isolation treatment in layouts/base.blade.php so the
    digits, caret movement, and copy/paste all behave naturally even when
    the surrounding interface is right-to-left Arabic.
--}}
<div {{ $attributes->only('class') }}>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input
        type="tel"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        inputmode="tel"
        autocomplete="tel"
        placeholder="+963 9XX XXX XXX"
        @if ($required) required @endif
        {{ $attributes->except('class')->merge(['class' => 'block w-full rounded-lg border-0 py-2.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-600']) }}
    >
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
