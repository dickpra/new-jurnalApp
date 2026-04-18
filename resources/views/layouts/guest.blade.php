<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Journal Platform') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .font-serif { font-family: 'Playfair Display', serif; }
            .font-sans { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
            
            <div class="mb-6 flex flex-col items-center">
                <a href="/" class="flex flex-col items-center group text-slate-800 hover:text-indigo-700 transition">
                    <x-application-logo class="w-16 h-16 text-indigo-700 mb-3" />
                    <span class="text-2xl font-serif font-bold tracking-tight">
                        {{ \App\Models\SiteSetting::first()->site_name ?? 'Journal Platform' }}
                    </span>
                    <span class="text-xs uppercase tracking-widest text-slate-400 mt-1">Publishing System</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white border border-slate-200 shadow-sm sm:rounded-xl">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-xs text-slate-400">
                &copy; {{ date('Y') }} {{ \App\Models\SiteSetting::first()->site_name ?? 'Journal Platform' }}. All rights reserved.
            </div>
        </div>
    </body>
</html>
