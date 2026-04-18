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
<body class="font-sans antialiased bg-[#FAF9F6] text-stone-800">

    <nav class="bg-white border-b border-stone-200 py-4 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-xl font-serif font-bold tracking-wide text-stone-900">
                    AGROMIX
                </a>
            </div>
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="py-12 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('journal.show', $journalTheme->slug) }}" class="text-sm text-stone-500 hover:text-stone-800 flex items-center transition font-serif">
                    &larr; Kembali ke Detail Jurnal
                </a>
            </div>

            <div class="bg-white shadow-sm border border-stone-200 overflow-hidden p-8 md:p-12">
                
                <header class="border-b-4 border-stone-900 pb-6 mb-10">
                    <p class="text-[10px] uppercase tracking-[0.3em] text-stone-500 font-bold mb-2">Published Archives</p>
                    <h1 class="text-3xl font-serif font-bold leading-tight text-stone-900">{{ $journalTheme->name }}</h1>
                </header>

                @if($issues->count() > 0)
                    <div class="flex flex-wrap justify-center md:justify-start gap-6">
                        @foreach($issues as $issue)
                            <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $issue->id]) }}" class="group block">
                                
                                <div class="w-32 md:w-36 bg-white border border-stone-200 overflow-hidden shadow-sm group-hover:shadow-md transition-all duration-300 flex flex-col">
                                    
                                    <div class="relative w-full h-44 md:h-48 overflow-hidden bg-stone-100 flex-shrink-0">
                                        <img src="{{ $issue->cover_image ? asset('storage/' . $issue->cover_image) : 'https://placehold.co/150x200?text=No+Cover' }}" 
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        
                                        <div class="absolute inset-0 bg-stone-900/0 group-hover:bg-stone-900/10 transition-colors"></div>
                                    </div>
                                    
                                    <div class="p-3 bg-stone-900 text-white group-hover:bg-stone-800 transition text-center flex-grow flex flex-col justify-center">
                                        <p class="text-[8px] uppercase tracking-widest font-bold opacity-60 mb-1">Vol {{ $issue->volume }}</p>
                                        <h3 class="text-[10px] md:text-xs font-serif font-bold leading-tight">
                                            No. {{ $issue->issue }}<br>
                                            <span class="opacity-70 font-normal">({{ $issue->year }})</span>
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-12 border-t border-stone-100 pt-6">
                        {{ $issues->links() }}
                    </div>
                @else
                    <div class="text-center py-20 border border-dashed border-stone-200 bg-stone-50">
                        <p class="font-serif italic text-stone-400">No issues available.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</body>
</html>