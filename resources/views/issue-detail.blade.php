<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vol. {{ $issue->volume }} No. {{ $issue->issue }} ({{ $issue->year }}) - {{ $journalTheme->name }}</title>
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
            
            <div class="mb-6 overflow-x-auto">
                <nav class="flex text-sm font-medium text-slate-500 whitespace-nowrap">
                    <a href="{{ route('journal.show', $journalTheme->slug) }}" class="hover:text-indigo-700 transition">Journal Info</a>
                    <span class="mx-3 text-slate-300">/</span>
                    <a href="{{ route('journal.archive', $journalTheme->slug) }}" class="hover:text-indigo-700 transition">Archive</a>
                    <span class="mx-3 text-slate-300">/</span>
                    <span class="text-slate-900 font-bold">Current Issue</span>
                </nav>
            </div>

            <!-- Issue Header -->
            <div class="bg-white border border-slate-200 shadow-sm p-8 md:p-12 mb-10 rounded-sm">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    
                    <div class="w-48 sm:w-56 flex-shrink-0 border border-slate-200 p-2 bg-slate-50 mx-auto md:mx-0">
                        <img src="{{ $issue->cover_image ? asset('storage/' . $issue->cover_image) : 'https://placehold.co/400x600?text=No+Cover' }}" 
                             class="w-full h-auto object-cover border border-slate-200" alt="Cover Volume">
                    </div>

                    <div class="w-full">
                        <p class="text-xs font-bold text-indigo-700 uppercase tracking-widest mb-3">
                            {{ $journalTheme->name }}
                        </p>
                        
                        <h1 class="text-3xl md:text-4xl font-serif text-slate-900 font-bold leading-tight mb-6">
                            Volume {{ $issue->volume }}, Issue {{ $issue->issue }} ({{ $issue->year }})
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-8 border-t border-slate-100 pt-6 mt-6">
                            <div>
                                <span class="text-slate-500 block text-xs uppercase tracking-wider mb-1">Total Submissions</span>
                                <span class="text-xl font-serif font-bold text-slate-900">{{ $issue->submissions->count() }} Papers</span>
                            </div>
                            <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                            <div>
                                <span class="text-slate-500 block text-xs uppercase tracking-wider mb-1">Status</span>
                                <span class="text-xl font-serif font-bold text-emerald-700">Published</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Table of Contents -->
            <div class="mb-6 border-b border-slate-300 pb-4">
                <h2 class="text-2xl font-serif font-bold text-slate-900">Table of Contents</h2>
            </div>

            <div class="space-y-4">
                @forelse($issue->submissions as $paper)
                    <div class="bg-white p-6 md:p-8 shadow-sm border border-slate-200 flex flex-col md:flex-row gap-6 items-start rounded-sm hover:border-indigo-300 transition">

                        <div class="flex-grow w-full">
                            <a href="{{ route('journal.article.detail', [$journalTheme->slug, $paper->id]) }}">
                                <h3 class="text-xl font-serif font-bold text-stone-900 mb-3 leading-snug group-hover:text-blue-700 transition">
                                    {{ $paper->title }}
                                </h3>
                            </a>
                            
                            <p class="text-sm text-slate-600 mb-4 bg-slate-50 inline-block px-3 py-1 rounded">
                                <span class="font-bold text-slate-800">{{ $paper->author->name }}</span>
                                @if($paper->co_authors)
                                    <span class="text-slate-500">, {{ collect($paper->co_authors)->pluck('name')->implode(', ') }}</span>
                                @endif
                            </p>

                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-t border-slate-100 pt-4 mt-2">
                                <div class="space-y-2">
                                    @if($paper->doi)
                                        <div class="flex items-center text-sm">
                                            <span class="w-20 font-bold text-slate-500 text-xs uppercase tracking-wider">DOI</span>
                                            <a href="https://doi.org/{{ $paper->doi }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline font-mono">
                                                https://doi.org/{{ $paper->doi }}
                                            </a>
                                        </div>
                                    @endif
                                    <div class="flex text-sm">
                                        <span class="w-20 font-bold text-slate-500 text-xs uppercase tracking-wider">Keywords</span>
                                        <span class="text-slate-700 max-w-xl">{{ $paper->keywords ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="flex-shrink-0 mt-4 lg:mt-0">
                                    <a href="{{ route('secure.file', encrypt(['id' => $paper->id, 'type' => 'manuscript'])) }}" 
                                       class="inline-flex items-center justify-center px-5 py-2 bg-slate-900 text-white font-semibold text-sm rounded hover:bg-indigo-700 transition border border-transparent shadow-sm">
                                        PDF Fulltext
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 text-center border border-dashed border-slate-300 rounded-sm">
                        <p class="font-serif italic text-slate-500 text-lg">Manuscripts are currently being processed for this issue.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</body>
@include('layouts.footer')

</html>