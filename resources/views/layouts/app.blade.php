<!DOCTYPE html>
<html lang="pt-PT">
    <head>
        @php
            $seoTitle = trim($__env->yieldContent('title')) ?: 'Faltou - Alertas comunitários em Portugal';
            $seoDescription = trim($__env->yieldContent('description')) ?:
                'Reporta falhas de eletricidade e água em Portugal com localização rápida e visibilidade por 24 horas.';
            $seoUrl = url()->current();
            $seoImage = asset('images/og-card.png');
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#f4f0e6">
        <meta name="description" content="{{ $seoDescription }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ $seoUrl }}">
        <title>{{ $seoTitle }}</title>

        <meta property="og:title" content="{{ $seoTitle }}">
        <meta property="og:description" content="{{ $seoDescription }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $seoUrl }}">
        <meta property="og:site_name" content="Faltou">
        <meta property="og:locale" content="pt_PT">
        <meta property="og:image" content="{{ $seoImage }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seoTitle }}">
        <meta name="twitter:description" content="{{ $seoDescription }}">
        <meta name="twitter:image" content="{{ $seoImage }}">

        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|fraunces:400,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-porcelain text-ink">
        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 bg-noise opacity-70"></div>
            <div class="pointer-events-none absolute -top-40 right-0 h-[28rem] w-[28rem] rounded-full bg-ember/30 blur-[120px]"></div>
            <div class="pointer-events-none absolute bottom-0 left-0 h-[26rem] w-[26rem] rounded-full bg-river/25 blur-[120px]"></div>

            <header class="relative z-10 border-b border-ink/10">
                <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-5">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/icon.png') }}" alt="Faltou" class="h-10 w-10 rounded-xl object-cover shadow-sm">
                        <div class="leading-none">
                            <div class="font-display text-xl">Faltou</div>
                            <div class="text-xs uppercase tracking-[0.24em] text-ink/60">Portugal</div>
                        </div>
                    </a>
                    <nav class="hidden items-center gap-6 text-sm font-medium md:flex">
                        <a class="nav-link" href="{{ url('/') }}">Início</a>
                        <a class="nav-link" href="{{ url('/falta-eletricidade') }}">Falta de eletricidade</a>
                        <a class="nav-link" href="{{ url('/falta-agua') }}">Falta de água</a>
                        <a class="nav-link" href="{{ url('/#como-funciona') }}">Como funciona</a>
                    </nav>
                    <a class="btn btn-primary" href="{{ url('/falta-eletricidade') }}">Reportar agora</a>
                </div>
                <div class="border-t border-ink/10 px-6 py-3 text-xs uppercase tracking-[0.2em] text-ink/60 md:hidden">
                    <div class="flex flex-wrap gap-4">
                        <a class="nav-link" href="{{ url('/') }}">Início</a>
                        <a class="nav-link" href="{{ url('/falta-eletricidade') }}">Eletricidade</a>
                        <a class="nav-link" href="{{ url('/falta-agua') }}">Água</a>
                    </div>
                </div>
            </header>

            <main class="relative z-10">
                @yield('content')
            </main>

            <footer class="relative z-10 border-t border-ink/10">
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-6 py-8 text-sm text-ink/60 md:flex-row md:items-center md:justify-between">
                    <div>Faltou é uma iniciativa comunitária para partilha rápida de falhas de serviço.</div>
                    <div class="flex items-center gap-4">
                        <span class="badge">Local-first</span>
                        <span class="badge">24h de visibilidade</span>
                        <span class="badge">Mapas leves</span>
                    </div>
                </div>
            </footer>
        </div>


        @stack('scripts')
    </body>
</html>
