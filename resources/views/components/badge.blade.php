@props(['tone' => 'neutral']) {{-- success | warning | danger | info | purple | neutral --}}

@php
    $tones = [
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-red-600/20',
        'info' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
        'purple' => 'bg-violet-50 text-violet-700 ring-violet-600/20',
        'neutral' => 'bg-gray-50 text-gray-600 ring-gray-500/20',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset '.$tones[$tone]]) }}>{{ $slot }}</span>
