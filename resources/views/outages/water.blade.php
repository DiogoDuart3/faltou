@extends('layouts.app')

@section('title', 'Falta de água - Faltou Portugal')
@section('description', 'Relate falhas de água com localização rápida e comentários da comunidade.')

@push('head')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
@endpush

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
        defer
    ></script>
@endpush

@section('content')
    <section class="mx-auto w-full max-w-6xl px-6 pb-16 pt-12">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]" data-outage-page data-outage-type="water">
            <div>
                <div class="pill pill-river">Falta de água</div>
                <h1 class="mt-4 text-4xl font-semibold">Relate uma falha de água</h1>
                <p class="mt-3 text-sm text-ink/70">
                    Os avisos ficam públicos durante 24 horas. Escolha a localização e escreva uma frase curta.
                </p>

                <div class="card mt-8 space-y-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <button class="btn btn-primary" type="button" data-use-location>Usar localização atual</button>
                        <span class="text-xs text-ink/60">Ou toque no mapa para selecionar manualmente.</span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="field">
                            <span>Latitude</span>
                            <input type="text" data-lat placeholder="39.5" />
                        </label>
                        <label class="field">
                            <span>Longitude</span>
                            <input type="text" data-lng placeholder="-8.0" />
                        </label>
                    </div>
                    <input type="hidden" data-location-method value="manual" />
                    <div class="text-xs text-ink/60" data-location-status>Sem localização definida.</div>
                    <div class="map-shell">
                        <div class="map" data-map></div>
                        <div class="map-caption">Mapa leve: carregamento reduzido para redes instáveis.</div>
                    </div>
                    <form class="space-y-4" data-report-form>
                        <label class="field">
                            <span>Descrição curta (se quiser)</span>
                            <textarea rows="3" data-note placeholder="Ex: Sem água desde as 09h."></textarea>
                        </label>
                        <label class="field">
                            <span>Impacto</span>
                            <select data-impact>
                                <option value="residencial">Residencial</option>
                                <option value="comercial">Comercial</option>
                                <option value="rua">Zona pública</option>
                                <option value="outros">Outros</option>
                            </select>
                        </label>
                        <button class="btn btn-secondary" type="submit">Publicar aviso</button>
                        <div class="text-xs text-ink/60" data-form-status></div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card">
                    <h2 class="font-display text-2xl">Avisos ativos (24h)</h2>
                    <p class="mt-2 text-xs text-ink/60">Mostra apenas relatos recentes para manter a informação atual.</p>
                    <div class="mt-4 grid gap-3" data-report-list>
                        <div class="empty-state">Ainda não existem avisos nesta página.</div>
                    </div>
                </div>

                <div class="card" data-comments>
                    <h2 class="font-display text-2xl">Comentários da comunidade</h2>
                    <p class="mt-2 text-xs text-ink/60">Use comentários curtos para confirmar ou atualizar o estado.</p>
                    <form class="mt-4 space-y-3" data-comment-form>
                        <label class="field">
                            <span>Comentário</span>
                            <textarea rows="2" data-comment-text placeholder="Ex: água voltou às 11h."></textarea>
                        </label>
                        <button class="btn btn-secondary" type="submit">Enviar comentário</button>
                        <div class="text-xs text-ink/60" data-comment-status></div>
                    </form>
                    <div class="mt-4 space-y-3" data-comment-list>
                        <div class="empty-state">Sem comentários ainda.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
