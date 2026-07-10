@php
    // Salon owners can pick a brand color that reskins their public booking
    // page (buttons, header accents, hero banner). Scoped to customer-facing
    // "public.*" routes only, so every owner's dashboard keeps the same
    // product identity regardless of what their customers see. The palette
    // travels to the frontend as a data attribute; assets/js/tailwind-config.js
    // turns it into the Tailwind "brand" color scale.
    $brandColorHex = request()->routeIs('public.*') && currentSalon()
        ? currentSalon()->brand_color
        : null;
    $brandPalette = \App\Support\ColorPalette::fromHex($brandColorHex);
    $isRtl = app()->getLocale() === 'ar';
    $assetVersion = '20260710';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}" data-brand-palette="{{ json_encode($brandPalette) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|cairo:400,500,600,700&display=swap" rel="stylesheet">
    <script src="{{ asset('assets/js/tailwind-config.js') }}?v={{ $assetVersion }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}?v={{ $assetVersion }}">
    <script defer src="{{ asset('assets/js/app.js') }}?v={{ $assetVersion }}"></script>
    @stack('head')
    {{-- Alpine loads last: deferred scripts run in order, so page components
         (e.g. booking-wizard.js pushed to the head stack) are defined before
         Alpine boots and evaluates x-data expressions. --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    {{ $slot ?? '' }}
    @yield('content')
</body>
</html>
