<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} | {{ $journalTheme->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50">

    <nav class="w-full bg-white border-b border-slate-200 py-3 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-14">
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-xl font-serif font-bold text-slate-900 hover:text-indigo-700 transition">
                    {{ $settings->site_name}}
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 overflow-x-auto">
                <nav class="flex text-sm font-medium text-slate-500 whitespace-nowrap">
                    <a href="{{ route('journal.show', $journalTheme->slug) }}" class="hover:text-indigo-700 transition">{{ $journalTheme->name }}</a>
                    <span class="mx-3 text-slate-300">/</span>
                    <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $article->journalIssue->id]) }}" class="hover:text-indigo-700 transition">
                        Vol. {{ $article->journalIssue->volume }} No. {{ $article->journalIssue->issue }}
                    </a>
                    <span class="mx-3 text-slate-300">/</span>
                    <span class="text-slate-900 font-bold truncate max-w-xs">Article</span>
                </nav>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-2/3 bg-white p-8 md:p-12 shadow-sm border border-slate-200 rounded-sm">
                    
                    <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 leading-tight mb-8 border-b border-slate-100 pb-6">
                        {{ $article->title }}
                    </h1>

                    <div class="mb-10 pb-8 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-l-2 border-indigo-600 pl-3">Authors</h3>
                        <div class="text-base text-slate-700 leading-relaxed ml-3 bg-slate-50 inline-block px-4 py-2 rounded border border-slate-100">
                            <span class="font-bold text-slate-900">{{ $article->author->name }}</span><sup class="text-slate-500 ml-0.5">1</sup>@if($article->co_authors), 
                                @foreach($article->co_authors as $index => $coAuthor)
                                    <span class="text-slate-700">{{ $coAuthor['name'] }}</span><sup class="text-slate-500 ml-0.5">{{ $index + 2 }}</sup>@if(!$loop->last), @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mb-10">
                        <h3 class="text-xl font-serif font-bold text-slate-900 mb-4 flex items-center border-b border-slate-100 pb-3">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            Abstract
                        </h3>
                        <div class="prose prose-slate max-w-none text-justify text-slate-700 leading-loose font-serif">
                            {!! nl2br(e($article->abstract)) !!}
                        </div>
                    </div>

                    <div class="mb-2 bg-slate-50 p-6 border border-slate-200 rounded-sm">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Keywords</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $article->keywords) as $keyword)
                                <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-sm shadow-sm rounded">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="w-full lg:w-1/3 space-y-6">
                    
                    <div class="bg-white p-6 md:p-8 border border-slate-200 shadow-sm rounded-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-l-2 border-indigo-600 pl-3">Access Article</h3>
                        
                        <a href="{{ route('secure.file', encrypt(['id' => $article->id, 'type' => 'manuscript'])) }}" 
                           class="w-full flex items-center justify-center px-6 py-3 bg-indigo-700 text-white font-bold rounded cursor-pointer hover:bg-indigo-800 transition shadow-sm mb-5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Full PDF
                        </a>

                        @if($article->doi)
                            <div class="mt-5 pt-5 border-t border-slate-100 ml-3">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Official DOI</span>
                                <a href="https://doi.org/{{ $article->doi }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm font-mono break-all font-medium">
                                    https://doi.org/{{ $article->doi }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white p-6 md:p-8 border border-slate-200 shadow-sm rounded-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-l-2 border-indigo-600 pl-3">Publication Details</h3>
                        
                        <ul class="space-y-4 text-sm ml-3 text-slate-600">
                            <li class="border-b border-slate-50 pb-3">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Published In</span>
                                <a href="{{ route('journal.show', $journalTheme->slug) }}" class="font-serif font-bold text-slate-900 hover:text-indigo-700 transition">{{ $journalTheme->name }}</a>
                            </li>
                            <li class="border-b border-slate-50 pb-3">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Issue / Volume</span>
                                <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $article->journalIssue->id]) }}" class="text-slate-800 font-medium hover:text-indigo-700 transition">
                                    Vol. {{ $article->journalIssue->volume }} No. {{ $article->journalIssue->issue }} ({{ $article->journalIssue->year }})
                                </a>
                            </li>
                            <li>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Date Published</span>
                                <span class="text-slate-800 font-medium">{{ $article->updated_at->format('F d, Y') }}</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>
</html>