<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') · {{ config('app.name', 'AI Sales Page Generator') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-950 text-white antialiased overflow-hidden" style="font-family: 'Inter', system-ui, sans-serif;">

    {{-- Animated aurora bg --}}
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-black"></div>
        <div class="absolute inset-0 animate-aurora-drift opacity-80"
             style="background:
                radial-gradient(60% 60% at 20% 20%, rgba(139,92,246,0.30) 0%, transparent 70%),
                radial-gradient(50% 50% at 80% 30%, rgba(56,189,248,0.22) 0%, transparent 70%),
                radial-gradient(70% 70% at 60% 100%, rgba(236,72,153,0.20) 0%, transparent 70%);"></div>
    </div>

    <main class="relative min-h-screen flex items-center justify-center px-6 py-16">
        <div class="w-full max-w-xl text-center">

            {{-- Status code --}}
            <div class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/[0.06] backdrop-blur-md px-5 py-2 text-xs uppercase tracking-[0.18em] text-white/70 animate-fade-in">
                <span class="h-1.5 w-1.5 rounded-full bg-rose-400 shadow-[0_0_8px_rgba(251,113,133,0.9)]"></span>
                Error @yield('code')
            </div>

            {{-- Big code --}}
            <h1 class="mt-8 font-display font-black text-[7rem] sm:text-[10rem] leading-none tracking-tighter
                       bg-clip-text text-transparent bg-gradient-to-br from-white via-white/80 to-white/30
                       animate-fade-in-up">
                @yield('code')
            </h1>

            {{-- Title --}}
            <h2 class="mt-4 font-display text-2xl sm:text-3xl font-bold text-white animate-fade-in-up" style="animation-delay: 100ms">
                @yield('title')
            </h2>

            {{-- Description --}}
            <p class="mt-4 text-white/60 leading-relaxed max-w-md mx-auto animate-fade-in-up" style="animation-delay: 200ms">
                @yield('description')
            </p>

            {{-- CTA --}}
            <div class="mt-10 flex items-center justify-center gap-3 flex-wrap animate-fade-in-up" style="animation-delay: 320ms">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] backdrop-blur-md px-5 py-3 text-sm font-medium text-white/80 hover:bg-white/[0.08] hover:text-white hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Go back
                </a>
                <a href="{{ url('/') }}"
                   class="group relative inline-flex items-center gap-2 rounded-full px-6 py-3 text-sm font-semibold text-white
                          bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500
                          shadow-[0_0_30px_-5px_rgba(139,92,246,0.6)]
                          hover:shadow-[0_0_50px_-5px_rgba(139,92,246,1)]
                          hover:-translate-y-0.5 hover:scale-[1.03] transition-all duration-300">
                    <span class="pointer-events-none absolute inset-0 rounded-full overflow-hidden">
                        <span class="absolute inset-y-0 -left-1/3 w-1/3 bg-gradient-to-r from-transparent via-white/30 to-transparent skew-x-12 -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000"></span>
                    </span>
                    <span class="relative">Take me home</span>
                    <svg class="relative h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            {{-- App watermark --}}
            <p class="mt-16 text-[11px] text-white/30 uppercase tracking-[0.18em] animate-fade-in" style="animation-delay: 500ms">
                {{ config('app.name', 'AI Sales Page Generator') }}
            </p>
        </div>
    </main>
</body>
</html>
