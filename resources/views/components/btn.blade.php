@props([
    'variant' => 'primary', // primary | secondary | danger | ghost | dark
    'size' => 'md',         // sm | md | lg
    'href' => null,
    'type' => 'submit',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-45';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-sm',
    ];

    $variants = [
        'primary' => 'bg-brand-600 text-white shadow-sm hover:bg-brand-700 focus-visible:outline-brand-600',
        'dark' => 'bg-gray-900 text-white shadow-sm hover:bg-gray-700 focus-visible:outline-gray-900',
        'secondary' => 'bg-white text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus-visible:outline-gray-400',
        'danger' => 'bg-red-600 text-white shadow-sm hover:bg-red-500 focus-visible:outline-red-600',
        'ghost' => 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-gray-400',
    ];

    $classes = $base.' '.$sizes[$size].' '.$variants[$variant];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
