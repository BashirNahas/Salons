@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center']) }}>
    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
        {{ $icon ?? '' }}
    </div>
    <h3 class="mt-4 text-sm font-semibold text-gray-900">{{ $title }}</h3>
    @if ($description)
        <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
