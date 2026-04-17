<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings->site_name ?? 'AGROMIX Journal' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased bg-[#FAF9F6] text-stone-800 selection:bg-stone-200">

    <nav class="bg-white border-b border-stone-200 py-4 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                @if(!empty($settings->logo_path))
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="h-10">
                @endif
                <span class="text-xl font-serif font-bold tracking-wide text-stone-900">
                    {{ $settings->site_name ?? 'AGROMIX' }}
                </span>
            </div>
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('author.dashboard') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-stone-800 text-white text-sm font-medium rounded hover:bg-stone-700 transition shadow-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="py-24 bg-white border-b border-stone-200">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-serif text-stone-900 mb-6 leading-tight">
                {{ $settings->hero_title ?? 'Scientific Journal Platform' }}
            </h1>
            <p class="text-lg md:text-xl text-stone-500 leading-relaxed max-w-2xl mx-auto font-light">
                {{ $settings->hero_subtitle ?? 'Mendesiminasikan hasil penelitian berkualitas untuk kemajuan ilmu pengetahuan.' }}
            </p>
        </div>
    </header>

    <main class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="mb-10 border-b border-stone-300 pb-4">
            <h2 class="text-2xl font-serif font-bold text-stone-800">Daftar Publikasi Jurnal</h2>
            <p class="text-sm text-stone-500 mt-1">Pilih jurnal di bawah ini untuk melihat panduan dan mengirim naskah.</p>
        </div>

        <div class="space-y-6">
            @forelse($themes as $theme)
                <div class="group bg-white border border-stone-200 p-8 hover:border-stone-300 hover:shadow-md transition-all duration-200 flex flex-col md:flex-row gap-6 items-start">
                    
                    <div class="flex-grow">
                        <h3 class="text-2xl font-serif font-bold text-stone-900 mb-3 group-hover:text-stone-600 transition">
                            {{ $theme->name }}
                        </h3>
                        <p class="text-stone-600 text-sm leading-relaxed mb-4 text-justify">
                            {{ $theme->description }}
                        </p>
                    </div>

                    <div class="md:min-w-[160px] md:text-right mt-4 md:mt-0 pt-4 md:pt-0 border-t border-stone-100 md:border-0 w-full md:w-auto">
                        <a href="{{ route('jurnal.show', $theme->slug) }}" class="inline-flex items-center justify-center ...">
                            Lihat Detail & Kirim Naskah
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white border border-stone-200 text-stone-500 font-serif italic">
                    Belum ada tema jurnal yang diterbitkan oleh administrator.
                </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-stone-100 border-t border-stone-200 py-16 text-stone-600">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-1 md:grid-cols-12 gap-10">
            
            <div class="md:col-span-5">
                <h4 class="text-lg font-serif font-bold text-stone-800 mb-4">Tentang {{ $settings->site_name ?? 'Portal' }}</h4>
                <p class="text-sm leading-relaxed text-justify">
                    {{ $settings->about_text ?? 'Portal jurnal ini dikelola dengan standar akademik untuk memfasilitasi publikasi karya ilmiah yang inovatif dan terpercaya.' }}
                </p>
            </div>
            
            <div class="md:col-span-4 md:col-start-9">
                <h4 class="text-lg font-serif font-bold text-stone-800 mb-4">Informasi Kontak</h4>
                <ul class="text-sm space-y-3">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>{{ $settings->contact_email ?? 'admin@yudharta.ac.id' }}</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $settings->contact_phone ?? '(0343) 611186' }}</span>
                    </li>
                </ul>
            </div>

        </div>
        
        <div class="max-w-5xl mx-auto px-4 mt-12 pt-8 border-t border-stone-200 text-center text-xs text-stone-400">
            &copy; {{ date('Y') }} {{ $settings->site_name ?? 'AGROMIX Journal' }}. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </footer>

</body>
</html>