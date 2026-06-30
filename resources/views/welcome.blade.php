<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Salons System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdf2f8', 100: '#fce7f3', 200: '#fbcfe8', 300: '#f9a8d4',
                            400: '#f472b6', 500: '#ec4899', 600: '#db2777', 700: '#be185d',
                            800: '#9d174d', 900: '#831843',
                        },
                    },
                },
            },
        };
    </script>
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-gray-50 px-6 text-center">
    <h1 class="text-3xl font-bold text-gray-900">{{ config('app.name', 'Salons System') }}</h1>
    <p class="mt-2 max-w-md text-gray-500">
        Multi-tenant salon booking platform. Each salon has its own booking page at
        <code class="rounded bg-gray-100 px-1 py-0.5 text-sm">{slug}.{{ config('tenancy.central_domain') }}</code>.
    </p>
    <a href="{{ route('admin.login') }}" class="mt-6 inline-block rounded-full bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">
        Super Admin Login
    </a>
</body>
</html>
