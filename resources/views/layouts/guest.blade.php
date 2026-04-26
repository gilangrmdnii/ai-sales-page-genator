<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AI Sales Page Generator') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|plus-jakarta-sans:600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-white bg-slate-950">

    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-900 to-black"></div>
    <div class="aurora-blob -z-10 animate-aurora-drift" style="top:-10%; left:-10%; width:60vw; height:60vw; background: radial-gradient(circle, rgba(139,92,246,0.55) 0%, transparent 70%);"></div>
    <div class="aurora-blob -z-10 animate-aurora-slow" style="bottom:-15%; right:-10%; width:55vw; height:55vw; background: radial-gradient(circle, rgba(236,72,153,0.42) 0%, transparent 70%);"></div>

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <a href="/" class="flex items-center gap-3 mb-8 animate-fade-in">
            <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 shadow-glow flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="font-display font-bold text-white text-lg tracking-tight">AI Sales Page Studio</span>
        </a>

        <div class="w-full max-w-md glass-strong rounded-3xl p-8 sm:p-10 animate-fade-in-up">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs text-white/30">© {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</body>
</html>
