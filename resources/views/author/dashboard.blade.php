<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end border-b border-stone-300 pb-4 mb-6">
                <div>
                    <p class="text-sm text-stone-500 uppercase tracking-widest font-semibold">AGROMIX Author Workspace</p>
                    <h2 class="text-3xl font-serif text-stone-900 mt-1 font-bold">My Manuscripts</h2>
                </div>
                <a href="{{ route('home') }}" class="px-5 py-2.5 bg-stone-900 text-white font-medium text-sm hover:bg-stone-800 transition shadow-sm">
                    Submit New Article
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-5 border border-stone-200 border-l-4 border-l-stone-800 shadow-sm">
                    <p class="text-xs uppercase tracking-widest text-stone-500 mb-1">Total Submissions</p>
                    <p class="text-2xl font-serif font-bold">{{ $submissions->count() }}</p>
                </div>
                <div class="bg-white p-5 border border-stone-200 border-l-4 border-l-amber-500 shadow-sm">
                    <p class="text-xs uppercase tracking-widest text-stone-500 mb-1">Needs Revision</p>
                    <p class="text-2xl font-serif font-bold">{{ $submissions->where('status.value', 'revision_required')->count() }}</p>
                </div>
                <div class="bg-white p-5 border border-stone-200 border-l-4 border-l-green-500 shadow-sm">
                    <p class="text-xs uppercase tracking-widest text-stone-500 mb-1">Accepted</p>
                    <p class="text-2xl font-serif font-bold">{{ $submissions->where('status.value', 'accepted')->count() }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-800 font-serif">
                    <p class="italic">"{{ session('success') }}"</p>
                </div>
            @endif

            <div class="space-y-6">
                @forelse ($submissions as $submission)
                    @php
                        $borderColor = 'border-stone-200';
                        $actionBadge = null;
                        
                        if($submission->status->value === 'revision_required') {
                            $borderColor = 'border-amber-400 ring-1 ring-amber-400';
                            $actionBadge = '<span class="bg-amber-100 text-amber-800 px-3 py-1 text-xs font-bold uppercase tracking-wider flex items-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span> Action Required: Revision</span>';
                        } elseif ($submission->status->value === 'accepted' && $submission->payment_status === 'unpaid') {
                            $borderColor = 'border-red-400 ring-1 ring-red-400';
                            $actionBadge = '<span class="bg-red-100 text-red-800 px-3 py-1 text-xs font-bold uppercase tracking-wider flex items-center"><span class="w-2 h-2 rounded-full bg-red-500 mr-2 animate-pulse"></span> Action Required: Payment</span>';
                        } elseif ($submission->status->value === 'accepted' && $submission->payment_status === 'pending_verification') {
                            $borderColor = 'border-blue-400';
                            $actionBadge = '<span class="bg-blue-100 text-blue-800 px-3 py-1 text-xs font-bold uppercase tracking-wider">Verifying Payment</span>';
                        }

                        // OBAT FIX WARNA TAILWIND (Class statis penuh)
                        $badgeClass = match($submission->status->value) {
                            'accepted' => 'bg-green-600',
                            'revision_required' => 'bg-amber-600',
                            'rejected' => 'bg-red-600',
                            'under_review' => 'bg-blue-600',
                            default => 'bg-stone-600',
                        };
                    @endphp

                    <div class="bg-white p-8 shadow-sm border {{ $borderColor }} transition hover:shadow-md relative">
                        
                        @if($actionBadge)
                            <div class="absolute -top-3 left-6">
                                {!! $actionBadge !!}
                            </div>
                        @endif

                        <div class="flex justify-between items-center mb-4 mt-2">
                            <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">
                                {{ $submission->journalTheme->name }}
                            </span>
                            <div class="flex space-x-2">
                                <span class="px-3 py-1 text-xs border border-stone-300 text-stone-600 uppercase tracking-wider">
                                    Round {{ $submission->current_round }}
                                </span>
                                <span class="px-3 py-1 text-xs text-white uppercase tracking-wider {{ $badgeClass }}">
                                    {{ $submission->status->getLabel() }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-2xl font-serif text-stone-900 font-bold mb-3 leading-snug">
                            {{ $submission->title }}
                        </h3>

                        <div class="flex justify-between items-center border-t border-stone-100 pt-4 mt-6">
                            <p class="text-xs text-stone-400">
                                Submitted on {{ $submission->created_at->format('F d, Y') }}
                            </p>
                            
                            <div class="flex items-center space-x-4">
                                @if($submission->status->value === 'revision_required')
                                    <a href="{{ route('author.submissions.revision', $submission->id) }}" class="px-4 py-2 bg-amber-600 text-white text-xs font-bold uppercase tracking-widest hover:bg-amber-700 transition">
                                        Upload Revision
                                    </a>
                                @endif
                                
                                <a href="{{ route('author.submissions.show', $submission->id) }}" class="text-sm font-bold text-stone-700 hover:text-stone-900 uppercase tracking-wide flex items-center border-b-2 border-transparent hover:border-stone-900 transition-all">
                                    View Details 
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 text-center shadow-sm border border-stone-200">
                        <h3 class="text-xl font-serif text-stone-800 mb-2">No Manuscripts Yet</h3>
                        <p class="text-stone-500 text-sm mb-6">Your research journey begins with a single submission.</p>
                        <a href="{{ route('home') }}" class="text-stone-700 hover:underline font-bold text-sm">Explore Journals &rarr;</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>