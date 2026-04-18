<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-serif font-bold text-slate-800">Secure Access</h2>
        <p class="text-sm text-slate-500 mt-1">Please log in to your account.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-semibold text-sm text-slate-700 mb-1">Email Address</label>
            <input id="email" class="block w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-slate-50" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <div class="flex justify-between items-center mb-1">
                <label for="password" class="block font-semibold text-sm text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:text-indigo-800 underline transition" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <input id="password" class="block w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-slate-50"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Remember me</span>
            </label>
        </div>

        <div class="mt-8 flex flex-col gap-4">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Log In
            </button>
            <p class="text-center text-sm text-slate-500">
                Don't have an account? 
                <a class="font-bold text-indigo-600 hover:text-indigo-800 underline transition" href="{{ route('register') }}">
                    Register here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
