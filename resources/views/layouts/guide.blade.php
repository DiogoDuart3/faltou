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
    <section class="border-b border-line bg-white">
        <div class="container-page max-w-5xl pt-4 pb-6 sm:pt-6 sm:pb-10">
            <nav class="flex items-center gap-1 text-sm text-muted" aria-label="Navegação estrutural">
                <a href="{{ url('/') }}" class="inline-flex min-h-11 items-center hover:text-ink">Início</a>
                <x-icon name="chevron-right" :size="14" />
                <span>@yield('guide-section', 'Guias')</span>
            </nav>
            <div class="mt-2 flex items-start gap-3 sm:mt-4 sm:gap-4">
                <span class="icon-tile {{ $guideTone ? 'icon-tile-' . $guideTone : '' }} sm:!h-12 sm:!w-12">
                    <x-icon :name="trim($__env->yieldContent('guide-icon', 'book'))" :size="22" />
                </span>
                <div class="min-w-0">
                    <h1 class="max-w-3xl text-[1.75rem] leading-tight font-semibold tracking-tight sm:text-4xl">
                        @yield('guide-heading')
                    </h1>
                    <p class="mt-2 max-w-2xl text-base text-muted sm:text-lg">@yield('guide-lead')</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container-page max-w-5xl py-6 sm:py-10">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_300px] lg:gap-10">
            <article class="prose-guide min-w-0">
                @yield('guide-body')
            </article>

            <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                @yield('guide-aside')
            </aside>
        </div>
    </section>

    <section class="border-t border-line bg-white">
        <div class="container-page max-w-5xl py-8 sm:py-10">
            <h2 class="text-lg font-semibold">Continue a ler</h2>
            <div class="mt-3 grid gap-2 sm:grid-cols-2 sm:gap-3">
                @foreach (array_slice($otherGuides, 0, 4) as $guide)
                    <a href="{{ url($guide['url']) }}" class="link-row">
                        <span class="flex items-center gap-3">
                            <span class="icon-tile !h-9 !w-9 {{ $guide['tone'] ? 'icon-tile-' . $guide['tone'] : '' }}">
                                <x-icon :name="$guide['icon']" :size="18" />
                            </span>
                            {{ $guide['title'] }}
                        </span>
                        <x-icon name="chevron-right" :size="16" class="shrink-0 text-muted" />
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
