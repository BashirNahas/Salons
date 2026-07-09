@props([
    'label',
    'value',
    'tone' => 'brand', // brand | amber | emerald | blue | violet | gray
])

@php
    $tones = [
        'brand' => 'bg-brand-50 text-brand-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'violet' => 'bg-violet-50 text-violet-600',
        'gray' => 'bg-gray-100 text-gray-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white p-5 shadow-card ring-1 ring-gray-950/5']) }}>
    <div class="flex items-center gap-3.5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tones[$tone] }}">
            {{ $icon ?? '' }}
        </div>
        <div class="min-w-0">
            <p class="truncate text-xs font-medium text-gray-500">{{ $label }}</p>
            <p class="mt-0.5 text-2xl font-semibold tracking-tight text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>
