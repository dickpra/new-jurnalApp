<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $journalTheme->name }} | {{ $settings->site_name ?? 'AGROMIX' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-[#FAF9F6] text-stone-800">

    <nav class="bg-white border-b border-stone-200 py-4 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-xl font-serif font-bold tracking-wide text-stone-900">
                    {{ $settings->site_name ?? 'AGROMIX' }}
                </a>
            </div>
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="py-12 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('home') }}" class="text-sm text-stone-500 hover:text-stone-800 flex items-center transition font-serif">
                    &larr; Kembali ke Lobi Utama
                </a>
            </div>

            <div class="bg-white shadow-sm border border-stone-200 overflow-hidden">
                <div class="p-8 md:p-12">
                    <h1 class="text-4xl font-serif text-stone-900 font-bold mb-6 leading-tight border-b border-stone-100 pb-6">
                        {{ $journalTheme->name }}
                    </h1>

                    <div class="prose prose-stone max-w-none font-serif text-stone-700 leading-relaxed text-justify mb-10">
                        <h3 class="text-lg font-bold text-stone-900 mb-2 uppercase tracking-widest text-sm">Tentang Jurnal</h3>
                        <p>{{ $journalTheme->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-stone-100">
                        <div>
                            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-widest mb-4">Biaya Registrasi</h3>
                            <ul class="space-y-2 text-stone-600 font-serif">
                                <li class="flex justify-between border-b border-stone-50 pb-2">
                                    <span>Author (Presenter)</span>
                                    <span class="font-bold text-stone-900">USD {{ number_format($journalTheme->author_fee_usd ?? 0, 2) }}</span>
                                </li>
                                <li class="flex justify-between border-b border-stone-50 pb-2">
                                    <span>Participant (Listener)</span>
                                    <span class="font-bold text-stone-900">USD {{ number_format($journalTheme->listener_fee_usd ?? 0, 2) }}</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-stone-900 uppercase tracking-widest mb-4">Informasi Rekening</h3>
                            <div class="text-sm text-stone-600 space-y-1 font-serif">
                                <p><span class="font-bold">Bank:</span> {{ $journalTheme->bank_name ?? '-' }}</p>
                                <p><span class="font-bold">No. Rekening:</span> {{ $journalTheme->account_number ?? '-' }}</p>
                                <p><span class="font-bold">Pemilik:</span> {{ $journalTheme->account_owner_name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 text-center flex flex-col md:flex-row justify-center items-center gap-4">
                        <a href="{{ route('author.submit', $journalTheme->slug) }}" 
                           class="inline-block w-full md:w-auto px-10 py-4 bg-stone-800 text-white font-serif text-sm font-bold tracking-wider hover:bg-stone-700 transition shadow-md">
                            KIRIM NASKAH (SUBMIT MANUSCRIPT)
                        </a>
                        
                        <a href="{{ route('journal.archive', $journalTheme->slug) }}" 
                           class="inline-block w-full md:w-auto px-10 py-4 bg-white border-2 border-stone-800 text-stone-800 font-serif text-sm font-bold tracking-wider hover:bg-stone-50 transition shadow-sm">
                            LIHAT ARSIP (VIEW ARCHIVES)
                        </a>
                    </div>
                    @guest
                        <p class="text-xs text-red-500 mt-4 italic text-center">Anda harus masuk (login) terlebih dahulu sebelum menekan tombol submit.</p>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</body>
</html>