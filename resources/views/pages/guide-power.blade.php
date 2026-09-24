@extends('layouts.guide')

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

@section('guide-icon', 'zap')
@section('guide-tone', 'ember')
@section('guide-heading', 'O que fazer quando falta a luz?')
@section('guide-lead', 'Um guia rápido para manter a segurança e reportar avarias.')

@section('guide-body')
    <div class="guide-step">
        <span class="guide-step-number">1</span>
        <div>
            <h2>Verifique a sua casa</h2>
            <p>Antes de assumir que é uma avaria geral, vá ao seu quadro elétrico. Se os disjuntores estiverem para baixo, tente ligá-los. Se voltarem a disparar, desligue alguns eletrodomésticos e tente novamente. Pode ter demasiados aparelhos ligados.</p>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">2</span>
        <div>
            <h2>Olhe lá para fora</h2>
            <p>Se o quadro está ligado mas não tem luz, veja se a iluminação pública ou os vizinhos têm eletricidade. Se estiver tudo às escuras, é provável que seja uma avaria na rede de distribuição.</p>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">3</span>
        <div>
            <h2>Reporte à comunidade</h2>
            <p>Use o <strong>Faltou</strong> para avisar os seus vizinhos. Mesmo que não saiba a causa, marcar a localização ajuda outros a perceberem a dimensão do problema.</p>
            <a href="{{ url('/falta-eletricidade') }}" class="btn btn-power mt-5">
                <x-icon name="zap" :size="16" /> Reportar falha de luz
            </a>
        </div>
    </div>

    <div class="callout callout-sand">
        <h3 class="flex items-center gap-2"><x-icon name="alert" :size="18" /> Dicas de segurança</h3>
        <ul>
            <li>Não abra o frigorífico desnecessariamente para manter o frio.</li>
            <li>Desligue aparelhos sensíveis (computadores, televisões) das tomadas para evitar danos quando a energia voltar (picos de tensão).</li>
            <li>Use lanternas em vez de velas para evitar risco de incêndio.</li>
        </ul>
    </div>

    <div>
        <h2>Links úteis</h2>
        <div class="mt-4 grid gap-2 text-base">
            <a href="https://www.e-redes.pt/pt-pt/interrupcoes-de-energia" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">E-Redes: Reportar Avarias</span><span class="block text-sm font-normal text-muted">Página oficial do operador de rede.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
            <a href="https://www.ipma.pt/" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">IPMA</span><span class="block text-sm font-normal text-muted">Verifique se existem avisos meteorológicos na sua zona.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
            <a href="https://andredotempo.pt/" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">André do Tempo</span><span class="block text-sm font-normal text-muted">Acompanhamento meteorológico não oficial popular.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
            <a href="https://www.facebook.com/andredotempo/?locale=pt_PT" target="_blank" rel="noopener noreferrer" class="link-row">
                <span><span class="block">André do Tempo (Facebook)</span><span class="block text-sm font-normal text-muted">Atualizações nas redes sociais.</span></span>
                <x-icon name="external" :size="16" class="shrink-0 text-muted" />
            </a>
        </div>
    </div>
@endsection

@section('guide-aside')
    <div class="card">
        <div class="card-title">Reportar agora</div>
        <p class="card-subtitle">Ajude a comunidade localizando a falha.</p>
        <a href="{{ url('/falta-eletricidade') }}" class="btn btn-power mt-4 w-full">
            <x-icon name="zap" :size="16" /> Falta luz
        </a>
    </div>
    <div class="card">
        <div class="card-title">Contactos de emergência</div>
        <div class="mt-4 space-y-3">
            <a href="tel:800506506" class="flex items-center justify-between gap-3 rounded-xl bg-porcelain px-3 py-2.5 hover:bg-stone">
                <span class="text-sm"><span class="block font-medium">Avarias Elétricas (E-Redes)</span><span class="text-muted">800 506 506</span></span>
                <x-icon name="phone" :size="16" class="text-muted" />
            </a>
            <a href="tel:112" class="flex items-center justify-between gap-3 rounded-xl bg-porcelain px-3 py-2.5 hover:bg-stone">
                <span class="text-sm"><span class="block font-medium">Emergência Nacional</span><span class="text-muted">112</span></span>
                <x-icon name="phone" :size="16" class="text-muted" />
            </a>
        </div>
    </div>
@endsection
