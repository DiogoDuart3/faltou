@extends('layouts.app')

@section('title', 'Faltou - Alertas comunitários de luz e água em Portugal')
@section(
    'description',
    'Partilha avisos de falta de eletricidade e água com localização rápida e visibilidade por 24 horas.'
)

@push('head')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
@endpush

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
        defer
    ></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js" defer></script>
    @push('schema')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "WebSite",
      "name": "Faltou",
      "url": "{{ url('/') }}"
    }
    </script>
    @endpush
@endpush

@section('content')
    <section class="mx-auto w-full max-w-6xl px-6 pb-16 pt-14">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                <div class="pill">Rede comunitária de alertas</div>
                <h1 class="mt-5 text-4xl font-semibold leading-tight md:text-5xl">
                    Avisos rápidos de falhas de luz e água em Portugal.
                </h1>
                <p class="mt-4 text-lg text-ink/70">
                    Partilhe a sua localização atual, acompanhe alertas nas últimas 24 horas e ajude a comunidade a
                    reagir mais depressa, mesmo com ligação instável.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a class="btn btn-primary" href="{{ url('/falta-eletricidade') }}">Reportar falta de luz</a>
                    <a class="btn btn-secondary" href="{{ url('/falta-agua') }}">Reportar falta de água</a>
                </div>
                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    <div class="stat-card">
                        <div class="stat-label">Eletricidade</div>
                        <div class="stat-value" data-report-count="power">0</div>
                        <div class="stat-help">alertas ativos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Água</div>
                        <div class="stat-value" data-report-count="water">0</div>
                        <div class="stat-help">alertas ativos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Tempo de vida</div>
                        <div class="stat-value">24h</div>
                        <div class="stat-help">visibilidade máxima</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <h2 class="font-display text-2xl">Estado da rede</h2>
                <p class="mt-2 text-sm text-ink/70">
                    Criado para funcionar em redes fracas: dados locais, mapas leves e avisos carregados do último
                    acesso.
                </p>
                <div class="mt-6 space-y-4" data-connection-state>
                    <div class="status-row">
                        <span class="status-dot"></span>
                        <div>
                            <div class="font-medium">Ligação atual</div>
                            <div class="text-xs text-ink/60" data-connection-text>A detetar...</div>
                        </div>
                    </div>
                    <div class="status-row">
                        <span class="status-dot status-dot-amber"></span>
                        <div>
                            <div class="font-medium">Modo resiliente</div>
                            <div class="text-xs text-ink/60">Alertas guardados localmente até 24h.</div>
                        </div>
                    </div>
                    <div class="status-row">
                        <span class="status-dot status-dot-blue"></span>
                        <div>
                            <div class="font-medium">Partilha rápida</div>
                            <div class="text-xs text-ink/60">Localização automática ou seleção manual no mapa.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="como-funciona" class="mx-auto w-full max-w-6xl px-6 pb-16">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="card">
                <div class="pill pill-ember">1. Localização</div>
                <h3 class="mt-4 text-xl font-semibold">Autorize ou marque no mapa</h3>
                <p class="mt-2 text-sm text-ink/70">
                    Se o GPS falhar, clique no mapa ou escreva coordenadas básicas. Sem registos obrigatórios.
                </p>
            </div>
            <div class="card">
                <div class="pill pill-river">2. Aviso rápido</div>
                <h3 class="mt-4 text-xl font-semibold">Partilhe o que faltou</h3>
                <p class="mt-2 text-sm text-ink/70">
                    Os alertas ficam visíveis durante 24 horas para dar contexto a quem está por perto.
                </p>
            </div>
            <div class="card">
                <div class="pill pill-sand">3. Comunidade</div>
                <h3 class="mt-4 text-xl font-semibold">Comentários curtos</h3>
                <p class="mt-2 text-sm text-ink/70">
                    Atualize a situação, confirme o restabelecimento e ajude outros a planear.
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto w-full max-w-6xl px-6 pb-20">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="pill">Avisos nas últimas 24 horas</div>
                <h2 class="mt-4 text-3xl font-semibold">Feed comunitário</h2>
                <p class="mt-2 text-sm text-ink/70">
                    Os alertas aparecem aqui mesmo com redes instáveis. Dados mantidos localmente quando necessário.
                </p>
            </div>
            <a class="btn btn-secondary" href="{{ url('/falta-eletricidade') }}">Criar novo aviso</a>
        </div>
        <div class="card mt-6 space-y-4">
            <div>
                <h3 class="font-display text-2xl">Mapa de avisos</h3>
                <p class="mt-2 text-xs text-ink/60">Toque num ponto para ver a localidade.</p>
            </div>
            <div class="map map--lg" data-report-map data-map-scope="all"></div>
            <div class="map-legend">
                <span class="legend-item"><span class="legend-dot legend-power"></span> Eletricidade</span>
                <span class="legend-item"><span class="legend-dot legend-water"></span> Água</span>
            </div>
        </div>
        <div class="mt-6 grid gap-4" data-report-feed>
            <div class="empty-state">Ainda não existem avisos nas últimas 24 horas.</div>
        </div>
    </section>

@endsection
