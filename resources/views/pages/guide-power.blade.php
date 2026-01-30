@extends('layouts.app')

@section('title', 'O que fazer quando falta a luz? Guia de segurança - Faltou')
@section('description', 'Saiba o que fazer em caso de falha de eletricidade. Check-list de segurança, quem contactar e como reportar a avaria à comunidade.')

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "O que fazer quando falta a luz?",
  "description": "Guia de segurança e procedimentos para falhas de eletricidade em Portugal.",
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
    "@@id": "{{ url('/guia-falta-eletricidade') }}"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [{
    "@@type": "Question",
    "name": "Quem devo contactar se faltar a luz?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "Se a falha for apenas na sua casa, verifique o quadro elétrico. Se for na rua ou bairro, contacte a E-Redes (antiga EDP Distribuição) através do 800 506 506."
    }
  }, {
    "@@type": "Question",
    "name": "Quanto tempo demora a voltar a luz?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "Depende da avaria. Pode consultar o estado da rede no site da E-Redes ou ver reportes da comunidade aqui na plataforma Faltou."
    }
  }]
}
</script>
@endpush

@section('content')
<section class="max-w-4xl px-6 py-12 mx-auto">
    <div class="mb-10">
        <a href="{{ url('/') }}" class="text-sm font-medium hover:underline text-ink/60">&larr; Voltar ao início</a>
        <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-5xl">O que fazer quando falta a luz?</h1>
        <p class="mt-4 text-xl text-ink/70">Um guia rápido para manter a segurança e reportar avarias.</p>
    </div>

    <div class="grid gap-10 md:grid-cols-[1fr_300px]">
        
        <div class="space-y-8 text-lg leading-relaxed text-ink/80">
            
            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">1. Verifique a sua casa</h2>
                <p>Antes de assumir que é uma avaria geral, vá ao seu quadro elétrico. Se os disjuntores estiverem para baixo, tente ligá-los. Se voltarem a disparar, desligue alguns eletrodomésticos e tente novamente. Pode ter demasiados aparelhos ligados.</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">2. Olhe lá para fora</h2>
                <p>Se o quadro está ligado mas não tem luz, veja se a iluminação pública ou os vizinhos têm eletricidade. Se estiver tudo às escuras, é provável que seja uma avaria na rede de distribuição.</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">3. Reporte à comunidade</h2>
                <p>Use o <strong>Faltou</strong> para avisar os seus vizinhos. Mesmo que não saiba a causa, marcar a localização ajuda outros a perceberem a dimensão do problema.</p>
                <div class="mt-4">
                    <a href="{{ url('/falta-eletricidade') }}" class="btn btn-primary">Reportar falha de luz</a>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-sand/30">
                <h3 class="font-semibold text-ink">Dicas de Segurança</h3>
                <ul class="mt-2 space-y-2 list-disc list-inside">
                    <li>Não abra o frigorífico desnecessariamente para manter o frio.</li>
                    <li>Desligue aparelhos sensíveis (computadores, televisões) das tomadas para evitar danos quando a energia voltar (picos de tensão).</li>
                    <li>Use lanternas em vez de velas para evitar risco de incêndio.</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">Links Úteis</h2>
                <ul class="space-y-2 text-base">
                    <li><a href="https://www.e-redes.pt/pt-pt/interrupcoes-de-energia" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">E-Redes: Reportar Avarias</a> - Página oficial do operador de rede.</li>
                    <li><a href="https://www.ipma.pt/" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">IPMA</a> - Verifique se existem avisos meteorológicos na sua zona.</li>
                    <li><a href="https://www.facebook.com/andredotempo/?locale=pt_PT" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">André do Tempo (Facebook)</a> e <a href="https://andredotempo.pt/" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">Site Oficial</a> - Acompanhamento meteorológico não oficial popular.</li>
                </ul>
            </div>

        </div>

        <aside class="space-y-6">
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Reportar Agora</h3>
                <p class="mt-2 text-sm text-ink/60">Ajude a comunidade localizando a falha.</p>
                <a href="{{ url('/falta-eletricidade') }}" class="mt-4 w-full btn btn-primary">Falta luz</a>
            </div>
            
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Contactos de Emergência</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li>
                        <div class="font-medium">Avarias Elétricas (E-Redes)</div>
                        <a href="tel:800506506" class="text-river hover:underline">800 506 506</a>
                    </li>
                    <li>
                        <div class="font-medium">Emergência Nacional</div>
                        <a href="tel:112" class="text-river hover:underline">112</a>
                    </li>
                </ul>
            </div>
        </aside>

    </div>
</section>
@endsection
