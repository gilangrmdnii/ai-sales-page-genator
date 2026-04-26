<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-display text-2xl font-bold text-white">Welcome back</h1>
        <p class="mt-1.5 text-sm text-white/60">Sign in to your studio.</p>
    </div>

    <x-auth-session-status class="mb-4 text-emerald-300" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-white/80 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="glass-input block w-full px-4 py-3" />
            @error('email') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-white/80 mb-2">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="glass-input block w-full px-4 py-3" />
            @error('password') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center text-sm text-white/70">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded bg-white/10 border-white/20 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-0">
                <span class="ms-2">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-300 hover:text-indigo-200 transition" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-gradient w-full">Sign in</button>

        <p class="text-center text-sm text-white/60">
            New here?
            <a href="{{ route('register') }}" class="text-indigo-300 hover:text-indigo-200 font-medium transition">Create an account</a>
        </p>
    </form>
</x-guest-layout>
