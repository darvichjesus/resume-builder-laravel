<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'sans-serif'] },
                        colors: { brand: { 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed' } }
                    }
                }
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-950">
            <div class="mb-8">
                <a href="/" class="flex flex-col items-center gap-3 no-underline group">
                    <div class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-brand-400 to-fuchsia-400 bg-clip-text text-transparent flex items-center gap-3 transition group-hover:scale-105">
                        <svg class="w-12 h-12 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z M2 17L12 22L22 17 M2 12L12 17L22 12"/>
                        </svg>
                        DevSuite PRO
                    </div>
                    <div class="text-[10px] uppercase tracking-[0.3em] font-bold text-brand-400/60 mt-1">Professional Suite</div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-8 bg-gray-900 border border-gray-800 shadow-2xl overflow-hidden sm:rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
