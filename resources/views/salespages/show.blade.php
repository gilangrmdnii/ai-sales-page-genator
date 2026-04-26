<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-white/40 uppercase tracking-wider">Sales page</p>
                <h1 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-white truncate">{{ $page->product_name }}</h1>
            </div>
            @if ($page->isGenerated())
                <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto">
                    <a href="{{ route('sales-pages.preview', $page) }}" target="_blank" class="btn-gradient flex-1 sm:flex-none justify-center sm:justify-start">
                        Live preview
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('sales-pages.export', $page) }}" class="btn-ghost hidden sm:inline-flex">Export HTML</a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        @if (session('success'))
            <div class="glass rounded-2xl px-5 py-4 text-emerald-300 border-emerald-400/20 bg-emerald-500/10">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="glass rounded-2xl px-5 py-4 text-rose-200 border-rose-400/20 bg-rose-500/10">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Sidebar: input + actions --}}
            <aside class="lg:col-span-1 space-y-6">
                <div class="glass rounded-2xl p-6 animate-fade-in-up">
                    <h3 class="font-display font-bold text-white">Product brief</h3>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div>
                            <dt class="text-[11px] uppercase tracking-wider text-white/40 font-semibold">Description</dt>
                            <dd class="mt-1.5 text-white/80 leading-relaxed">{{ $page->description }}</dd>
                        </div>
                        <div>
                            <dt class="text-[11px] uppercase tracking-wider text-white/40 font-semibold">Target audience</dt>
                            <dd class="mt-1.5 text-white/80">{{ $page->target_audience }}</dd>
                        </div>
                        @if ($page->price)
                            <div>
                                <dt class="text-[11px] uppercase tracking-wider text-white/40 font-semibold">Price</dt>
                                <dd class="mt-1.5 text-white/80">${{ number_format((float) $page->price, 2) }}</dd>
                            </div>
                        @endif
                        @if ($page->usp)
                            <div>
                                <dt class="text-[11px] uppercase tracking-wider text-white/40 font-semibold">Unique selling points</dt>
                                <dd class="mt-1.5 text-white/80 leading-relaxed">{{ $page->usp }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-[11px] uppercase tracking-wider text-white/40 font-semibold">Features</dt>
                            <dd class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ((array) $page->features as $f)
                                    <span class="inline-flex items-center rounded-full bg-white/[0.06] border border-white/10 px-2.5 py-1 text-xs text-white/70">{{ $f }}</span>
                                @endforeach
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="glass rounded-2xl p-6 space-y-3 animate-fade-in-up">
                    <h3 class="font-display font-bold text-white">Template</h3>
                    <form method="POST" action="{{ route('sales-pages.set-template', $page) }}" class="space-y-2">
                        @csrf
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (\App\Models\SalesPage::TEMPLATES as $key => $meta)
                                @php $current = $page->templateKey() === $key; @endphp
                                <button type="submit" name="template" value="{{ $key }}"
                                        class="group relative rounded-xl border p-2 text-center transition-all duration-200
                                               {{ $current ? 'border-indigo-400 bg-indigo-500/10 shadow-[0_0_20px_-8px_rgba(139,92,246,0.7)]' : 'border-white/10 bg-white/[0.03] hover:bg-white/[0.06] hover:-translate-y-0.5' }}">
                                    <div class="aspect-[4/3] rounded-md overflow-hidden mb-1.5 relative">
                                        @if ($key === 'aurora')
                                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 to-fuchsia-950"></div>
                                            <div class="absolute inset-0" style="background: radial-gradient(50% 50% at 50% 50%, rgba(139,92,246,0.5), transparent 70%);"></div>
                                        @elseif ($key === 'minimal')
                                            <div class="absolute inset-0 bg-stone-50"></div>
                                            <div class="absolute inset-x-1.5 top-1/2 -translate-y-1/2 h-0.5 bg-stone-900"></div>
                                        @else
                                            <div class="absolute inset-0 bg-yellow-300"></div>
                                            <div class="absolute top-1 right-1 h-1.5 w-1.5 bg-black"></div>
                                            <div class="absolute inset-x-1.5 bottom-2 h-1 bg-black"></div>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-white/80 font-medium">{{ $meta['name'] }}</p>
                                    @if ($current)
                                        <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-indigo-500 flex items-center justify-center">
                                            <svg class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>

                <div class="glass rounded-2xl p-6 space-y-3 animate-fade-in-up">
                    <h3 class="font-display font-bold text-white">Actions</h3>
                    <form method="POST" action="{{ route('sales-pages.regenerate', $page) }}">
                        @csrf
                        <button type="submit" class="btn-gradient w-full" @if ($page->isProcessing()) disabled @endif>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ $page->isProcessing() ? 'Generating…' : 'Regenerate everything' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('sales-pages.destroy', $page) }}"
                          onsubmit="return confirm('Delete this sales page?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger w-full">Delete page</button>
                    </form>
                </div>
            </aside>

            {{-- Main: generated content --}}
            <div class="lg:col-span-2 space-y-4" id="content-area" data-status="{{ $page->status }}" data-status-url="{{ route('sales-pages.status', $page) }}">
                @if ($page->isProcessing())
                    <div id="processing-banner" class="glass-strong rounded-2xl p-8 text-indigo-200 border-indigo-400/20 flex items-center gap-4">
                        <svg class="h-6 w-6 animate-spin text-indigo-300" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold">Generating your sales page…</p>
                            <p class="text-sm text-white/60 mt-1">This usually takes 5–15 seconds. The page will refresh automatically.</p>
                        </div>
                    </div>
                @elseif ($page->hasFailed())
                    <div class="glass-strong rounded-2xl p-8 text-rose-200 border-rose-400/20 space-y-3">
                        <p class="font-semibold">Generation failed</p>
                        <p class="text-sm text-white/70">{{ $page->failure_reason ?? 'Unknown error.' }}</p>
                        <p class="text-sm text-white/60">Click "Regenerate everything" to retry.</p>
                    </div>
                @elseif (! $page->isGenerated())
                    <div class="glass-strong rounded-2xl p-8 text-amber-200 border-amber-400/20">
                        No content has been generated yet. Use "Regenerate everything" to retry.
                    </div>
                @else
                    @php $g = $page->generated_content; @endphp

                    @foreach ([
                        'headline'     => 'Headline',
                        'subheadline'  => 'Subheadline',
                        'description'  => 'Description',
                        'benefits'     => 'Benefits',
                        'features'     => 'Features',
                        'social_proof' => 'Social proof',
                        'pricing'      => 'Pricing',
                        'cta'          => 'Call to action',
                    ] as $key => $label)
                        <div class="glass rounded-2xl p-6 hover:bg-white/[0.06] transition animate-fade-in-up">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[11px] uppercase tracking-[0.16em] text-white/40 font-semibold">{{ $label }}</h4>
                                    <div class="mt-2 text-white/90">
                                        @if (is_array($g[$key] ?? null))
                                            <ul class="space-y-2">
                                                @foreach ($g[$key] as $item)
                                                    <li class="flex items-start gap-2">
                                                        <svg class="h-4 w-4 mt-1 flex-none text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        <span class="leading-relaxed">{{ $item }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="whitespace-pre-line leading-relaxed">{{ $g[$key] ?? '—' }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <button type="button"
                                            class="copy-btn text-xs text-indigo-300 hover:text-indigo-200 transition"
                                            data-copy="{{ is_array($g[$key] ?? null) ? implode("\n", $g[$key]) : ($g[$key] ?? '') }}">
                                        Copy
                                    </button>
                                    <form method="POST" action="{{ route('sales-pages.regenerate-section', $page) }}">
                                        @csrf
                                        <input type="hidden" name="section" value="{{ $key }}" />
                                        <button type="submit" class="text-xs text-white/50 hover:text-white transition">Regenerate</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(btn.dataset.copy || '');
                    const original = btn.textContent;
                    btn.textContent = 'Copied!';
                    setTimeout(() => (btn.textContent = original), 1500);
                } catch (e) {
                    alert('Could not copy: ' + e.message);
                }
            });
        });

        (function () {
            const area = document.getElementById('content-area');
            if (!area) return;
            const initial = area.dataset.status;
            if (initial !== 'pending' && initial !== 'generating') return;

            const url = area.dataset.statusUrl;
            let attempts = 0;
            const poll = async () => {
                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (res.ok) {
                        const data = await res.json();
                        if (data.status === 'completed' || data.status === 'failed') {
                            window.location.reload();
                            return;
                        }
                    }
                } catch (_) {}
                attempts++;
                // Tight cadence early (Groq usually returns in 1–3s), then ease off.
                const next = attempts < 8 ? 600 : attempts < 20 ? 1200 : 2500;
                setTimeout(poll, next);
            };
            setTimeout(poll, 600);
        })();
    </script>
    @endpush
</x-app-layout>
