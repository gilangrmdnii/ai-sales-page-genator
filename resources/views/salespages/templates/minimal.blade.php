{{-- Minimal — clean editorial typography, lots of whitespace --}}
@php
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

<div class="bg-stone-50 text-stone-900" style="font-family: 'Inter', system-ui, sans-serif;">

    {{-- HERO --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none opacity-[0.04]"
             style="background-image: radial-gradient(circle at 1px 1px, #000 1px, transparent 0); background-size: 28px 28px;"></div>

        <div class="relative max-w-3xl mx-auto px-6 pt-32 pb-24">
            <div class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.22em] text-stone-500 animate-fade-in-down">
                <span class="h-px w-8 bg-stone-400"></span>
                {{ $page->product_name }}
            </div>

            <h1 class="mt-8 font-serif font-medium text-5xl sm:text-7xl leading-[1.05] tracking-tight text-stone-900 animate-fade-in-up"
                style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                {{ $headline }}
            </h1>

            @if ($subheadline)
                <p class="mt-8 text-xl sm:text-2xl text-stone-600 leading-relaxed max-w-2xl animate-fade-in-up" style="animation-delay: 120ms">{{ $subheadline }}</p>
            @endif

            <div class="mt-12 flex items-center gap-5 flex-wrap animate-fade-in-up" style="animation-delay: 240ms">
                <a href="#cta"
                   class="group inline-flex items-center gap-2 bg-stone-900 text-stone-50 px-7 py-3.5 text-sm font-medium tracking-wide hover:bg-stone-700 transition-all duration-300 hover:gap-3">
                    {{ $cta }}
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="#features" class="text-sm text-stone-500 hover:text-stone-900 transition border-b border-stone-300 hover:border-stone-900 pb-0.5">
                    Read more
                </a>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-6 pb-16">
            <div class="h-px bg-stone-200"></div>
        </div>
    </section>

    {{-- DESCRIPTION --}}
    @if ($description)
    <section class="bg-stone-50">
        <div class="max-w-2xl mx-auto px-6 py-20" data-reveal>
            <p class="text-xs uppercase tracking-[0.22em] text-stone-400 font-medium">For {{ $page->target_audience }}</p>
            <div class="mt-8 space-y-6 text-lg text-stone-700 leading-[1.85]" style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                @foreach (preg_split("/\n{2,}/", $description) as $para)
                    <p>{{ $para }}</p>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- BENEFITS --}}
    @if (! empty($benefits))
    <section class="bg-white border-y border-stone-200">
        <div class="max-w-4xl mx-auto px-6 py-24">
            <div data-reveal>
                <p class="text-xs uppercase tracking-[0.22em] text-stone-400 font-medium">Benefits</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight text-stone-900" style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                    Why it matters
                </h2>
            </div>
            <ol class="mt-14 space-y-12">
                @foreach ($benefits as $benefit)
                    <li data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms"
                        class="grid grid-cols-[auto_1fr] gap-8 items-start group">
                        <span class="font-mono text-xs text-stone-400 pt-1.5 tabular-nums group-hover:text-stone-900 transition-colors">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <p class="text-xl text-stone-800 leading-relaxed font-light" style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                            {{ $benefit }}
                        </p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
    @endif

    {{-- FEATURES --}}
    @if (! empty($features))
    <section id="features" class="bg-stone-50">
        <div class="max-w-4xl mx-auto px-6 py-24">
            <div data-reveal>
                <p class="text-xs uppercase tracking-[0.22em] text-stone-400 font-medium">Features</p>
                <h2 class="mt-3 font-serif text-4xl tracking-tight text-stone-900" style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                    What's inside
                </h2>
            </div>
            <ul class="mt-12 grid sm:grid-cols-2 gap-x-12 gap-y-6">
                @foreach ($features as $feature)
                    <li data-reveal style="--reveal-delay: {{ $loop->index * 50 }}ms"
                        class="group flex items-start gap-4 py-4 border-b border-stone-200 hover:border-stone-900 transition-colors">
                        <svg class="h-5 w-5 text-stone-400 mt-0.5 flex-none group-hover:text-stone-900 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-stone-700 leading-relaxed">{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif

    {{-- SOCIAL --}}
    @if ($social)
    <section class="bg-white border-y border-stone-200">
        <div class="max-w-2xl mx-auto px-6 py-24 text-center" data-reveal>
            <svg class="h-7 w-7 mx-auto text-stone-300" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
            <p class="mt-8 text-2xl text-stone-800 leading-relaxed font-light italic" style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                {{ $social }}
            </p>
        </div>
    </section>
    @endif

    {{-- PRICING + CTA --}}
    <section id="cta" class="bg-stone-900 text-stone-50">
        <div class="max-w-3xl mx-auto px-6 py-28 text-center">
            <p class="text-xs uppercase tracking-[0.22em] text-stone-400 font-medium animate-fade-in-down">Get started</p>
            <h2 class="mt-4 font-serif text-4xl sm:text-5xl tracking-tight animate-fade-in-up" style="font-family: 'Plus Jakarta Sans', Georgia, serif;">
                {{ $headline }}
            </h2>

            @if ($pricing)
                <p class="mt-10 font-mono text-3xl text-stone-50 animate-scale-in" style="animation-delay: 200ms">{{ $pricing }}</p>
            @endif

            <a href="#"
               class="mt-12 group inline-flex items-center gap-2 bg-stone-50 text-stone-900 px-8 py-4 text-sm font-medium tracking-wide hover:bg-white hover:gap-3 transition-all duration-300 animate-fade-in-up"
               style="animation-delay: 320ms">
                {{ $cta }}
                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </section>

    <footer class="bg-stone-900 text-stone-500 text-center py-8 text-xs border-t border-stone-800">
        &copy; {{ date('Y') }} {{ $page->product_name }}.
    </footer>
</div>
