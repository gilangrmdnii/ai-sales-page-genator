<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AI Sales Page Generator') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-white bg-slate-950">

    {{-- Animated aurora background --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-900 to-black"></div>
    <div class="aurora-blob -z-10" style="top:-10%; left:-10%; width:55vw; height:55vw; background: radial-gradient(circle, rgba(139,92,246,0.55) 0%, transparent 70%);"
         x-data x-init="$el.classList.add('animate-aurora-drift')"></div>
    <div class="aurora-blob -z-10" style="top:30%; right:-15%; width:55vw; height:55vw; background: radial-gradient(circle, rgba(56,189,248,0.42) 0%, transparent 70%);"
         x-data x-init="$el.classList.add('animate-aurora-slow')"></div>
    <div class="aurora-blob -z-10" style="bottom:-15%; left:25%; width:50vw; height:50vw; background: radial-gradient(circle, rgba(236,72,153,0.35) 0%, transparent 70%);"
         x-data x-init="$el.classList.add('animate-aurora-drift')"></div>
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.04),transparent_60%)]"></div>
    {{-- Subtle noise to break up the gradient --}}
    <div class="fixed inset-0 -z-10 opacity-[0.025] mix-blend-overlay pointer-events-none"
         style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22120%22 height=%22120%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22/></filter><rect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/></svg>');"></div>

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-30 w-72 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="h-full glass-strong lg:rounded-none rounded-r-3xl m-0 flex flex-col">
                {{-- Brand --}}
                <div class="px-6 pt-7 pb-6">
                    <a href="{{ route('sales-pages.index') }}" class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 shadow-glow flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-display font-bold text-white tracking-tight">AI Sales</p>
                            <p class="text-xs text-white/50 -mt-0.5">Page Studio</p>
                        </div>
                    </a>
                </div>

                <div class="px-3 mt-2 flex-1 overflow-y-auto">
                    <p class="px-3 mb-2 text-[10px] uppercase tracking-[0.18em] text-white/40 font-semibold">Workspace</p>
                    <nav class="space-y-1">
                        <a href="{{ route('sales-pages.index') }}"
                           class="nav-pill {{ request()->routeIs('sales-pages.index') || request()->routeIs('sales-pages.show') ? 'nav-pill-active' : '' }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
                            </svg>
                            Sales Pages
                        </a>
                        <a href="{{ route('sales-pages.create') }}"
                           class="nav-pill {{ request()->routeIs('sales-pages.create') ? 'nav-pill-active' : '' }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Generation
                        </a>
                    </nav>

                    <p class="px-3 mt-8 mb-2 text-[10px] uppercase tracking-[0.18em] text-white/40 font-semibold">Account</p>
                    <nav class="space-y-1">
                        <a href="{{ route('profile.edit') }}"
                           class="nav-pill {{ request()->routeIs('profile.*') ? 'nav-pill-active' : '' }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Profile
                        </a>
                    </nav>
                </div>

                {{-- Upgrade card --}}
                <div class="p-4">
                    <div class="glass rounded-2xl p-4 relative overflow-hidden">
                        <div class="absolute -top-8 -right-8 h-24 w-24 rounded-full bg-fuchsia-500/30 blur-2xl"></div>
                        <p class="text-xs text-white/60">Powered by</p>
                        <p class="font-display font-bold text-white">Groq · Llama 3.3</p>
                        <p class="mt-2 text-[11px] text-white/50">Crafting copy that converts.</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-20 lg:hidden"
             style="display: none;"></div>

        {{-- Main column --}}
        <div class="flex-1 min-w-0 flex flex-col lg:pl-0">

            {{-- Top bar --}}
            <header class="sticky top-0 z-10 px-4 sm:px-8 pt-5">
                <div class="glass rounded-2xl flex items-center gap-4 px-4 sm:px-6 py-3">
                    <button @click="sidebarOpen = ! sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-white/5 text-white/70">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="flex-1 min-w-0">
                        @isset($header)
                            <div class="text-white">{{ $header }}</div>
                        @else
                            <p class="font-display font-semibold text-white tracking-tight">Welcome back, {{ Auth::user()->name }}</p>
                        @endisset
                    </div>

                    {{-- User menu --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = ! open" class="flex items-center gap-3 rounded-full pl-3 pr-2 py-1.5 hover:bg-white/5 transition">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm text-white font-medium leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-white/50 leading-tight">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white text-sm font-semibold shadow-glow">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" x-transition @click.outside="open = false"
                             class="absolute right-0 mt-2 w-56 glass-strong rounded-2xl p-2 z-50"
                             style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm rounded-lg text-white/80 hover:text-white hover:bg-white/5">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-sm rounded-lg text-rose-300 hover:bg-rose-500/10">Log out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 sm:px-8 py-8 animate-fade-in">
                {{ $slot }}
            </main>

            <footer class="px-8 pb-6 text-center text-xs text-white/30">
                {{ config('app.name') }} &middot; {{ date('Y') }}
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
