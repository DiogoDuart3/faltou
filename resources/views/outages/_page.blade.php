@php
    $btnClass = $type === 'water' ? 'btn-water' : 'btn-power';
@endphp

<section class="container-page pt-5 sm:pt-8" data-outage-page data-outage-type="{{ $type }}">
    <div class="flex items-center gap-3 sm:gap-4">
        <span class="icon-tile icon-tile-{{ $tone }} !h-12 !w-12"><x-icon :name="$icon" :size="24" /></span>
        <div class="min-w-0">
            <h1 class="text-2xl leading-tight font-semibold tracking-tight sm:text-3xl">{{ $title }}</h1>
            <p class="mt-1 flex items-center gap-2 text-sm text-muted">
                <span class="live-dot shrink-0"></span>
                <span>
                    <strong class="font-semibold text-ink tabular-nums" data-report-count="{{ $type }}">0</strong>
                    avisos ativos nas últimas 24 horas
                </span>
            </p>
        </div>
    </div>

    <div class="mt-5 grid items-start gap-5 sm:mt-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,1fr)] lg:gap-6">

        {{-- Report form --}}
        <form class="card space-y-6 lg:sticky lg:top-24" data-report-form novalidate>
            <div>
                <div class="step">
                    <span class="step-number">1</span>
                    <h2 class="step-title">Onde está a acontecer?</h2>
                </div>
                <div class="mt-3 space-y-3">
                    <button class="btn {{ $btnClass }} btn-lg w-full" type="button" data-use-location>
                        <x-icon name="locate" :size="18" /> Usar a minha localização
                    </button>
                    <p class="text-center text-sm text-muted">ou toque no mapa para marcar o local</p>
                    <div class="map-shell">
                        <div class="map" data-map>A carregar mapa...</div>
                    </div>
                    <div class="location-status" data-location-status-box>
                        <x-icon name="map-pin" :size="16" class="shrink-0" />
                        <span class="min-w-0 break-words" data-location-status role="status">Sem localização definida.</span>
                    </div>
                    <p class="text-xs text-muted">{{ $mapCaption }} Os pontos no mapa são avisos de outras pessoas.</p>
                    <details class="group">
                        <summary class="inline-flex min-h-11 cursor-pointer list-none items-center gap-1 text-sm font-medium text-muted hover:text-ink [&::-webkit-details-marker]:hidden">
                            <x-icon name="chevron-right" :size="14" class="transition group-open:rotate-90" />
                            Inserir coordenadas manualmente
                        </summary>
                        <div class="mt-1 grid grid-cols-2 gap-3">
                            <label class="field">
                                <span class="field-label">Latitude</span>
                                <input class="input" type="text" inputmode="decimal" data-lat placeholder="39.5" />
                            </label>
                            <label class="field">
                                <span class="field-label">Longitude</span>
                                <input class="input" type="text" inputmode="decimal" data-lng placeholder="-8.0" />
                            </label>
                        </div>
                    </details>
                    <input type="hidden" data-location-method value="manual" />
                </div>
            </div>

            <div>
                <div class="step">
                    <span class="step-number">2</span>
                    <h2 class="step-title">O que está afetado?</h2>
                </div>
                <fieldset class="mt-3 grid grid-cols-2 gap-2">
                    <legend class="sr-only">Impacto</legend>
                    @foreach ($impacts as $value => $impactLabel)
                        <label class="choice">
                            <input type="radio" name="impact" value="{{ $value }}" class="sr-only" data-impact
                                @checked($loop->first)>
                            {{ $impactLabel }}
                        </label>
                    @endforeach
                </fieldset>
                <label class="field mt-4">
                    <span class="field-label">
                        <span>Descrição <span class="font-normal text-muted">(opcional)</span></span>
                        <span class="field-hint"><span data-count-for="note">0</span>/160</span>
                    </span>
                    <textarea class="input" rows="2" maxlength="160" data-note data-counted="note"
                        placeholder="{{ $notePlaceholder }}"></textarea>
                </label>
                <p class="mt-3 flex items-start gap-2 text-sm text-muted">
                    <x-icon name="shield" :size="16" class="mt-0.5 shrink-0" />
                    Anónimo. Só a localidade aproximada fica visível, durante 24 horas.
                </p>
            </div>

            <div class="sticky-submit space-y-2">
                <p class="form-status text-center" data-form-status role="status"></p>
                <button class="btn btn-primary btn-lg w-full" type="submit">Publicar aviso</button>
            </div>
        </form>

        {{-- Live info --}}
        <div class="space-y-5">
            <div class="card !p-0" data-tabs>
                <div class="border-b border-line p-3">
                    <div class="tabs tabs-full" role="tablist" aria-label="Atividade na zona">
                        <button type="button" class="tab" role="tab" aria-selected="true" id="tab-avisos-{{ $type }}"
                            aria-controls="painel-avisos-{{ $type }}" data-tab="avisos">
                            Avisos ativos
                        </button>
                        <button type="button" class="tab" role="tab" aria-selected="false" id="tab-comentarios-{{ $type }}"
                            aria-controls="painel-comentarios-{{ $type }}" data-tab="comentarios">
                            <x-icon name="message" :size="16" /> Comentários
                        </button>
                    </div>
                </div>

                <div class="p-3 sm:p-4" role="tabpanel" id="painel-avisos-{{ $type }}"
                    aria-labelledby="tab-avisos-{{ $type }}" data-tab-panel="avisos">
                    <p class="mb-3 px-1 text-sm text-muted">Relatos das últimas 24 horas, mais recentes primeiro.</p>
                    <div class="max-h-[30rem] space-y-2 overflow-y-auto" data-report-list>
                        <div class="skeleton"></div>
                        <div class="skeleton"></div>
                    </div>
                </div>

                <div class="p-3 sm:p-4" role="tabpanel" id="painel-comentarios-{{ $type }}"
                    aria-labelledby="tab-comentarios-{{ $type }}" data-tab-panel="comentarios" data-comments hidden>
                    <form class="space-y-2" data-comment-form novalidate>
                        <label class="field-label" for="comment-{{ $type }}">
                            <span>Atualização curta para a comunidade</span>
                            <span class="field-hint shrink-0"><span data-count-for="comment">0</span>/140</span>
                        </label>
                        <div class="flex items-end gap-2">
                            <textarea id="comment-{{ $type }}" class="input" rows="2" maxlength="140" data-comment-text
                                data-counted="comment" placeholder="{{ $commentPlaceholder }}"></textarea>
                            <button class="btn btn-primary !h-12 !w-12 shrink-0 !p-0" type="submit" aria-label="Enviar comentário">
                                <x-icon name="send" :size="18" />
                            </button>
                        </div>
                        <p class="form-status" data-comment-status role="status"></p>
                    </form>
                    <div class="mt-3 max-h-96 space-y-2 overflow-y-auto" data-comment-list>
                        <div class="skeleton !h-12"></div>
                    </div>
                </div>
            </div>

            <div class="card-flat flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between lg:flex-col lg:items-stretch xl:flex-row xl:items-center">
                <div class="flex items-start gap-3">
                    <span class="icon-tile"><x-icon name="phone" :size="18" /></span>
                    <div>
                        <div class="font-semibold">{{ $contactTitle }}</div>
                        <p class="text-sm text-muted">{{ $contactText }}</p>
                    </div>
                </div>
                <div class="grid shrink-0 grid-cols-2 gap-2 sm:flex">
                    @if (!empty($contactPhone))
                        <a href="tel:{{ str_replace(' ', '', $contactPhone) }}" class="btn btn-secondary tabular-nums">
                            <x-icon name="phone" :size="16" /> {{ $contactPhone }}
                        </a>
                    @else
                        <a href="{{ url('/contactos') }}" class="btn btn-secondary">Ver contactos</a>
                    @endif
                    <a href="{{ url($guideUrl) }}" class="btn btn-secondary">Guia <x-icon name="arrow-right" :size="14" /></a>
                </div>
            </div>
        </div>
    </div>
</section>
