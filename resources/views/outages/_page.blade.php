@php
    $btnClass = $type === 'water' ? 'btn-water' : 'btn-power';
@endphp

<section class="border-b border-line bg-white">
    <div class="container-page py-8 sm:py-10">
        <nav class="flex items-center gap-1.5 text-sm text-muted" aria-label="Navegação estrutural">
            <a href="{{ url('/') }}" class="hover:text-ink">Início</a>
            <x-icon name="chevron-right" :size="14" />
            <span class="text-ink">{{ $label }}</span>
        </nav>
        <div class="mt-4 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex items-start gap-4">
                <span class="icon-tile icon-tile-{{ $tone }} !h-12 !w-12"><x-icon :name="$icon" :size="24" /></span>
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">{{ $title }}</h1>
                    <p class="mt-1.5 max-w-xl text-muted">{{ $lead }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-line bg-porcelain px-4 py-2.5">
                <span class="live-dot"></span>
                <span class="text-sm text-muted">
                    <strong class="text-lg font-semibold text-ink tabular-nums" data-report-count="{{ $type }}">0</strong>
                    avisos ativos (24h)
                </span>
            </div>
        </div>
    </div>
</section>

<section class="container-page py-8 sm:py-10">
    <div class="grid items-start gap-6 lg:grid-cols-[1fr_1fr]" data-outage-page data-outage-type="{{ $type }}">

        {{-- Report form --}}
        <form class="card space-y-7 lg:sticky lg:top-24" data-report-form novalidate>
            <div>
                <div class="step">
                    <span class="step-number">1</span>
                    <h2 class="step-title">Onde está a acontecer?</h2>
                </div>
                <div class="mt-4 space-y-3">
                    <button class="btn {{ $btnClass }} btn-lg w-full" type="button" data-use-location>
                        <x-icon name="locate" :size="18" /> Usar a minha localização
                    </button>
                    <div class="flex items-center gap-3 text-xs text-muted">
                        <span class="h-px flex-1 bg-line"></span> ou toque no mapa <span class="h-px flex-1 bg-line"></span>
                    </div>
                    <div class="map-shell">
                        <div class="map" data-map>A carregar mapa...</div>
                    </div>
                    <div class="location-status" data-location-status-box>
                        <x-icon name="map-pin" :size="16" class="shrink-0" />
                        <span data-location-status>Sem localização definida.</span>
                    </div>
                    <details class="group">
                        <summary class="inline-flex cursor-pointer list-none items-center gap-1 text-sm font-medium text-muted hover:text-ink [&::-webkit-details-marker]:hidden">
                            <x-icon name="chevron-right" :size="14" class="transition group-open:rotate-90" />
                            Inserir coordenadas manualmente
                        </summary>
                        <div class="mt-3 grid grid-cols-2 gap-3">
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
                <fieldset class="mt-4 grid grid-cols-2 gap-2">
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
                        Descrição <span class="field-hint"><span data-count-for="note">0</span>/160</span>
                    </span>
                    <textarea class="input" rows="3" maxlength="160" data-note data-counted="note"
                        placeholder="{{ $notePlaceholder }}"></textarea>
                </label>
            </div>

            <div class="space-y-3 border-t border-line pt-6">
                <button class="btn btn-primary btn-lg w-full" type="submit">
                    Publicar aviso
                </button>
                <p class="form-status text-center" data-form-status role="status"></p>
                <p class="text-center text-xs text-muted">
                    Anónimo. Só a localidade aproximada fica visível, durante 24 horas.
                </p>
            </div>
        </form>

        {{-- Live info --}}
        <div class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Mapa de avisos</h2>
                        <p class="card-subtitle">{{ $mapCaption }}</p>
                    </div>
                </div>
                <div class="map-shell mt-4">
                    <div class="map map--lg" data-report-map data-map-scope="{{ $type }}">A carregar mapa...</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Avisos ativos</h2>
                        <p class="card-subtitle">Relatos das últimas 24 horas, mais recentes primeiro.</p>
                    </div>
                </div>
                <div class="mt-4 max-h-[28rem] space-y-2 overflow-y-auto" data-report-list>
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                </div>
            </div>

            <div class="card" data-comments>
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Comentários</h2>
                        <p class="card-subtitle">Atualizações curtas da comunidade.</p>
                    </div>
                    <span class="icon-tile !h-9 !w-9"><x-icon name="message" :size="18" /></span>
                </div>
                <form class="mt-4 space-y-2" data-comment-form novalidate>
                    <label class="sr-only" for="comment-{{ $type }}">Comentário</label>
                    <div class="flex items-end gap-2">
                        <textarea id="comment-{{ $type }}" class="input" rows="2" maxlength="140" data-comment-text
                            data-counted="comment" placeholder="{{ $commentPlaceholder }}"></textarea>
                        <button class="btn btn-primary !h-11 !w-11 shrink-0 !p-0" type="submit" aria-label="Enviar comentário">
                            <x-icon name="send" :size="18" />
                        </button>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <p class="form-status !min-h-0 text-xs" data-comment-status role="status"></p>
                        <span class="field-hint shrink-0"><span data-count-for="comment">0</span>/140</span>
                    </div>
                </form>
                <div class="mt-4 max-h-96 space-y-2 overflow-y-auto" data-comment-list>
                    <div class="skeleton !h-12"></div>
                </div>
            </div>

            <div class="card-flat flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="icon-tile"><x-icon name="phone" :size="18" /></span>
                    <div>
                        <div class="text-sm font-semibold">{{ $contactTitle }}</div>
                        <p class="text-sm text-muted">{{ $contactText }}</p>
                    </div>
                </div>
                <div class="flex shrink-0 gap-2">
                    @if (!empty($contactPhone))
                        <a href="tel:{{ str_replace(' ', '', $contactPhone) }}" class="btn btn-secondary">{{ $contactPhone }}</a>
                    @else
                        <a href="{{ url('/contactos') }}" class="btn btn-secondary">Ver contactos</a>
                    @endif
                    <a href="{{ url($guideUrl) }}" class="btn btn-ghost">Guia <x-icon name="arrow-right" :size="14" /></a>
                </div>
            </div>
        </div>
    </div>
</section>
