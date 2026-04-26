<x-app-layout>
    <x-slot name="header">
        <div class="min-w-0">
            <h1 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-white truncate">Generate a sales page</h1>
            <p class="text-xs sm:text-sm text-white/50 mt-0.5">Tell us about the product. We'll do the persuasion.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto animate-fade-in-up">
        <div class="glass-strong rounded-3xl overflow-hidden">
            <div class="px-8 pt-8 pb-2">
                <p class="text-[11px] uppercase tracking-[0.18em] text-white/50 font-semibold">Step 1 of 1</p>
                <h2 class="mt-1 font-display text-xl font-bold text-white">Product brief</h2>
            </div>

            <form id="generate-form" method="POST" action="{{ route('sales-pages.store') }}" class="p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Product name</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" required
                           placeholder="e.g. Aurora Notes"
                           class="glass-input block w-full px-4 py-3" />
                    @error('product_name') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Description</label>
                    <textarea name="description" rows="4" required
                              placeholder="What does the product do, and for whom?"
                              class="glass-input block w-full px-4 py-3 resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">
                        Features <span class="text-white/40 font-normal">(comma- or newline-separated)</span>
                    </label>
                    <textarea name="features" rows="4" required
                              placeholder="Realtime sync&#10;Offline mode&#10;Team workspaces"
                              class="glass-input block w-full px-4 py-3 resize-none">{{ old('features') }}</textarea>
                    @error('features') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Target audience</label>
                        <input type="text" name="target_audience" value="{{ old('target_audience') }}" required
                               placeholder="e.g. Indie SaaS founders"
                               class="glass-input block w-full px-4 py-3" />
                        @error('target_audience') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-2">Price <span class="text-white/40 font-normal">(optional)</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                               placeholder="29.00"
                               class="glass-input block w-full px-4 py-3" />
                        @error('price') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/80 mb-2">Unique selling points <span class="text-white/40 font-normal">(optional)</span></label>
                    <textarea name="usp" rows="2"
                              placeholder="What makes this different from competitors?"
                              class="glass-input block w-full px-4 py-3 resize-none">{{ old('usp') }}</textarea>
                    @error('usp') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-white/80 mb-3">Choose a template</label>
                    <div class="grid sm:grid-cols-3 gap-3">
                        @foreach (\App\Models\SalesPage::TEMPLATES as $key => $meta)
                            @php $checked = old('template', 'aurora') === $key; @endphp
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="template" value="{{ $key }}" {{ $checked ? 'checked' : '' }}
                                       class="peer sr-only" />
                                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 transition-all duration-300
                                            peer-checked:border-indigo-400 peer-checked:bg-indigo-500/10 peer-checked:shadow-[0_0_30px_-8px_rgba(139,92,246,0.6)]
                                            hover:bg-white/[0.06] hover:-translate-y-0.5">
                                    {{-- Template thumbnail --}}
                                    <div class="aspect-[4/3] rounded-lg overflow-hidden mb-3 relative">
                                        @if ($key === 'aurora')
                                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-fuchsia-950"></div>
                                            <div class="absolute inset-0" style="background:
                                                radial-gradient(50% 50% at 30% 30%, rgba(139,92,246,0.45) 0%, transparent 70%),
                                                radial-gradient(50% 50% at 70% 70%, rgba(236,72,153,0.35) 0%, transparent 70%);"></div>
                                            <div class="absolute inset-x-3 top-1/2 -translate-y-1/2 text-center">
                                                <div class="h-1.5 w-12 mx-auto rounded-full bg-gradient-to-r from-indigo-300 to-fuchsia-300"></div>
                                                <div class="mt-1.5 h-1 w-16 mx-auto rounded-full bg-white/30"></div>
                                            </div>
                                        @elseif ($key === 'minimal')
                                            <div class="absolute inset-0 bg-stone-50"></div>
                                            <div class="absolute inset-3 flex flex-col justify-center">
                                                <div class="h-1 w-6 bg-stone-400 rounded-full"></div>
                                                <div class="mt-1.5 h-2 w-16 bg-stone-900 rounded-sm"></div>
                                                <div class="mt-1 h-1 w-12 bg-stone-400 rounded-full"></div>
                                                <div class="mt-2.5 inline-block h-2 w-10 bg-stone-900 rounded-sm"></div>
                                            </div>
                                        @else {{-- bold --}}
                                            <div class="absolute inset-0 bg-yellow-300"></div>
                                            <div class="absolute top-2 right-2 h-5 w-5 bg-black"></div>
                                            <div class="absolute bottom-2 left-2 h-3 w-3 bg-fuchsia-500 border-2 border-black rotate-12"></div>
                                            <div class="absolute inset-x-3 top-3">
                                                <div class="inline-block bg-black h-1 w-8"></div>
                                            </div>
                                            <div class="absolute inset-x-3 top-1/2 -translate-y-1/2">
                                                <div class="h-2 w-20 bg-black"></div>
                                                <div class="mt-1 h-2 w-14 bg-black"></div>
                                            </div>
                                        @endif
                                        {{-- Selected check --}}
                                        <div class="absolute top-2 right-2 h-6 w-6 rounded-full bg-indigo-500 text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition scale-90 peer-checked:scale-100"
                                             style="opacity: 0;">
                                        </div>
                                    </div>
                                    <p class="font-display font-bold text-white text-sm">{{ $meta['name'] }}</p>
                                    <p class="text-xs text-white/50 mt-0.5">{{ $meta['tagline'] }}</p>
                                </div>
                                <div class="absolute top-3 right-3 h-6 w-6 rounded-full bg-indigo-500 text-white items-center justify-center
                                            hidden peer-checked:flex animate-scale-in z-10">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('template') <p class="mt-1.5 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-white/10">
                    <a href="{{ route('sales-pages.index') }}" class="text-sm text-white/60 hover:text-white transition">← Cancel</a>
                    <button type="submit" id="submit-btn" class="btn-gradient">
                        <svg id="spinner" class="hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="btn-label">Generate sales page</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-5 text-center text-xs text-white/40">
            Generation typically takes 10–30 seconds. Please don't refresh the page.
        </p>
    </div>

    @push('scripts')
    <script>
        document.getElementById('generate-form').addEventListener('submit', function () {
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('spinner').classList.remove('hidden');
            document.getElementById('btn-label').textContent = 'Generating…';
        });
    </script>
    @endpush
</x-app-layout>
