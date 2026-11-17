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
    {{-- Status + report + live map --}}
    <section class="container-page pt-5 sm:pt-8 lg:pt-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between lg:gap-8">
            <div>
                <h1 class="text-[1.75rem] leading-tight font-semibold tracking-tight sm:text-4xl">
                    Faltou a luz ou a água?
                </h1>
                <p class="mt-2 flex items-center gap-2.5 text-muted">
                    <span class="live-dot shrink-0"></span>
                    <span>
                        <strong class="font-semibold text-ink tabular-nums" data-report-count="all">0</strong>
                        avisos ativos nas últimas 24h
                    </span>
                </p>
            </div>

            {{-- Desktop: the header already carries both report buttons, so only the counts show here --}}
            <div class="hidden shrink-0 items-center gap-2 lg:flex">
                <span class="pill pill-ember !px-3 !py-1.5 !text-sm">
                    <x-icon name="zap" :size="14" /> <span class="tabular-nums" data-report-count="power">0</span> de luz
                </span>
                <span class="pill pill-river !px-3 !py-1.5 !text-sm">
                    <x-icon name="droplet" :size="14" /> <span class="tabular-nums" data-report-count="water">0</span> de água
                </span>
            </div>

            <div id="reportar" class="grid scroll-mt-24 grid-cols-2 gap-3 lg:hidden">
                <a href="{{ url('/falta-eletricidade') }}" class="report-cta report-cta-power">
                    <x-icon name="zap" :size="24" class="shrink-0" />
                    <span class="min-w-0 leading-tight">
                        <span class="block font-semibold">Falta luz</span>
                        <span class="mt-0.5 block text-xs font-normal text-white/85">
                            <span class="tabular-nums" data-report-count="power">0</span> avisos ativos
                        </span>
                    </span>
                </a>
                <a href="{{ url('/falta-agua') }}" class="report-cta report-cta-water">
                    <x-icon name="droplet" :size="24" class="shrink-0" />
                    <span class="min-w-0 leading-tight">
                        <span class="block font-semibold">Falta água</span>
                        <span class="mt-0.5 block text-xs font-normal text-white/85">
                            <span class="tabular-nums" data-report-count="water">0</span> avisos ativos
                        </span>
                    </span>
                </a>
            </div>
        </div>

        <div id="mapa" class="mt-5 grid grid-cols-1 scroll-mt-20 gap-4 lg:mt-6 lg:h-[34rem] lg:grid-cols-[1.6fr_1fr]">
            <div class="flex min-h-0 flex-col gap-2">
                <div class="map-shell min-h-0 flex-1">
                    <div class="map map--xl" data-report-map data-map-scope="all">A carregar mapa...</div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                    <div class="map-legend">
                        <span class="legend-item"><span class="legend-dot legend-power"></span> Eletricidade</span>
                        <span class="legend-item"><span class="legend-dot legend-water"></span> Água</span>
                    </div>
                    <p class="text-xs text-muted">Toque num ponto para ver detalhes.</p>
                </div>
            </div>

            <section class="card flex min-h-0 flex-col !p-0" aria-labelledby="avisos-recentes">
                <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-2 border-b border-line px-4 py-3">
                    <h2 id="avisos-recentes" class="text-base font-semibold">Avisos recentes</h2>
                    <div class="tabs" role="tablist" aria-label="Filtrar avisos">
                        <button type="button" class="tab" role="tab" aria-selected="true" data-feed-filter="all">Todos</button>
                        <button type="button" class="tab" role="tab" aria-selected="false" data-feed-filter="power">Luz</button>
                        <button type="button" class="tab" role="tab" aria-selected="false" data-feed-filter="water">Água</button>
                    </div>
                </div>
                <div class="min-h-0 flex-1 space-y-2 overflow-y-auto p-3 max-lg:max-h-[26rem]" data-report-feed>
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                </div>
            </section>
        </div>
    </section>

    {{-- Emergency strip --}}
    <section class="container-page mt-6 sm:mt-8">
        <div class="flex flex-col gap-4 rounded-2xl bg-ink p-5 text-white sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex items-start gap-4">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10">
                    <x-icon name="phone" />
                </span>
                <div>
                    <h2 class="font-semibold">Avaria elétrica? Ligue à E-Redes.</h2>
                    <p class="mt-0.5 text-sm text-white/75">
                        Linha gratuita 24h. O Faltou é comunitário e não comunica avarias aos operadores.
                    </p>
                </div>
            </div>
            <div class="grid shrink-0 grid-cols-2 gap-2 sm:flex">
                <a href="tel:800506506" class="btn bg-white text-ink tabular-nums hover:bg-white/90">
                    <x-icon name="phone" :size="16" /> 800 506 506
                </a>
                <a href="{{ url('/contactos') }}" class="btn border border-white/25 text-white hover:bg-white/10">Contactos</a>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="como-funciona" class="container-page scroll-mt-20 pt-10 sm:pt-14">
        <h2 class="text-xl font-semibold sm:text-2xl">Como funciona</h2>
        <ol class="mt-4 grid gap-3 md:grid-cols-3 md:gap-4">
            @foreach ([
                ['title' => 'Indique onde', 'text' => 'Use o GPS ou toque no mapa. Se o GPS falhar, pode escrever as coordenadas à mão.'],
                ['title' => 'Publique o aviso', 'text' => 'Uma frase curta chega. O aviso fica visível durante 24 horas para quem está por perto.'],
                ['title' => 'Acompanhe a zona', 'text' => 'Veja outros avisos, deixe comentários e avise quando o serviço for restabelecido.'],
            ] as $i => $step)
                <li class="card-flat flex gap-3 !p-4">
                    <span class="step-number">{{ $i + 1 }}</span>
                    <div>
                        <h3 class="font-semibold">{{ $step['title'] }}</h3>
                        <p class="mt-0.5 text-sm text-muted">{{ $step['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- Guides --}}
    <section class="container-page pt-10 sm:pt-14">
        <div class="flex items-end justify-between gap-4">
            <h2 class="text-xl font-semibold sm:text-2xl">Guias práticos</h2>
            <a href="{{ url('/contactos') }}" class="btn btn-ghost -mr-3 hidden sm:inline-flex">
                Contactos de avaria <x-icon name="arrow-right" :size="16" />
            </a>
        </div>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
            @foreach ([
                ['url' => '/guia-falta-eletricidade', 'title' => 'Sem luz: o que fazer', 'text' => 'Verificações em casa, segurança e quem contactar.', 'icon' => 'zap', 'tone' => 'ember'],
                ['url' => '/guia-falta-agua', 'title' => 'Sem água: o que fazer', 'text' => 'Identifique a causa e saiba cuidados no regresso da água.', 'icon' => 'droplet', 'tone' => 'river'],
                ['url' => '/guia-kit-emergencia', 'title' => 'Kit de emergência', 'text' => 'O essencial para ter em casa para 3 dias.', 'icon' => 'package', 'tone' => 'sand'],
                ['url' => '/guia-indemnizacao', 'title' => 'Pedir indemnização', 'text' => 'Equipamentos danificados? Saiba como reclamar.', 'icon' => 'scale', 'tone' => ''],
            ] as $guide)
                <a href="{{ url($guide['url']) }}"
                    class="group card-flat flex items-center gap-4 !p-4 transition hover:border-ink/25 lg:flex-col lg:items-start lg:!p-5">
                    <span class="icon-tile {{ $guide['tone'] ? 'icon-tile-' . $guide['tone'] : '' }}">
                        <x-icon :name="$guide['icon']" />
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block font-semibold">{{ $guide['title'] }}</span>
                        <span class="mt-0.5 block text-sm text-muted">{{ $guide['text'] }}</span>
                    </span>
                    <x-icon name="chevron-right" :size="18" class="shrink-0 text-muted transition group-hover:translate-x-0.5 lg:hidden" />
                </a>
            @endforeach
        </div>
    </section>
@endsection
