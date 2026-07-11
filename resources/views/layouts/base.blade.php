@php
    // Salon owners can pick a brand color that reskins their public booking
    // page (buttons, header accents, hero banner). Scoped to customer-facing
    // "public.*" routes only, so every owner's dashboard keeps the same
    // product identity regardless of what their customers see.
    $brandColorHex = request()->routeIs('public.*') && currentSalon()
        ? currentSalon()->brand_color
        : null;
    $brandPalette = \App\Support\ColorPalette::fromHex($brandColorHex);
    $isRtl = app()->getLocale() === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|cairo:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Cairo', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Tahoma', 'sans-serif'],
                    },
                    colors: {
                        brand: @json($brandPalette),
                    },
                    boxShadow: {
                        card: '0 1px 2px 0 rgb(9 9 11 / 0.04), 0 1px 3px 0 rgb(9 9 11 / 0.06)',
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            html { -webkit-tap-highlight-color: transparent; }
            [dir="rtl"] body { letter-spacing: 0 !important; }
            input[type="tel"] { direction: ltr; text-align: start; unicode-bidi: plaintext; }
            [dir="rtl"] input[type="tel"] { text-align: right; }
            input[type="tel"]::placeholder { direction: ltr; unicode-bidi: plaintext; }
        }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-900 antialiased">
    {{ $slot ?? '' }}
    @yield('content')
</body>
</html>
