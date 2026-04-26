<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->generated_content['headline'] ?? $page->product_name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        h1, h2 { font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif; }

        /* Scroll-reveal: hidden by default, fade up when visible */
        [data-reveal] {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1);
            transition-delay: var(--reveal-delay, 0ms);
        }
        [data-reveal].is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { opacity: 1; transform: none; transition: none; }
            * { animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; transition-duration: 0.001ms !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-white">

    {{-- Floating preview bar --}}
    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50">
        <div class="flex items-center gap-4 rounded-full border border-white/10 bg-white/[0.06] backdrop-blur-xl px-5 py-2 text-xs shadow-[0_8px_32px_-8px_rgba(0,0,0,0.5)]">
            <span class="text-white/60">Live preview · {{ $page->product_name }}</span>
            <span class="h-3 w-px bg-white/15"></span>
            <a href="{{ route('sales-pages.show', $page) }}" class="text-indigo-300 hover:text-indigo-200 transition">← Editor</a>
            <a href="{{ route('sales-pages.export', $page) }}" class="text-white/70 hover:text-white transition">Export HTML</a>
        </div>
    </div>

    @include('salespages._landing')

    <script>
        (function () {
            const els = document.querySelectorAll('[data-reveal]');
            if (!('IntersectionObserver' in window) || els.length === 0) {
                els.forEach(el => el.classList.add('is-visible'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
            els.forEach(el => io.observe(el));
        })();
    </script>
</body>
</html>
