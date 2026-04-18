<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings->site_name ?? 'Journal Publishing Platform' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-indigo-100 selection:text-indigo-900">

    <!-- Solid Professional Navigation -->
    <nav class="w-full z-50 bg-white border-b border-slate-200 py-3 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14">
                <div class="flex items-center space-x-4">
                    @if(!empty($settings->logo_path))
                        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-10 object-contain">
                    @else
                        <x-application-logo class="w-8 h-8 text-indigo-700" />
                    @endif
                    <span class="text-xl font-serif font-bold text-slate-900 tracking-tight border-l pl-4 border-slate-300 ml-2">
                        {{ $settings->site_name ?? 'Publishing Platform' }}
                    </span>
                </div>
                <div class="hidden md:flex items-center space-x-6 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-indigo-700 transition">Journals</a>
                    @auth
                        <a href="{{ route('author.dashboard') }}" class="text-indigo-700 hover:text-indigo-900 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-indigo-700 transition">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-indigo-700 text-white rounded hover:bg-indigo-800 transition">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section (Clean and Structured) -->
    <header class="bg-white border-b border-slate-200 py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-serif text-slate-900 mb-4 leading-tight font-bold">
                {{ $settings->hero_title ?? 'Advancing Research and Discovery' }}
            </h1>
            <p class="text-lg md:text-xl text-slate-600 leading-relaxed max-w-3xl mx-auto">
                {{ $settings->hero_subtitle ?? 'A peer-reviewed scientific publishing platform dedicated to disseminating high-quality scholarly research across multiple disciplines.' }}
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen">
        
        <div class="mb-10 text-left border-b border-slate-200 pb-4">
            <h2 class="text-2xl font-serif font-bold text-slate-900">Current Journals</h2>
            <p class="text-slate-500 mt-1">Select a journal to view its aims, scopes, and instructions for authors.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            @forelse($themes as $theme)
                <div class="bg-white border border-slate-200 p-8 shadow-sm hover:border-indigo-300 transition flex flex-col h-full rounded-sm">
                    
                    <div class="mb-6">
                        <h3 class="text-xl font-serif font-bold text-slate-900 mb-3 hover:text-indigo-700 transition">
                            <a href="{{ route('jurnal.show', $theme->slug) }}">{{ $theme->name }}</a>
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed text-justify line-clamp-4">
                            {{ $theme->description }}
                        </p>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mt-auto flex justify-between items-center">
                        <a href="{{ route('jurnal.show', $theme->slug) }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900 transition flex items-center">
                            View Journal 
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="{{ route('author.submit', $theme->slug) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition border border-slate-200 px-3 py-1 rounded">
                            Submit Article
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white border border-slate-200 rounded-sm">
                    <p class="text-slate-500 font-serif italic text-lg">Platform initialization in progress. No journals published yet.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-8">
            
            <div class="md:col-span-5">
                <span class="text-xl font-serif font-bold text-white mb-4 block">
                    {{ $settings->site_name ?? 'Journal Publishing Platform' }}
                </span>
                <p class="text-sm leading-relaxed pr-8 text-slate-400">
                    {{ $settings->about_text ?? 'An independent academic portal committed to maintaining rigorous peer-review standards and promoting open access to global research.' }}
                </p>
            </div>
            
            <div class="md:col-span-3 md:col-start-9">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">Support & Contact</h4>
                <ul class="text-sm space-y-3">
                    <li class="flex items-center hover:text-white transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <a href="mailto:{{ $settings->contact_email ?? 'admin@yudharta.ac.id' }}">{{ $settings->contact_email ?? 'admin@yudharta.ac.id' }}</a>
                    </li>
                    <li class="flex items-center hover:text-white transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $settings->contact_phone ?? '(0343) 611186' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-8 border-t border-slate-800 text-xs text-slate-500 flex justify-between">
            <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? 'Publishing Platform' }}. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>