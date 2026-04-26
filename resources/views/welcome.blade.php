<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Generate complete, persuasive, beautifully designed sales landing pages from a short product brief. Powered by Groq, built for speed.">
    <title>{{ config('app.name', 'AI Sales Page Generator') }} — Sales pages, generated in seconds</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800,900|jetbrains-mono:400,500&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            font-feature-settings: "ss01", "cv11";
        }
        .font-display { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', ui-monospace, monospace; }

        /* Mouse-aware spotlight */
        .spotlight {
            background: radial-gradient(600px circle at var(--mx, 50%) var(--my, 50%),
                rgba(139, 92, 246, 0.18),
                transparent 40%);
        }

        /* Grid pattern overlay */
        .grid-bg {
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 30%, transparent 80%);
        }

        /* Animated gradient border */
        .gradient-border {
            position: relative;
            background: linear-gradient(#0b0b14, #0b0b14) padding-box,
                        linear-gradient(135deg, #818cf8, #c084fc, #f0abfc, #818cf8) border-box;
            background-size: 100% 100%, 200% 200%;
            border: 1px solid transparent;
            animation: borderShift 6s ease infinite;
        }
        @keyframes borderShift {
            0%, 100% { background-position: 0% 0%, 0% 50%; }
            50%      { background-position: 0% 0%, 100% 50%; }
        }

        /* Ticker / marquee */
        @keyframes ticker {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-ticker { animation: ticker 40s linear infinite; }

        /* Typing caret */
        .caret::after {
            content: '▌';
            color: #a5b4fc;
            margin-left: 2px;
            animation: blink 1.1s steps(2) infinite;
        }
        @keyframes blink { 0%, 49% { opacity: 1; } 50%, 100% { opacity: 0; } }

        /* Float for floating chunks */
        .float-slow { animation: floaty 7s ease-in-out infinite; }
        .float-mid  { animation: floaty 5s ease-in-out infinite; }
        @keyframes floaty {
            0%, 100% { transform: translateY(0) rotate(var(--r, 0deg)); }
            50%      { transform: translateY(-14px) rotate(var(--r, 0deg)); }
        }

        /* Tilt-on-hover — pure CSS with JS-set vars */
        .tilt {
            transform-style: preserve-3d;
            transition: transform 0.4s cubic-bezier(0.22,1,0.36,1);
        }
        .tilt-content { transform-style: preserve-3d; transition: transform 0.4s cubic-bezier(0.22,1,0.36,1); }

        /* Reveal on scroll */
        [data-reveal] {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.9s cubic-bezier(0.22,1,0.36,1), transform 0.9s cubic-bezier(0.22,1,0.36,1);
            transition-delay: var(--reveal-delay, 0ms);
        }
        [data-reveal].is-visible { opacity: 1; transform: translateY(0); }

        /* Counter shimmer */
        .stat-glow {
            background: linear-gradient(135deg, #fff 0%, #c4b5fd 40%, #f0abfc 80%, #fff 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: statShine 4s ease-in-out infinite;
        }
        @keyframes statShine {
            0%, 100% { background-position: 0% 50%; }
            50%      { background-position: 100% 50%; }
        }

        /* Scrollbar in code preview */
        .code-scroll::-webkit-scrollbar { width: 6px; }
        .code-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 9999px; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }
            [data-reveal] { opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="bg-[#06060c] text-white overflow-x-hidden antialiased">

    {{-- ═══════════════════════════════════════════════════════════════════
         AMBIENT BACKGROUND — fixed, behind everything
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute inset-0 bg-[#06060c]"></div>
        <div class="aurora-blob bg-indigo-500" style="top:-20%; left:-10%; width:60vw; height:60vw; animation: auroraDrift 22s ease-in-out infinite;"></div>
        <div class="aurora-blob bg-fuchsia-500" style="top:30%; right:-15%; width:55vw; height:55vw; animation: auroraDrift 28s ease-in-out infinite reverse;"></div>
        <div class="aurora-blob bg-violet-500" style="bottom:-20%; left:30%; width:50vw; height:50vw; animation: auroraDrift 34s ease-in-out infinite;"></div>
        <div class="absolute inset-0 grid-bg"></div>
        <div class="absolute inset-0" style="background:
            radial-gradient(ellipse at top, transparent 0%, #06060c 70%);"></div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         STICKY NAV
         ═══════════════════════════════════════════════════════════════════ --}}
    <header class="fixed top-0 inset-x-0 z-50">
        <div class="mx-auto mt-4 max-w-6xl px-4">
            <nav class="glass rounded-full flex items-center justify-between gap-4 px-4 sm:px-6 py-2.5">
                <a href="#" class="flex items-center gap-2.5 group">
                    <span class="relative h-8 w-8 rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 shadow-glow flex items-center justify-center group-hover:scale-105 transition">
                        <span class="absolute inset-0 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 blur-md opacity-50 group-hover:opacity-100 transition"></span>
                        <svg class="relative h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </span>
                    <span class="font-display font-bold text-white tracking-tight">SalesGen<span class="text-fuchsia-400">.</span></span>
                </a>

                <div class="hidden md:flex items-center gap-7 text-sm text-white/60">
                    <a href="#how" class="hover:text-white transition">How it works</a>
                    <a href="#features" class="hover:text-white transition">Features</a>
                    <a href="#templates" class="hover:text-white transition">Templates</a>
                    <a href="#faq" class="hover:text-white transition">FAQ</a>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-gradient">
                            Dashboard
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm text-white/70 hover:text-white px-3 py-2 transition">Sign in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-gradient">
                                Get started
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    {{-- ═══════════════════════════════════════════════════════════════════
         HERO
         ═══════════════════════════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center pt-32 pb-20" id="hero">
        <div class="spotlight absolute inset-0 pointer-events-none" id="spotlight"></div>

        <div class="relative max-w-7xl mx-auto px-6 w-full">
            <div class="grid lg:grid-cols-12 gap-12 items-center">

                {{-- Left: copy --}}
                <div class="lg:col-span-7 space-y-8">

                    {{-- Status pill --}}
                    <div class="inline-flex items-center gap-2.5 rounded-full border border-white/15 bg-white/[0.05] backdrop-blur-md px-4 py-1.5 text-xs uppercase tracking-[0.18em] text-white/70 animate-fade-in-down">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        Powered by Groq · Llama 3.3
                    </div>

                    {{-- Headline --}}
                    <h1 class="font-display font-black tracking-tighter text-5xl sm:text-7xl lg:text-[5.5rem] leading-[0.92] animate-fade-in-up">
                        Sales pages,<br>
                        <span class="text-gradient">written by AI.</span><br>
                        <span class="relative inline-block">
                            Designed by you.
                            <svg class="absolute -bottom-3 left-0 w-full h-3 text-fuchsia-500/70" viewBox="0 0 200 8" fill="none" preserveAspectRatio="none">
                                <path d="M0 4 Q 50 0, 100 4 T 200 4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>

                    <p class="text-lg sm:text-xl text-white/65 leading-relaxed max-w-xl animate-fade-in-up" style="animation-delay: 100ms">
                        Drop in a product brief. Get a complete, persuasive landing page — headline, benefits, CTAs, the works — rendered through one of three luxury templates. <span class="text-white">Done in under five seconds.</span>
                    </p>

                    {{-- CTAs --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 animate-fade-in-up" style="animation-delay: 220ms">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="group relative inline-flex items-center justify-center gap-2.5 rounded-full px-7 py-4 text-base font-semibold text-white
                                      bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500
                                      shadow-[0_0_50px_-5px_rgba(139,92,246,0.7)]
                                      hover:shadow-[0_0_80px_-5px_rgba(139,92,246,1)]
                                      hover:-translate-y-0.5 hover:scale-[1.03] active:scale-[0.98]
                                      animate-glow-pulse
                                      transition-all duration-300 overflow-hidden">
                                <span class="pointer-events-none absolute inset-0 rounded-full overflow-hidden">
                                    <span class="absolute inset-y-0 -left-1/3 w-1/3 bg-gradient-to-r from-transparent via-white/40 to-transparent skew-x-12 -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000"></span>
                                </span>
                                <span class="relative">Generate your first page</span>
                                <svg class="relative h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                        <a href="#demo" class="btn-ghost px-6 py-4 text-base">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Watch it work
                        </a>
                    </div>

                    {{-- Trust micro-row --}}
                    <div class="flex items-center gap-6 text-xs text-white/40 flex-wrap pt-2 animate-fade-in" style="animation-delay: 350ms">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            No credit card required
                        </div>
                        <div class="hidden sm:flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Free during beta
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Export as standalone HTML
                        </div>
                    </div>
                </div>

                {{-- Right: live demo preview card --}}
                <div class="lg:col-span-5 animate-fade-in-up" style="animation-delay: 200ms">
                    <div class="relative">
                        {{-- Floating decorative chunks --}}
                        <div class="hidden sm:block absolute -top-6 -left-6 h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500/30 to-fuchsia-500/30 backdrop-blur-md border border-white/10 float-slow" style="--r: -8deg"></div>
                        <div class="hidden sm:block absolute -bottom-4 -right-4 h-20 w-20 rounded-full bg-gradient-to-br from-fuchsia-500/30 to-rose-500/30 backdrop-blur-md border border-white/10 float-mid" style="--r: 5deg"></div>

                        {{-- Main card --}}
                        <div class="gradient-border rounded-3xl p-1">
                            <div class="rounded-[1.4rem] bg-[#0b0b14]/95 backdrop-blur-xl overflow-hidden">
                                {{-- Card header — terminal-style --}}
                                <div class="flex items-center justify-between px-5 py-3 border-b border-white/[0.06]">
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-full bg-rose-400/80"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-amber-400/80"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-400/80"></span>
                                    </div>
                                    <div class="font-mono text-[11px] text-white/40">salesgen.app/generate</div>
                                    <span class="text-[10px] text-emerald-400 font-mono">● live</span>
                                </div>

                                {{-- Body — typing simulation --}}
                                <div class="p-6 font-mono text-sm space-y-4 min-h-[420px]">
                                    <div class="flex items-start gap-3">
                                        <span class="text-fuchsia-400 select-none">▸</span>
                                        <div class="text-white/50 text-xs uppercase tracking-wider">brief</div>
                                    </div>
                                    <div class="rounded-lg bg-white/[0.03] border border-white/5 p-3.5">
                                        <div class="text-white/70 text-[13px] leading-relaxed" id="brief-line"></div>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2">
                                        <span class="text-emerald-400 select-none">▸</span>
                                        <span class="text-white/50 text-xs uppercase tracking-wider">generating</span>
                                        <span id="gen-dots" class="text-emerald-400 font-mono text-xs"></span>
                                    </div>

                                    <div class="space-y-3" id="gen-output">
                                        <div class="rounded-lg bg-gradient-to-r from-indigo-500/10 to-transparent border border-white/5 p-3">
                                            <div class="text-[10px] text-indigo-300 font-semibold uppercase tracking-wider">Headline</div>
                                            <div class="mt-1 text-white text-base font-display font-bold caret" id="out-headline"></div>
                                        </div>
                                        <div class="rounded-lg bg-gradient-to-r from-violet-500/10 to-transparent border border-white/5 p-3">
                                            <div class="text-[10px] text-violet-300 font-semibold uppercase tracking-wider">Subheadline</div>
                                            <div class="mt-1 text-white/85 text-sm" id="out-sub"></div>
                                        </div>
                                        <div class="rounded-lg bg-gradient-to-r from-fuchsia-500/10 to-transparent border border-white/5 p-3">
                                            <div class="text-[10px] text-fuchsia-300 font-semibold uppercase tracking-wider">CTA</div>
                                            <div class="mt-1 inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500 text-white text-xs font-semibold" id="out-cta"></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-white/[0.06]">
                                        <div class="flex items-center gap-1.5 text-[11px] text-white/40 font-mono">
                                            <svg class="h-3 w-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            <span id="gen-time">3.2s</span>
                                        </div>
                                        <div class="text-[11px] text-white/40 font-mono">432 tokens</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         TICKER STRIP — animated marquee
         ═══════════════════════════════════════════════════════════════════ --}}
    <section class="relative border-y border-white/[0.06] bg-white/[0.02] backdrop-blur-sm py-5 overflow-hidden">
        <div class="flex animate-ticker whitespace-nowrap gap-12 text-sm font-display font-medium">
            @for ($i = 0; $i < 6; $i++)
                <span class="flex items-center gap-3 text-white/50 px-3">
                    <svg class="h-4 w-4 text-fuchsia-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6.2 4.5L18.2 22 12 17.5 5.8 22l2.4-8.1L2 9.4h7.6z"/></svg>
                    <span>Eight cohesive sections</span>
                </span>
                <span class="flex items-center gap-3 text-white/50 px-3">
                    <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>5-second turnaround</span>
                </span>
                <span class="flex items-center gap-3 text-white/50 px-3">
                    <svg class="h-4 w-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    <span>Three luxury templates</span>
                </span>
                <span class="flex items-center gap-3 text-white/50 px-3">
                    <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Section-by-section regenerate</span>
                </span>
                <span class="flex items-center gap-3 text-white/50 px-3">
                    <svg class="h-4 w-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export as standalone HTML</span>
                </span>
            @endfor
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         STATS — counter-up
         ═══════════════════════════════════════════════════════════════════ --}}
    <section class="relative py-24" data-reveal>
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="glass rounded-3xl p-8 text-center">
                    <div class="font-display font-black text-5xl sm:text-6xl stat-glow tracking-tighter" data-counter="5" data-suffix="s">0s</div>
                    <p class="mt-3 text-sm text-white/50 uppercase tracking-wider">Avg generation</p>
                </div>
                <div class="glass rounded-3xl p-8 text-center">
                    <div class="font-display font-black text-5xl sm:text-6xl stat-glow tracking-tighter" data-counter="8" data-suffix="">0</div>
                    <p class="mt-3 text-sm text-white/50 uppercase tracking-wider">Sections per page</p>
                </div>
                <div class="glass rounded-3xl p-8 text-center">
                    <div class="font-display font-black text-5xl sm:text-6xl stat-glow tracking-tighter" data-counter="3" data-suffix="">0</div>
                    <p class="mt-3 text-sm text-white/50 uppercase tracking-wider">Design templates</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         HOW IT WORKS — 3 steps
         ═══════════════════════════════════════════════════════════════════ --}}
    <section id="how" class="relative py-32">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto" data-reveal>
                <p class="text-xs uppercase tracking-[0.22em] text-indigo-300/80 font-semibold">How it works</p>
                <h2 class="mt-4 font-display font-bold text-4xl sm:text-6xl tracking-tight">
                    Three steps. One <span class="text-gradient">remarkable</span> page.
                </h2>
                <p class="mt-5 text-white/60 leading-relaxed">No prompt engineering. No design decisions. No copywriting hangover at 2am. Just a brief, a button, and a page that converts.</p>
            </div>

            <div class="mt-20 grid lg:grid-cols-3 gap-6 relative">
                {{-- Connecting line (desktop) --}}
                <div class="hidden lg:block absolute top-12 left-[16.66%] right-[16.66%] h-px bg-gradient-to-r from-transparent via-indigo-500/30 to-transparent"></div>

                @foreach ([
                    ['01', 'Describe', 'Drop in your product name, audience, features, and price. Two minutes, max.', 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['02', 'Generate', 'Our model writes eight cohesive sections — headline through CTA — in one shot. Validated JSON. No hallucinated keys.', 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['03', 'Publish', 'Pick a template, preview live, share the URL or download standalone HTML. Shipped.', 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'],
                ] as $i => [$num, $title, $desc, $icon])
                    <div data-reveal style="--reveal-delay: {{ $i * 120 }}ms"
                         class="glass rounded-3xl p-8 hover:bg-white/[0.06] hover:-translate-y-1 transition-all duration-300 group">
                        <div class="flex items-center justify-between">
                            <div class="relative h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-fuchsia-500/20 border border-white/10 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform">
                                <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 blur-xl opacity-0 group-hover:opacity-30 transition"></div>
                                <svg class="relative h-5 w-5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                            </div>
                            <span class="font-mono text-2xl font-bold text-white/15 group-hover:text-white/30 transition">{{ $num }}</span>
                        </div>
                        <h3 class="mt-6 font-display font-bold text-xl text-white">{{ $title }}</h3>
                        <p class="mt-3 text-white/60 leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         FEATURES GRID
         ═══════════════════════════════════════════════════════════════════ --}}
    <section id="features" class="relative py-32">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto" data-reveal>
                <p class="text-xs uppercase tracking-[0.22em] text-fuchsia-300/80 font-semibold">Built right</p>
                <h2 class="mt-4 font-display font-bold text-4xl sm:text-6xl tracking-tight">
                    Production-grade<br>from <span class="text-gradient">day one</span>.
                </h2>
            </div>

            <div class="mt-20 grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ([
                    [
                        'title' => 'Async by default',
                        'desc'  => 'Generation runs on a queue. Your browser polls, the server stays unblocked, and 30s LLM calls never time out a request.',
                        'icon'  => 'M13 10V3L4 14h7v7l9-11h-7z',
                        'color' => 'from-indigo-500/20 to-violet-500/10 text-indigo-300',
                    ],
                    [
                        'title' => 'Two-tier rate limits',
                        'desc'  => '5 per minute, 50 per day per user — enforced at the route layer plus a quota table. Your wallet stays put.',
                        'icon'  => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                        'color' => 'from-fuchsia-500/20 to-rose-500/10 text-fuchsia-300',
                    ],
                    [
                        'title' => 'Section regeneration',
                        'desc'  => 'Hate the headline? Regenerate just that. The other seven sections stay exactly as you styled them.',
                        'icon'  => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                        'color' => 'from-emerald-500/20 to-teal-500/10 text-emerald-300',
                    ],
                    [
                        'title' => 'Three luxury templates',
                        'desc'  => 'Aurora (dark luxe), Minimal (editorial), Bold (brutalist). Switch any time — your content survives.',
                        'icon'  => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                        'color' => 'from-violet-500/20 to-fuchsia-500/10 text-violet-300',
                    ],
                    [
                        'title' => 'Standalone export',
                        'desc'  => 'One click downloads a single HTML file. Tailwind CDN, fonts, animations — all inlined. Host it anywhere.',
                        'icon'  => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4',
                        'color' => 'from-amber-500/20 to-orange-500/10 text-amber-300',
                    ],
                    [
                        'title' => 'Provider-agnostic',
                        'desc'  => 'Works with any OpenAI-compatible endpoint. Swap Groq for OpenAI, Cerebras, OpenRouter, or your own — one env var.',
                        'icon'  => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                        'color' => 'from-sky-500/20 to-cyan-500/10 text-sky-300',
                    ],
                ] as $i => $f)
                    <div data-reveal style="--reveal-delay: {{ $i * 70 }}ms"
                         class="group relative glass rounded-3xl p-7 hover:bg-white/[0.07] hover:-translate-y-1 hover:shadow-[0_30px_60px_-20px_rgba(139,92,246,0.4)] transition-all duration-500">
                        <div class="absolute -top-px left-12 right-12 h-px bg-gradient-to-r from-transparent via-white/30 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $f['color'] }} border border-white/10 flex items-center justify-center group-hover:scale-110 group-hover:rotate-6 transition-transform">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <h3 class="mt-6 font-display font-bold text-lg text-white">{{ $f['title'] }}</h3>
                        <p class="mt-2.5 text-sm text-white/60 leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         TEMPLATES SHOWCASE
         ═══════════════════════════════════════════════════════════════════ --}}
    <section id="templates" class="relative py-32">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto" data-reveal>
                <p class="text-xs uppercase tracking-[0.22em] text-violet-300/80 font-semibold">Templates</p>
                <h2 class="mt-4 font-display font-bold text-4xl sm:text-6xl tracking-tight">
                    Three personalities.<br><span class="text-gradient">Pick yours.</span>
                </h2>
                <p class="mt-5 text-white/60 leading-relaxed">Every template is a complete, mobile-ready landing layout — not just a colour swap. Switch between them at any time without regenerating content.</p>
            </div>

            <div class="mt-20 grid md:grid-cols-3 gap-6">
                {{-- Aurora --}}
                <div data-reveal class="group relative rounded-3xl overflow-hidden border border-white/10 bg-white/[0.02] hover:border-white/20 hover:-translate-y-2 transition-all duration-500">
                    <div class="aspect-[4/5] relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-fuchsia-950"></div>
                        <div class="absolute inset-0 animate-aurora-drift" style="background:
                            radial-gradient(50% 50% at 30% 30%, rgba(139,92,246,0.5) 0%, transparent 70%),
                            radial-gradient(50% 50% at 70% 70%, rgba(236,72,153,0.4) 0%, transparent 70%);"></div>
                        <div class="relative h-full flex flex-col justify-center items-center p-8 text-center">
                            <span class="text-[10px] uppercase tracking-[0.22em] text-white/50">Aurora Notes</span>
                            <h4 class="mt-4 font-display font-bold text-2xl text-gradient">Notes that move at the speed of thought.</h4>
                            <div class="mt-6 inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 bg-gradient-to-r from-indigo-500 to-fuchsia-500 text-white text-xs font-semibold">
                                Start writing free →
                            </div>
                        </div>
                    </div>
                    <div class="p-6 border-t border-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display font-bold text-white">Aurora</h3>
                                <p class="text-xs text-white/50 mt-0.5">Dark luxe with glowing gradients</p>
                            </div>
                            <span class="text-[10px] uppercase tracking-wider text-indigo-300 font-semibold">Default</span>
                        </div>
                    </div>
                </div>

                {{-- Minimal --}}
                <div data-reveal style="--reveal-delay: 120ms" class="group relative rounded-3xl overflow-hidden border border-white/10 bg-white/[0.02] hover:border-white/20 hover:-translate-y-2 transition-all duration-500">
                    <div class="aspect-[4/5] relative overflow-hidden bg-stone-50">
                        <div class="absolute top-6 left-6 right-6 flex items-center gap-2 text-[10px] text-stone-400 uppercase tracking-[0.22em]">
                            <span class="h-px w-6 bg-stone-300"></span> The Quiet Desk
                        </div>
                        <div class="absolute inset-x-6 top-1/2 -translate-y-1/2">
                            <h4 class="font-display font-medium text-3xl text-stone-900 leading-tight">Quiet ideas, weekly.</h4>
                            <p class="mt-3 text-sm text-stone-500 leading-relaxed">A long-form letter for makers tired of the feed.</p>
                            <div class="mt-6 inline-flex items-center gap-2 bg-stone-900 text-stone-50 px-5 py-2 text-xs font-medium tracking-wide">
                                Subscribe now →
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-6 right-6 h-px bg-stone-200"></div>
                    </div>
                    <div class="p-6 border-t border-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display font-bold text-white">Minimal</h3>
                                <p class="text-xs text-white/50 mt-0.5">Clean editorial typography</p>
                            </div>
                            <span class="text-[10px] uppercase tracking-wider text-stone-300 font-semibold">Editorial</span>
                        </div>
                    </div>
                </div>

                {{-- Bold --}}
                <div data-reveal style="--reveal-delay: 240ms" class="group relative rounded-3xl overflow-hidden border border-white/10 bg-white/[0.02] hover:border-white/20 hover:-translate-y-2 transition-all duration-500">
                    <div class="aspect-[4/5] relative overflow-hidden bg-yellow-300">
                        <div class="absolute inset-0 opacity-[0.06]" style="background-image: repeating-linear-gradient(45deg, #000 0, #000 2px, transparent 2px, transparent 24px);"></div>
                        <div class="absolute top-6 right-6 h-12 w-12 bg-black float-slow"></div>
                        <div class="absolute bottom-12 left-6 h-8 w-8 bg-fuchsia-500 border-2 border-black rotate-12 float-mid"></div>
                        <div class="absolute inset-x-6 top-1/2 -translate-y-1/2">
                            <div class="inline-block bg-black text-yellow-300 px-3 py-1 font-mono text-[10px] uppercase tracking-widest -rotate-2">Launchpad</div>
                            <h4 class="mt-5 font-display font-black text-3xl text-black uppercase leading-[0.9] tracking-tighter">Code in 12 weeks. Hired in 6.</h4>
                            <div class="mt-6 inline-flex items-center gap-2 bg-black text-yellow-300 px-5 py-3 text-xs font-black uppercase tracking-wide border-2 border-black shadow-[4px_4px_0_0_#000]">
                                Apply now →
                            </div>
                        </div>
                    </div>
                    <div class="p-6 border-t border-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-display font-bold text-white">Bold</h3>
                                <p class="text-xs text-white/50 mt-0.5">Brutalist energy</p>
                            </div>
                            <span class="text-[10px] uppercase tracking-wider text-fuchsia-300 font-semibold">High-impact</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         FAQ
         ═══════════════════════════════════════════════════════════════════ --}}
    <section id="faq" class="relative py-32">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center" data-reveal>
                <p class="text-xs uppercase tracking-[0.22em] text-indigo-300/80 font-semibold">FAQ</p>
                <h2 class="mt-4 font-display font-bold text-4xl sm:text-5xl tracking-tight">Common questions.</h2>
            </div>

            <div class="mt-14 space-y-3">
                @foreach ([
                    ['Does it work without an OpenAI account?', 'Absolutely — we recommend Groq because their free tier is fast and generous. Any OpenAI-compatible endpoint works (Cerebras, OpenRouter, DeepSeek, your own).'],
                    ['How long does generation take?', 'On Groq with Llama 3.3 70B, end-to-end is typically 2–5 seconds. Slower providers may take up to 30s — and the queue handles that gracefully so your browser never times out.'],
                    ['Can I edit the generated content?', 'Yes — regenerate any single section without touching the rest, or regenerate the whole page. Switching templates does not regenerate either.'],
                    ['Is the exported HTML standalone?', 'Yes. One file. Tailwind CDN, fonts, animations, scroll-reveal — all inlined. Drop it in S3, Netlify, or your own server.'],
                    ['Is my product info kept private?', 'Your briefs are stored in your own database. The LLM provider sees only the generation request — no third-party analytics, no telemetry beyond Laravel logs.'],
                ] as $i => [$q, $a])
                    <details data-reveal style="--reveal-delay: {{ $i * 80 }}ms"
                             class="group glass rounded-2xl px-6 py-5 hover:bg-white/[0.06] transition-colors">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span class="font-display font-semibold text-white pr-4">{{ $q }}</span>
                            <svg class="h-5 w-5 text-white/40 group-open:rotate-180 transition-transform flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </summary>
                        <p class="mt-3 text-white/65 leading-relaxed text-sm">{{ $a }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         FINAL CTA
         ═══════════════════════════════════════════════════════════════════ --}}
    <section class="relative py-32" data-reveal>
        <div class="max-w-5xl mx-auto px-6">
            <div class="relative rounded-[2rem] overflow-hidden border border-white/15">
                {{-- Animated bg --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/60 via-slate-950 to-fuchsia-900/60"></div>
                <div class="absolute inset-0 animate-aurora-drift" style="background:
                    radial-gradient(50% 50% at 30% 50%, rgba(139,92,246,0.45) 0%, transparent 70%),
                    radial-gradient(50% 50% at 70% 50%, rgba(236,72,153,0.35) 0%, transparent 70%);"></div>
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[40rem] h-[40rem] rounded-full opacity-50 blur-3xl pointer-events-none"
                     style="background: conic-gradient(from 0deg, rgba(139,92,246,0.4), rgba(56,189,248,0.3), rgba(236,72,153,0.4), rgba(139,92,246,0.4));"></div>

                <div class="relative px-8 py-20 sm:py-28 text-center">
                    <p class="text-xs uppercase tracking-[0.28em] text-indigo-300/90 font-semibold animate-fade-in-down">Stop staring at blank pages</p>
                    <h2 class="mt-5 font-display font-black text-5xl sm:text-7xl tracking-tighter leading-[0.95] text-gradient animate-fade-in-up">
                        Your next sales page<br>writes itself.
                    </h2>
                    <p class="mt-6 text-white/70 text-lg max-w-xl mx-auto animate-fade-in-up" style="animation-delay: 100ms">
                        Free during beta. No credit card. Set up in under a minute.
                    </p>

                    <div class="mt-12 flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 animate-fade-in-up" style="animation-delay: 220ms">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="group relative inline-flex items-center justify-center gap-2.5 rounded-full px-10 py-5 text-lg font-semibold text-white
                                      bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500
                                      shadow-[0_0_60px_-5px_rgba(139,92,246,0.8)]
                                      hover:shadow-[0_0_100px_-5px_rgba(139,92,246,1)]
                                      hover:-translate-y-0.5 hover:scale-[1.03] active:scale-[0.98]
                                      animate-glow-pulse
                                      transition-all duration-300 overflow-hidden">
                                <span class="pointer-events-none absolute inset-0 rounded-full overflow-hidden">
                                    <span class="absolute inset-y-0 -left-1/3 w-1/3 bg-gradient-to-r from-transparent via-white/40 to-transparent skew-x-12 -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000"></span>
                                </span>
                                <span class="relative">Generate your first page</span>
                                <svg class="relative h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn-ghost px-7 py-5 text-base">
                                I already have an account
                            </a>
                        @endif
                    </div>

                    <div class="mt-12 flex items-center justify-center gap-x-7 gap-y-2 text-xs text-white/50 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Demo account ready
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            5-minute setup
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Open source
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         FOOTER
         ═══════════════════════════════════════════════════════════════════ --}}
    <footer class="relative border-t border-white/[0.06] py-12">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <span class="h-7 w-7 rounded-lg bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 flex items-center justify-center">
                    <svg class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <span class="font-display font-bold text-white text-sm">SalesGen<span class="text-fuchsia-400">.</span></span>
            </div>
            <p class="text-xs text-white/40">
                &copy; {{ date('Y') }} {{ config('app.name', 'AI Sales Page Generator') }}. Powered by Groq · Llama 3.3.
            </p>
            <div class="flex items-center gap-5 text-xs text-white/50">
                <a href="#how" class="hover:text-white transition">How it works</a>
                <a href="#features" class="hover:text-white transition">Features</a>
                <a href="#templates" class="hover:text-white transition">Templates</a>
            </div>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════════════════════════════════
         INTERACTIONS
         ═══════════════════════════════════════════════════════════════════ --}}
    <script>
        // Mouse-aware spotlight on hero
        (function () {
            const sl = document.getElementById('spotlight');
            if (!sl) return;
            const hero = document.getElementById('hero');
            hero.addEventListener('pointermove', (e) => {
                const r = hero.getBoundingClientRect();
                sl.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                sl.style.setProperty('--my', (e.clientY - r.top) + 'px');
            });
        })();

        // Typing simulation in the hero preview card
        (function () {
            const briefEl = document.getElementById('brief-line');
            const dotsEl  = document.getElementById('gen-dots');
            const headEl  = document.getElementById('out-headline');
            const subEl   = document.getElementById('out-sub');
            const ctaEl   = document.getElementById('out-cta');
            const timeEl  = document.getElementById('gen-time');
            if (!briefEl) return;

            const briefs = [
                'Aurora Notes — encrypted, blazing-fast notes for indie founders, $9/mo',
                'Launchpad — 12-week dev bootcamp, job guarantee or full refund',
                'The Quiet Desk — weekly newsletter for makers tired of the feed',
            ];
            const outputs = [
                {
                    head: 'Notes that move at the speed of thought.',
                    sub:  'Encrypted, synced, unbelievably fast.',
                    cta:  'Start writing free',
                    time: '3.2s',
                },
                {
                    head: 'Code in 12 weeks. Hired in 6 months.',
                    sub:  'Or your money back — every cent.',
                    cta:  'Apply now',
                    time: '2.8s',
                },
                {
                    head: 'Quiet ideas, weekly.',
                    sub:  'A long-form letter for makers tired of the feed.',
                    cta:  'Subscribe now',
                    time: '3.6s',
                },
            ];

            let cycle = 0;

            const typeOut = (el, text, speed = 22) => new Promise(res => {
                el.textContent = '';
                let i = 0;
                const tick = () => {
                    if (i <= text.length) {
                        el.textContent = text.slice(0, i++);
                        setTimeout(tick, speed);
                    } else {
                        res();
                    }
                };
                tick();
            });

            const animateDots = (duration) => new Promise(res => {
                const start = Date.now();
                const seq = ['', '.', '..', '...'];
                let i = 0;
                const id = setInterval(() => {
                    dotsEl.textContent = seq[i++ % seq.length];
                    if (Date.now() - start >= duration) {
                        clearInterval(id);
                        dotsEl.textContent = '';
                        res();
                    }
                }, 300);
            });

            const runCycle = async () => {
                const brief  = briefs[cycle % briefs.length];
                const output = outputs[cycle % outputs.length];
                cycle++;

                // Reset
                headEl.textContent = '';
                subEl.textContent = '';
                ctaEl.textContent = '';

                await typeOut(briefEl, brief, 18);
                await animateDots(900);
                await typeOut(headEl, output.head, 28);
                await typeOut(subEl, output.sub, 18);
                await typeOut(ctaEl, output.cta + ' →', 22);
                timeEl.textContent = output.time;

                setTimeout(runCycle, 4000);
            };

            runCycle();
        })();

        // Counter-up
        (function () {
            const counters = document.querySelectorAll('[data-counter]');
            if (!counters.length) return;

            const animate = (el) => {
                const target = parseFloat(el.dataset.counter);
                const suffix = el.dataset.suffix || '';
                const duration = 1400;
                const start = performance.now();

                const tick = (now) => {
                    const t = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - t, 3);
                    const value = (target * eased);
                    el.textContent = (target % 1 === 0 ? Math.round(value) : value.toFixed(1)) + suffix;
                    if (t < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            };

            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        animate(e.target);
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.4 });

            counters.forEach(c => io.observe(c));
        })();

        // Scroll-reveal
        (function () {
            const els = document.querySelectorAll('[data-reveal]');
            if (!('IntersectionObserver' in window)) {
                els.forEach(el => el.classList.add('is-visible'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-visible');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });
            els.forEach(el => io.observe(el));
        })();
    </script>
</body>
</html>
