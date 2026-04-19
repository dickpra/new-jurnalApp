<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-serif font-bold text-slate-900 hover:text-indigo-700 transition">
                        SustainScript
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <x-nav-link :href="route('author.dashboard')" :active="request()->routeIs('author.*')">
                        {{ __('Author Workspace') }}
                    </x-nav-link>

                    @if(\App\Models\Review::where('reviewer_id', Auth::id())->exists())
                        <x-nav-link :href="route('reviewer.dashboard')" :active="request()->routeIs('reviewer.*')">
                            {{ __('Reviewer Workspace') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->journalThemes()->wherePivot('role_in_theme', 'manager')->exists())
                        <x-nav-link :href="url('/manager')" class="text-indigo-600 font-bold border-indigo-500 hover:text-indigo-800">
                            {{ __('⚙️ Manager Panel') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->is_super_admin)
                        <x-nav-link :href="url('/admin')" class="text-rose-600 font-bold border-rose-500 hover:text-rose-800">
                            {{ __('👑 Super Admin') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-500 bg-white hover:text-slate-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="font-semibold">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <span class="text-rose-600 font-medium">{{ __('Log Out') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            
            <x-responsive-nav-link :href="route('author.dashboard')" :active="request()->routeIs('author.*')">
                {{ __('Author Workspace') }}
            </x-responsive-nav-link>

            @if(\App\Models\Review::where('reviewer_id', Auth::id())->exists())
                <x-responsive-nav-link :href="route('reviewer.dashboard')" :active="request()->routeIs('reviewer.*')">
                    {{ __('Reviewer Workspace') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->journalThemes()->wherePivot('role_in_theme', 'manager')->exists())
                <x-responsive-nav-link :href="url('/manager')" class="text-indigo-700 font-bold bg-indigo-50 border-indigo-300">
                    {{ __('⚙️ Manager Panel') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->is_super_admin)
                <x-responsive-nav-link :href="url('/admin')" class="text-rose-700 font-bold bg-rose-50 border-rose-300">
                    {{ __('👑 Super Admin') }}
                </x-responsive-nav-link>
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <span class="text-rose-600">{{ __('Log Out') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>