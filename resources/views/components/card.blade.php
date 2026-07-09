@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white shadow-card ring-1 ring-gray-950/5 '.($padding ? 'p-6' : '')]) }}>
    {{ $slot }}
</div>
