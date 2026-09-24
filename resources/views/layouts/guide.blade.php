@extends('layouts.app')

@php
    $allGuides = [
        ['url' => '/guia-falta-eletricidade', 'title' => 'Sem luz: o que fazer', 'icon' => 'zap', 'tone' => 'ember'],
        ['url' => '/guia-falta-agua', 'title' => 'Sem água: o que fazer', 'icon' => 'droplet', 'tone' => 'river'],
        ['url' => '/guia-kit-emergencia', 'title' => 'Kit de emergência', 'icon' => 'package', 'tone' => 'sand'],
        ['url' => '/guia-indemnizacao', 'title' => 'Pedir indemnização', 'icon' => 'scale', 'tone' => ''],
        ['url' => '/contactos', 'title' => 'Contactos de avaria', 'icon' => 'phone', 'tone' => ''],
    ];
    $currentPath = '/' . ltrim(request()->path(), '/');
    $otherGuides = array_values(array_filter($allGuides, fn ($g) => $g['url'] !== $currentPath));
    $guideTone = trim($__env->yieldContent('guide-tone'));
@endphp

@section('content')
    <section class="relative overflow-hidden border-b border-line bg-white">
        <div class="bg-grid mask-fade-b pointer-events-none absolute inset-0 opacity-60"></div>
        <div class="container-page relative max-w-5xl py-10 sm:py-14">
            <nav class="flex items-center gap-1.5 text-sm text-muted" aria-label="Navegação estrutural">
                <a href="{{ url('/') }}" class="hover:text-ink">Início</a>
                <x-icon name="chevron-right" :size="14" />
                <span>@yield('guide-section', 'Guias')</span>
            </nav>
            <span class="icon-tile {{ $guideTone ? 'icon-tile-' . $guideTone : '' }} mt-6 !h-12 !w-12">
                <x-icon :name="trim($__env->yieldContent('guide-icon', 'book'))" :size="24" />
            </span>
            <h1 class="mt-5 max-w-3xl text-3xl font-semibold leading-tight tracking-tight sm:text-5xl">
                @yield('guide-heading')
            </h1>
            <p class="mt-4 max-w-2xl text-lg text-muted">@yield('guide-lead')</p>
        </div>
    </section>

    <section class="container-page max-w-5xl py-10 sm:py-14">
        <div class="grid gap-10 lg:grid-cols-[1fr_300px]">
            <article class="prose-guide min-w-0">
                @yield('guide-body')
            </article>

            <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                @yield('guide-aside')
            </aside>
        </div>
    </section>

    <section class="border-t border-line bg-white">
        <div class="container-page max-w-5xl py-10">
            <div class="eyebrow">Continue a ler</div>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (array_slice($otherGuides, 0, 4) as $guide)
                    <a href="{{ url($guide['url']) }}" class="link-row">
                        <span class="flex items-center gap-3">
                            <span class="icon-tile !h-8 !w-8 {{ $guide['tone'] ? 'icon-tile-' . $guide['tone'] : '' }}">
                                <x-icon :name="$guide['icon']" :size="16" />
                            </span>
                            {{ $guide['title'] }}
                        </span>
                        <x-icon name="arrow-right" :size="14" class="text-muted" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
