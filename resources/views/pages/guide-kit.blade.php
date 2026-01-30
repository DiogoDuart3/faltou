@extends('layouts.app')

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

@section('content')
<section class="max-w-4xl px-6 py-12 mx-auto">
    <div class="mb-10">
        <a href="{{ url('/') }}" class="text-sm font-medium hover:underline text-ink/60">&larr; Voltar ao início</a>
        <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-5xl">Kit de Emergência: O que ter em casa?</h1>
        <p class="mt-4 text-xl text-ink/70">Prepare-se para o inesperado com esta lista simples de itens essenciais.</p>
    </div>

    <div class="grid gap-10 md:grid-cols-[1fr_300px]">
        
        <div class="space-y-8 text-lg leading-relaxed text-ink/80">
            
            <div class="p-6 rounded-2xl bg-sand/30">
                <h2 class="mb-4 text-2xl font-semibold text-ink">Básico Indispensável</h2>
                <ul class="space-y-3 list-disc list-inside">
                    <li><strong>Água Potável:</strong> Pelo menos 2-3 garrafões de 5L guardados num local fresco e escuro.</li>
                    <li><strong>Lanternas e Pilhas:</strong> Uma lanterna potente e pilhas extra. A lanterna do telemóvel gasta muita bateria.</li>
                    <li><strong>Power Bank:</strong> Carregado, para manter o telemóvel ligado e comunicar.</li>
                    <li><strong>Rádio a Pilhas:</strong> Para ouvir as notícias se a internet e a TV falharem.</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">Para Falhas de Eletricidade</h2>
                <p class="mb-4">Quando a luz falta, o conforto térmico e a conservação de alimentos são as prioridades.</p>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Mantas ou sacos cama extra (no inverno).</li>
                    <li>Alimentos que não precisem de cozinhar (conservas, bolachas, frutos secos).</li>
                    <li>Caixa de primeiros socorros básica.</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">Para Falhas de Água</h2>
                <p class="mb-4">A higiene torna-se complicada. Tenha sempre:</p>
                <ul class="space-y-2 list-disc list-inside">
                    <li>Toalhitas húmidas e desinfetante de mãos (álcool gel).</li>
                    <li>Baldes vazios que possa encher se houver aviso prévio.</li>
                    <li>Pratos e talheres descartáveis (para não ter de lavar louça).</li>
                </ul>
            </div>

            <div>
                <h2 class="mb-4 text-2xl font-semibold text-ink">Documentos Importantes</h2>
                <p>Tenha cópias dos seus documentos de identificação e apólices de seguro (casa/saúde) numa pasta acessível ou digitalizados no telemóvel.</p>
            </div>

        </div>

        <aside class="space-y-6">
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Checklist Rápida</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-ink/20 text-ember focus:ring-ember">
                        <span>Água (3 dias)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-ink/20 text-ember focus:ring-ember">
                        <span>Lanterna + Pilhas</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-ink/20 text-ember focus:ring-ember">
                        <span>Power Bank</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-ink/20 text-ember focus:ring-ember">
                        <span>Conservas</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-ink/20 text-ember focus:ring-ember">
                        <span>Kit 1º Socorros</span>
                    </label>
                </div>
                <p class="mt-4 text-xs italic text-ink/60">Esta lista é interativa apenas para sua verificação visual.</p>
            </div>
            
            <div class="card bg-white/50">
                <h3 class="font-display text-lg">Links Úteis</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    <li>
                        <a href="https://prociv.gov.pt/" target="_blank" rel="noopener noreferrer" class="text-river hover:underline">Proteção Civil</a>
                    </li>
                    <li>
                        <a href="{{ url('/guia-falta-eletricidade') }}" class="text-river hover:underline">Guia Falta de Luz</a>
                    </li>
                    <li>
                        <a href="{{ url('/guia-falta-agua') }}" class="text-river hover:underline">Guia Falta de Água</a>
                    </li>
                </ul>
            </div>
        </aside>

    </div>
</section>
@endsection
