{{-- Bold — high-contrast brutalist energy --}}
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

<div class="bg-yellow-300 text-black overflow-hidden">

    {{-- HERO --}}
    <section class="relative min-h-screen flex items-center">
        {{-- Diagonal stripes pattern --}}
        <div class="absolute inset-0 opacity-[0.06] pointer-events-none"
             style="background-image: repeating-linear-gradient(45deg, #000 0, #000 2px, transparent 2px, transparent 24px);"></div>

        {{-- Floating chunks --}}
        <div class="absolute top-20 right-12 h-32 w-32 bg-black animate-float pointer-events-none"></div>
        <div class="absolute bottom-32 left-8 h-20 w-20 bg-fuchsia-500 border-4 border-black rotate-12 animate-tilt pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-24 w-full">
            <div class="inline-block bg-black text-yellow-300 px-4 py-2 font-mono text-xs uppercase tracking-widest -rotate-2 animate-fade-in-down">
                ▲ {{ $page->product_name }}
            </div>

            <h1 class="mt-10 font-black text-7xl sm:text-9xl leading-[0.88] tracking-tighter uppercase animate-slide-in-left">
                {{ $headline }}
            </h1>

            @if ($subheadline)
                <p class="mt-10 text-2xl sm:text-3xl text-black/80 max-w-3xl leading-tight font-bold animate-fade-in-up" style="animation-delay: 200ms">
                    {{ $subheadline }}
                </p>
            @endif

            <div class="mt-14 flex items-center gap-4 flex-wrap animate-fade-in-up" style="animation-delay: 380ms">
                <a href="#cta"
                   class="group inline-flex items-center gap-3 bg-black text-yellow-300 px-10 py-5 text-lg font-black uppercase tracking-wide border-4 border-black
                          shadow-[8px_8px_0_0_#000] hover:shadow-[12px_12px_0_0_#000] hover:-translate-x-1 hover:-translate-y-1
                          active:shadow-[2px_2px_0_0_#000] active:translate-x-1 active:translate-y-1
                          transition-all duration-150">
                    {{ $cta }}
                    <span class="inline-block group-hover:translate-x-1 transition-transform">→</span>
                </a>
                <a href="#features"
                   class="inline-flex items-center gap-2 bg-yellow-300 text-black px-8 py-5 text-lg font-black uppercase tracking-wide border-4 border-black
                          hover:bg-fuchsia-500 hover:text-white transition-all duration-150">
                    See features
                </a>
            </div>
        </div>
    </section>

    {{-- MARQUEE --}}
    <div class="bg-black text-yellow-300 py-6 border-y-4 border-black overflow-hidden">
        <div class="flex animate-marquee whitespace-nowrap font-black text-3xl uppercase tracking-tight">
            @for ($i = 0; $i < 8; $i++)
                <span class="mx-8">{{ $headline }}</span>
                <span class="mx-8 text-fuchsia-500">★</span>
            @endfor
        </div>
    </div>

    {{-- BENEFITS --}}
    @if (! empty($benefits))
    <section class="bg-fuchsia-500 text-white border-b-4 border-black">
        <div class="max-w-7xl mx-auto px-6 py-24">
            <div data-reveal>
                <p class="font-mono text-sm uppercase tracking-[0.3em]">// Benefits</p>
                <h2 class="mt-3 font-black text-6xl sm:text-7xl uppercase leading-[0.9] tracking-tighter">
                    Why<br>this slaps.
                </h2>
            </div>
            <div class="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($benefits as $benefit)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms"
                         class="group bg-yellow-300 text-black border-4 border-black p-8
                                shadow-[8px_8px_0_0_#000] hover:shadow-[12px_12px_0_0_#000] hover:-translate-x-1 hover:-translate-y-1
                                transition-all duration-200">
                        <div class="font-black font-mono text-5xl text-fuchsia-500 group-hover:text-black transition-colors">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <p class="mt-5 font-bold text-lg leading-snug">{{ $benefit }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- DESCRIPTION --}}
    @if ($description)
    <section class="bg-black text-yellow-300 border-b-4 border-black">
        <div class="max-w-4xl mx-auto px-6 py-24" data-reveal>
            <p class="font-mono text-sm uppercase tracking-[0.3em] text-fuchsia-400">// The pitch</p>
            <div class="mt-8 space-y-6 text-2xl sm:text-3xl leading-tight font-bold">
                @foreach (preg_split("/\n{2,}/", $description) as $para)
                    <p>{{ $para }}</p>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- FEATURES --}}
    @if (! empty($features))
    <section id="features" class="bg-white border-b-4 border-black">
        <div class="max-w-7xl mx-auto px-6 py-24">
            <div data-reveal>
                <p class="font-mono text-sm uppercase tracking-[0.3em] text-black">// Features</p>
                <h2 class="mt-3 font-black text-6xl sm:text-7xl text-black uppercase leading-[0.9] tracking-tighter">
                    What you<br>get.
                </h2>
            </div>
            <ul class="mt-14 grid gap-0 sm:grid-cols-2 border-4 border-black">
                @foreach ($features as $feature)
                    <li data-reveal style="--reveal-delay: {{ $loop->index * 50 }}ms"
                        class="group flex items-start gap-4 p-7 border-black border-r-4 border-b-4 last:border-r-0 even:border-r-0 sm:even:border-r-4 sm:[&:nth-child(2n)]:border-r-0
                               hover:bg-yellow-300 transition-colors duration-200">
                        <span class="font-black font-mono text-2xl text-fuchsia-500 group-hover:text-black transition-colors">
                            +
                        </span>
                        <span class="font-bold text-lg leading-tight pt-0.5">{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    @endif

    {{-- SOCIAL --}}
    @if ($social)
    <section class="bg-yellow-300 border-b-4 border-black overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 py-20 text-center" data-reveal>
            <div class="inline-block bg-black text-yellow-300 px-4 py-1 font-mono text-xs uppercase tracking-[0.3em] -rotate-2">
                People say
            </div>
            <p class="mt-8 font-black text-3xl sm:text-5xl uppercase leading-tight tracking-tight">
                "{{ $social }}"
            </p>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section id="cta" class="relative bg-fuchsia-500 text-white border-b-4 border-black overflow-hidden">
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: repeating-linear-gradient(-45deg, #000 0, #000 2px, transparent 2px, transparent 24px);"></div>
        <div class="relative max-w-4xl mx-auto px-6 py-32 text-center">
            <p class="font-mono text-sm uppercase tracking-[0.3em] animate-fade-in-down">// Ready?</p>
            <h2 class="mt-4 font-black text-7xl sm:text-9xl uppercase leading-[0.88] tracking-tighter animate-slide-in-left">
                Let's<br>go.
            </h2>

            @if ($pricing)
                <div class="mt-10 inline-block bg-black text-yellow-300 px-8 py-4 border-4 border-black font-black text-3xl -rotate-1 animate-scale-in">
                    {{ $pricing }}
                </div>
            @endif

            <div class="mt-12 animate-fade-in-up" style="animation-delay: 300ms">
                <a href="#"
                   class="group inline-flex items-center gap-3 bg-yellow-300 text-black px-12 py-6 text-2xl font-black uppercase tracking-wide border-4 border-black
                          shadow-[10px_10px_0_0_#000] hover:shadow-[16px_16px_0_0_#000] hover:-translate-x-1 hover:-translate-y-1
                          active:shadow-[2px_2px_0_0_#000] active:translate-x-2 active:translate-y-2
                          transition-all duration-150">
                    {{ $cta }}
                    <span class="inline-block group-hover:translate-x-2 transition-transform">→</span>
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-black text-yellow-300 text-center py-8 font-mono text-xs uppercase tracking-[0.3em]">
        &copy; {{ date('Y') }} · {{ $page->product_name }}
    </footer>
</div>
