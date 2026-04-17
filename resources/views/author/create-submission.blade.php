<x-app-layout>
    <div class="py-12 bg-[#FAF9F6] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('jurnal.show', $journalTheme->slug) }}" class="text-sm text-stone-500 hover:text-stone-800 flex items-center transition">
                    &larr; Kembali ke Detail Jurnal
                </a>
            </div>

            <div class="bg-white p-10 md:p-16 shadow-lg border border-stone-200">
                <div class="text-center mb-10 border-b pb-8">
                    <p class="text-xs text-stone-500 uppercase tracking-widest mb-2">Penyerahan Naskah Baru</p>
                    <h2 class="text-3xl font-serif text-stone-900">{{ $journalTheme->name }}</h2>
                </div>

                <form action="{{ route('author.store', $journalTheme->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide mb-2">Judul Manuskrip <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="Masukkan judul lengkap penelitian Anda..." class="w-full border-0 border-b-2 border-stone-300 bg-transparent py-2 px-0 text-stone-900 focus:ring-0 focus:border-stone-900 font-serif text-2xl placeholder-stone-300 transition" required>
                        @error('title') <span class="text-red-600 text-xs mt-1 block italic">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide mb-2">Abstrak <span class="text-red-500">*</span></label>
                        <div class="bg-stone-50 border border-stone-200 p-1">
                            <textarea name="abstract" rows="8" placeholder="Ringkasan metodologi, temuan, dan kesimpulan..." class="w-full border-0 bg-transparent focus:ring-0 text-stone-800 font-serif text-justify leading-relaxed placeholder-stone-400" required></textarea>
                        </div>
                        @error('abstract') <span class="text-red-600 text-xs mt-1 block italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-stone-700 mb-2">Keywords (Pisahkan dengan koma)</label>
                        <input type="text" name="keywords" class="w-full border-stone-300 rounded shadow-sm focus:ring-stone-500" placeholder="Contoh: Pertanian, Teknologi, Padi" required>
                    </div>

                   <div class="mb-6 bg-stone-50 p-6 border border-stone-200">
                        <div class="flex justify-between items-center mb-4 border-b border-stone-200 pb-4">
                            <div>
                                <label class="block text-sm font-bold text-stone-900 uppercase tracking-widest">Penulis Pendamping (Co-Authors)</label>
                                <p class="text-xs text-stone-500 mt-1">Tambahkan anggota tim peneliti lainnya jika ada.</p>
                            </div>
                            <button type="button" id="add-author-btn" class="px-4 py-2 bg-stone-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-stone-800 transition flex items-center shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Author
                            </button>
                        </div>
                        
                        <div id="authorContainer" class="space-y-3">
                            <div class="co-author-row flex items-center gap-4">
                                <input type="text" name="co_authors[0][name]" class="w-1/2 border-stone-300 text-sm focus:ring-stone-500 focus:border-stone-500 shadow-sm" placeholder="Nama Lengkap Co-Author">
                                <input type="email" name="co_authors[0][email]" class="w-1/2 border-stone-300 text-sm focus:ring-stone-500 focus:border-stone-500 shadow-sm" placeholder="Email Instansi">
                                <button type="button" class="remove-author-btn text-stone-300 hover:text-red-600 transition p-2" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const container = document.getElementById('authorContainer');
                            const addBtn = document.getElementById('add-author-btn');
                            let authorIndex = 1; // Mulai dari 1 karena index 0 sudah ada di baris pertama

                            // Fungsi untuk menambah baris
                            addBtn.addEventListener('click', function() {
                                // Pastikan kita membuat elemen DIV baru, bukan menimpa ID container-nya
                                const row = document.createElement('div');
                                row.className = 'co-author-row flex items-center gap-4 animate-fade-in'; 
                                
                                // Isi HTML di dalam baris baru
                                row.innerHTML = `
                                    <input type="text" name="co_authors[${authorIndex}][name]" class="w-1/2 border-stone-300 text-sm focus:ring-stone-500 focus:border-stone-500 shadow-sm" placeholder="Nama Lengkap Co-Author" required>
                                    <input type="email" name="co_authors[${authorIndex}][email]" class="w-1/2 border-stone-300 text-sm focus:ring-stone-500 focus:border-stone-500 shadow-sm" placeholder="Email Instansi" required>
                                    <button type="button" class="remove-author-btn text-stone-400 hover:text-red-600 transition p-2" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                `;
                                
                                container.appendChild(row);
                                authorIndex++;
                            });

                            // Fungsi untuk menghapus baris (menggunakan Event Delegation agar tombol dinamis bisa diklik)
                            container.addEventListener('click', function(e) {
                                const removeBtn = e.target.closest('.remove-author-btn');
                                if (removeBtn) {
                                    const row = removeBtn.closest('.co-author-row');
                                    // Opsional: cegah penghapusan jika hanya tersisa 1 baris
                                    if (container.children.length > 1) {
                                        row.remove();
                                    } else {
                                        // Kalau cuma 1 baris, cukup kosongkan isinya
                                        row.querySelectorAll('input').forEach(input => input.value = '');
                                    }
                                }
                            });
                        });
                    </script>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wide mb-2">File Manuskrip (PDF/DOCX) <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-stone-300 border-dashed hover:border-stone-500 transition cursor-pointer bg-stone-50">
                            <div class="space-y-1 text-center">
                                <input id="file-upload" name="manuscript_file" type="file" class="text-sm text-stone-600" accept=".pdf,.doc,.docx" required>
                                <p class="text-xs text-stone-500 mt-2">Maksimal 10MB</p>
                            </div>
                        </div>
                        @error('manuscript_file') <span class="text-red-600 text-xs mt-1 block italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-8 text-center border-t border-stone-200 mt-8">
                        <button type="submit" class="w-full px-10 py-4 bg-stone-800 text-white font-serif text-lg tracking-wider hover:bg-stone-700 transition shadow-md uppercase">
                            Kirim Naskah Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>