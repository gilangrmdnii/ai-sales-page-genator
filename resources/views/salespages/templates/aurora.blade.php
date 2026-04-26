{{-- Shared luxury landing layout — used by preview & export --}}
@php
    /** @var array $content */
    $content = $page->generated_content ?? [];
    $headline    = $content['headline']     ?? $page->product_name;
    $subheadline = $content['subheadline']  ?? '';
    $description = $content['description']  ?? $page->description;
    $benefits    = (array) ($content['benefits']     ?? []);
    $features    = (array) ($content['features']     ?? $page->features);
    $social      = $content['social_proof']          ?? '';
    $pricing     = $content['pricing']               ?? ($page->price ? '$' . number_format((float) $page->price, 2) : '');
    $cta         = $content['cta']                   ?? 'Get Started';
@endphp

{{-- HERO --}}
<section class="relative overflow-hidden bg-slate-950 text-white">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-black"></div>
    <div class="absolute inset-0 animate-aurora-drift"
         style="background:
            radial-gradient(60% 60% at 20% 20%, rgba(139,92,246,0.30) 0%, transparent 70%),
            radial-gradient(50% 50% at 80% 30%, rgba(56,189,248,0.22) 0%, transparent 70%),
            radial-gradient(70% 70% at 60% 100%, rgba(236,72,153,0.20) 0%, transparent 70%);"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.05),transparent_60%)]"></div>

    <div class="relative max-w-6xl mx-auto px-6 py-28 sm:py-36 text-center">
        <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.06] backdrop-blur-md px-4 py-1.5 text-xs uppercase tracking-[0.18em] text-white/70 animate-fade-in">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.9)]"></span>
            {{ $page->product_name }}
        </div>

        <h1 class="mt-8 font-bold tracking-tight leading-[1.05] text-5xl sm:text-7xl text-gradient animate-fade-in-up">
            {{ $headline }}
        </h1>

        @if ($subheadline)
            <p class="mt-7 text-lg sm:text-2xl text-white/70 max-w-3xl mx-auto leading-relaxed animate-fade-in-up">{{ $subheadline }}</p>
        @endif

        <div class="mt-12 flex items-center justify-center gap-4 flex-wrap animate-fade-in-up">
            <a href="#cta"
               class="group relative inline-flex items-center gap-2 rounded-full px-8 py-4 text-base font-semibold text-white
                      bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500
                      shadow-[0_0_40px_-5px_rgba(139,92,246,0.6)]
                      hover:shadow-[0_0_70px_-5px_rgba(139,92,246,1)]
                      hover:-translate-y-0.5 hover:scale-[1.04] active:scale-[0.98]
                      transition-all duration-300">
                {{-- Shine sweep --}}
                <span class="pointer-events-none absolute inset-0 rounded-full overflow-hidden">
                    <span class="absolute inset-y-0 -left-1/3 w-1/3 bg-gradient-to-r from-transparent via-white/30 to-transparent skew-x-12 -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000"></span>
                </span>
                <span class="relative">{{ $cta }}</span>
                <svg class="relative h-4 w-4 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a href="#features" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] backdrop-blur-md px-7 py-4 text-base font-medium text-white/80 hover:bg-white/[0.08] hover:text-white hover:-translate-y-0.5 transition-all duration-300">
                Explore features
            </a>
        </div>

        {{-- Trust strip --}}
        <div class="mt-14 flex items-center justify-center gap-6 sm:gap-10 text-xs text-white/40 flex-wrap">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>No credit card required</span>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Cancel anytime</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Built for {{ $page->target_audience }}</span>
            </div>
        </div>

        @if ($social)
            <p class="mt-10 text-sm text-white/50 italic max-w-xl mx-auto">"{{ $social }}"</p>
        @endif
    </div>
</section>

{{-- BENEFITS --}}
@if (! empty($benefits))
<section class="relative bg-slate-950 text-white">
    <div class="max-w-6xl mx-auto px-6 py-24">
        <div class="text-center max-w-2xl mx-auto" data-reveal>
            <p class="text-xs uppercase tracking-[0.22em] text-indigo-300/80 font-semibold">Why teams switch</p>
            <h2 class="mt-3 font-bold text-4xl sm:text-5xl tracking-tight">The advantages you'll feel</h2>
        </div>
        <div class="mt-16 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($benefits as $benefit)
                <div data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms"
                     class="group relative rounded-3xl border border-white/10 bg-white/[0.03] backdrop-blur-md p-7 hover:border-white/20 hover:bg-white/[0.06] hover:-translate-y-1 hover:shadow-[0_20px_50px_-20px_rgba(139,92,246,0.5)] transition-all duration-300">
                    <div class="absolute -top-px left-8 right-8 h-px bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                    <div class="relative h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 shadow-glow flex items-center justify-center text-white font-bold group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <span class="absolute inset-0 rounded-2xl bg-indigo-500/40 opacity-0 group-hover:opacity-100 group-hover:animate-pulse-ring"></span>
                        <span class="relative">{{ $loop->iteration }}</span>
                    </div>
                    <p class="mt-5 text-white/80 leading-relaxed">{{ $benefit }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- DESCRIPTION --}}
@if ($description)
<section class="relative bg-gradient-to-b from-slate-950 to-slate-900 text-white">
    <div class="max-w-3xl mx-auto px-6 py-24 text-center">
        <p class="text-xs uppercase tracking-[0.22em] text-indigo-300/80 font-semibold">Built for {{ $page->target_audience }}</p>
        <h2 class="mt-3 font-bold text-4xl sm:text-5xl tracking-tight">A better way to {{ strtolower($page->product_name) }}</h2>
        <div class="mt-8 space-y-5 text-lg text-white/70 leading-relaxed">
            @foreach (preg_split("/\n{2,}/", $description) as $para)
                <p>{{ $para }}</p>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURES --}}
@if (! empty($features))
<section id="features" class="relative bg-slate-900 text-white">
    <div class="max-w-6xl mx-auto px-6 py-24">
        <div class="text-center max-w-2xl mx-auto" data-reveal>
            <p class="text-xs uppercase tracking-[0.22em] text-indigo-300/80 font-semibold">Everything you get</p>
            <h2 class="mt-3 font-bold text-4xl sm:text-5xl tracking-tight">Crafted to perform</h2>
        </div>
        <ul class="mt-16 grid gap-4 sm:grid-cols-2">
            @foreach ($features as $feature)
                <li data-reveal style="--reveal-delay: {{ $loop->index * 60 }}ms"
                    class="group flex items-start gap-4 rounded-2xl border border-white/10 bg-white/[0.03] backdrop-blur-md p-5 hover:bg-white/[0.06] hover:border-white/20 hover:-translate-y-0.5 transition-all duration-300">
                    <div class="flex-none h-9 w-9 rounded-xl bg-gradient-to-br from-indigo-500/30 to-fuchsia-500/30 border border-white/10 flex items-center justify-center group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-4 w-4 text-indigo-300 group-hover:text-emerald-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-white/85 leading-relaxed pt-1">{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- PRICING --}}
@if ($pricing)
<section class="relative bg-slate-950 text-white overflow-hidden">
    <div class="absolute inset-0"
         style="background: radial-gradient(50% 50% at 50% 50%, rgba(139,92,246,0.18) 0%, transparent 70%);"></div>
    <div class="relative max-w-3xl mx-auto px-6 py-24 text-center">
        <p class="text-xs uppercase tracking-[0.22em] text-indigo-300/80 font-semibold">Simple, honest</p>
        <h2 class="mt-3 font-bold text-4xl sm:text-5xl tracking-tight">One price. Zero surprises.</h2>

        <div class="mt-12 inline-block rounded-3xl border border-white/15 bg-white/[0.05] backdrop-blur-2xl px-12 py-12 shadow-[0_30px_80px_-20px_rgba(0,0,0,0.6)] hover:-translate-y-1 hover:shadow-[0_40px_100px_-20px_rgba(139,92,246,0.5)] transition-all duration-500">
            <p class="text-sm text-white/50 uppercase tracking-wider">{{ $page->product_name }}</p>
            <p class="mt-4 font-bold text-5xl sm:text-6xl bg-clip-text text-transparent bg-gradient-to-r from-indigo-300 via-violet-300 to-fuchsia-300">{{ $pricing }}</p>
            <a href="#cta"
               class="mt-8 inline-flex items-center justify-center gap-2 rounded-full px-8 py-3.5 text-base font-semibold text-white
                      bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500
                      shadow-[0_0_40px_-5px_rgba(139,92,246,0.7)]
                      hover:shadow-[0_0_60px_-5px_rgba(139,92,246,1)]
                      hover:scale-[1.03] transition-all duration-300">
                {{ $cta }}
            </a>
        </div>
    </div>
</section>
@endif

{{-- FINAL CTA --}}
<section id="cta" class="relative bg-slate-950 text-white overflow-hidden">
    {{-- Animated multi-layer aurora --}}
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/60 via-slate-950 to-fuchsia-900/50"></div>
    <div class="absolute inset-0 animate-aurora-drift"
         style="background:
            radial-gradient(50% 50% at 30% 50%, rgba(139,92,246,0.35) 0%, transparent 70%),
            radial-gradient(50% 50% at 70% 50%, rgba(236,72,153,0.30) 0%, transparent 70%);"></div>

    {{-- Conic glow ring behind the card --}}
    <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[40rem] h-[40rem] rounded-full opacity-60 blur-3xl pointer-events-none"
         style="background: conic-gradient(from 0deg, rgba(139,92,246,0.3), rgba(56,189,248,0.25), rgba(236,72,153,0.3), rgba(139,92,246,0.3));"></div>

    <div class="relative max-w-4xl mx-auto px-6 py-28 sm:py-36 text-center">
        <p class="text-xs uppercase tracking-[0.28em] text-indigo-300/90 font-semibold">Ready when you are</p>

        <h2 class="mt-4 font-bold text-4xl sm:text-6xl tracking-tight leading-[1.05] text-gradient">
            {{ $headline }}
        </h2>

        @if ($subheadline)
            <p class="mt-6 text-white/75 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">{{ $subheadline }}</p>
        @endif

        {{-- Glass CTA card --}}
        <div class="mt-12 inline-flex flex-col items-center gap-5 rounded-3xl border border-white/15 bg-white/[0.04] backdrop-blur-2xl px-10 py-10 shadow-[0_30px_80px_-20px_rgba(0,0,0,0.7)]">

            <a href="#"
               class="group relative inline-flex items-center gap-2.5 rounded-full px-12 py-5 text-lg font-semibold text-white
                      bg-gradient-to-r from-indigo-500 via-violet-500 to-fuchsia-500
                      animate-glow-pulse
                      hover:scale-[1.04] hover:-translate-y-0.5 active:scale-[0.98]
                      transition-all duration-300 ease-out">
                {{-- Shine sweep --}}
                <span class="pointer-events-none absolute inset-0 rounded-full overflow-hidden">
                    <span class="absolute inset-y-0 -left-1/3 w-1/3 bg-gradient-to-r from-transparent via-white/35 to-transparent skew-x-12 -translate-x-full group-hover:translate-x-[400%] transition-transform duration-1000"></span>
                </span>
                <span class="relative">{{ $cta }}</span>
                <svg class="relative h-5 w-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>

            <div class="flex items-center justify-center gap-x-5 gap-y-2 text-xs text-white/55 flex-wrap">
                <div class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Instant access
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    No credit card
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Cancel anytime
                </div>
            </div>
        </div>

        @if ($social)
            <p class="mt-10 text-sm text-white/50 italic max-w-xl mx-auto">"{{ $social }}"</p>
        @endif
    </div>
</section>

<footer class="bg-slate-950 border-t border-white/5 text-center py-8 text-sm text-white/40">
    &copy; {{ date('Y') }} {{ $page->product_name }}. All rights reserved.
</footer>
