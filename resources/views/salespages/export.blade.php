<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->generated_content['headline'] ?? $page->product_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800,900&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:    ['Inter', 'system-ui', 'sans-serif'],
                        display: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in':       'fadeIn 0.6s ease-out both',
                        'fade-in-up':    'fadeInUp 0.7s ease-out both',
                        'fade-in-down':  'fadeInDown 0.7s ease-out both',
                        'slide-in-left': 'slideInLeft 0.7s cubic-bezier(0.22,1,0.36,1) both',
                        'scale-in':      'scaleIn 0.5s cubic-bezier(0.22,1,0.36,1) both',
                        'aurora-drift':  'auroraDrift 22s ease-in-out infinite',
                        'glow-pulse':    'glowPulse 3.2s ease-in-out infinite',
                        'marquee':       'marquee 30s linear infinite',
                        'float':         'float 6s ease-in-out infinite',
                        'tilt':          'tilt 10s ease-in-out infinite',
                        'pulse-ring':    'pulseRing 2.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite',
                    },
                    keyframes: {
                        fadeIn:   { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                        fadeInUp: { '0%': { opacity: 0, transform: 'translateY(12px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                        fadeInDown: { '0%': { opacity: 0, transform: 'translateY(-14px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                        slideInLeft: { '0%': { opacity: 0, transform: 'translateX(-30px)' }, '100%': { opacity: 1, transform: 'translateX(0)' } },
                        scaleIn: { '0%': { opacity: 0, transform: 'scale(0.92)' }, '100%': { opacity: 1, transform: 'scale(1)' } },
                        auroraDrift: { '0%, 100%': { transform: 'translate3d(0,0,0) scale(1)' }, '33%': { transform: 'translate3d(4%,-3%,0) scale(1.08)' }, '66%': { transform: 'translate3d(-3%,4%,0) scale(0.96)' } },
                        glowPulse: { '0%, 100%': { boxShadow: '0 0 30px -5px rgba(139,92,246,0.55), 0 0 0 rgba(139,92,246,0)' }, '50%': { boxShadow: '0 0 60px -5px rgba(139,92,246,0.95), 0 0 0 6px rgba(139,92,246,0.12)' } },
                        marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
                        float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                        tilt: { '0%, 100%': { transform: 'rotate(-1deg)' }, '50%': { transform: 'rotate(1deg)' } },
                        pulseRing: { '0%': { transform: 'scale(0.8)', opacity: 0.7 }, '100%': { transform: 'scale(2)', opacity: 0 } },
                    },
                    boxShadow: {
                        glow:     '0 0 40px -10px rgba(139, 92, 246, 0.55)',
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .text-gradient {
            background: linear-gradient(90deg, #a5b4fc, #c4b5fd, #f0abfc);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        [data-reveal] {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1);
            transition-delay: var(--reveal-delay, 0ms);
        }
        [data-reveal].is-visible { opacity: 1; transform: translateY(0); }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { opacity: 1; transform: none; transition: none; }
            * { animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; transition-duration: 0.001ms !important; }
        }
    </style>
</head>
<body>

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
