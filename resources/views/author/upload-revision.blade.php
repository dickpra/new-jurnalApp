<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen font-serif text-stone-800">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 border border-stone-200 shadow-sm">
                
                <h2 class="text-3xl font-bold text-stone-900 mb-2">Upload Revision</h2>
                <p class="text-stone-500 mb-8 pb-4 border-b">Submission Round: {{ $submission->current_round }}</p>

                <div class="mb-10 p-6 bg-amber-50 border-l-4 border-amber-400">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-amber-800 mb-2">Editor's Instructions:</h3>
                    <div class="text-stone-700 leading-relaxed whitespace-pre-line font-sans text-sm">
                        {{ $submission->revision_notes }}
                    </div>
                </div>

                <form action="{{ route('author.submissions.updateRevision', $submission->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 font-sans">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-stone-900 uppercase tracking-widest mb-2">Manuscript Title</label>
                        <input type="text" name="title" value="{{ old('title', $submission->title) }}" class="w-full border-stone-300 p-3 shadow-sm focus:border-stone-900 focus:ring-0" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-stone-900 uppercase tracking-widest mb-2">Abstract</label>
                        <textarea name="abstract" rows="6" class="w-full border-stone-300 p-3 shadow-sm focus:border-stone-900 focus:ring-0" required>{{ old('abstract', $submission->abstract) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-stone-900 uppercase tracking-widest mb-2">Keywords</label>
                        <input type="text" name="keywords" value="{{ old('keywords', $submission->keywords) }}" class="w-full border-stone-300 p-3 shadow-sm focus:border-stone-900 focus:ring-0" required>
                    </div>

                    <div class="bg-stone-50 p-6 border border-stone-200">
                        <div class="flex justify-between items-center mb-4 border-b border-stone-200 pb-4">
                            <label class="block text-sm font-bold text-stone-900 uppercase tracking-widest">Co-Authors</label>
                            <button type="button" id="add-author-btn" class="px-4 py-2 bg-stone-900 text-white text-xs uppercase tracking-widest hover:bg-stone-800 transition">
                                + Add Author
                            </button>
                        </div>
                        
                        <div id="authorContainer" class="space-y-3">
                            @php
                                $oldCoAuthors = old('co_authors', is_array($submission->co_authors) ? $submission->co_authors : [['name' => '', 'email' => '']]);
                                if(empty($oldCoAuthors)) $oldCoAuthors = [['name' => '', 'email' => '']];
                            @endphp

                            @foreach($oldCoAuthors as $index => $author)
                                <div class="co-author-row flex items-center gap-4">
                                    <input type="text" name="co_authors[{{ $index }}][name]" value="{{ $author['name'] ?? '' }}" class="w-1/2 border-stone-300 p-2 text-sm" placeholder="Full Name">
                                    <input type="email" name="co_authors[{{ $index }}][email]" value="{{ $author['email'] ?? '' }}" class="w-1/2 border-stone-300 p-2 text-sm" placeholder="Email Address">
                                    <button type="button" class="remove-author-btn text-stone-400 hover:text-red-600 p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 border-2 border-dashed border-stone-300 bg-stone-50">
                        <label class="block text-sm font-bold text-stone-900 uppercase tracking-widest mb-2">Upload Revised Manuscript (PDF/DOCX)</label>
                        <p class="text-xs text-stone-500 mb-4 italic">Biarkan kosong jika Anda hanya merevisi Judul/Abstrak dan tidak mengubah file naskah. Mengupload file baru akan menghapus naskah lama.</p>
                        <input type="file" name="manuscript_file" accept=".pdf,.doc,.docx" class="w-full bg-white p-2 border border-stone-200">
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-stone-900 text-white py-4 font-bold tracking-widest hover:bg-stone-800 transition">
                            SUBMIT REVISION
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('authorContainer');
            const addBtn = document.getElementById('add-author-btn');
            let authorIndex = {{ count($oldCoAuthors) }}; 

            addBtn.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'co-author-row flex items-center gap-4'; 
                row.innerHTML = `
                    <input type="text" name="co_authors[${authorIndex}][name]" class="w-1/2 border-stone-300 p-2 text-sm" placeholder="Full Name">
                    <input type="email" name="co_authors[${authorIndex}][email]" class="w-1/2 border-stone-300 p-2 text-sm" placeholder="Email Address">
                    <button type="button" class="remove-author-btn text-stone-400 hover:text-red-600 p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
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
</x-app-layout>