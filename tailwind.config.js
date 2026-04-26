import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:    ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                glow:     '0 0 40px -10px rgba(139, 92, 246, 0.55)',
                'glow-lg':'0 10px 60px -10px rgba(99, 102, 241, 0.5)',
                'glow-xl':'0 0 80px -10px rgba(139, 92, 246, 0.8)',
            },
            backgroundImage: {
                'aurora': 'radial-gradient(60% 60% at 20% 20%, rgba(139,92,246,0.25) 0%, transparent 70%), radial-gradient(50% 50% at 80% 30%, rgba(56,189,248,0.18) 0%, transparent 70%), radial-gradient(70% 70% at 60% 100%, rgba(236,72,153,0.18) 0%, transparent 70%)',
            },
            animation: {
                'fade-in':       'fadeIn 0.6s ease-out both',
                'fade-in-up':    'fadeInUp 0.7s ease-out both',
                'fade-in-down':  'fadeInDown 0.7s ease-out both',
                'slide-in-left': 'slideInLeft 0.7s cubic-bezier(0.22,1,0.36,1) both',
                'slide-in-right':'slideInRight 0.7s cubic-bezier(0.22,1,0.36,1) both',
                'scale-in':      'scaleIn 0.5s cubic-bezier(0.22,1,0.36,1) both',
                'aurora-drift':  'auroraDrift 22s ease-in-out infinite',
                'aurora-slow':   'auroraDrift 34s ease-in-out infinite reverse',
                'glow-pulse':    'glowPulse 3.2s ease-in-out infinite',
                'shimmer':       'shimmer 2.6s linear infinite',
                'marquee':       'marquee 30s linear infinite',
                'float':         'float 6s ease-in-out infinite',
                'tilt':          'tilt 10s ease-in-out infinite',
                'gradient-x':    'gradientX 8s ease infinite',
                'bounce-soft':   'bounceSoft 2.4s ease-in-out infinite',
                'pulse-ring':    'pulseRing 2.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite',
                'wiggle':        'wiggle 0.6s ease-in-out',
            },
            keyframes: {
                fadeIn:   { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                fadeInUp: {
                    '0%':   { opacity: 0, transform: 'translateY(12px)' },
                    '100%': { opacity: 1, transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%':   { opacity: 0, transform: 'translateY(-14px)' },
                    '100%': { opacity: 1, transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%':   { opacity: 0, transform: 'translateX(-30px)' },
                    '100%': { opacity: 1, transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%':   { opacity: 0, transform: 'translateX(30px)' },
                    '100%': { opacity: 1, transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%':   { opacity: 0, transform: 'scale(0.92)' },
                    '100%': { opacity: 1, transform: 'scale(1)' },
                },
                auroraDrift: {
                    '0%, 100%': { transform: 'translate3d(0,0,0) scale(1)' },
                    '33%':      { transform: 'translate3d(4%,-3%,0) scale(1.08)' },
                    '66%':      { transform: 'translate3d(-3%,4%,0) scale(0.96)' },
                },
                glowPulse: {
                    '0%, 100%': { boxShadow: '0 0 30px -5px rgba(139,92,246,0.55), 0 0 0 rgba(139,92,246,0)' },
                    '50%':      { boxShadow: '0 0 60px -5px rgba(139,92,246,0.95), 0 0 0 6px rgba(139,92,246,0.12)' },
                },
                shimmer: {
                    '0%':   { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                marquee: {
                    '0%':   { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%':      { transform: 'translateY(-10px)' },
                },
                tilt: {
                    '0%, 100%': { transform: 'rotate(-1deg)' },
                    '50%':      { transform: 'rotate(1deg)' },
                },
                gradientX: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%':      { backgroundPosition: '100% 50%' },
                },
                bounceSoft: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%':      { transform: 'translateY(-6px)' },
                },
                pulseRing: {
                    '0%':   { transform: 'scale(0.8)', opacity: 0.7 },
                    '100%': { transform: 'scale(2)',   opacity: 0 },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(0)' },
                    '25%':      { transform: 'rotate(-3deg)' },
                    '75%':      { transform: 'rotate(3deg)' },
                },
            },
        },
    },

    plugins: [forms],
};
