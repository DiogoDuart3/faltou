<!DOCTYPE html>
<html lang="pt-PT">

<head>
    @php
        $seoTitle = trim($__env->yieldContent('title')) ?: 'Faltou - Alertas comunitários em Portugal';
        $seoDescription = trim($__env->yieldContent('description')) ?:
            'Reporta falhas de eletricidade e água em Portugal com localização rápida e visibilidade por 24 horas.';
        $seoUrl = url()->current();
        $seoImage = asset('images/og-card.png');
        $openPanel = config('services.openpanel');

        $guides = [
            ['url' => '/guia-falta-eletricidade', 'label' => 'Sem luz: o que fazer', 'icon' => 'zap', 'tone' => 'ember'],
            ['url' => '/guia-falta-agua', 'label' => 'Sem água: o que fazer', 'icon' => 'droplet', 'tone' => 'river'],
            ['url' => '/guia-kit-emergencia', 'label' => 'Kit de emergência', 'icon' => 'package', 'tone' => 'sand'],
            ['url' => '/guia-indemnizacao', 'label' => 'Pedir indemnização', 'icon' => 'scale', 'tone' => ''],
        ];
        $isGuide = request()->is('guia-*');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#f6f6f3">
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
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <script>
        !function (t, e) { var o, n, p, r; e.__SV || (window.posthog && window.posthog.__loaded) || (window.posthog = e, e._i = [], e.init = function (i, s, a) { function g(t, e) { var o = e.split("."); 2 == o.length && (t = t[o[0]], e = o[1]), t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))) } } (p = t.createElement("script")).type = "text/javascript", p.crossOrigin = "anonymous", p.async = !0, p.src = s.api_host.replace(".i.posthog.com", "-assets.i.posthog.com") + "/static/array.js", (r = t.getElementsByTagName("script")[0]).parentNode.insertBefore(p, r); var u = e; for (void 0 !== a ? u = e[a] = [] : a = "posthog", u.people = u.people || [], u.toString = function (t) { var e = "posthog"; return "posthog" !== a && (e += "." + a), t || (e += " (stub)"), e }, u.people.toString = function () { return u.toString(1) + ".people (stub)" }, o = "init rs ls bi ns us ts ss capture calculateEventProperties vs register register_once register_for_session unregister unregister_for_session gs getFeatureFlag getFeatureFlagPayload getFeatureFlagResult isFeatureEnabled reloadFeatureFlags updateFlags updateEarlyAccessFeatureEnrollment getEarlyAccessFeatures on onFeatureFlags onSurveysLoaded onSessionId getSurveys getActiveMatchingSurveys renderSurvey displaySurvey cancelPendingSurvey canRenderSurvey canRenderSurveyAsync identify setPersonProperties group resetGroups setPersonPropertiesForFlags resetPersonPropertiesForFlags setGroupPropertiesForFlags resetGroupPropertiesForFlags reset get_distinct_id getGroups get_session_id get_session_replay_url alias set_config startSessionRecording stopSessionRecording sessionRecordingStarted captureException startExceptionAutocapture stopExceptionAutocapture loadToolbar get_property getSessionProperty fs ds createPersonProfile ps Qr opt_in_capturing opt_out_capturing has_opted_in_capturing has_opted_out_capturing get_explicit_consent_status is_capturing clear_opt_in_out_capturing hs debug O cs getPageViewId captureTraceFeedback captureTraceMetric Kr".split(" "), n = 0; n < o.length; n++)g(u, o[n]); e._i.push([i, s, a]) }, e.__SV = 1) }(document, window.posthog || []);
        posthog.init('phc_OmtNJJLYAu84cbo2fm2S5ZA3ICT26vf8jXuo4z7RSe2', {
            api_host: 'https://eu.i.posthog.com',
            defaults: '2025-11-30',
            person_profiles: 'identified_only', // or 'always' to create profiles for anonymous users as well
        })
    </script>

    @if (!empty($openPanel['client_id']))
        <script>
            window.op = window.op || function (...args) { (window.op.q = window.op.q || []).push(args); };
            window.op('init', {
                apiUrl: @json($openPanel['api_url']),
                clientId: @json($openPanel['client_id']),
                trackScreenViews: true,
                trackOutgoingLinks: true,
                trackAttributes: true,
            });
        </script>
        <script src="{{ $openPanel['script_url'] }}" defer async></script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('schema')
    @stack('head')
</head>

<body class="min-h-screen bg-porcelain text-ink">
    <a href="#conteudo"
        class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:rounded-lg focus:bg-ink focus:px-4 focus:py-2 focus:text-white">
        Saltar para o conteúdo
    </a>

    <header class="sticky top-0 z-50 border-b border-line/80 bg-porcelain/85 backdrop-blur-md">
        <div class="container-page flex h-16 items-center justify-between gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5" aria-label="Faltou — início">
                <img src="{{ asset('images/icon.png') }}" alt="" class="h-9 w-9 rounded-xl object-cover">
                <span class="leading-none">
                    <span class="block text-[17px] font-semibold tracking-tight">Faltou</span>
                    <span class="mt-0.5 block text-[11px] font-medium text-muted">Alertas em Portugal</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Principal">
                <a class="nav-link" href="{{ url('/') }}" @if (request()->is('/')) aria-current="page" @endif>Mapa ao vivo</a>
                <a class="nav-link" href="{{ url('/falta-eletricidade') }}" @if (request()->is('falta-eletricidade')) aria-current="page" @endif>Eletricidade</a>
                <a class="nav-link" href="{{ url('/falta-agua') }}" @if (request()->is('falta-agua')) aria-current="page" @endif>Água</a>
                <details class="group relative" data-dropdown>
                    <summary class="nav-link flex cursor-pointer list-none items-center gap-1 [&::-webkit-details-marker]:hidden"
                        @if ($isGuide) aria-current="page" @endif>
                        Guias
                        <x-icon name="chevron-down" :size="16" class="transition group-open:rotate-180" />
                    </summary>
                    <div class="card absolute top-full left-0 mt-2 w-72 !p-2">
                        @foreach ($guides as $guide)
                            <a href="{{ url($guide['url']) }}"
                                class="flex items-center gap-3 rounded-xl px-2.5 py-2 text-sm font-medium text-ink transition hover:bg-stone">
                                <span class="icon-tile !h-8 !w-8 {{ $guide['tone'] ? 'icon-tile-' . $guide['tone'] : '' }}">
                                    <x-icon :name="$guide['icon']" :size="16" />
                                </span>
                                {{ $guide['label'] }}
                            </a>
                        @endforeach
                    </div>
                </details>
                <a class="nav-link" href="{{ url('/contactos') }}" @if (request()->is('contactos')) aria-current="page" @endif>Contactos</a>
            </nav>

            <div class="flex items-center gap-2">
                <a class="btn btn-primary hidden sm:inline-flex" href="{{ url('/#reportar') }}">
                    <x-icon name="plus" :size="16" /> Reportar falha
                </a>
                <details class="group lg:hidden" data-dropdown>
                    <summary class="btn btn-secondary !px-2.5 cursor-pointer list-none [&::-webkit-details-marker]:hidden"
                        aria-label="Abrir menu">
                        <x-icon name="menu" :size="20" class="group-open:hidden" />
                        <x-icon name="x" :size="20" class="hidden group-open:block" />
                    </summary>
                    <div
                        class="absolute inset-x-0 top-16 max-h-[calc(100dvh-4rem)] overflow-y-auto border-b border-line bg-porcelain px-4 pt-3 pb-6 shadow-lg">
                        <nav class="space-y-1" aria-label="Menu móvel">
                            <a class="mobile-nav-link" href="{{ url('/') }}">
                                <span class="icon-tile !h-8 !w-8"><x-icon name="map-pin" :size="16" /></span> Mapa ao vivo
                            </a>
                            <a class="mobile-nav-link" href="{{ url('/falta-eletricidade') }}">
                                <span class="icon-tile icon-tile-ember !h-8 !w-8"><x-icon name="zap" :size="16" /></span> Falta de eletricidade
                            </a>
                            <a class="mobile-nav-link" href="{{ url('/falta-agua') }}">
                                <span class="icon-tile icon-tile-river !h-8 !w-8"><x-icon name="droplet" :size="16" /></span> Falta de água
                            </a>
                            <a class="mobile-nav-link" href="{{ url('/contactos') }}">
                                <span class="icon-tile !h-8 !w-8"><x-icon name="phone" :size="16" /></span> Contactos de avaria
                            </a>
                        </nav>
                        <div class="eyebrow mt-5 mb-2 px-3">Guias</div>
                        <nav class="space-y-1" aria-label="Guias">
                            @foreach ($guides as $guide)
                                <a class="mobile-nav-link" href="{{ url($guide['url']) }}">
                                    <span class="icon-tile !h-8 !w-8 {{ $guide['tone'] ? 'icon-tile-' . $guide['tone'] : '' }}">
                                        <x-icon :name="$guide['icon']" :size="16" />
                                    </span>
                                    {{ $guide['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </details>
            </div>
        </div>
    </header>

    <main id="conteudo">
        @yield('content')
    </main>

    <footer class="mt-8 border-t border-line bg-white">
        <div class="container-page grid gap-10 py-12 md:grid-cols-[1.4fr_1fr_1fr_1fr]">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/icon.png') }}" alt="" class="h-8 w-8 rounded-lg object-cover">
                    <span class="text-base font-semibold tracking-tight">Faltou</span>
                </a>
                <p class="mt-3 max-w-xs text-sm text-muted">
                    Iniciativa comunitária para partilhar rapidamente falhas de luz e água em Portugal. Sem registo,
                    sem publicidade.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="badge"><x-icon name="clock" :size="12" /> Avisos duram 24h</span>
                    <span class="badge"><x-icon name="wifi" :size="12" /> Funciona offline</span>
                </div>
            </div>
            <div>
                <div class="eyebrow mb-3">Reportar</div>
                <ul class="space-y-2">
                    <li><a class="footer-link" href="{{ url('/falta-eletricidade') }}">Falta de eletricidade</a></li>
                    <li><a class="footer-link" href="{{ url('/falta-agua') }}">Falta de água</a></li>
                    <li><a class="footer-link" href="{{ url('/') }}">Mapa ao vivo</a></li>
                </ul>
            </div>
            <div>
                <div class="eyebrow mb-3">Guias</div>
                <ul class="space-y-2">
                    @foreach ($guides as $guide)
                        <li><a class="footer-link" href="{{ url($guide['url']) }}">{{ $guide['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <div class="eyebrow mb-3">Ajuda</div>
                <ul class="space-y-2">
                    <li><a class="footer-link" href="{{ url('/contactos') }}">Contactos de avaria</a></li>
                    <li><a class="footer-link" href="tel:800506506">E-Redes · 800 506 506</a></li>
                    <li><a class="footer-link" href="tel:112">Emergência · 112</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-line">
            <div class="container-page flex flex-col gap-2 py-5 text-xs text-muted sm:flex-row sm:items-center sm:justify-between">
                <span>&copy; {{ date('Y') }} Faltou. Informação partilhada pela comunidade — não substitui os canais oficiais.</span>
                <span>Mapas &copy; OpenStreetMap</span>
            </div>
        </div>
        @unless (trim($__env->yieldContent('hide-mobile-cta')))
            <div class="h-20 sm:hidden"></div>
        @endunless
    </footer>

    @unless (trim($__env->yieldContent('hide-mobile-cta')))
        <div class="pb-safe fixed inset-x-0 bottom-0 z-40 border-t border-line bg-white/95 px-4 pt-3 backdrop-blur sm:hidden">
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ url('/falta-eletricidade') }}" class="btn btn-power">
                    <x-icon name="zap" :size="16" /> Falta luz
                </a>
                <a href="{{ url('/falta-agua') }}" class="btn btn-water">
                    <x-icon name="droplet" :size="16" /> Falta água
                </a>
            </div>
        </div>
    @endunless

    <div class="pointer-events-none fixed inset-x-0 bottom-24 z-[60] flex flex-col items-center gap-2 px-4 sm:bottom-6"
        data-toast-region aria-live="polite"></div>

    @stack('scripts')
</body>

</html>
