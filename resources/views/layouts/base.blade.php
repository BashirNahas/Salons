@php
    // Salon owners can pick a brand color that reskins their public booking
    // page (buttons, header accents, hero banner). Scoped to customer-facing
    // "public.*" routes only, so every owner's dashboard stays the same
    // familiar pink regardless of what their customers see.
    $brandColorHex = request()->routeIs('public.*') && currentSalon()
        ? currentSalon()->brand_color
        : null;
    $brandPalette = \App\Support\ColorPalette::fromHex($brandColorHex);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: @json($brandPalette),
                    },
                },
            },
        }
    </script>
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">
    {{ $slot ?? '' }}
    @yield('content')
</body>
</html>
