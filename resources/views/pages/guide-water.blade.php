@extends('layouts.app')

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

@section('content')
<section class="max-w-4xl px-6 py-12 mx-auto">
    <div class="mb-10">
        <a href="{{ url('/') }}" class="text-sm font-medium hover:underline text-ink/60">&larr; Voltar ao início</a>
        <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-5xl">O que fazer quando falta a água?</h1>
        <p class="mt-4 text-xl text-ink/70">Dicas essenciais para lidar com cortes de abastecimento.</p>
    </div>

    <div class="grid gap-10 md:grid-cols-[1fr_300px]">
        
        <div class="space-y-8 text-lg leading-relaxed text-ink/80">
            
            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">1. Identifique a causa</h2>
                <p>Verifique se tem alguma torneira de segurança fechada em casa (geralmente junto ao contador). Se não for o caso, veja se os vizinhos têm o mesmo problema ou se há obras na rua.</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">2. Contacte a sua autarquia ou SMAS</h2>
                <p>O abastecimento de água é geralmente gerido pelos serviços municipalizados (SMAS) ou pela câmara municipal. Eles saberão informar se é uma rotura conhecida.</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">3. Avise os vizinhos</h2>
                <p>Um corte de água afeta a higiene e a alimentação de todos. Reporte no <strong>Faltou</strong> para que outros saibam que não é um problema isolado da canalização deles.</p>
                <div class="mt-4">
                    <a href="{{ url('/falta-agua') }}" class="btn btn-secondary">Reportar falta de água</a>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-river/10">
                <h3 class="font-semibold text-ink">Quando a água voltar</h3>
                <p class="mt-2 text-base">É normal que a água saia com ar (esbranquiçada) ou com sedimentos (acastanhada) após um corte. <strong>Deixe a água correr</strong> durante alguns minutos na torneira mais próxima do contador até ficar transparente antes de a usar para beber ou cozinhar.</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">Links Úteis</h2>
                <ul class="space-y-2 text-base">
                    <li><a href="https://www.epal.pt/" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">EPAL (Lisboa e Vale do Tejo)</a></li>
                    <li><a href="https://www.aguasdeportugal.pt/" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">Águas de Portugal</a></li>
                    <li><a href="https://www.ipma.pt/" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">IPMA</a> - Para verificar situações de seca ou avisos meteorológicos.</li>
                </ul>
            </div>

        </div>

        <aside class="space-y-6">
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Reportar Agora</h3>
                <p class="mt-2 text-sm text-ink/60">Ajude a comunidade localizando a falha.</p>
                <a href="{{ url('/falta-agua') }}" class="mt-4 w-full btn btn-secondary">Falta água</a>
            </div>
            
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Dica Rápida</h3>
                <p class="mt-2 text-sm text-ink/80">Mantenha sempre alguns garrafões de água potável em casa para emergências.</p>
            </div>
        </aside>

    </div>
</section>
@endsection
