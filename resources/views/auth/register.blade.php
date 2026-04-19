<x-guest-layout>
    <div class="mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-xl font-serif font-bold text-slate-800">Create Account</h2>
        <p class="text-sm text-slate-500 mt-1">Register to submit manuscripts or review articles.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-semibold text-sm text-slate-700 mb-1">Full Name</label>
            <input id="name" class="block w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-slate-50" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-rose-500" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <label for="email" class="block font-semibold text-sm text-slate-700 mb-1">Email Address</label>
            <input id="email" class="block w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-slate-50" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500" />
        </div>

        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <select id="country" name="country" class="block mt-1 w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled selected>Select Country...</option>

                @foreach(config('countries', []) as $country)
                    <option value="{{ $country }}">{{ $country }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block font-semibold text-sm text-slate-700 mb-1">Password</label>
            <input id="password" class="block w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-slate-50"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <label for="password_confirmation" class="block font-semibold text-sm text-slate-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" class="block w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-slate-50"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500" />
        </div>

        <div class="mt-8 flex flex-col gap-4">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Register Account
            </button>
            <p class="text-center text-sm text-slate-500">
                Already registered? 
                <a class="font-bold text-indigo-600 hover:text-indigo-800 underline transition" href="{{ route('login') }}">
                    Log in here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
