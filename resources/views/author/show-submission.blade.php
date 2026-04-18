<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('author.dashboard') }}" class="text-sm text-stone-500 hover:text-stone-800 flex items-center transition font-serif">
                    &larr; Back to Dashboard
                </a>
                
                @if($submission->status->value === 'revision_required')
                    <a href="{{ route('author.submissions.revision', $submission->id) }}" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-widest transition shadow-sm">
                        Upload Revised File
                    </a>
                @endif
            </div>

            <div class="bg-white shadow-sm border border-stone-200 mb-8">
                @php
                    $headerColor = match($submission->status->value) {
                        'accepted' => 'bg-green-700',
                        'paid' => 'bg-green-700',
                        'rejected' => 'bg-red-700',
                        'revision_required' => 'bg-amber-600',
                        default => 'bg-stone-900',
                    };
                @endphp
                <div class="{{ $headerColor }} px-8 py-6 text-white flex justify-between items-center transition-colors">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-white/70 mb-1">Current Status</p>
                        <h2 class="text-xl font-bold font-serif uppercase tracking-wide">{{ $submission->status->getLabel() }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-widest text-white/70 mb-1">Review Round</p>
                        <h2 class="text-xl font-bold font-serif">{{ $submission->current_round }}</h2>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <p class="text-sm font-bold text-stone-500 uppercase tracking-widest mb-4">
                        {{ $submission->journalTheme->name }}
                    </p>

                    <h1 class="text-3xl font-serif text-stone-900 font-bold mb-6 leading-tight">
                        {{ $submission->title }}
                    </h1>

                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-stone-900 uppercase tracking-widest border-b border-stone-100 pb-2 mb-4">Abstract</h3>
                        <p class="text-stone-700 text-justify font-serif leading-relaxed text-sm">
                            {{ $submission->abstract }}
                        </p>
                    </div>

                    <div class="pt-6 border-t border-stone-100">
                        <a href="{{ route('author.submissions.download', $submission->id) }}" class="inline-flex items-center justify-center px-6 py-3 bg-stone-50 text-stone-800 hover:bg-stone-100 border border-stone-300 font-serif text-sm transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Current Manuscript
                        </a>
                    </div>
                </div>
            </div>

            @if(in_array($submission->status->value, ['pending', 'under_review']))
                <div class="bg-white p-8 shadow-sm border border-stone-200 text-center">
                    <svg class="w-12 h-12 text-stone-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-serif text-stone-900 font-bold mb-2">In Progress</h3>
                    <p class="text-stone-500 font-serif text-sm">Your manuscript is currently being processed by the editorial team. Any feedback or decisions will appear here once the review round is complete.</p>
                </div>
            
            @elseif(in_array($submission->status->value, ['revision_required', 'rejected']))
                <div class="bg-white shadow-sm border {{ $submission->status->value === 'rejected' ? 'border-red-200' : 'border-amber-200' }}">
                    <div class="{{ $submission->status->value === 'rejected' ? 'bg-red-50 text-red-800' : 'bg-amber-50 text-amber-900' }} px-8 py-4 border-b {{ $submission->status->value === 'rejected' ? 'border-red-100' : 'border-amber-100' }}">
                        <h3 class="text-sm font-bold uppercase tracking-widest">Editor & Reviewer Feedback</h3>
                    </div>
                    <div class="p-8">
                        <div class="prose prose-stone max-w-none font-serif text-sm text-stone-700 whitespace-pre-line leading-relaxed">
                            {{ $submission->revision_notes ?? 'No specific notes provided.' }}
                        </div>
                    </div>
                </div>

            @elseif($submission->status->value === 'accepted' || $submission->status->value === 'paid')
                
                <div class="bg-white shadow-sm border border-green-200 mb-8">
                    <div class="bg-green-50 px-8 py-4 border-b border-green-100">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-green-900">Editor Notes</h3>
                    </div>
                    <div class="p-8">
                        <div class="prose prose-stone max-w-none font-serif text-sm text-stone-700 whitespace-pre-line leading-relaxed">
                            {{ $submission->revision_notes ?? 'Congratulations! Your manuscript has been accepted for publication.' }}
                        </div>
                    </div>
                </div>

                <div class="bg-stone-900 shadow-sm border border-stone-800 text-white mt-8">
                    <div class="px-8 py-6 border-b border-stone-700 flex justify-between items-center">
                        <h3 class="text-lg font-bold font-serif">Payment Information</h3>
                        
                        @php
                            $paymentBadge = match($submission->payment_status) {
                                'unpaid' => '<span class="px-3 py-1 bg-red-950 text-red-400 text-xs font-bold uppercase tracking-widest border border-red-900">Awaiting Payment</span>',
                                'pending_verification' => '<span class="px-3 py-1 bg-blue-950 text-blue-400 text-xs font-bold uppercase tracking-widest border border-blue-900 animate-pulse">Verifying...</span>',
                                'paid' => '<span class="px-3 py-1 bg-green-950 text-green-400 text-xs font-bold uppercase tracking-widest border border-green-900">Paid / Verified</span>',
                                default => '',
                            };
                        @endphp
                        {!! $paymentBadge !!}
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-stone-400 mb-4 border-b border-stone-700 pb-2">Registration Fee</p>
                            @if($submission->journalTheme->author_fee_usd)
                                <div class="text-4xl font-serif font-bold text-green-400 mb-2">
                                    USD {{ number_format($submission->journalTheme->author_fee_usd, 2) }}
                                </div>
                                <p class="text-xs text-stone-400">Author / Presenter Fee</p>
                            @else
                                <div class="text-2xl font-serif font-bold text-stone-300">Free of Charge</div>
                            @endif
                        </div>

                        @if($submission->journalTheme->author_fee_usd)
                        <div>
                            <p class="text-xs uppercase tracking-widest text-stone-400 mb-4 border-b border-stone-700 pb-2">Bank Transfer Details</p>
                            <ul class="space-y-3 text-sm font-serif text-stone-300">
                                <li><span class="font-bold text-white w-24 inline-block">Bank:</span> {{ $submission->journalTheme->bank_name ?? '-' }}</li>
                                <li><span class="font-bold text-white w-24 inline-block">Account:</span> <span class="tracking-widest">{{ $submission->journalTheme->account_number ?? '-' }}</span></li>
                                <li><span class="font-bold text-white w-24 inline-block">Beneficiary:</span> {{ $submission->journalTheme->account_owner_name ?? '-' }}</li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    
                    @if($submission->journalTheme->author_fee_usd)
                        <div class="px-8 py-6 bg-stone-950 border-t border-stone-800">
                            
                            @if($submission->payment_status === 'unpaid')
                                <form action="{{ route('author.submissions.payment', $submission->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-start md:items-center justify-between">
                                    @csrf
                                    <div class="w-full md:w-auto mb-4 md:mb-0">
                                        <label class="block text-xs font-bold text-stone-400 uppercase tracking-wide mb-2">Upload Transfer Receipt (JPG/PNG, Max: 5MB)</label>
                                        <input type="file" name="payment_proof" class="w-full text-sm text-stone-400 file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-sm file:font-semibold file:bg-stone-800 file:text-white hover:file:bg-stone-700" required>
                                        
                                        @error('payment_proof')
                                            <p class="text-red-400 text-xs mt-2 italic flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                        
                                    </div>
                                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-green-600 hover:bg-green-500 text-white text-sm font-bold uppercase tracking-widest transition shadow-md mt-2 md:mt-0">
                                        Submit Payment Proof
                                    </button>
                                </form>
                            
                            @elseif($submission->payment_status === 'pending_verification')
                                <div class="text-center py-2">
                                    <p class="text-amber-400 font-bold uppercase tracking-widest text-sm flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Verifying Payment...
                                    </p>
                                </div>
                            
                            @elseif($submission->payment_status === 'paid')
                                <div class="flex flex-col md:flex-row justify-between items-center py-2">
                                    <div>
                                        <p class="text-green-400 font-bold uppercase tracking-widest text-sm flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Payment Verified (PAID)
                                        </p>
                                        <p class="text-xs text-stone-500 mt-1">Your Letter of Acceptance is now ready.</p>
                                    </div>
                                    <a href="{{ route('author.submissions.loa', $submission->id) }}" target="_blank" class="mt-4 md:mt-0 px-8 py-3 bg-stone-100 hover:bg-white text-stone-900 text-sm font-bold uppercase tracking-widest transition shadow-md flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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