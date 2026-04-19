<x-guest-layout>
    <div class="mb-6 border-b border-slate-100 pb-4 text-center">
        <h2 class="text-xl font-serif font-bold text-slate-800">Verify Your Email Address</h2>
    </div>

    <div class="mb-6 text-sm text-slate-600 text-justify leading-relaxed">
        Thanks for registering! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded relative text-sm font-medium">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto flex justify-center py-2.5 px-6 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-indigo-700 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto inline-flex justify-center text-sm font-bold text-slate-600 hover:text-rose-600 underline transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
