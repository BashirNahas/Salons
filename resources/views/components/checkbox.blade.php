@props([
    'label',
    'name',
    'checked' => false,
    'hint' => null,
    'hidden' => false, // emit a hidden 0 so unchecked still submits
])

<div>
    <label class="flex items-start gap-2.5">
        @if ($hidden)
            <input type="hidden" name="{{ $name }}" value="0">
        @endif
        <input type="checkbox" name="{{ $name }}" value="1"
               @checked(old($name, $checked))
               {{ $attributes->merge(['class' => 'mt-0.5 h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-600']) }}>
        <span>
            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
            @if ($hint)
                <span class="block text-xs text-gray-400">{{ $hint }}</span>
            @endif
        </span>
    </label>
</div>
