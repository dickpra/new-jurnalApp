<footer class="bg-slate-900 border-t border-slate-800 text-slate-300 py-12 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-8">
        
        <div class="md:col-span-5">
            <span class="text-xl font-serif font-bold text-white mb-4 block">
                {{ $settings->site_name ?? 'Journal Publishing Platform' }}
            </span>
            <p class="text-sm leading-relaxed pr-8 text-slate-400 text-justify">
                {{ $settings->about_text ?? 'An independent academic portal committed to maintaining rigorous peer-review standards and promoting open access to global research.' }}
            </p>
        </div>
        
        <div class="md:col-span-3 md:col-start-9">
            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-slate-700 pb-2">Support & Contact</h4>
            <ul class="text-sm space-y-3">
                <li class="flex items-center hover:text-white transition">
                    <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <a href="mailto:{{ $settings->contact_email ?? 'admin@yudharta.ac.id' }}">{{ $settings->contact_email ?? 'admin@yudharta.ac.id' }}</a>
                </li>
                <li class="flex items-center hover:text-white transition">
                    <svg class="w-4 h-4 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span>{{ $settings->contact_phone ?? '(0343) 611186' }}</span>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 mt-8 pt-8 border-t border-slate-800 text-xs text-slate-500 flex justify-between">
        <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? 'Publishing Platform' }}. All rights reserved.</p>
    </div>
</footer>