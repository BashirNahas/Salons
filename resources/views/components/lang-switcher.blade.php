@php
    $routeName = currentSalon() ? 'public.locale.update' : 'locale.update';
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full bg-gray-100 p-0.5 text-xs font-medium']) }}>
    <a href="{{ route($routeName, 'en') }}"
       class="rounded-full px-2.5 py-1 transition {{ app()->getLocale() === 'en' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
    <a href="{{ route($routeName, 'ar') }}"
       class="rounded-full px-2.5 py-1 transition {{ app()->getLocale() === 'ar' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">عربي</a>
</div>
