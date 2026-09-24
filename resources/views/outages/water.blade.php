@extends('layouts.app')

@section('title', 'Falta de água - Faltou Portugal')
@section('description', 'Relate falhas de água com localização rápida e comentários da comunidade.')
@section('hide-mobile-cta', '1')

@include('partials.leaflet')

@section('content')
    @include('outages._page', [
        'type' => 'water',
        'tone' => 'river',
        'icon' => 'droplet',
        'label' => 'Falta de água',
        'title' => 'Reportar falta de água',
        'lead' => 'Os avisos ficam públicos durante 24 horas. Indique a localização e, se quiser, deixe uma nota curta.',
        'impacts' => [
            'residencial' => 'Residencial',
            'comercial' => 'Comercial',
            'rua' => 'Zona pública',
            'outros' => 'Outros',
        ],
        'notePlaceholder' => 'Ex: Sem água desde as 09h.',
        'commentPlaceholder' => 'Ex: A água voltou às 11h.',
        'mapCaption' => 'Falhas de água nas últimas 24 horas.',
        'contactTitle' => 'Avarias de água',
        'contactText' => 'Contacte os serviços de água do seu município (SMAS).',
        'contactPhone' => null,
        'guideUrl' => '/guia-falta-agua',
    ])
@endsection
