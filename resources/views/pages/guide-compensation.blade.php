@extends('layouts.app')

@section('title', 'Danos por Falha de Energia: Como pedir indemnização? - Faltou')
@section('description', 'Os seus eletrodomésticos avariaram devido a um pico de corrente ou falha de luz? Saiba como pedir indemnização à E-Redes e quais os seus direitos.')

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "Como pedir indemnização por danos elétricos?",
  "description": "Guia passo-a-passo para reclamar danos causados por falhas de energia em Portugal.",
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
    "@@id": "{{ url('/guia-indemnizacao') }}"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [{
    "@@type": "Question",
    "name": "Quem é responsável pelos danos?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "A responsabilidade é geralmente do operador da rede de distribuição (E-Redes), exceto em casos de força maior devidamente justificados."
    }
  }, {
    "@@type": "Question",
    "name": "Qual o prazo para apresentar reclamação?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "Deve apresentar a reclamação por escrito o mais breve possível. O Regulamento de Qualidade de Serviço estabelece prazos específicos, mas recomenda-se fazê-lo nos primeiros 60 dias após o incidente."
    }
  }]
}
</script>
@endpush

@section('content')
<section class="max-w-4xl px-6 py-12 mx-auto">
    <div class="mb-10">
        <a href="{{ url('/') }}" class="text-sm font-medium hover:underline text-ink/60">&larr; Voltar ao início</a>
        <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-5xl">Danos por falha de energia:<br>Como pedir indemnização?</h1>
        <p class="mt-4 text-xl text-ink/70">Se a falta de luz ou um pico de tensão estragou os seus equipamentos, você tem direitos. Saiba como ativá-los.</p>
    </div>

    <div class="grid gap-10 md:grid-cols-[1fr_300px]">
        
        <div class="space-y-8 text-lg leading-relaxed text-ink/80">
            
            <div class="p-6 rounded-2xl bg-amber-50 border border-amber-100">
                <h3 class="font-semibold text-amber-900">Nota Importante</h3>
                <p class="mt-2 text-base text-amber-800">Este guia aplica-se a consumidores em Portugal Continental ligados à rede de baixa tensão (domésticos). O operador responsável pela distribuição é a <strong>E-Redes</strong> (antiga EDP Distribuição).</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">1. Registe a Ocorrência</h2>
                <p>Assim que detetar a avaria, contacte a linha de avarias (800 506 506) para que fique registado que houve um problema na sua zona. Anote a data e hora exata da falha.</p>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">2. Reúna Provas</h2>
                <p>Para o processo de indemnização, vai precisar de:</p>
                <ul class="mt-2 space-y-2 list-disc list-inside">
                    <li><strong>Fotografias</strong> aos equipamentos danificados e alimentos estragados (no frigorífico/arca).</li>
                    <li><strong>Relatório Técnico</strong> de um reparador credenciado a confirmar que a avaria foi causada por "sobretensão" ou "problema na rede elétrica".</li>
                    <li><strong>Orçamento de Reparação</strong> ou fatura da reparação.</li>
                    <li><strong>Lista dos alimentos</strong> deteriorados com estimativa de valor (guarde talões se tiver).</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">3. Apresente a Reclamação</h2>
                <p>Pode fazer o pedido diretamente no site da E-Redes através do formulário "Danos em Equipamentos".</p>
                <p class="mt-2">Deve incluir:</p>
                <ul class="mt-2 space-y-2 list-disc list-inside">
                    <li>Código do Ponto de Entrega (CPE) - encontra na sua fatura da luz.</li>
                    <li>Data e hora do incidente.</li>
                    <li>Descrição dos danos e provas recolhidas.</li>
                </ul>
                <div class="mt-6">
                    <a href="https://www.e-redes.pt/pt-pt/podemos-ajudar/pedidos-de-indemnizacao" target="_blank" rel="noopener noreferrer" class="btn btn-primary">Formulário E-Redes &rarr;</a>
                </div>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">4. Prazos de Resposta</h2>
                <p>A E-Redes tem, por norma, 15 dias úteis para responder à sua reclamação. Se a resposta for favorável, o pagamento é processado via transferência bancária.</p>
            </div>

        </div>

        <aside class="space-y-6">
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Direitos do Consumidor</h3>
                <p class="mt-2 text-sm text-ink/60">A qualidade de serviço é regulada pela ERSE. Se não concordar com a decisão da E-Redes, pode recorrer ao Livro de Reclamações ou à ERSE.</p>
            </div>
            
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Outros Guias</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li>
                        <a href="{{ url('/guia-falta-eletricidade') }}" class="text-river hover:underline">O que fazer sem luz?</a>
                    </li>
                    <li>
                        <a href="{{ url('/guia-kit-emergencia') }}" class="text-river hover:underline">Kit de Emergência</a>
                    </li>
                </ul>
            </div>
        </aside>

    </div>
</section>
@endsection
