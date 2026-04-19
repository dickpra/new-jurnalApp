<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $journalTheme->name }} | {{ $settings->site_name ?? 'Publishing Platform' }}</title>
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition flex items-center">
                    &larr; Back to Home
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-2/3 bg-white p-8 md:p-12 shadow-sm border border-slate-200 rounded-sm">
                    
                    @if($journalTheme->journal_logo)
                        <img src="{{ asset('storage/' . $journalTheme->journal_logo) }}" alt="Journal Logo" class="h-20 mb-8 object-contain">
                    @else
                        <div class="mb-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            Journal Overview
                        </div>
                    @endif

                    <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 leading-tight mb-6">
                        {{ $journalTheme->name }}
                    </h1>

                    <div class="flex flex-wrap gap-3 mb-8 border-b border-slate-100 pb-6">
                        @if($journalTheme->accreditation_status)
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider rounded">{{ $journalTheme->accreditation_status }}</span>
                        @endif
                        @if($journalTheme->e_issn)
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold tracking-wider rounded border border-slate-200">e-ISSN: {{ $journalTheme->e_issn }}</span>
                        @endif
                        @if($journalTheme->p_issn)
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold tracking-wider rounded border border-slate-200">p-ISSN: {{ $journalTheme->p_issn }}</span>
                        @endif
                    </div>

                    <div class="prose prose-slate max-w-none text-justify text-slate-700 leading-loose font-serif mb-12">
                        {!! $journalTheme->description !!}
                    </div>

                    @if($journalTheme->focus_scope)
                        <h3 class="text-xl font-serif font-bold text-slate-900 mb-4 flex items-center border-b border-slate-100 pb-3">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Focus and Scope
                        </h3>
                        <div class="prose prose-slate max-w-none text-justify text-slate-700 leading-loose text-sm mb-12">
                            {!! $journalTheme->focus_scope !!}
                        </div>
                    @endif

                    @if($journalTheme->peer_review_process)
                        <h3 class="text-xl font-serif font-bold text-slate-900 mb-4 flex items-center border-b border-slate-100 pb-3 mt-8">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Peer Review Process
                        </h3>
                        <div class="prose prose-slate max-w-none text-justify text-slate-700 leading-loose text-sm">
                            {!! nl2br(e($journalTheme->peer_review_process)) !!}
                        </div>
                    @endif

                </div>

                <div class="w-full lg:w-1/3 space-y-6">
                    
                    <div class="bg-white p-6 md:p-8 border border-slate-200 shadow-sm rounded-sm">
                        <a href="{{ route('author.submit', $journalTheme->slug) }}" class="w-full flex items-center justify-center px-6 py-3 bg-indigo-700 text-white font-bold rounded cursor-pointer hover:bg-indigo-800 transition mb-3 shadow-sm">
                            Submit Article
                        </a>
                        <a href="{{ route('journal.archive', $journalTheme->slug) }}" class="w-full flex items-center justify-center px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded hover:bg-slate-50 transition">
                            View All Archives
                        </a>

                        @guest
                            <div class="mt-4 text-[11px] text-center text-slate-500 bg-slate-50 p-2 rounded">
                                Please <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">log in</a> before submitting.
                            </div>
                        @endguest
                    </div>

                    <div class="bg-white p-6 md:p-8 border border-slate-200 shadow-sm rounded-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5 border-l-2 border-indigo-600 pl-3">Current Issue</h3>
                        
                        @php
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
                                <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $currentIssue->id]) }}" class="group block mb-2">
                                    <div class="font-serif text-lg font-bold text-slate-900 group-hover:text-indigo-700 transition">
                                        Vol. {{ $currentIssue->volume }} No. {{ $currentIssue->issue }} ({{ $currentIssue->year }})
                                    </div>
                                </a>
                                
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold text-slate-400 uppercase tracking-wider">Published:</span> 
                                    <span class="font-medium text-slate-800">{{ $currentIssue->created_at->format('M d, Y') }}</span>
                                </div>
                                
                                <div class="mt-5 pt-4 border-t border-slate-100">
                                    <a href="{{ route('journal.issue.detail', [$journalTheme->slug, $currentIssue->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs uppercase tracking-widest transition inline-flex items-center gap-1">
                                        Browse Articles 
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                </div>
                            @else
                                <div class="italic text-slate-400 border border-dashed border-slate-200 p-4 text-center bg-slate-50 text-xs">
                                    No published issues available yet.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white p-6 md:p-8 border border-slate-200 shadow-sm rounded-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5 border-l-2 border-indigo-600 pl-3">Editorial Info</h3>
                        
                        <ul class="space-y-4 text-sm ml-3 text-slate-600">
                            @if($journalTheme->publisher)
                            <li class="border-b border-slate-50 pb-3">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Publisher</span>
                                <span class="font-medium text-slate-800">{{ $journalTheme->publisher }}</span>
                            </li>
                            @endif
                            
                            @if($journalTheme->publication_frequency)
                            <li class="border-b border-slate-50 pb-3">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Publication Frequency</span>
                                <span class="font-medium text-slate-800">{{ $journalTheme->publication_frequency }}</span>
                            </li>
                            @endif
                            
                            @if($journalTheme->principal_contact_name)
                            <li class="border-b border-slate-50 pb-3">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Principal Contact</span>
                                <span class="font-medium text-slate-800">{{ $journalTheme->principal_contact_name }}</span>
                            </li>
                            @endif

                            @if($journalTheme->support_email)
                            <li>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Support Email</span>
                                <a href="mailto:{{ $journalTheme->support_email }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium break-all">{{ $journalTheme->support_email }}</a>
                            </li>
                            @endif
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>
@include('layouts.footer')
</html>