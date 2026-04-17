<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('reviewer.dashboard') }}" class="text-sm text-stone-500 hover:text-stone-800 flex items-center transition font-serif">
                    &larr; Back to Dashboard
                </a>
            </div>

            <div class="bg-white shadow-sm border border-stone-200 p-8 md:p-10 mb-8">
                <div class="flex justify-between items-start mb-6 border-b border-stone-100 pb-6">
                    <div>
                        <p class="text-xs text-stone-500 uppercase tracking-widest mb-1">{{ $review->submission->journalTheme->name }}</p>
                        <h1 class="text-2xl font-serif text-stone-900 font-bold leading-tight">{{ $review->submission->title }}</h1>
                    </div>
                    <a href="{{ route('reviewer.download', $review->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-stone-100 text-stone-800 hover:bg-stone-200 border border-stone-300 text-sm font-medium transition whitespace-nowrap ml-4">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download PDF/DOC
                    </a>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-stone-900 uppercase tracking-widest mb-2">Abstract</h3>
                    <p class="text-stone-700 text-justify font-serif leading-relaxed text-sm">
                        {{ $review->submission->abstract }}
                    </p>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-stone-200 p-8 md:p-10">
                <h2 class="text-xl font-serif text-stone-900 font-bold mb-6 border-b border-stone-100 pb-4">Evaluation Form</h2>
                
                @if($review->is_completed)
                    <div class="p-4 bg-stone-50 border border-stone-200 mb-6 font-serif text-stone-700 italic">
                        You have already submitted your evaluation for this manuscript.
                    </div>
                @endif

                <form action="{{ route('reviewer.evaluate.store', $review->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide mb-2">Recommendation <span class="text-red-500">*</span></label>
                        <select name="recommendation" class="w-full border-stone-300 bg-transparent text-stone-900 focus:ring-stone-500 shadow-sm" required {{ $review->is_completed ? 'disabled' : '' }}>
                            <option value="">-- Select Decision --</option>
                            @foreach(\App\Enums\ReviewRecommendation::cases() as $case)
                                <option value="{{ $case->value }}" {{ $review->recommendation === $case ? 'selected' : '' }}>
                                    {{ $case->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide mb-2">Notes for Author (Visible to Author) <span class="text-red-500">*</span></label>
                        <textarea name="notes_for_author" rows="6" class="w-full border-stone-300 bg-transparent text-stone-900 focus:ring-stone-500 shadow-sm font-serif text-sm leading-relaxed" placeholder="Detail your feedback, corrections, and suggestions here..." required {{ $review->is_completed ? 'disabled' : '' }}>{{ $review->notes_for_author }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide mb-2">Confidential Notes (Visible only to Manager)</label>
                        <textarea name="notes_for_manager" rows="4" class="w-full border-stone-300 bg-transparent text-stone-900 focus:ring-stone-500 shadow-sm font-serif text-sm leading-relaxed" placeholder="Private comments regarding the manuscript's quality, plagiarism concerns, etc." {{ $review->is_completed ? 'disabled' : '' }}>{{ $review->notes_for_manager }}</textarea>
                    </div>

                    @if(!$review->is_completed)
                        <div class="pt-6 border-t border-stone-100 text-right">
                            <button type="submit" class="px-8 py-3 bg-stone-900 text-white font-medium hover:bg-stone-800 transition shadow-sm uppercase tracking-wide">
                                Submit Evaluation
                            </button>
                        </div>
                    @endif
                </form>
            </div>

        </div>
    </div>
</x-app-layout>