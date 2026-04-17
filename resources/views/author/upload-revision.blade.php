<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen font-serif">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 border border-stone-200 shadow-sm">
                <h2 class="text-3xl font-bold text-stone-900 mb-2">Upload Revision</h2>
                <p class="text-stone-500 mb-8 pb-4 border-b">Submission Round: {{ $submission->current_round }}</p>

                <div class="mb-10 p-6 bg-amber-50 border-l-4 border-amber-400">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-amber-800 mb-2">Editor's Instructions:</h3>
                    <div class="text-stone-700 leading-relaxed whitespace-pre-line">
                        {{ $submission->revision_notes }}
                    </div>
                </div>

                <form action="{{ route('author.submissions.store_revision', $submission->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-2">Upload Revised Manuscript (PDF/DOCX)</label>
                        <input type="file" name="revised_file" class="w-full border border-stone-300 p-3" required>
                        <p class="text-xs text-stone-400 mt-2 italic">Uploading a new file will replace your previous manuscript for the next review round.</p>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-stone-900 text-white py-4 font-bold hover:bg-stone-800 transition">
                            SUBMIT REVISION
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>