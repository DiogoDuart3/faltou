@extends('layouts.app')

@section('title', 'Falta de eletricidade - Faltou Portugal')
@section('description', 'Partilhe rapidamente um aviso de falta de eletricidade na sua localidade.')
@section('hide-mobile-cta', '1')

@include('partials.leaflet')

@section('content')
    @include('outages._page', [
        'type' => 'power',
        'tone' => 'ember',
        'icon' => 'zap',
        'label' => 'Falta de eletricidade',
        'title' => 'Reportar falta de luz',
        'lead' => 'Os avisos ficam públicos durante 24 horas. Indique a localização e, se quiser, deixe uma nota curta.',
        'impacts' => [
            'residencial' => 'Residencial',
            'comercial' => 'Comercial',
            'rua' => 'Iluminação pública',
            'outros' => 'Outros',
        ],
        'notePlaceholder' => 'Ex: Rua sem luz desde as 14h.',
        'commentPlaceholder' => 'Ex: A energia voltou às 16h.',
        'mapCaption' => 'Falhas de eletricidade nas últimas 24 horas.',
        'contactTitle' => 'Avarias elétricas (E-Redes)',
        'contactText' => 'Linha gratuita, disponível 24 horas.',
        'contactPhone' => '800 506 506',
        'guideUrl' => '/guia-falta-eletricidade',
    ])
@endsection
