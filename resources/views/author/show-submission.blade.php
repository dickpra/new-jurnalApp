<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen text-slate-800 font-sans">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                    &larr; Back to Dashboard
                </a>
                
                @if($submission->status->value === 'revision_required')
                    <a href="{{ route('author.submissions.revision', $submission->id) }}" class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 text-sm font-bold rounded hover:bg-amber-100 transition shadow-sm">
                        Upload Revised Manuscript
                    </a>
                @endif
            </div>

            <!-- Header Card -->
            <div class="bg-white shadow-sm rounded-sm border border-slate-200 mb-8">
                @php
                    $headerHeader = match($submission->status->value) {
                        'accepted' => 'bg-emerald-50 border-b border-emerald-100',
                        'paid' => 'bg-emerald-50 border-b border-emerald-100',
                        'rejected' => 'bg-rose-50 border-b border-rose-100',
                        'revision_required' => 'bg-amber-50 border-b border-amber-100',
                        'published' => 'bg-emerald-50 border-b border-emerald-100',
                        default => 'bg-slate-100 border-b border-slate-200',
                    };
                    $badgeStyle = match($submission->status->value) {
                        'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
                        'revision_required' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'published' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        default => 'bg-slate-200 text-slate-800 border-slate-300',
                    };
                @endphp
                
                <div class="{{ $headerHeader }} px-8 py-5 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Status</span>
                        <span class="px-3 py-1 border rounded text-xs font-bold tracking-wider uppercase {{ $badgeStyle }}">
                            {{ $submission->status->getLabel() }}
                        </span>
                    </div>
                    <div class="text-sm font-bold text-slate-700">
                        Review Round: {{ $submission->current_round }}
                    </div>
                </div>

                <div class="p-8">
                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-700 mb-4 bg-indigo-50 inline-block px-3 py-1 rounded">
                        {{ $submission->journalTheme->name }}
                    </p>

                    <h1 class="text-3xl font-serif text-slate-900 font-bold mb-6 leading-tight">
                        {{ $submission->title }}
                    </h1>

                    <div class="mb-6">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">Abstract</h3>
                        <p class="text-slate-700 font-serif leading-relaxed text-sm text-justify">
                            {{ $submission->abstract }}
                        </p>
                    </div>

                    {{-- ================== TAMBAHAN CO-AUTHORS ================== --}}
                    @php
                        $authors = [];
                        if (!empty($submission->co_authors)) {
                            if (is_array($submission->co_authors)) {
                                $authors = $submission->co_authors;
                            } else {
                                $authors = json_decode($submission->co_authors, true) ?? [];
                            }
                        }
                    @endphp

                    @if(count($authors) > 0)
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200 pb-2 mb-3">
                                Authors
                            </h3>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($authors as $author)
                                    <li class="text-stone-700 text-sm">
                                        <span class="font-medium">{{ $author['name'] ?? '' }}</span>
                                        @if(!empty($author['email']))
                                            <span class="text-stone-500">&lt;{{ $author['email'] }}&gt;</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- ========================================================= --}}

                    {{-- ===== KEYWORDS ===== --}}
                    <div class="w-full md:w-1/3 mt-6 md:mt-0">
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-2">Keywords</p>

                        @php
                            $keywords = [];
                            if (!empty($submission->keywords)) {
                                if (is_array($submission->keywords)) {
                                    $keywords = $submission->keywords;
                                } else {
                                    $keywords = explode(',', $submission->keywords);
                                }
                                $keywords = array_map('trim', $keywords);
                                $keywords = array_filter($keywords);
                            }
                        @endphp

                        @if(count($keywords) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($keywords as $keyword)
                                    <span class="inline-block bg-slate-100 text-slate-800 text-xs px-3 py-1 rounded-full font-serif">
                                        {{ $keyword }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-slate-500 text-sm italic">No keywords available.</span>
                        @endif
                    </div>

                    <div class="pt-6 border-t border-slate-200">
                        <a href="{{ route('author.submissions.download', $submission->id) }}" class="inline-flex px-4 py-2 bg-slate-100 text-slate-700 border border-slate-300 rounded text-sm font-semibold hover:bg-slate-200 transition">
                            Download Current Manuscript
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dynamic Panels based on Status -->
            @if(in_array($submission->status->value, ['pending', 'under_review']))
                <div class="bg-white p-8 rounded-sm shadow-sm border border-slate-200">
                    <h3 class="font-serif font-bold text-slate-900 border-b border-slate-200 pb-2 mb-4">Editorial Status</h3>
                    <p class="text-sm text-slate-600">Your manuscript is currently being evaluated. Any decisions or feedback from the editorial board will appear here once the review process concludes.</p>
                </div>
            
            @elseif(in_array($submission->status->value, ['revision_required', 'rejected']))
                <div class="bg-white shadow-sm rounded-sm border border-slate-200 mb-8 overflow-hidden">
                    <div class="{{ $submission->status->value === 'rejected' ? 'bg-rose-50 border-b border-rose-100' : 'bg-amber-50 border-b border-amber-100' }} px-8 py-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Reviewer / Editor Notes</h3>
                    </div>
                    <div class="p-8">
                        <div class="prose prose-slate max-w-none text-slate-700 whitespace-pre-line text-sm font-serif">
                            {{ $submission->revision_notes ?? 'No additional notes provided.' }}
                        </div>
                    </div>
                </div>

            @elseif($submission->status->value === 'accepted' || $submission->status->value === 'paid' || $submission->status->value === 'published')
                
                <div class="bg-white shadow-sm rounded-sm border border-emerald-200 mb-8 overflow-hidden">
                    <div class="bg-emerald-50 px-8 py-4 border-b border-emerald-100">
                        <h3 class="text-sm font-bold text-emerald-900 uppercase tracking-widest">Editorial Decision</h3>
                    </div>
                    <div class="p-8">
                        <div class="prose prose-slate max-w-none text-slate-700 whitespace-pre-line text-sm font-serif">
                            {{ $submission->revision_notes ?? 'Congratulations! Your manuscript has been accepted for publication.' }}
                        </div>
                    </div>
                </div>

                <!-- Payment Area -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-sm overflow-hidden text-slate-800 mb-8">
                    <div class="px-8 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                        <h3 class="font-bold text-slate-900">APC Payment Details</h3>
                        
                        @php
                            $paymentBadge = match($submission->payment_status) {
                                'unpaid' => '<span class="px-3 py-1 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold uppercase rounded">Awaiting Payment</span>',
                                'pending_verification' => '<span class="px-3 py-1 bg-sky-50 border border-sky-200 text-sky-700 text-xs font-bold uppercase rounded">Verifying Proof...</span>',
                                'paid' => '<span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase rounded">Payment Verified</span>',
                                default => '',
                            };
                        @endphp
                        {!! $paymentBadge !!}
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Article Processing Charge (APC)</p>
                            @if($submission->journalTheme->author_fee_usd)
                                <div class="text-3xl font-serif font-bold text-slate-900 mb-1">
                                    <span class="text-lg text-slate-500 font-sans align-top">USD</span> {{ number_format($submission->journalTheme->author_fee_usd, 2) }}
                                </div>
                            @else
                                <div class="text-xl font-bold text-emerald-600">Free of Charge</div>
                            @endif
                        </div>

                        @if($submission->journalTheme->author_fee_usd)
                        <div class="bg-slate-50 p-5 rounded border border-slate-200">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Bank Transfer Details</p>
                            <ul class="text-sm space-y-2">
                                <li><strong class="text-slate-600 w-24 inline-block">Bank Name:</strong> {{ $submission->journalTheme->bank_name ?? '-' }}</li>
                                <li><strong class="text-slate-600 w-24 inline-block">App. Acct:</strong> <span class="font-mono bg-slate-200 px-1 rounded">{{ $submission->journalTheme->account_number ?? '-' }}</span></li>
                                <li><strong class="text-slate-600 w-24 inline-block">Beneficiary:</strong> {{ $submission->journalTheme->account_owner_name ?? '-' }}</li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    
                    @if($submission->journalTheme->author_fee_usd)
                        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200">
                            
                            @if($submission->payment_status === 'unpaid')
                                <form action="{{ route('author.submissions.payment', $submission->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-end gap-4">
                                    @csrf
                                    <div class="w-full">
                                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Upload Payment Proof (PDF / PNG)</label>
                                        <input type="file" name="payment_proof" accept=".pdf,.png,.jpeg,.jpg,.docx" class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 border border-slate-300 rounded bg-white" required>
                                        @error('payment_proof')
                                            <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="shrink-0 px-6 py-2.5 bg-indigo-700 hover:bg-indigo-800 text-white text-sm font-bold rounded transition mt-2 sm:mt-0">
                                        Submit Receipt
                                    </button>
                                </form>
                            
                            @elseif($submission->payment_status === 'pending_verification')
                                <div class="text-sm text-slate-600">
                                    <strong>Status:</strong> The editorial office is currently verifying your payment. The Letter of Acceptance (LOA) will be unlocked soon.
                                </div>
                            
                            @elseif($submission->payment_status === 'paid')
                                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <div class="text-sm text-slate-700">
                                        <strong>Letter of Acceptance:</strong> Your payment is verified. You may now download the official LOA.
                                    </div>
                                    <a href="{{ route('author.submissions.loa', $submission->id) }}" target="_blank" class="shrink-0 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded transition shadow-sm">
                                        Download LOA
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>