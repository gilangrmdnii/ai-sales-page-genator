<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="font-display text-2xl font-bold text-white">Create your account</h1>
        <p class="mt-1.5 text-sm text-white/60">Start generating premium sales pages in seconds.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-white/80 mb-2">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="glass-input block w-full px-4 py-3" />
            @error('name') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-white/80 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="glass-input block w-full px-4 py-3" />
            @error('email') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-white/80 mb-2">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="glass-input block w-full px-4 py-3" />
            @error('password') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-white/80 mb-2">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="glass-input block w-full px-4 py-3" />
            @error('password_confirmation') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-gradient w-full">Create account</button>

        <p class="text-center text-sm text-white/60">
            Already have one?
            <a href="{{ route('login') }}" class="text-indigo-300 hover:text-indigo-200 font-medium transition">Sign in</a>
        </p>
    </form>
</x-guest-layout>
