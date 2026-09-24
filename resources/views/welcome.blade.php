@extends('layouts.app')

@section('title', 'Faltou - Alertas comunitários de luz e água em Portugal')
@section(
    'description',
    'Partilha avisos de falta de eletricidade e água com localização rápida e visibilidade por 24 horas.'
)

@include('partials.leaflet')

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

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-line">
        <div class="bg-grid mask-fade-b pointer-events-none absolute inset-0"></div>
        <div class="container-page relative pt-12 pb-14 sm:pt-16 lg:pt-20">
            <div class="grid items-center gap-10 lg:grid-cols-[1.15fr_0.85fr]">
                <div>
                    <div class="badge">
                        <span class="live-dot"></span>
                        <span><strong class="font-semibold text-ink" data-report-count="all">0</strong> avisos ativos nas últimas 24h</span>
                    </div>
                    <h1 class="mt-5 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl lg:text-[3.5rem]">
                        Faltou a luz ou a água?<br class="hidden sm:block">
                        <span class="text-muted">Saiba se não é só consigo.</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-lg text-muted">
                        Reporte uma falha em segundos e veja em tempo real o que os vizinhos estão a reportar. Sem
                        registo, e funciona mesmo com rede fraca.
                    </p>

                    <div id="reportar" class="mt-8 grid scroll-mt-24 gap-3 sm:grid-cols-2">
                        <a href="{{ url('/falta-eletricidade') }}"
                            class="group card flex items-center gap-4 !p-4 transition hover:-translate-y-0.5 hover:border-ember/40">
                            <span class="icon-tile icon-tile-ember !h-12 !w-12"><x-icon name="zap" :size="22" /></span>
                            <span class="flex-1">
                                <span class="block font-semibold">Falta de eletricidade</span>
                                <span class="block text-sm text-muted">
                                    <span data-report-count="power">0</span> avisos ativos
                                </span>
                            </span>
                            <x-icon name="arrow-right" :size="18" class="text-muted transition group-hover:translate-x-0.5 group-hover:text-ember" />
                        </a>
                        <a href="{{ url('/falta-agua') }}"
                            class="group card flex items-center gap-4 !p-4 transition hover:-translate-y-0.5 hover:border-river/40">
                            <span class="icon-tile icon-tile-river !h-12 !w-12"><x-icon name="droplet" :size="22" /></span>
                            <span class="flex-1">
                                <span class="block font-semibold">Falta de água</span>
                                <span class="block text-sm text-muted">
                                    <span data-report-count="water">0</span> avisos ativos
                                </span>
                            </span>
                            <x-icon name="arrow-right" :size="18" class="text-muted transition group-hover:translate-x-0.5 group-hover:text-river" />
                        </a>
                    </div>
                </div>

                <div class="card hidden lg:block">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Estado do serviço</div>
                            <div class="card-subtitle">Pensado para redes instáveis</div>
                        </div>
                        <span class="pill"><x-icon name="shield" :size="12" /> Anónimo</span>
                    </div>
                    <div class="mt-6 space-y-5" data-connection-state>
                        <div class="status-row">
                            <span class="icon-tile !h-9 !w-9"><x-icon name="wifi" :size="18" /></span>
                            <div>
                                <div class="text-sm font-medium">Ligação atual</div>
                                <div class="text-sm text-muted" data-connection-text>A detetar...</div>
                            </div>
                        </div>
                        <div class="status-row">
                            <span class="icon-tile icon-tile-sand !h-9 !w-9"><x-icon name="layers" :size="18" /></span>
                            <div>
                                <div class="text-sm font-medium">Modo resiliente</div>
                                <div class="text-sm text-muted">Avisos guardados no dispositivo e enviados quando houver rede.</div>
                            </div>
                        </div>
                        <div class="status-row">
                            <span class="icon-tile icon-tile-river !h-9 !w-9"><x-icon name="locate" :size="18" /></span>
                            <div>
                                <div class="text-sm font-medium">Localização rápida</div>
                                <div class="text-sm text-muted">GPS automático ou toque no mapa. Só a localidade é mostrada.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Live map + feed --}}
    <section id="mapa" class="container-page scroll-mt-20 py-12 sm:py-16">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="eyebrow">Últimas 24 horas</div>
                <h2 class="mt-2 text-2xl font-semibold sm:text-3xl">Mapa ao vivo</h2>
                <p class="mt-1 text-muted">Avisos da comunidade em todo o país. Toque num ponto para ver detalhes.</p>
            </div>
            <div class="map-legend">
                <span class="legend-item"><span class="legend-dot legend-power"></span> Eletricidade</span>
                <span class="legend-item"><span class="legend-dot legend-water"></span> Água</span>
            </div>
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-[1.5fr_1fr]">
            <div class="map-shell">
                <div class="map map--xl" data-report-map data-map-scope="all">A carregar mapa...</div>
            </div>

            <div class="card flex flex-col !p-0 lg:max-h-[28rem]">
                <div class="flex items-center justify-between gap-3 border-b border-line px-4 py-3">
                    <div class="text-sm font-semibold">Avisos recentes</div>
                    <div class="tabs" role="tablist" aria-label="Filtrar avisos">
                        <button type="button" class="tab" role="tab" aria-selected="true" data-feed-filter="all">Todos</button>
                        <button type="button" class="tab" role="tab" aria-selected="false" data-feed-filter="power">Luz</button>
                        <button type="button" class="tab" role="tab" aria-selected="false" data-feed-filter="water">Água</button>
                    </div>
                </div>
                <div class="flex-1 space-y-2 overflow-y-auto p-3 max-lg:max-h-[26rem]" data-report-feed>
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="como-funciona" class="scroll-mt-20 border-y border-line bg-white">
        <div class="container-page py-12 sm:py-16">
            <div class="max-w-2xl">
                <div class="eyebrow">Como funciona</div>
                <h2 class="mt-2 text-2xl font-semibold sm:text-3xl">Três passos, menos de um minuto</h2>
            </div>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                <div>
                    <span class="icon-tile icon-tile-ember"><x-icon name="locate" /></span>
                    <h3 class="mt-4 font-semibold">1. Indique onde</h3>
                    <p class="mt-1 text-sm text-muted">
                        Use o GPS ou toque no mapa. Se o GPS falhar, pode escrever as coordenadas à mão.
                    </p>
                </div>
                <div>
                    <span class="icon-tile icon-tile-river"><x-icon name="send" /></span>
                    <h3 class="mt-4 font-semibold">2. Publique o aviso</h3>
                    <p class="mt-1 text-sm text-muted">
                        Uma frase curta chega. O aviso fica visível durante 24 horas para quem está por perto.
                    </p>
                </div>
                <div>
                    <span class="icon-tile icon-tile-sand"><x-icon name="users" /></span>
                    <h3 class="mt-4 font-semibold">3. Acompanhe a zona</h3>
                    <p class="mt-1 text-sm text-muted">
                        Veja outros avisos, deixe comentários e confirme quando o serviço for restabelecido.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Guides --}}
    <section class="container-page py-12 sm:py-16">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="eyebrow">Guias práticos</div>
                <h2 class="mt-2 text-2xl font-semibold sm:text-3xl">Esteja preparado</h2>
            </div>
            <a href="{{ url('/contactos') }}" class="btn btn-ghost -mx-3 self-start sm:self-auto">
                Contactos de avaria <x-icon name="arrow-right" :size="16" />
            </a>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['url' => '/guia-falta-eletricidade', 'title' => 'Sem luz: o que fazer', 'text' => 'Verificações em casa, segurança e quem contactar.', 'icon' => 'zap', 'tone' => 'ember'],
                ['url' => '/guia-falta-agua', 'title' => 'Sem água: o que fazer', 'text' => 'Identifique a causa e saiba cuidados no regresso da água.', 'icon' => 'droplet', 'tone' => 'river'],
                ['url' => '/guia-kit-emergencia', 'title' => 'Kit de emergência', 'text' => 'O essencial para ter em casa para 3 dias.', 'icon' => 'package', 'tone' => 'sand'],
                ['url' => '/guia-indemnizacao', 'title' => 'Pedir indemnização', 'text' => 'Equipamentos danificados? Saiba como reclamar.', 'icon' => 'scale', 'tone' => ''],
            ] as $guide)
                <a href="{{ url($guide['url']) }}" class="group card flex flex-col transition hover:-translate-y-0.5 hover:border-ink/20">
                    <span class="icon-tile {{ $guide['tone'] ? 'icon-tile-' . $guide['tone'] : '' }}">
                        <x-icon :name="$guide['icon']" />
                    </span>
                    <h3 class="mt-4 font-semibold">{{ $guide['title'] }}</h3>
                    <p class="mt-1 flex-1 text-sm text-muted">{{ $guide['text'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-ink">
                        Ler guia <x-icon name="arrow-right" :size="14" class="transition group-hover:translate-x-0.5" />
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Emergency strip --}}
    <section class="container-page pb-4">
        <div class="flex flex-col gap-4 rounded-2xl bg-ink p-6 text-white sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div class="flex items-start gap-4">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10">
                    <x-icon name="phone" />
                </span>
                <div>
                    <div class="font-semibold">Avaria elétrica? Ligue à E-Redes.</div>
                    <p class="mt-0.5 text-sm text-white/70">
                        Linha gratuita 24h. O Faltou é comunitário e não comunica avarias aos operadores.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="tel:800506506" class="btn bg-white text-ink hover:bg-white/90">800 506 506</a>
                <a href="{{ url('/contactos') }}" class="btn border border-white/20 text-white hover:bg-white/10">Outros contactos</a>
            </div>
        </div>
    </section>
@endsection
