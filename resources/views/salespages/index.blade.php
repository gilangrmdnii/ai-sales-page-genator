<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="min-w-0">
                <h1 class="font-display text-xl sm:text-2xl font-bold tracking-tight text-white truncate">Your Sales Pages</h1>
                <p class="text-xs sm:text-sm text-white/50 mt-0.5">All your AI-crafted landing pages in one place.</p>
            </div>
            <a href="{{ route('sales-pages.create') }}" class="btn-gradient w-full sm:w-auto justify-center sm:justify-start flex-shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                New page
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        @if (session('success'))
            <div class="glass rounded-2xl px-5 py-4 text-emerald-300 border-emerald-400/20 bg-emerald-500/10">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="glass rounded-2xl px-5 py-4 text-rose-200 border-rose-400/20 bg-rose-500/10">
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats row --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="glass rounded-2xl p-6">
                <p class="text-xs text-white/50 uppercase tracking-wider">Total pages</p>
                <p class="mt-2 font-display text-3xl font-bold text-white">{{ $pages->total() }}</p>
            </div>
            <div class="glass rounded-2xl p-6">
                <p class="text-xs text-white/50 uppercase tracking-wider">Generated</p>
                <p class="mt-2 font-display text-3xl font-bold text-white">
                    {{ $pages->getCollection()->filter->isGenerated()->count() }}
                </p>
            </div>
            <div class="glass rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute -top-6 -right-6 h-24 w-24 rounded-full bg-indigo-500/30 blur-2xl"></div>
                <p class="text-xs text-white/50 uppercase tracking-wider">Workspace</p>
                <p class="mt-2 font-display text-xl font-bold text-white">{{ Auth::user()->name }}</p>
            </div>
        </div>

        @if ($pages->isEmpty())
            <div class="glass-strong rounded-3xl p-16 text-center animate-fade-in-up">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 shadow-glow flex items-center justify-center">
                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="mt-6 font-display text-2xl font-bold text-white">Your first page awaits</h3>
                <p class="mt-2 text-white/60 max-w-md mx-auto">Generate a high-converting landing page in under a minute with AI.</p>
                <a href="{{ route('sales-pages.create') }}" class="btn-gradient mt-8">
                    Generate your first page
                </a>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($pages as $p)
                    <div class="group glass card-lift rounded-2xl p-6 flex flex-col hover:border-white/20 hover:bg-white/[0.06] animate-fade-in-up">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-display font-bold text-white text-lg leading-snug truncate">{{ $p->product_name }}</h3>
                                <p class="text-xs text-white/40 mt-1">{{ $p->created_at->diffForHumans() }}</p>
                            </div>
                            @if ($p->isGenerated())
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[11px] font-medium text-emerald-300 border border-emerald-400/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]"></span>
                                    Ready
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-500/10 px-2.5 py-0.5 text-[11px] font-medium text-amber-300 border border-amber-400/20">Pending</span>
                            @endif
                        </div>

                        <p class="mt-4 text-sm text-white/60 line-clamp-3 leading-relaxed">{{ $p->description }}</p>

                        <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-4">
                            <a href="{{ route('sales-pages.show', $p) }}"
                               class="text-sm font-medium text-indigo-300 hover:text-indigo-200 transition">View</a>
                            @if ($p->isGenerated())
                                <a href="{{ route('sales-pages.preview', $p) }}" target="_blank"
                                   class="text-sm font-medium text-white/70 hover:text-white transition">Preview ↗</a>
                            @endif
                            <form method="POST" action="{{ route('sales-pages.destroy', $p) }}"
                                  class="ml-auto"
                                  onsubmit="return confirm('Delete this sales page?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-rose-300/80 hover:text-rose-200 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-4">
                {{ $pages->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
