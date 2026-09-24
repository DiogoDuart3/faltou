@extends('layouts.guide')

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

@section('guide-icon', 'scale')
@section('guide-heading', 'Danos por falha de energia: como pedir indemnização?')
@section('guide-lead', 'Se a falta de luz ou um pico de tensão estragou os seus equipamentos, você tem direitos. Saiba como ativá-los.')

@section('guide-body')
    <div class="callout callout-sand">
        <h3 class="flex items-center gap-2"><x-icon name="info" :size="18" /> Nota importante</h3>
        <p>Este guia aplica-se a consumidores em Portugal Continental ligados à rede de baixa tensão (domésticos). O operador responsável pela distribuição é a <strong>E-Redes</strong> (antiga EDP Distribuição).</p>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">1</span>
        <div>
            <h2>Registe a ocorrência</h2>
            <p>Assim que detetar a avaria, contacte a linha de avarias (<a href="tel:800506506">800 506 506</a>) para que fique registado que houve um problema na sua zona. Anote a data e hora exata da falha.</p>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">2</span>
        <div>
            <h2>Reúna provas</h2>
            <p>Para o processo de indemnização, vai precisar de:</p>
            <ul>
                <li><strong>Fotografias</strong> aos equipamentos danificados e alimentos estragados (no frigorífico/arca).</li>
                <li><strong>Relatório Técnico</strong> de um reparador credenciado a confirmar que a avaria foi causada por "sobretensão" ou "problema na rede elétrica".</li>
                <li><strong>Orçamento de Reparação</strong> ou fatura da reparação.</li>
                <li><strong>Lista dos alimentos</strong> deteriorados com estimativa de valor (guarde talões se tiver).</li>
            </ul>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">3</span>
        <div>
            <h2>Apresente a reclamação</h2>
            <p>Pode fazer o pedido diretamente no site da E-Redes através do formulário "Danos em Equipamentos". Deve incluir:</p>
            <ul>
                <li>Código do Ponto de Entrega (CPE) - encontra na sua fatura da luz.</li>
                <li>Data e hora do incidente.</li>
                <li>Descrição dos danos e provas recolhidas.</li>
            </ul>
            <a href="https://balcaodigital.e-redes.pt/requests/connection/losses" target="_blank" rel="noopener noreferrer" class="btn btn-primary mt-5">
                Formulário E-Redes <x-icon name="external" :size="16" />
            </a>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number">4</span>
        <div>
            <h2>Prazos de resposta</h2>
            <p>A E-Redes tem, por norma, 15 dias úteis para responder à sua reclamação. Se a resposta for favorável, o pagamento é processado via transferência bancária.</p>
        </div>
    </div>
@endsection

@section('guide-aside')
    <div class="card">
        <div class="card-title">Checklist do processo</div>
        <ul class="mt-3 space-y-2.5 text-sm">
            @foreach (['Data e hora da falha', 'Fotografias dos danos', 'Relatório técnico', 'Orçamento ou fatura', 'Código CPE (fatura da luz)'] as $item)
                <li class="flex items-start gap-2.5"><x-icon name="check-circle" :size="16" class="mt-0.5 shrink-0 text-success" /> {{ $item }}</li>
            @endforeach
        </ul>
    </div>
    <div class="card">
        <div class="card-title">Direitos do consumidor</div>
        <p class="mt-2 text-sm text-muted">A qualidade de serviço é regulada pela ERSE. Se não concordar com a decisão da E-Redes, pode recorrer ao Livro de Reclamações ou à ERSE.</p>
    </div>
@endsection
