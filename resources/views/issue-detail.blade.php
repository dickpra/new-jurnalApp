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
<body class="font-sans antialiased bg-[#FAF9F6] text-stone-800">

    <nav class="bg-white border-b border-stone-200 py-4 sticky top-0 z-50 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-xl font-serif font-bold tracking-wide text-stone-900">
                    AGROMIX
                </a>
            </div>
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="py-10 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 border-b border-stone-200 pb-4">
                <nav class="flex text-sm font-serif text-stone-500">
                    <a href="{{ route('journal.show', $journalTheme->slug) }}" class="hover:text-stone-900 transition">Detail Jurnal</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('journal.archive', $journalTheme->slug) }}" class="hover:text-stone-900 transition">Archives</a>
                    <span class="mx-2">/</span>
                    <span class="text-stone-900 font-bold">Current Issue</span>
                </nav>
            </div>

            <div class="bg-white shadow-sm border border-stone-200 p-8 md:p-12 mb-10">
                <div class="flex flex-col md:flex-row gap-10 items-start">
                    
                    <div class="w-full md:w-1/4 flex-shrink-0">
                        <div class="border border-stone-200 p-2 bg-stone-50 shadow-md">
                            <img src="{{ $issue->cover_image ? asset('storage/' . $issue->cover_image) : 'https://placehold.co/400x600?text=No+Cover' }}" 
                                 class="w-full h-auto object-cover border border-stone-100" alt="Cover Volume">
                        </div>
                    </div>

                    <div class="w-full md:w-3/4">
                        <p class="text-xs font-bold text-stone-500 uppercase tracking-[0.2em] mb-2">{{ $journalTheme->name }}</p>
                        <h1 class="text-4xl md:text-5xl font-serif text-stone-900 font-bold leading-tight mb-4 border-b-4 border-stone-900 pb-6">
                            Volume {{ $issue->volume }}, Nomor {{ $issue->issue }} ({{ $issue->year }})
                        </h1>
                        
                        <div class="flex items-center gap-6 mt-6">
                            <div class="text-sm font-serif">
                                <span class="text-stone-500 block text-xs uppercase tracking-widest">Total Naskah</span>
                                <span class="text-xl font-bold text-stone-900">{{ $issue->submissions->count() }} Papers</span>
                            </div>
                            <div class="text-sm font-serif">
                                <span class="text-stone-500 block text-xs uppercase tracking-widest">Status</span>
                                <span class="text-xl font-bold text-green-700 italic">Published</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-serif font-bold text-stone-900 uppercase tracking-widest">Table of Contents</h2>
                <div class="h-px bg-stone-300 flex-grow ml-6"></div>
            </div>

            <div class="space-y-6">
                @forelse($issue->submissions as $paper)
                    <div class="bg-white border-l-4 border-stone-900 p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow group">
                        
                        <h3 class="text-2xl font-serif font-bold text-stone-900 mb-3 leading-snug group-hover:text-stone-600 transition">
                            {{ $paper->title }}
                        </h3>
                        
                        <p class="text-sm font-serif italic text-stone-600 mb-4">
                            <span class="font-bold text-stone-800">{{ $paper->author->name }}</span>@if($paper->co_authors), {{ collect($paper->co_authors)->pluck('name')->implode(', ') }}@endif
                        </p>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-stone-50 p-4 border border-stone-100">
                            
                            <div class="space-y-2">
                                @if($paper->doi)
                                    <div class="flex items-center text-xs font-sans">
                                        <span class="w-16 font-bold text-stone-500 uppercase tracking-widest">DOI</span>
                                        <a href="https://doi.org/{{ $paper->doi }}" target="_blank" class="text-blue-600 hover:underline font-mono">
                                            https://doi.org/{{ $paper->doi }}
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="flex items-center text-xs font-sans">
                                    <span class="w-16 font-bold text-stone-500 uppercase tracking-widest">Keywords</span>
                                    <span class="text-stone-700">{{ $paper->keywords ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <a href="{{ route('secure.file', encrypt(['id' => $paper->id, 'type' => 'manuscript'])) }}" 
                                   class="inline-flex items-center justify-center px-6 py-2.5 bg-white border border-stone-300 text-stone-700 font-bold text-xs uppercase tracking-widest hover:bg-stone-900 hover:text-white hover:border-stone-900 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    PDF Fulltext
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white p-16 text-center border border-dashed border-stone-300">
                        <p class="font-serif italic text-stone-500 text-lg">Naskah sedang dalam proses publikasi untuk edisi ini.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</body>
</html>