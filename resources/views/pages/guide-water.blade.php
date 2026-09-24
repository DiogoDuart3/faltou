@extends('layouts.guide')

@section('title', 'O que fazer quando falta a água? Guia prático - Faltou')
@section('description', 'Saiba o que fazer em caso de corte de água. Dicas de higiene, como verificar avisos da companhia e reportar à comunidade.')

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "O que fazer quando falta a água?",
  "description": "Guia prático para lidar com cortes de água inesperados em Portugal.",
  "author": {
    "@@type": "Organization",
    "name": "Faltou"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Faltou",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('images/icon.png') }}"
    }
  },
  "datePublished": "2024-01-30",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ url('/guia-falta-agua') }}"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [{
    "@@type": "Question",
    "name": "Porque é que falta a água?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "Pode ser uma rotura na via pública, obras programadas ou falta de pagamento. Verifique se recebeu algum aviso prévio ou se há obras na sua rua."
    }
  }, {
    "@@type": "Question",
    "name": "Posso beber a primeira água que sai depois do corte?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "Não. Quando a água volta, pode vir turva (branca ou castanha). Deixe correr a torneira por alguns minutos até sair límpida antes de consumir."
    }
  }]
}
</script>
@endpush

@section('guide-icon', 'droplet')
@section('guide-tone', 'river')
@section('guide-heading', 'O que fazer quando falta a água?')
@section('guide-lead', 'Dicas essenciais para lidar com cortes de abastecimento.')

@section('guide-body')
    <div class="guide-step">
        <span class="guide-step-number">1</span>
        <div>
            <h2>Identifique a causa</h2>
            <p>Verifique se tem alguma torneira de segurança fechada em casa (geralmente junto ao contador). Se não for o caso, veja se os vizinhos têm o mesmo problema ou se há obras na rua.</p>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">2</span>
        <div>
            <h2>Contacte a sua autarquia ou SMAS</h2>
            <p>O abastecimento de água é geralmente gerido pelos serviços municipalizados (SMAS) ou pela câmara municipal. Eles saberão informar se é uma rotura conhecida.</p>
            <a href="{{ url('/contactos') }}" class="btn btn-secondary mt-5">
                <x-icon name="phone" :size="16" /> Ver contactos de água
            </a>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">3</span>
        <div>
            <h2>Avise os vizinhos</h2>
            <p>Um corte de água afeta a higiene e a alimentação de todos. Reporte no <strong>Faltou</strong> para que outros saibam que não é um problema isolado da canalização deles.</p>
            <a href="{{ url('/falta-agua') }}" class="btn btn-water mt-5">
                <x-icon name="droplet" :size="16" /> Reportar falta de água
            </a>
        </div>
    </div>

    <div class="callout callout-river">
        <h3 class="flex items-center gap-2"><x-icon name="info" :size="18" /> Quando a água voltar</h3>
        <p>É normal que a água saia com ar (esbranquiçada) ou com sedimentos (acastanhada) após um corte. <strong>Deixe a água correr</strong> durante alguns minutos na torneira mais próxima do contador até ficar transparente antes de a usar para beber ou cozinhar.</p>
    </div>

    <div>
        <h2>Links úteis</h2>
        <div class="mt-4 grid gap-2 text-base">
            <a href="https://www.epal.pt/" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">EPAL</span><span class="block text-sm font-normal text-muted">Lisboa e Vale do Tejo.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
            <a href="https://www.aguasdeportugal.pt/" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">Águas de Portugal</span><span class="block text-sm font-normal text-muted">Grupo público de abastecimento de água.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
            <a href="https://www.ipma.pt/" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">IPMA</span><span class="block text-sm font-normal text-muted">Para verificar situações de seca ou avisos meteorológicos.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
        </div>
    </div>
@endsection

@section('guide-aside')
    <div class="card">
        <div class="card-title">Reportar agora</div>
        <p class="card-subtitle">Ajude a comunidade localizando a falha.</p>
        <a href="{{ url('/falta-agua') }}" class="btn btn-water mt-4 w-full">
            <x-icon name="droplet" :size="16" /> Falta água
        </a>
    </div>
    <div class="callout callout-sand !text-sm">
        <div class="font-semibold text-ink">Dica rápida</div>
        <p class="mt-1 text-ink/80">Mantenha sempre alguns garrafões de água potável em casa para emergências.</p>
    </div>
@endsection
