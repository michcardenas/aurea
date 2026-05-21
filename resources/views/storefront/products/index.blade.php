@extends('layouts.app')

@section('title', 'Catálogo de lentes | Nuvion Glass')
@section('meta_description', 'Catálogo completo de lentes nuvion glass con protección de luz azul. Con y sin graduación. 2×1 combinables. Envío gratis +$999.')
@section('canonical', route('products.index'))
@section('og_title', 'Catálogo de lentes | Nuvion Glass')
@section('og_description', 'Catálogo completo de lentes nuvion glass con protección de luz azul. Con y sin graduación.')
@section('twitter_title', 'Catálogo de lentes | Nuvion Glass')
@section('twitter_description', 'Catálogo completo de lentes nuvion glass con protección de luz azul. Con y sin graduación.')

@push('schema')
    {!! $breadcrumbs !!}
@endpush

@section('content')

    {{-- ============================================================
         HEADER
         ============================================================ --}}
    <section style="background:#fff;padding:48px 24px 32px;border-bottom:1px solid rgba(0,0,0,0.06);">
        <div style="max-width:1200px;margin:0 auto;">
            {{-- Breadcrumb --}}
            <nav style="display:flex;align-items:center;gap:6px;font-size:13px;color:#aaa;margin-bottom:20px;">
                <a href="{{ route('home') }}" style="color:#aaa;text-decoration:none;" onmouseover="this.style.color='#378ADD'" onmouseout="this.style.color='#aaa'">Inicio</a>
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/></svg>
                <span style="color:#666;">Catálogo</span>
            </nav>
            <h1 style="font-family:'Bai Jamjuree',sans-serif;font-size:28px;font-weight:600;color:#1a1a2e;margin:0;">
                {{ $lentesPage->catalog_title ?? 'Catálogo' }}
            </h1>
            <p style="font-size:14px;color:#888;margin-top:6px;">
                {{ $lentesPage->catalog_subtitle ?? 'Todos los lentes con filtro de luz azul · 2×1 combinables' }}
            </p>
        </div>
    </section>

    {{-- ============================================================
         FILTROS STICKY
         ============================================================ --}}
    @php
        $tipos = [
            'todos' => 'Todos',
            'miopia' => 'Miopía',
            'lectura' => 'Lectura',
            'sin_graduacion' => 'Sin Graduación',
            'toallitas' => 'Toallitas',
        ];
        $activeFilterCount = ($tipoFiltro !== 'todos' ? 1 : 0) + ($graduacionFiltro ? 1 : 0) + ($colorFiltro ? 1 : 0);
    @endphp
    <section x-data="{ filtersOpen: true }" style="background:#f8f9fa;border-bottom:1px solid rgba(0,0,0,0.06);position:sticky;top:72px;z-index:10;">

        {{-- Mobile toggle bar (visible ≤640px) --}}
        <div class="filters-mobile-toggle" style="display:none;padding:12px 24px;">
            <div style="max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                    <button @click="filtersOpen = !filtersOpen"
                            style="display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #ddd;
                                   border-radius:8px;padding:8px 14px;font-size:13px;color:#555;cursor:pointer;
                                   font-family:inherit;transition:all .2s;white-space:nowrap;"
                            :style="filtersOpen ? 'border-color:#378ADD;color:#378ADD;background:#EBF4FF' : ''">
                        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/>
                        </svg>
                        Filtros
                        @if($activeFilterCount > 0)
                        <span style="background:#378ADD;color:#fff;font-size:11px;font-weight:600;
                                     width:18px;height:18px;border-radius:50%;display:flex;
                                     align-items:center;justify-content:center;">{{ $activeFilterCount }}</span>
                        @endif
                    </button>

                    {{-- Active filter pills (compact summary) --}}
                    @if($activeFilterCount > 0)
                    <div style="display:flex;align-items:center;gap:6px;overflow-x:auto;flex:1;min-width:0;
                                scrollbar-width:none;-ms-overflow-style:none;">
                        @if($tipoFiltro !== 'todos')
                        <span style="background:#EBF4FF;color:#185FA5;font-size:11px;padding:4px 10px;
                                     border-radius:20px;white-space:nowrap;display:flex;align-items:center;gap:4px;">
                            {{ $tipos[$tipoFiltro] ?? $tipoFiltro }}
                            <span onclick="setFilter('type','todos')" style="cursor:pointer;font-size:13px;line-height:1;">&times;</span>
                        </span>
                        @endif
                        @if($graduacionFiltro)
                        <span style="background:#EBF4FF;color:#185FA5;font-size:11px;padding:4px 10px;
                                     border-radius:20px;white-space:nowrap;display:flex;align-items:center;gap:4px;">
                            {{ $graduacionFiltro }}
                            <span onclick="setFilter('graduation','')" style="cursor:pointer;font-size:13px;line-height:1;">&times;</span>
                        </span>
                        @endif
                        @if($colorFiltro)
                        <span style="background:#EBF4FF;color:#185FA5;font-size:11px;padding:4px 10px;
                                     border-radius:20px;white-space:nowrap;display:flex;align-items:center;gap:4px;">
                            <span style="width:10px;height:10px;border-radius:50%;background:{{ $colorHexMap[$colorFiltro] ?? \App\Helpers\ColorHelper::hex($colorFiltro) }};
                                         display:inline-block;border:1px solid rgba(0,0,0,0.1);"></span>
                            {{ $colorFiltro }}
                            <span onclick="setFilter('color','')" style="cursor:pointer;font-size:13px;line-height:1;">&times;</span>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <span style="font-size:12px;color:#888;white-space:nowrap;margin-left:10px;">
                    {{ $products->count() }} resultado{{ $products->count() !== 1 ? 's' : '' }}
                </span>
            </div>
        </div>

        {{-- Filter content (always visible on desktop, collapsible on mobile) --}}
        <div class="filters-content" :class="{ 'filters-hidden-mobile': !filtersOpen }"
             style="padding:20px 24px;">
            <div style="max-width:1200px;margin:0 auto;">

                {{-- Fila 1: Tipo --}}
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:14px;">
                    <span style="font-size:12px;color:#888;margin-right:4px;">Tipo:</span>
                    @foreach($tipos as $value => $label)
                        @php
                            $isActive = $tipoFiltro === $value;
                        @endphp
                        <button onclick="setFilter('type','{{ $value }}')"
                                style="border:1px solid {{ $isActive ? '#378ADD' : '#ddd' }};
                                       background:{{ $isActive ? '#378ADD' : '#fff' }};
                                       color:{{ $isActive ? '#fff' : '#555' }};
                                       border-radius:20px;padding:5px 14px;font-size:13px;
                                       cursor:pointer;transition:all .2s;font-family:inherit;
                                       white-space:nowrap;"
                                onmouseover="@if(!$isActive)this.style.borderColor='#378ADD';this.style.color='#378ADD'@endif"
                                onmouseout="@if(!$isActive)this.style.borderColor='#ddd';this.style.color='#555'@endif">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                {{-- Fila 2: Graduación --}}
                @if($graduacionesDisponibles->count() > 0)
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
                    <span style="font-size:12px;color:#888;margin-right:4px;">Graduación:</span>
                    <button onclick="setFilter('graduation','')"
                            style="border:1px solid {{ !$graduacionFiltro ? '#378ADD' : '#ddd' }};
                                   background:{{ !$graduacionFiltro ? '#378ADD' : '#fff' }};
                                   color:{{ !$graduacionFiltro ? '#fff' : '#555' }};
                                   border-radius:20px;padding:4px 12px;font-size:12px;
                                   cursor:pointer;transition:all .2s;font-family:inherit;">
                        Todas
                    </button>
                    @foreach($graduacionesDisponibles as $grad)
                        @php
                            $isActiveGrad = $graduacionFiltro === $grad;
                        @endphp
                        <button onclick="setFilter('graduation','{{ $grad }}')"
                                style="border:1px solid {{ $isActiveGrad ? '#378ADD' : '#ddd' }};
                                       background:{{ $isActiveGrad ? '#378ADD' : '#fff' }};
                                       color:{{ $isActiveGrad ? '#fff' : '#555' }};
                                       border-radius:20px;padding:4px 12px;font-size:12px;
                                       cursor:pointer;transition:all .2s;font-family:inherit;">
                            {{ $grad }}
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- Fila 3: Color --}}
                @if($coloresDisponibles->count() > 0)
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;">
                    <span style="font-size:12px;color:#888;margin-right:4px;">Color:</span>
                    {{-- Clear color --}}
                    <button onclick="setFilter('color','')"
                            title="Todos los colores"
                            style="width:24px;height:24px;border-radius:50%;
                                   border:2px solid {{ !$colorFiltro ? '#378ADD' : '#ddd' }};
                                   background:#fff;cursor:pointer;display:flex;
                                   align-items:center;justify-content:center;
                                   font-size:11px;color:#999;transition:all .2s;
                                   {{ !$colorFiltro ? 'box-shadow:0 0 0 2px rgba(55,138,221,0.3);' : '' }}">
                        ✕
                    </button>
                    @foreach($coloresDisponibles as $color)
                        @php
                            $isActiveColor = $colorFiltro === $color;
                            $hex = $colorHexMap[$color] ?? \App\Helpers\ColorHelper::hex($color);
                        @endphp
                        <button onclick="setFilter('color','{{ $color }}')"
                                title="{{ $color }}"
                                style="width:24px;height:24px;border-radius:50%;
                                       background-color:{{ $hex }};cursor:pointer;
                                       border:2px solid {{ $isActiveColor ? '#378ADD' : 'rgba(0,0,0,0.1)' }};
                                       transition:all .2s;
                                       {{ $isActiveColor ? 'box-shadow:0 0 0 2px rgba(55,138,221,0.3);' : '' }}">
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- Conteo (desktop) --}}
                <p class="filters-count-desktop" style="font-size:13px;color:#888;margin-top:12px;">
                    {{ $products->count() }} producto{{ $products->count() !== 1 ? 's' : '' }} encontrado{{ $products->count() !== 1 ? 's' : '' }}
                    @if($tipoFiltro !== 'todos' || $colorFiltro || $graduacionFiltro)
                        <a href="{{ route('products.index') }}" style="color:#378ADD;margin-left:8px;text-decoration:none;font-size:12px;"
                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            Limpiar filtros
                        </a>
                    @endif
                </p>

                {{-- Limpiar filtros link (mobile, inside expanded panel) --}}
                @if($tipoFiltro !== 'todos' || $colorFiltro || $graduacionFiltro)
                <div class="filters-clear-mobile" style="display:none;margin-top:12px;">
                    <a href="{{ route('products.index') }}" style="color:#378ADD;font-size:13px;text-decoration:none;">
                        Limpiar todos los filtros
                    </a>
                </div>
                @endif

            </div>
        </div>
    </section>

    {{-- ============================================================
         GRID DE PRODUCTOS
         ============================================================ --}}
    <section style="background:#f8f9fa;padding:32px 24px 48px;">
        <div style="max-width:1200px;margin:0 auto;">

            @if($products->count())
            <div class="catalog-grid" style="display:grid;gap:20px;">
                @foreach($products as $product)
                    @php
                        $variantsInStock = $product->variants->where('is_active', true)->where('stock', '>', 0);
                        $coloresVariantes = $variantsInStock->filter(fn($v) => $v->color)
                            ->unique('color')
                            ->values();
                        $graduaciones = $variantsInStock->pluck('graduation')->unique()->filter()
                            ->sortBy(fn($g) => (float)$g)->values();
                        $firstImage = $product->images[0] ?? null;
                    @endphp

                    <div style="background:#fff;border-radius:12px;overflow:hidden;
                                border:0.5px solid rgba(0,0,0,0.08);cursor:pointer;
                                transition:transform .2s ease,box-shadow .2s ease;"
                         onclick="location.href='{{ route('products.show', $product->slug) }}'"
                         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

                        {{-- Imagen --}}
                        <div style="height:220px;position:relative;overflow:hidden;">
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}"
                                     alt="{{ $product->name }}"
                                     loading="lazy"
                                     style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;
                                    background:linear-gradient(135deg,#0f1b3d,#1a3a6e);
                                    display:flex;align-items:center;justify-content:center;">
                                    <div style="text-align:center;">
                                        <svg style="width:48px;height:48px;color:rgba(255,255,255,0.12);margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        <p style="margin-top:8px;font-size:11px;color:rgba(255,255,255,0.2);">{{ $product->name }}</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Badge 2x1 --}}
                            @if($product->badge_2x1)
                            <div style="position:absolute;top:10px;left:10px;
                                background:#378ADD;color:#fff;font-size:11px;
                                font-weight:600;padding:3px 10px;border-radius:20px;">
                                2 × 1
                            </div>
                            @endif

                            {{-- Badge tipo --}}
                            <div style="position:absolute;top:10px;right:10px;
                                background:rgba(0,0,0,0.45);color:#fff;font-size:10px;
                                padding:3px 8px;border-radius:20px;
                                backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);">
                                {{ $product->type_labels ?: 'Lente' }}
                            </div>
                        </div>

                        {{-- Body --}}
                        <div style="padding:16px 18px 20px;">
                            <h3 style="font-size:16px;font-weight:600;color:#1a1a2e;margin:0 0 6px;">
                                {{ $product->name }}
                            </h3>

                            {{-- Colores --}}
                            @if($coloresVariantes->count() > 0)
                            <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:10px;align-items:center;">
                                @foreach($coloresVariantes->take(7) as $variant)
                                    @php
                                        $hex = $variant->color_hex;
                                        $isBlackDefault = $hex && strtolower($hex) === '#000000' && stripos($variant->color, 'negro') === false;
                                        if (! $hex || $isBlackDefault) {
                                            $hex = \App\Helpers\ColorHelper::hex($variant->color);
                                        }
                                    @endphp
                                    <div style="width:16px;height:16px;border-radius:50%;
                                        background-color:{{ $hex }};
                                        border:1.5px solid rgba(0,0,0,0.15);"
                                        title="{{ $variant->color }}"></div>
                                @endforeach
                                @if($coloresVariantes->count() > 7)
                                <span style="font-size:11px;color:#aaa;">+{{ $coloresVariantes->count() - 7 }}</span>
                                @endif
                            </div>
                            @endif

                            {{-- Graduaciones --}}
                            @if($graduaciones->count() > 0)
                            <p style="font-size:12px;color:#888;margin:0 0 10px;">
                                @if($product->hasType('miopia'))
                                    {{ $graduaciones->filter(fn($g) => (float)$g < 0)->count() }} grad. miopía
                                    @if($graduaciones->filter(fn($g) => (float)$g > 0)->count())
                                        + {{ $graduaciones->filter(fn($g) => (float)$g > 0)->count() }} lectura
                                    @endif
                                @else
                                    {{ $graduaciones->count() }} graduaciones
                                @endif
                            </p>
                            @endif

                            {{-- Badge texto 2x1 --}}
                            @if($product->badge_2x1)
                            <div style="background:#EBF4FF;color:#185FA5;font-size:11px;
                                padding:4px 10px;border-radius:6px;margin-bottom:12px;
                                display:inline-block;font-weight:500;">
                                Llévate uno y el siguiente gratis
                            </div>
                            @endif

                            {{-- Precio --}}
                            <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:14px;">
                                <span style="font-size:20px;font-weight:700;color:#1a1a2e;">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                @if($product->compare_price)
                                <span style="font-size:13px;color:#bbb;text-decoration:line-through;">
                                    ${{ number_format($product->compare_price, 2) }}
                                </span>
                                @endif
                            </div>

                            {{-- Botón --}}
                            <a href="{{ route('products.show', $product->slug) }}"
                               onclick="event.stopPropagation()"
                               style="display:block;text-align:center;background:#1a1a2e;
                                      color:#fff;border-radius:8px;padding:10px;font-size:14px;
                                      font-weight:500;text-decoration:none;transition:background .2s;"
                               onmouseover="this.style.background='#378ADD'"
                               onmouseout="this.style.background='#1a1a2e'">
                                Ver detalle →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                {{-- Estado vacío --}}
                <div style="text-align:center;padding:64px 24px;">
                    <svg style="width:48px;height:48px;color:#ccc;margin:0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                    <p style="font-size:16px;color:#888;margin-bottom:16px;">
                        No hay productos con esos filtros.
                    </p>
                    <a href="{{ route('products.index') }}" style="color:#378ADD;font-size:14px;text-decoration:none;"
                       onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                        Ver todos los productos
                    </a>
                </div>
            @endif

        </div>
    </section>

    {{-- ============================================================
         SECCIÓN TOALLITAS
         ============================================================ --}}
    @if(!in_array($tipoFiltro, ['todos', 'toallitas']) && $toallitas->count() > 0)
    <section style="background:#fff;padding:48px 24px;">
        <div style="max-width:1200px;margin:0 auto;">

            {{-- Separador --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:32px;">
                <div style="flex:1;height:1px;background:#e5e5e5;"></div>
                <span style="font-size:13px;color:#aaa;white-space:nowrap;">Complementa tu compra</span>
                <div style="flex:1;height:1px;background:#e5e5e5;"></div>
            </div>

            <div class="catalog-grid" style="display:grid;gap:20px;">
                @foreach($toallitas as $product)
                    @php $firstImage = $product->images[0] ?? null; @endphp

                    <div style="background:#fff;border-radius:12px;overflow:hidden;
                                border:0.5px solid rgba(0,0,0,0.08);cursor:pointer;
                                transition:transform .2s ease,box-shadow .2s ease;"
                         onclick="location.href='{{ route('products.show', $product->slug) }}'"
                         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

                        <div style="height:220px;position:relative;overflow:hidden;">
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}"
                                     alt="{{ $product->name }}"
                                     loading="lazy"
                                     style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;
                                    background:linear-gradient(135deg,#5D4037,#8D6E63);
                                    display:flex;align-items:center;justify-content:center;">
                                    <div style="text-align:center;">
                                        <svg style="width:48px;height:48px;color:rgba(255,255,255,0.15);margin:0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
                                        </svg>
                                        <p style="margin-top:8px;font-size:11px;color:rgba(255,255,255,0.25);">{{ $product->name }}</p>
                                    </div>
                                </div>
                            @endif

                            <div style="position:absolute;top:10px;right:10px;
                                background:rgba(0,0,0,0.45);color:#fff;font-size:10px;
                                padding:3px 8px;border-radius:20px;backdrop-filter:blur(4px);">
                                Toallitas
                            </div>
                        </div>

                        <div style="padding:16px 18px 20px;">
                            <h3 style="font-size:16px;font-weight:600;color:#1a1a2e;margin:0 0 10px;">
                                {{ $product->name }}
                            </h3>

                            <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:14px;">
                                <span style="font-size:20px;font-weight:700;color:#1a1a2e;">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                                @if($product->compare_price)
                                <span style="font-size:13px;color:#bbb;text-decoration:line-through;">
                                    ${{ number_format($product->compare_price, 2) }}
                                </span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $product->slug) }}"
                               onclick="event.stopPropagation()"
                               style="display:block;text-align:center;background:#1a1a2e;
                                      color:#fff;border-radius:8px;padding:10px;font-size:14px;
                                      font-weight:500;text-decoration:none;transition:background .2s;"
                               onmouseover="this.style.background='#378ADD'"
                               onmouseout="this.style.background='#1a1a2e'">
                                Ver detalle →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

@endsection

@push('scripts')
<script>
function setFilter(key, value) {
    var params = new URLSearchParams(window.location.search);

    if (key === 'type') {
        // Reset graduation and color when changing type
        params.delete('graduation');
        params.delete('color');
        if (value && value !== 'todos') {
            params.set('type', value);
        } else {
            params.delete('type');
        }
    } else if (key === 'graduation') {
        if (value) {
            params.set('graduation', value);
        } else {
            params.delete('graduation');
        }
    } else if (key === 'color') {
        if (value) {
            params.set('color', value);
        } else {
            params.delete('color');
        }
    }

    var qs = params.toString();
    window.location.href = '/lentes' + (qs ? '?' + qs : '');
}
</script>

<style>
.catalog-grid {
    grid-template-columns: repeat(3, 1fr);
}
@media (max-width: 1024px) {
    .catalog-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .catalog-grid {
        grid-template-columns: 1fr;
    }
    /* Mobile: show toggle bar, collapse filters */
    .filters-mobile-toggle {
        display: flex !important;
    }
    .filters-hidden-mobile {
        display: none !important;
    }
    .filters-content {
        border-top: 1px solid rgba(0,0,0,0.06);
    }
    .filters-count-desktop {
        display: none !important;
    }
    .filters-clear-mobile {
        display: block !important;
    }
}
/* Hide scrollbar on active filter pills */
.filters-mobile-toggle div::-webkit-scrollbar {
    display: none;
}
</style>
@endpush
