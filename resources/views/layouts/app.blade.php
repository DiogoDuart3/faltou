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
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|fraunces:400,600,700"
        rel="stylesheet" />

    <script>
        !function (t, e) { var o, n, p, r; e.__SV || (window.posthog && window.posthog.__loaded) || (window.posthog = e, e._i = [], e.init = function (i, s, a) { function g(t, e) { var o = e.split("."); 2 == o.length && (t = t[o[0]], e = o[1]), t[e] = function () { t.push([e].concat(Array.prototype.slice.call(arguments, 0))) } } (p = t.createElement("script")).type = "text/javascript", p.crossOrigin = "anonymous", p.async = !0, p.src = s.api_host.replace(".i.posthog.com", "-assets.i.posthog.com") + "/static/array.js", (r = t.getElementsByTagName("script")[0]).parentNode.insertBefore(p, r); var u = e; for (void 0 !== a ? u = e[a] = [] : a = "posthog", u.people = u.people || [], u.toString = function (t) { var e = "posthog"; return "posthog" !== a && (e += "." + a), t || (e += " (stub)"), e }, u.people.toString = function () { return u.toString(1) + ".people (stub)" }, o = "init rs ls bi ns us ts ss capture calculateEventProperties vs register register_once register_for_session unregister unregister_for_session gs getFeatureFlag getFeatureFlagPayload getFeatureFlagResult isFeatureEnabled reloadFeatureFlags updateFlags updateEarlyAccessFeatureEnrollment getEarlyAccessFeatures on onFeatureFlags onSurveysLoaded onSessionId getSurveys getActiveMatchingSurveys renderSurvey displaySurvey cancelPendingSurvey canRenderSurvey canRenderSurveyAsync identify setPersonProperties group resetGroups setPersonPropertiesForFlags resetPersonPropertiesForFlags setGroupPropertiesForFlags resetGroupPropertiesForFlags reset get_distinct_id getGroups get_session_id get_session_replay_url alias set_config startSessionRecording stopSessionRecording sessionRecordingStarted captureException startExceptionAutocapture stopExceptionAutocapture loadToolbar get_property getSessionProperty fs ds createPersonProfile ps Qr opt_in_capturing opt_out_capturing has_opted_in_capturing has_opted_out_capturing get_explicit_consent_status is_capturing clear_opt_in_out_capturing hs debug O cs getPageViewId captureTraceFeedback captureTraceMetric Kr".split(" "), n = 0; n < o.length; n++)g(u, o[n]); e._i.push([i, s, a]) }, e.__SV = 1) }(document, window.posthog || []);
        posthog.init('phc_OmtNJJLYAu84cbo2fm2S5ZA3ICT26vf8jXuo4z7RSe2', {
            api_host: 'https://eu.i.posthog.com',
            defaults: '2025-11-30',
            person_profiles: 'identified_only', // or 'always' to create profiles for anonymous users as well
        })
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('schema')
    @stack('head')
</head>

<body class="min-h-screen bg-porcelain text-ink">
    <div class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-noise opacity-70"></div>
        <div
            class="pointer-events-none absolute -top-40 right-0 h-[28rem] w-[28rem] rounded-full bg-ember/30 blur-[120px]">
        </div>
        <div
            class="pointer-events-none absolute bottom-0 left-0 h-[26rem] w-[26rem] rounded-full bg-river/25 blur-[120px]">
        </div>

        <header class="relative z-10 border-b border-ink/10">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-5">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/icon.png') }}" alt="Faltou"
                        class="h-10 w-10 rounded-xl object-cover shadow-sm">
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
            <div
                class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-6 py-8 text-sm text-ink/60 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-col gap-2">
                    <div>Faltou é uma iniciativa comunitária para partilha rápida de falhas de serviço.</div>
                    <div class="flex flex-wrap gap-4 gap-y-2">
                        <a href="{{ url('/guia-falta-eletricidade') }}" class="hover:text-ink hover:underline">Guia:
                            Luz</a>
                        <a href="{{ url('/guia-falta-agua') }}" class="hover:text-ink hover:underline">Guia: Água</a>
                        <a href="{{ url('/guia-kit-emergencia') }}" class="hover:text-ink hover:underline">Kit
                            Emergência</a>
                        <a href="{{ url('/guia-indemnizacao') }}"
                            class="hover:text-ink hover:underline">Indemnização</a>
                        <a href="{{ url('/contactos') }}" class="hover:text-ink hover:underline">Contactos</a>
                    </div>
                </div>
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