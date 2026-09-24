@extends('layouts.guide')

@section('title', 'Kit de Emergência: O que ter em casa? - Faltou')
@section('description', 'Lista essencial de itens para ter em casa em caso de falha de luz ou água. Lanternas, água, power banks e recomendações da Proteção Civil.')

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Article",
  "headline": "Kit de Emergência: O que ter em casa?",
  "description": "Lista de itens essenciais para sobreviver confortavelmente a falhas de serviços.",
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
    "@@id": "{{ url('/guia-kit-emergencia') }}"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [{
    "@@type": "Question",
    "name": "Quantos litros de água devo ter armazenados?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "A recomendação geral é de pelo menos 3 dias de autossuficiência. Calcule cerca de 2 a 3 litros de água potável por pessoa por dia."
    }
  }, {
    "@@type": "Question",
    "name": "Que tipo de lanterna é melhor?",
    "acceptedAnswer": {
      "@@type": "Answer",
      "text": "Prefira lanternas a LED (gastam menos pilhas) ou a dínamo/manivela. Evite usar velas devido ao risco de incêndio."
    }
  }]
}
</script>
@endpush

@section('guide-icon', 'package')
@section('guide-tone', 'sand')
@section('guide-heading', 'Kit de Emergência: O que ter em casa?')
@section('guide-lead', 'Prepare-se para o inesperado com esta lista simples de itens essenciais.')

@section('guide-body')
    <div class="callout callout-sand">
        <h2 class="!text-xl">Básico indispensável</h2>
        <ul>
            <li><strong>Água Potável:</strong> Pelo menos 2-3 garrafões de 5L guardados num local fresco e escuro.</li>
            <li><strong>Lanternas e Pilhas:</strong> Uma lanterna potente e pilhas extra. A lanterna do telemóvel gasta muita bateria.</li>
            <li><strong>Power Bank:</strong> Carregado, para manter o telemóvel ligado e comunicar.</li>
            <li><strong>Rádio a Pilhas:</strong> Para ouvir as notícias se a internet e a TV falharem.</li>
        </ul>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div class="card-flat">
            <span class="icon-tile icon-tile-ember"><x-icon name="zap" :size="18" /></span>
            <h2 class="mt-4 !text-lg">Para falhas de eletricidade</h2>
            <p class="text-base">Quando a luz falta, o conforto térmico e a conservação de alimentos são as prioridades.</p>
            <ul class="text-base">
                <li>Mantas ou sacos cama extra (no inverno).</li>
                <li>Alimentos que não precisem de cozinhar (conservas, bolachas, frutos secos).</li>
                <li>Caixa de primeiros socorros básica.</li>
            </ul>
        </div>
        <div class="card-flat">
            <span class="icon-tile icon-tile-river"><x-icon name="droplet" :size="18" /></span>
            <h2 class="mt-4 !text-lg">Para falhas de água</h2>
            <p class="text-base">A higiene torna-se complicada. Tenha sempre:</p>
            <ul class="text-base">
                <li>Toalhitas húmidas e desinfetante de mãos (álcool gel).</li>
                <li>Baldes vazios que possa encher se houver aviso prévio.</li>
                <li>Pratos e talheres descartáveis (para não ter de lavar louça).</li>
            </ul>
        </div>
    </div>

    <div class="guide-step">
        <span class="guide-step-number"><x-icon name="file" :size="16" /></span>
        <div>
            <h2>Documentos importantes</h2>
            <p>Tenha cópias dos seus documentos de identificação e apólices de seguro (casa/saúde) numa pasta acessível ou digitalizados no telemóvel.</p>
        </div>
    </div>
@endsection

@section('guide-aside')
    <div class="card" data-checklist>
        <div class="card-header">
            <div class="card-title">Checklist rápida</div>
            <span class="field-hint"><span data-checklist-count>0</span>/5</span>
        </div>
        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-stone">
            <div class="h-full w-0 rounded-full bg-success transition-all" data-checklist-bar></div>
        </div>
        <div class="mt-4 space-y-1">
            @foreach (['Água (3 dias)', 'Lanterna + Pilhas', 'Power Bank', 'Conservas', 'Kit 1º Socorros'] as $i => $item)
                <label class="flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm hover:bg-porcelain">
                    <input type="checkbox" class="h-4 w-4 rounded border-line accent-ink" data-checklist-item="{{ $i }}">
                    <span>{{ $item }}</span>
                </label>
            @endforeach
        </div>
        <p class="mt-3 text-xs text-muted">Guardada apenas neste dispositivo.</p>
    </div>
    <div class="card">
        <div class="card-title">Links úteis</div>
        <div class="mt-3 space-y-1 text-sm">
            <a href="https://prociv.gov.pt/" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-lg px-2 py-2 font-medium hover:bg-porcelain">
                Proteção Civil <x-icon name="external" :size="14" class="text-muted" />
            </a>
            <a href="{{ url('/guia-falta-eletricidade') }}" class="flex items-center justify-between rounded-lg px-2 py-2 font-medium hover:bg-porcelain">
                Guia Falta de Luz <x-icon name="arrow-right" :size="14" class="text-muted" />
            </a>
            <a href="{{ url('/guia-falta-agua') }}" class="flex items-center justify-between rounded-lg px-2 py-2 font-medium hover:bg-porcelain">
                Guia Falta de Água <x-icon name="arrow-right" :size="14" class="text-muted" />
            </a>
        </div>
    </div>
@endsection
