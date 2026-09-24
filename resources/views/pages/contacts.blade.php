@extends('layouts.guide')

@section('title', 'Contactos de Avaria de Eletricidade e Água - Faltou')
@section('description', 'Lista de contactos de emergência para avarias de luz (E-Redes) e água (EPAL, Águas do Porto, e outros municípios) em Portugal.')

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "Contactos de Avaria e Emergência",
  "description": "Lista de números gratuitos e contactos para reportar falhas de serviços públicos.",
  "author": {
    "@@type": "Organization",
    "name": "Faltou"
  },
  "datePublished": "2024-01-30",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url('/contactos') }}"
  }
}
</script>
@endpush

@section('guide-section', 'Ajuda')
@section('guide-icon', 'phone')
@section('guide-heading', 'Contactos de avaria')
@section('guide-lead', 'Números diretos para reportar interrupções de serviço.')

@section('guide-body')
    <div class="flex items-center gap-4 rounded-2xl border border-danger/20 bg-danger/5 p-5">
        <a href="tel:112" class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-danger text-xl font-bold text-white hover:bg-danger/90">112</a>
        <div>
            <h2 class="!mb-0 !text-lg">Emergência Nacional</h2>
            <p class="text-base text-ink/70">Ligue apenas em caso de risco iminente para a vida, incêndio ou saúde.</p>
        </div>
    </div>

    <div class="card">
        <div class="flex items-center gap-3">
            <span class="icon-tile icon-tile-ember"><x-icon name="zap" :size="18" /></span>
            <h2 class="!mb-0 !text-xl">Eletricidade</h2>
        </div>
        <p class="mt-3 text-base text-muted">Em Portugal Continental, a rede de distribuição é gerida por uma entidade única.</p>
        <a href="tel:800506506" class="mt-5 flex flex-col gap-3 rounded-xl border border-line bg-porcelain p-4 transition hover:border-ember/40 sm:flex-row sm:items-center sm:justify-between">
            <span>
                <span class="eyebrow block">E-Redes (Nacional)</span>
                <span class="mt-1 block text-2xl font-semibold tracking-tight text-ink">800 506 506</span>
                <span class="mt-1 block text-sm text-muted">Para falhas de luz, avarias na rede e situações de risco elétrico.</span>
            </span>
            <span class="pill pill-ember shrink-0 self-start sm:self-center">Grátis 24h</span>
        </a>
    </div>

    <div class="card">
        <div class="flex items-center gap-3">
            <span class="icon-tile icon-tile-river"><x-icon name="droplet" :size="18" /></span>
            <h2 class="!mb-0 !text-xl">Água</h2>
        </div>
        <p class="mt-3 text-base text-muted">O abastecimento é gerido localmente. Contacte a empresa do seu município.</p>
        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            <a href="tel:213251000" class="rounded-xl border border-line bg-porcelain p-4 transition hover:border-river/40">
                <span class="eyebrow block">Lisboa e Vale do Tejo (EPAL)</span>
                <span class="mt-1 block text-xl font-semibold tracking-tight text-ink">213 251 000</span>
            </a>
            <a href="tel:225190800" class="rounded-xl border border-line bg-porcelain p-4 transition hover:border-river/40">
                <span class="eyebrow block">Porto (Águas do Porto)</span>
                <span class="mt-1 block text-xl font-semibold tracking-tight text-ink">225 190 800</span>
            </a>
        </div>
        <div class="mt-3 rounded-xl border border-dashed border-line p-4">
            <div class="eyebrow">Outros municípios</div>
            <p class="mt-1 text-base">Pesquise por "SMAS + [Nome do Concelho]" ou consulte a sua fatura da água para o número de avarias.</p>
        </div>
    </div>
@endsection

@section('guide-aside')
    <div class="card">
        <div class="card-title">Avise também os vizinhos</div>
        <p class="card-subtitle">Depois de ligar, partilhe a falha no mapa.</p>
        <div class="mt-4 grid gap-2">
            <a href="{{ url('/falta-eletricidade') }}" class="btn btn-power w-full"><x-icon name="zap" :size="16" /> Falta luz</a>
            <a href="{{ url('/falta-agua') }}" class="btn btn-water w-full"><x-icon name="droplet" :size="16" /> Falta água</a>
        </div>
    </div>
@endsection
