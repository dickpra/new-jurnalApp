<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end border-b border-stone-300 pb-4 mb-6">
                <div>
                    <p class="text-sm text-stone-500 uppercase tracking-widest font-semibold">AGROMIX Reviewer Workspace</p>
                    <h2 class="text-3xl font-serif text-stone-900 mt-1 font-bold">Assigned Manuscripts</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-white p-5 border border-stone-200 border-l-4 border-l-stone-800 shadow-sm">
                    <p class="text-xs uppercase tracking-widest text-stone-500 mb-1">Total Assigned</p>
                    <p class="text-2xl font-serif font-bold">{{ $assignments->count() }}</p>
                </div>
                <div class="bg-white p-5 border border-stone-200 border-l-4 border-l-blue-500 shadow-sm">
                    <p class="text-xs uppercase tracking-widest text-stone-500 mb-1">Pending Evaluation</p>
                    <p class="text-2xl font-serif font-bold">{{ $assignments->where('is_completed', false)->count() }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-800 font-serif">
                    <p class="italic">"{{ session('success') }}"</p>
                </div>
            @endif

            <div class="space-y-6">
                @forelse ($assignments as $assignment)
                    @php
                        $isPending = !$assignment->is_completed;
                        $borderColor = $isPending ? 'border-blue-400 ring-1 ring-blue-400' : 'border-stone-200';
                        
                        // FIX BUG WARNA TAILWIND (Gunakan class statis, jangan dinamis)
                        $badgeClass = match($assignment->submission->status->value) {
                            'accepted' => 'bg-green-600',
                            'revision_required' => 'bg-amber-600',
                            'rejected' => 'bg-red-600',
                            'under_review' => 'bg-blue-600',
                            default => 'bg-stone-600',
                        };
                    @endphp

                    <div class="bg-white p-8 shadow-sm border {{ $borderColor }} transition hover:shadow-md flex flex-col md:flex-row gap-6 items-start relative">
                        
                        @if($isPending)
                            <div class="absolute -top-3 left-6">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 text-xs font-bold uppercase tracking-wider flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span> Action Required: Evaluate
                                </span>
                            </div>
                        @endif

                        <div class="flex-grow mt-2">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">
                                    {{ $assignment->submission->journalTheme->name }}
                                </span>
                                <span class="px-2 py-0.5 text-xs border border-stone-300 text-stone-600 uppercase tracking-wider">
                                    Round {{ $assignment->round }}
                                </span>
                                <span class="px-3 py-1 text-xs text-white uppercase tracking-wider {{ $badgeClass }}">
                                    {{ $assignment->submission->status->getLabel() }}
                                </span>
                            </div>

                            <h3 class="text-xl font-serif text-stone-900 font-bold mb-2 leading-snug">
                                {{ $assignment->submission->title }}
                            </h3>
                            <p class="text-sm text-stone-500">
                                Assigned on: {{ $assignment->created_at->format('M d, Y') }}
                            </p>
                        </div>

                        <div class="md:min-w-[150px] w-full md:w-auto mt-4 md:mt-0 flex flex-col gap-2">
                            @if($isPending)
                                <a href="{{ route('reviewer.evaluate.show', $assignment->id) }}" class="flex items-center justify-center w-full px-4 py-2 bg-stone-900 text-white text-sm font-medium hover:bg-stone-800 transition shadow-sm">
                                    Evaluate Now
                                </a>
                            @else
                                <a href="{{ route('reviewer.evaluate.show', $assignment->id) }}" class="flex items-center justify-center w-full px-4 py-2 border border-stone-300 text-sm font-medium text-stone-600 hover:bg-stone-50 transition">
                                    View Evaluation
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 text-center shadow-sm border border-stone-200">
                        <h3 class="text-xl font-serif text-stone-800 mb-2">No Assignments Yet</h3>
                        <p class="text-stone-500 text-sm">You have not been assigned any manuscripts for review.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>