@extends('layouts.app')

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

@section('content')
<section class="max-w-4xl px-6 py-12 mx-auto">
    <div class="mb-10">
        <a href="{{ url('/') }}" class="text-sm font-medium hover:underline text-ink/60">&larr; Voltar ao início</a>
        <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-5xl">Contactos de Avaria</h1>
        <p class="mt-4 text-xl text-ink/70">Números diretos para reportar interrupções de serviço.</p>
    </div>

    <div class="grid gap-8 md:grid-cols-2">
        
        <!-- Eletricidade -->
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-2 h-8 rounded-full bg-ember"></span>
                <h2 class="text-2xl font-display">Eletricidade</h2>
            </div>
            <p class="mb-6 text-sm text-ink/70">Em Portugal Continental, a rede de distribuição é gerida por uma entidade única.</p>
            
            <div class="space-y-4">
                <div>
                    <div class="text-xs font-bold tracking-wider uppercase text-ink/40">E-Redes (Nacional)</div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <a href="tel:800506506" class="text-2xl font-bold font-display text-ink hover:text-ember">800 506 506</a>
                        <span class="text-sm text-ink/60">Grátis 24h</span>
                    </div>
                    <p class="mt-1 text-xs text-ink/60">Para falhas de luz, avarias na rede e situações de risco elétrico.</p>
                </div>
            </div>
        </div>

        <!-- Água -->
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-2 h-8 rounded-full bg-river"></span>
                <h2 class="text-2xl font-display">Água</h2>
            </div>
            <p class="mb-6 text-sm text-ink/70">O abastecimento é gerido localmente. Contacte a empresa do seu município.</p>
            
            <div class="space-y-6">
                <div>
                    <div class="text-xs font-bold tracking-wider uppercase text-ink/40">Lisboa e Vale do Tejo (EPAL)</div>
                    <a href="tel:213251000" class="block mt-1 text-xl font-bold font-display text-ink hover:text-river">213 251 000</a>
                </div>

                <div>
                    <div class="text-xs font-bold tracking-wider uppercase text-ink/40">Porto (Águas do Porto)</div>
                    <a href="tel:225190800" class="block mt-1 text-xl font-bold font-display text-ink hover:text-river">225 190 800</a>
                </div>

                <div>
                    <div class="text-xs font-bold tracking-wider uppercase text-ink/40">Outros Municípios</div>
                    <p class="mt-1 text-sm text-ink/80">Pesquise por "SMAS + [Nome do Concelho]" ou consulte a sua fatura da água para o número de avarias.</p>
                </div>
            </div>
        </div>

        <!-- Emergência -->
        <div class="card bg-sand/30 md:col-span-2">
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 text-xl font-bold text-white bg-red-600 rounded-xl">112</div>
                <div>
                    <h2 class="text-xl font-bold text-ink">Emergência Nacional</h2>
                    <p class="text-sm text-ink/70">Ligue apenas em caso de risco iminente para a vida, incêndio ou saúde.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
