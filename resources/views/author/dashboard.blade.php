<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen font-sans text-slate-800">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 bg-white p-6 md:p-8 border border-slate-200 shadow-sm rounded-sm">
                <div>
                    <h2 class="text-2xl font-serif text-slate-900 font-bold">Author Dashboard</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage your active submissions and manuscripts.</p>
                </div>
                <a href="{{ route('home') }}" class="mt-4 md:mt-0 px-5 py-2 bg-indigo-700 text-white rounded font-medium text-sm hover:bg-indigo-800 transition shadow-sm border border-transparent">
                    New Submission
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Card -->
                <div class="bg-white p-6 border border-slate-200 shadow-sm rounded-sm">
                    <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-2">Total Submissions</p>
                    <p class="text-3xl font-serif font-bold text-slate-900">{{ $submissions->count() }}</p>
                </div>

                <!-- Revision Card -->
                <div class="bg-white p-6 border border-slate-200 shadow-sm rounded-sm">
                    <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-2">Revisions Required</p>
                    <p class="text-3xl font-serif font-bold text-slate-900">{{ $submissions->where('status.value', 'revision_required')->count() }}</p>
                </div>

                <!-- Accepted Card -->
                <div class="bg-white p-6 border border-slate-200 shadow-sm rounded-sm">
                    <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-2">Accepted</p>
                    <p class="text-3xl font-serif font-bold text-emerald-700">{{ $submissions->where('status.value', 'accepted' || 'paid')->count() }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm border border-slate-200 rounded-sm">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-serif font-bold text-lg text-slate-800">My Manuscripts</h3>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse ($submissions as $submission)
                        @php
                            $actionRequired = false;
                            $actionMessage = '';
                            $actionColor = '';

                            if($submission->status->value === 'revision_required') {
                                $actionRequired = true;
                                $actionMessage = 'Revisions Required';
                                $actionColor = 'bg-amber-100 text-amber-800 border-amber-200';
                            } elseif ($submission->status->value === 'accepted' && $submission->payment_status === 'unpaid') {
                                $actionRequired = true;
                                $actionMessage = 'APC Payment Required';
                                $actionColor = 'bg-rose-100 text-rose-800 border-rose-200';
                            } elseif ($submission->status->value === 'accepted' && $submission->payment_status === 'pending_verification') {
                                $actionRequired = true;
                                $actionMessage = 'Verifying Payment';
                                $actionColor = 'bg-sky-100 text-sky-800 border-sky-200';
                            }

                            $badgeClass = match($submission->status->value) {
                                'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'revision_required' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
                                'under_review' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'published' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                default => 'bg-slate-100 text-slate-800 border-slate-200',
                            };
                        @endphp

                        <div class="p-6 md:p-8 hover:bg-slate-50 transition">
                            
                            @if($actionRequired)
                                <div class="mb-3">
                                    <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase tracking-wider {{ $actionColor }} border rounded">
                                        {{ $actionMessage }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-2 gap-4">
                                <h3 class="text-lg md:text-xl font-serif text-slate-900 font-bold leading-snug">
                                    <a href="{{ route('author.submissions.show', $submission->id) }}" class="hover:text-indigo-700 transition">
                                        {{ $submission->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center space-x-2 shrink-0">
                                    <span class="px-2 py-1 text-[10px] uppercase tracking-wider font-semibold border rounded {{ $badgeClass }}">
                                        {{ $submission->status->getLabel() }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs text-indigo-700 font-semibold uppercase tracking-wider mb-4 border-l-2 border-indigo-300 pl-2">
                                {{ $submission->journalTheme->name }}
                            </p>

                            <div class="flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 mt-4 pt-4 border-t border-slate-100 gap-4">
                                <div>
                                    <span class="mr-4">Submitted: {{ $submission->created_at->format('M d, Y') }}</span>
                                    <span>Round {{ $submission->current_round }}</span>
                                </div>
                                
                                <div class="flex items-center space-x-3 w-full md:w-auto">
                                    @if($submission->status->value === 'revision_required')
                                        <a href="{{ route('author.submissions.revision', $submission->id) }}" class="px-3 py-1.5 bg-amber-50 text-amber-800 border border-amber-300 rounded font-bold hover:bg-amber-100 transition">
                                            Upload Revision
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('author.submissions.show', $submission->id) }}" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 font-medium rounded hover:bg-slate-50 transition flex items-center justify-center flex-grow md:flex-none">
                                        View Details 
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-slate-500 font-serif italic mb-4">You have not submitted any manuscripts yet.</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded hover:bg-slate-50 transition">
                                Browse Journals
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>