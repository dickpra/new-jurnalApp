<footer class="bg-slate-900 border-t border-slate-800 text-slate-300 py-12 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        
        <div class="md:col-span-4">
            <span class="text-xl font-serif font-bold text-white mb-4 block">
                {{ $settings->site_name ?? 'SustainScript' }}
            </span>
            <p class="text-sm leading-relaxed text-slate-400 text-justify">
                {{ $settings->about_text ?? 'An independent academic portal committed to maintaining rigorous peer-review standards and promoting open access to global research.' }}
            </p>
        </div>
        
        <div class="md:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-8 md:pl-8">
            @php
                try {
                    // Ambil kategori CMS dari database
                    $footerMenus = \App\Models\FooterCategory::with(['cmsPages' => function($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }])->orderBy('sort_order')->get();
                } catch (\Exception $e) {
                    $footerMenus = collect(); 
                }
            @endphp

            @forelse($footerMenus as $menu)
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">
                        {{ $menu->name }}
                    </h4>
                    <ul class="text-sm space-y-3">
                        @forelse($menu->cmsPages as $page)
                            <li>
                                <a href="{{ route('cms.show', $page->slug) }}" class="flex items-center hover:text-white transition group text-slate-400">
                                    <svg class="w-3 h-3 mr-2 text-slate-600 group-hover:text-indigo-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    {{ $page->title }}
                                </a>
                            </li>
                        @empty
                            <li class="text-slate-600 italic text-xs">Belum ada konten.</li>
                        @endforelse
                    </ul>
                </div>
            @empty
                <div class="col-span-full">
                    <p class="text-slate-500 italic text-xs">Menu footer belum diatur di Admin.</p>
                </div>
            @endforelse
        </div>

        <div class="md:col-span-3 flex md:justify-end">
            <div class="w-full md:w-auto">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">
                    Support & Contact
                </h4>
                <ul class="text-sm space-y-3">
                    <li class="flex items-center hover:text-white transition">
                        <svg class="w-4 h-4 mr-3 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <a href="mailto:{{ $settings->contact_email ?? '-' }}" class="truncate">{{ $settings->contact_email ?? '-' }}</a>
                    </li>
                    <li class="flex items-center hover:text-white transition">
                        <svg class="w-4 h-4 mr-3 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $settings->contact_phone ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    
    <div class="max-w-7xl mx-auto px-4 mt-12 pt-6 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500">
        
        <p class="mb-4 md:mb-0">&copy; {{ date('Y') }} {{ $settings->site_name ?? 'SustainScript' }}. All rights reserved.</p>

        <div class="flex items-center space-x-6 uppercase tracking-widest text-[10px] bg-slate-800/50 px-4 py-2 rounded">
            @php
                try {
                    $totalVisitors = cache()->remember('total_visitors', 600, function() {
                        return \App\Models\Visitor::count();
                    });
                    $todayVisitors = cache()->remember('today_visitors', 600, function() {
                        return \App\Models\Visitor::whereDate('created_at', now())->count();
                    });
                } catch (\Exception $e) {
                    $totalVisitors = 0;
                    $todayVisitors = 0;
                }
            @endphp

            <div class="flex items-center">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse" title="Live Users Tracking"></span>
                Today: <span class="ml-1 text-slate-300 font-bold">{{ number_format($todayVisitors) }}</span>
            </div>
            
            <div class="flex items-center border-l border-slate-700 pl-6">
                <svg class="w-3 h-3 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Total: <span class="ml-1 text-slate-300 font-bold">{{ number_format($totalVisitors) }}</span>
            </div>
        </div>

    </div>
</footer>