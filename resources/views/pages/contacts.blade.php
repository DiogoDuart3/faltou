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
    <a href="tel:112" class="call-row !border-danger/25 !bg-danger/5">
        <span class="min-w-0">
            <span class="call-row-number !text-danger">112</span>
            <span class="block text-sm font-semibold text-ink">Emergência Nacional</span>
            <span class="mt-0.5 block text-sm text-ink/70">Só em caso de risco para a vida, incêndio ou saúde.</span>
        </span>
        <span class="call-row-action !bg-danger"><x-icon name="phone" :size="16" /> Ligar</span>
    </a>

    <div>
        <div class="flex items-center gap-3">
            <span class="icon-tile icon-tile-ember"><x-icon name="zap" :size="18" /></span>
            <h2 class="!mb-0">Eletricidade</h2>
        </div>
        <p class="mt-2 text-muted">Em Portugal Continental, a rede de distribuição é gerida por uma entidade única.</p>
        <a href="tel:800506506" class="call-row mt-4">
            <span class="min-w-0">
                <span class="call-row-number">800 506 506</span>
                <span class="block text-sm font-semibold text-ink">E-Redes (nacional) · grátis, 24h</span>
                <span class="mt-0.5 block text-sm text-muted">Falhas de luz, avarias na rede e situações de risco elétrico.</span>
            </span>
            <span class="call-row-action"><x-icon name="phone" :size="16" /> Ligar</span>
        </a>
    </div>

    <div>
        <div class="flex items-center gap-3">
            <span class="icon-tile icon-tile-river"><x-icon name="droplet" :size="18" /></span>
            <h2 class="!mb-0">Água</h2>
        </div>
        <p class="mt-2 text-muted">O abastecimento é gerido localmente. Contacte a empresa do seu município.</p>
        <div class="mt-4 grid gap-2">
            <a href="tel:213251000" class="call-row">
                <span class="min-w-0">
                    <span class="call-row-number">213 251 000</span>
                    <span class="block text-sm font-semibold text-ink">EPAL · Lisboa e Vale do Tejo</span>
                </span>
                <span class="call-row-action"><x-icon name="phone" :size="16" /> Ligar</span>
            </a>
            <a href="tel:225190800" class="call-row">
                <span class="min-w-0">
                    <span class="call-row-number">225 190 800</span>
                    <span class="block text-sm font-semibold text-ink">Águas do Porto · Porto</span>
                </span>
                <span class="call-row-action"><x-icon name="phone" :size="16" /> Ligar</span>
            </a>
        </div>
        <div class="callout callout-river mt-3">
            <div class="font-semibold text-ink">Outros municípios</div>
            <p class="mt-1">Pesquise por "SMAS + nome do concelho" ou consulte a sua fatura da água para o número de avarias.</p>
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
