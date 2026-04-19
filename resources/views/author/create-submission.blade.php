<x-app-layout>
    <div class="py-10 bg-slate-50 min-h-screen text-slate-800 font-sans">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('jurnal.show', $journalTheme->slug) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition flex items-center">
                    &larr; Back to Journal Info
                </a>
            </div>

            <div class="bg-white p-8 md:p-12 border border-slate-200 shadow-sm rounded-sm">
                
                <div class="border-b border-slate-200 pb-6 mb-8">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">New Submission</span>
                    <h2 class="text-3xl font-serif font-bold text-slate-900">{{ $journalTheme->name }}</h2>
                    <p class="text-sm text-slate-500 mt-2">Please complete the metadata carefully before submitting your final paper.</p>
                </div>

                <form action="{{ route('author.store', $journalTheme->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Paper Title <span class="text-rose-600">*</span></label>
                        <textarea name="title" rows="2" class="w-full border-slate-300 rounded-md focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-serif text-lg py-3 resize-none" required></textarea>
                        @error('title') <span class="text-rose-600 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Abstract -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Abstract <span class="text-rose-600">*</span></label>
                        <textarea name="abstract" rows="8" class="w-full border-slate-300 rounded-md focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 text-sm leading-relaxed" required></textarea>
                        @error('abstract') <span class="text-rose-600 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Keywords -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Keywords <span class="text-rose-600">*</span></label>
                        <input type="text" name="keywords" class="w-full border-slate-300 rounded-md focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50" placeholder="e.g.: Technology, Education, Neural Networks" required>
                        <p class="text-[11px] text-slate-500 mt-1">Separate keywords with commas (3-5 keywords recommended).</p>
                    </div>

                    <!-- Co-Authors -->
                    <div class="border border-slate-200 rounded-md p-6 bg-slate-50">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                            <div>
                                <label class="text-sm font-bold text-slate-800">Authors</label>
                                <p class="text-[11px] text-slate-500">Add contributing authors here</p>
                            </div>
                            <button type="button" id="add-author-btn" class="mt-3 sm:mt-0 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs font-semibold rounded hover:bg-slate-100 transition shadow-sm">
                                + Add Co-Author
                            </button>
                        </div>
                        
                        <div id="authorContainer" class="space-y-3">
                            <div class="co-author-row flex flex-col sm:flex-row gap-3">
                                <div class="w-full sm:w-1/2">
                                    <input type="text" name="co_authors[0][name]" class="w-full border-slate-300 text-sm rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white" placeholder="Full Name">
                                </div>
                                <div class="w-full sm:w-1/2 flex items-center gap-2">
                                    <input type="email" name="co_authors[0][email]" class="w-full border-slate-300 text-sm rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white" placeholder="Affiliation Email">
                                    <button type="button" class="remove-author-btn text-slate-400 hover:text-rose-600 transition" title="Remove">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const container = document.getElementById('authorContainer');
                            const addBtn = document.getElementById('add-author-btn');
                            let authorIndex = 1;

                            addBtn.addEventListener('click', function() {
                                const row = document.createElement('div');
                                row.className = 'co-author-row flex flex-col sm:flex-row gap-3 mt-3'; 
                                
                                row.innerHTML = `
                                    <div class="w-full sm:w-1/2">
                                        <input type="text" name="co_authors[${authorIndex}][name]" class="w-full border-slate-300 text-sm rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white" placeholder="Full Name" required>
                                    </div>
                                    <div class="w-full sm:w-1/2 flex items-center gap-2">
                                        <input type="email" name="co_authors[${authorIndex}][email]" class="w-full border-slate-300 text-sm rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white" placeholder="Affiliation Email" required>
                                        <button type="button" class="remove-author-btn text-slate-400 hover:text-rose-600 transition" title="Remove">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                `;
                                container.appendChild(row);
                                authorIndex++;
                            });

                            container.addEventListener('click', function(e) {
                                const removeBtn = e.target.closest('.remove-author-btn');
                                if (removeBtn) {
                                    const row = removeBtn.closest('.co-author-row');
                                    if (container.children.length > 1) {
                                        row.remove();
                                    } else {
                                        row.querySelectorAll('input').forEach(input => input.value = '');
                                    }
                                }
                            });
                        });
                    </script>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-bold text-slate-800 mb-2">Paper File (DOCX / PDF) <span class="text-rose-600">*</span></label>
                        <div class="mt-1">
                            <input id="file-upload" name="manuscript_file" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 border border-slate-300 bg-slate-50 rounded" accept=".pdf,.doc,.docx" required>
                            <p class="text-[11px] text-slate-500 mt-2">Maximum file size: 10MB.</p>
                        </div>
                        @error('manuscript_file') <span class="text-rose-600 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-200 mt-8">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-indigo-700 text-white font-bold text-sm rounded shadow-sm hover:bg-indigo-800 transition">
                            Submit Paper
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>