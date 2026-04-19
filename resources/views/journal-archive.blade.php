<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Archive - {{ $journalTheme->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">

    <nav class="w-full bg-white border-b border-slate-200 py-3">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-14">
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-xl font-serif font-bold text-slate-900 hover:text-indigo-700 transition">
                    {{ $settings->site_name ?? 'Journal Platform' }}
                </a>
            </div>
            <div class="flex items-center space-x-6 text-sm font-semibold">
                @auth
                    <a href="{{ route('author.dashboard') }}" class="text-slate-600 hover:text-indigo-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-indigo-700 transition">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="py-12 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 pl-4 sm:pl-0">
                <a href="{{ route('journal.show', $journalTheme->slug) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition flex items-center">
                    &larr; Back to Journal Detail
                </a>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm p-8 md:p-12">
                
                <header class="border-b border-slate-200 pb-6 mb-10">
                    <p class="text-xs uppercase tracking-widest text-slate-400 font-bold mb-2">Published Archives</p>
                    <h1 class="text-3xl md:text-4xl font-serif font-bold leading-tight text-slate-900">{{ $journalTheme->name }}</h1>
                </header>

                @if($issues->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($issues as $issue)
                            <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $issue->id]) }}" class="group flex flex-col h-full bg-white border border-slate-200 transition hover:border-indigo-400 shadow-sm rounded-sm">
                                
                                <div class="w-full aspect-[3/4] bg-slate-100 overflow-hidden border-b border-slate-200">
                                    <img src="{{ $issue->cover_image ? asset('storage/' . $issue->cover_image) : 'https://placehold.co/300x400?text=No+Cover' }}" 
                                        class="w-full h-full object-cover">
                                </div>
                                
                                <div class="p-4 flex-grow flex flex-col justify-center text-center">
                                    <p class="text-xs text-slate-500 font-semibold mb-1">Vol {{ $issue->volume }}, No {{ $issue->issue }}</p>
                                    <span class="text-sm text-slate-800 font-bold group-hover:text-indigo-700 transition">
                                        ({{ $issue->year }})
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-12 border-t border-slate-100 pt-6">
                        {{ $issues->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-slate-50 border border-slate-200 rounded-sm">
                        <p class="font-serif italic text-slate-500 text-lg">No issues have been published for this journal yet.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</body>
@include('layouts.footer')

</html>