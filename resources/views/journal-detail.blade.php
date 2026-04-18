<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $journalTheme->name }} | {{ $settings->site_name ?? 'Publishing Platform' }}</title>
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition flex items-center">
                    &larr; Back to Journal List
                </a>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm rounded-sm">
                
                <div class="p-8 md:p-12">
                    <div class="mb-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                        Journal Overview
                    </div>

                    <h1 class="text-3xl md:text-4xl font-serif text-slate-900 font-bold mb-6 border-b border-slate-100 pb-6 leading-tight">
                        {{ $journalTheme->name }}
                    </h1>

                    <div class="prose prose-slate max-w-none text-slate-700 text-justify mb-10 leading-relaxed font-serif">
                        <p>{{ $journalTheme->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-200">
                        
                        <!-- <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-l-2 border-indigo-600 pl-3">Publication Fees (APC)</h3>
                            <ul class="space-y-3 text-sm text-slate-600 ml-3">
                                <li class="flex justify-between items-center border-b border-slate-100 pb-2">
                                    <span>Article Processing Charge</span>
                                    <span class="font-bold text-slate-900">USD {{ number_format($journalTheme->author_fee_usd ?? 0, 2) }}</span>
                                </li>
                                <li class="flex justify-between items-center">
                                    <span>Fast-Track / Additional Author</span>
                                    <span class="font-bold text-slate-900">USD {{ number_format($journalTheme->listener_fee_usd ?? 0, 2) }}</span>
                                </li>
                            </ul>
                        </div> -->

                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-l-2 border-indigo-600 pl-3">Current Issue</h3>
                            
                            @php
                                // Mengambil edisi terbaru yang memiliki naskah berstatus PUBLISHED
                                $currentIssue = \App\Models\JournalIssue::where('journal_theme_id', $journalTheme->id)
                                    ->where('is_active', true)
                                    ->whereHas('submissions', function($query) {
                                        $query->where('status', \App\Enums\SubmissionStatus::PUBLISHED);
                                    })
                                    ->orderBy('year', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->first();
                            @endphp

                            <div class="space-y-2 text-sm text-slate-600 ml-3">
                                @if($currentIssue)
                                    <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $currentIssue->id]) }}" class="group block">
                                        <div class="font-serif text-lg font-bold text-slate-900 group-hover:text-indigo-700 transition">
                                            Vol. {{ $currentIssue->volume }} No. {{ $currentIssue->issue }} ({{ $currentIssue->year }})
                                        </div>
                                    </a>
                                    
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-500 uppercase text-[10px] tracking-widest">Published:</span> 
                                        <span>{{ $currentIssue->created_at->format('Y-m-d') }}</span>
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-t border-slate-100">
                                        <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $currentIssue->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs uppercase tracking-widest transition inline-flex items-center gap-1">
                                            View Articles in this Issue 
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    </div>
                                @else
                                    <div class="italic text-slate-400 border border-dashed border-slate-200 p-4 text-center bg-slate-50">
                                        No published issues available yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('author.submit', $journalTheme->slug) }}" 
                           class="flex justify-center items-center px-6 py-3 bg-indigo-700 text-white font-bold text-sm rounded cursor-pointer hover:bg-indigo-800 transition">
                            Submit Paper
                        </a>
                        
                        <a href="{{ route('journal.archive', $journalTheme->slug) }}" 
                           class="flex justify-center items-center px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold text-sm rounded cursor-pointer hover:bg-slate-50 transition">
                            View All Archives
                        </a>
                    </div>
                    
                    @guest
                        <div class="mt-6 text-sm text-rose-600 bg-rose-50 border border-rose-100 p-4 rounded-sm">
                            <strong>Note:</strong> You must <a href="{{ route('login') }}" class="underline hover:text-rose-800">log in</a> before submitting a manuscript.
                        </div>
                    @endguest

                </div>
            </div>
        </div>
    </div>
</body>
</html>