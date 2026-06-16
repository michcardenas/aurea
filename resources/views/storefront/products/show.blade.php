@extends('layouts.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('canonical', $seo['canonical'])
@section('og_type', $seo['og_type'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])
@section('og_image', $seo['og_image'])
@section('twitter_title', $seo['twitter_title'])
@section('twitter_description', $seo['twitter_description'])
@section('twitter_image', $seo['twitter_image'])

{{-- noindex toggle por producto --}}
@if($product->noindex)
@section('robots', 'noindex, nofollow')
@endif

{{-- OG image específica del producto si existe --}}
@if($product->og_image_path)
@section('og_image', asset('storage/'.$product->og_image_path))
@section('twitter_image', asset('storage/'.$product->og_image_path))
@endif

@push('schema')
    {!! $schema !!}
    {!! $breadcrumbs !!}
    @if($howToSchema ?? false)
        {!! $howToSchema !!}
    @endif
@endpush

@section('content')

    @php
        // Maps color => image_path and color => hex (first variant per color wins)
        $variantImagesByColor = [];
        $variantHexByColor = [];
        $firstVariantImage = null;
        foreach ($product->variants as $v) {
            if ($v->color && $v->color_hex && ! isset($variantHexByColor[$v->color])) {
                // Skip the default #000000 that the <input type="color"> submits when untouched,
                // unless the color is actually called "Negro".
                $hexLower = strtolower($v->color_hex);
                $isBlackDefault = $hexLower === '#000000' && stripos($v->color, 'negro') === false;
                if (! $isBlackDefault) {
                    $variantHexByColor[$v->color] = $v->color_hex;
                }
            }
            if ($v->image_path) {
                if ($v->color && ! isset($variantImagesByColor[$v->color])) {
                    $variantImagesByColor[$v->color] = asset('storage/' . $v->image_path);
                }
                if (! $firstVariantImage) {
                    $firstVariantImage = $v->image_path;
                }
            }
        }

        // Build the images list used for display: product images first, else fall back to variant image
        $displayImages = ! empty($product->images) ? $product->images : ($firstVariantImage ? [$firstVariantImage] : []);

        // Stock maps for enabling/disabling buttons
        $stockByColor = $product->variants->where('is_active', true)
            ->groupBy('color')
            ->map(fn ($group) => (int) $group->sum('stock'))
            ->toArray();

        $productHasStock = $product->hasStock();
        $availableStock = $product->availableStock();
    @endphp

    {{-- ============================================================
         FICHA PRINCIPAL: IMAGEN + DATOS
         ============================================================ --}}
    <section style="background:#fff;padding:48px 24px;" x-data="productDetail()" @variant-selection-changed.window="recomputeMax(); stockError = '';">
        <div class="product-layout" style="max-width:1100px;margin:0 auto;">

            {{-- ==================== COLUMNA IZQUIERDA: IMAGEN ==================== --}}
            <div style="position:relative;">
                @php $firstImage = $displayImages[0] ?? null; @endphp

                @if($firstImage)
                    <div class="product-zoom-container"
                         style="position:relative;border-radius:16px;overflow:hidden;cursor:zoom-in;min-height:300px;height:480px;background:#f8f9fa;"
                         @click="openLightbox()"
                         onmousemove="productZoomMove(event, this)"
                         onmouseleave="productZoomLeave(this)">
                        @foreach($displayImages as $i => $image)
                        <img src="{{ asset('storage/' . $image) }}"
                             alt="{{ $product->name }} - imagen {{ $i + 1 }}"
                             class="product-main-image"
                             data-image-index="{{ $i }}"
                             style="width:100%;height:100%;object-fit:contain;border-radius:16px;
                                    transition:transform .2s ease;transform-origin:center center;
                                    {{ $i > 0 ? 'position:absolute;top:0;left:0;display:none;' : '' }}"
                             x-show="activeImage === {{ $i }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                        @endforeach

                        {{-- Variant color image overlay (plain JS controlled) --}}
                        <img id="variant-color-image"
                             src=""
                             alt="{{ $product->name }}"
                             style="width:100%;height:100%;object-fit:contain;border-radius:16px;
                                    transition:transform .2s ease;transform-origin:center center;
                                    position:absolute;top:0;left:0;display:none;z-index:1;">

                    </div>

                    {{-- Thumbnails --}}
                    @if(count($displayImages) > 1)
                    <div style="display:flex;gap:10px;margin-top:12px;overflow-x:auto;padding-bottom:4px;">
                        @foreach($displayImages as $i => $image)
                        <button @click="activeImage = {{ $i }}; hideVariantImage()"
                                style="flex-shrink:0;width:72px;height:72px;border-radius:10px;
                                       overflow:hidden;cursor:pointer;transition:all .2s;
                                       opacity:0.5;"
                                :style="activeImage === {{ $i }} ? 'opacity:1;box-shadow:0 0 0 2px #D9B56D;' : 'opacity:0.5;'">
                            <img src="{{ asset('storage/' . $image) }}" alt=""
                                 style="width:100%;height:100%;object-fit:cover;">
                        </button>
                        @endforeach
                    </div>
                    @endif
                @else
                    <div style="width:100%;height:400px;border-radius:16px;position:relative;
                                background:linear-gradient(135deg,#0f1b3d,#1a3a6e);
                                display:flex;align-items:center;justify-content:center;">
                        <svg style="width:64px;height:64px;color:rgba(255,255,255,0.1);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>

                    </div>
                @endif
            </div>

            {{-- ==================== COLUMNA DERECHA: DATOS ==================== --}}
            <div>
                {{-- 1. Breadcrumb --}}
                <nav style="font-size:12px;color:#aaa;margin-bottom:20px;">
                    <a href="{{ route('home') }}" style="color:#aaa;text-decoration:none;"
                       onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='#aaa'">Inicio</a>
                    <span style="margin:0 6px;">·</span>
                    <a href="{{ route('products.index') }}" style="color:#aaa;text-decoration:none;"
                       onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='#aaa'">Productos</a>
                    @if($product->category)
                    <span style="margin:0 6px;">·</span>
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                       style="color:#aaa;text-decoration:none;"
                       onmouseover="this.style.color='#D9B56D'" onmouseout="this.style.color='#aaa'">{{ $product->category->name }}</a>
                    @endif
                    <span style="margin:0 6px;">·</span>
                    <span style="color:#666;">{{ $product->name }}</span>
                </nav>

                {{-- 2. Marca + Categoría --}}
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:10px;">
                    @if($product->brand)
                    <span style="font-size:11px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:#BE9A53;">
                        {{ $product->brand->name }}
                    </span>
                    @endif
                    @if($product->brand && $product->category)
                    <span style="color:#ddd;">·</span>
                    @endif
                    @if($product->category)
                    <span style="font-size:11px;color:#888;letter-spacing:.06em;">
                        {{ $product->category->name }}
                    </span>
                    @endif
                </div>

                {{-- 3. Nombre --}}
                <h1 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:700;
                           color:#2E2A26;margin:0 0 16px;">
                    {{ $product->name }}
                </h1>

                {{-- Código de referencia (útil para distribuidores) --}}
                @if($product->internal_code)
                <p style="font-size:12px;color:#aaa;margin:0 0 16px;">
                    Ref. <span style="font-family:'Montserrat',monospace;color:#666;">{{ $product->internal_code }}</span>
                </p>
                @endif

                {{-- 4. Precio --}}
                <div style="display:flex;align-items:baseline;gap:10px;">
                    <span id="product-current-price" style="font-size:28px;font-weight:700;color:#2E2A26;">
                        ${{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    @if($product->compare_price && $product->compare_price > $product->price)
                    <span style="font-size:16px;color:#bbb;text-decoration:line-through;">
                        ${{ number_format($product->compare_price, 0, ',', '.') }}
                    </span>
                    @endif
                </div>
                {{-- 6. Selector de color --}}
                @if($colores->count() > 0)
                <div style="margin-bottom:20px;margin-top:20px;">
                    <p style="font-size:14px;font-weight:500;color:#2E2A26;margin:0 0 10px;display:flex;align-items:center;gap:8px;">
                        <span>Color: <span id="selected-color-name" style="font-weight:400;color:#666;">{{ $colores->first() }}</span></span>
                        <button type="button" id="clear-color-btn" onclick="clearColor()" style="display:none;background:none;border:none;color:#D9B56D;font-size:12px;font-weight:500;cursor:pointer;text-decoration:underline;padding:0;">Quitar</button>
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach($colores as $color)
                            @php
                                $hex = $variantHexByColor[$color] ?? \App\Helpers\ColorHelper::hex($color);
                                $colorOutOfStock = ($stockByColor[$color] ?? 0) <= 0;
                            @endphp
                            <div class="color-btn"
                                 data-color="{{ $color }}"
                                 data-out-of-stock="{{ $colorOutOfStock ? '1' : '0' }}"
                                 style="position:relative;width:28px;height:28px;border-radius:50%;
                                        background-color:{{ $hex }};
                                        border:2px solid rgba(0,0,0,0.1);transition:all .15s;display:inline-block;
                                        {{ $colorOutOfStock ? 'opacity:0.4;cursor:not-allowed;' : 'cursor:pointer;' }}"
                                 title="{{ $color }}{{ $colorOutOfStock ? ' (Agotado)' : '' }}"
                                 @if(!$colorOutOfStock) onclick="selectColor('{{ $color }}')" @endif>
                                @if($colorOutOfStock)
                                    <span style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#dc2626;font-weight:700;font-size:18px;line-height:1;">×</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 6b. Selectores genéricos (Tamaño · Aroma · Acabado · Estilo …) --}}
                @if(($genericVariants ?? collect())->isNotEmpty())
                    @foreach($genericVariants as $optionLabel => $variants)
                        @php
                            // Tomar la primera variante del grupo para extraer el option_type
                            $sampleType = $variants->first()->option_type ?? 'other';
                            $isColor = $sampleType === 'color';
                        @endphp
                        <div class="ba-option-group" style="margin-bottom:22px;"
                             data-option-label="{{ $optionLabel }}"
                             data-option-type="{{ $sampleType }}">
                            <p style="font-size:13px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#6B6157;margin:0 0 12px;display:flex;align-items:center;gap:8px;">
                                <span>{{ $optionLabel }}:</span>
                                <span class="ba-option-selected" style="font-weight:400;color:#2E2A26;text-transform:none;letter-spacing:0;font-size:14px;">— Selecciona</span>
                            </p>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach($variants as $v)
                                    @php
                                        $vOut = ($v->stock <= 0);
                                        $vHex = $v->color_hex ?: \App\Helpers\ColorHelper::hex($v->value);
                                    @endphp
                                    <button type="button"
                                            class="ba-opt"
                                            data-variant-id="{{ $v->id }}"
                                            data-variant-value="{{ $v->value }}"
                                            data-variant-price-mod="{{ (float) $v->price_modifier }}"
                                            data-variant-image="{{ $v->image_path ? asset('storage/'.$v->image_path) : '' }}"
                                            data-out-of-stock="{{ $vOut ? '1' : '0' }}"
                                            @disabled($vOut)
                                            title="{{ $v->value }}{{ $vOut ? ' (Agotado)' : '' }}"
                                            style="
                                                @if($isColor)
                                                    position:relative;width:34px;height:34px;border-radius:50%;
                                                    background:{{ $vHex }};
                                                    border:2px solid {{ $vOut ? 'rgba(0,0,0,.1)' : 'rgba(184,169,153,.35)' }};
                                                @else
                                                    padding:9px 18px;border-radius:2px;
                                                    background:#FFFFFF;border:1px solid #D1C7BC;
                                                    font-size:13px;color:#2E2A26;
                                                @endif
                                                transition:all .25s ease;
                                                {{ $vOut ? 'opacity:.4;cursor:not-allowed;text-decoration:'.($isColor ? 'none' : 'line-through').';' : 'cursor:pointer;' }}
                                            ">
                                        @if($isColor)
                                            @if($vOut)
                                                <span style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#dc2626;font-weight:700;font-size:18px;line-height:1;">×</span>
                                            @endif
                                        @else
                                            {{ $v->value }}
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Descripción corta --}}
                @if($product->description)
                <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:20px;">
                    {{ $product->description }}
                </p>
                @endif

                {{-- Trust badges contextuales (cruelty-free, vegan, origen) --}}
                @if($product->is_cruelty_free || $product->is_vegan || $product->country_origin)
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px;">
                    @if($product->is_cruelty_free)
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;letter-spacing:.06em;color:#5C6B54;background:#F0F2EB;border:1px solid #A8B29A;padding:5px 10px;border-radius:999px;">
                        🐰 Cruelty-free
                    </span>
                    @endif
                    @if($product->is_vegan)
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;letter-spacing:.06em;color:#5C6B54;background:#F0F2EB;border:1px solid #A8B29A;padding:5px 10px;border-radius:999px;">
                        🌱 Vegano
                    </span>
                    @endif
                    @if($product->country_origin)
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;letter-spacing:.06em;color:#BE9A53;background:#FBF4E6;border:1px solid #E8CC92;padding:5px 10px;border-radius:999px;">
                        Origen · {{ $product->country_origin }}
                    </span>
                    @endif
                    @if($product->weight_value && $product->weight_unit)
                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:600;letter-spacing:.06em;color:#6B6157;background:#FBF8F2;border:1px solid #D1C7BC;padding:5px 10px;border-radius:999px;">
                        {{ rtrim(rtrim(number_format($product->weight_value, 2, '.', ''), '0'), '.') }} {{ $product->weight_unit }}
                    </span>
                    @endif
                </div>
                @endif

                {{-- Key features bullets — AI-friendly + visual --}}
                @if(! empty($product->key_features) && is_array($product->key_features))
                <div style="margin-bottom:20px;">
                    <p style="font-size:11px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:#BE9A53;margin:0 0 10px;">Características clave</p>
                    <ul style="list-style:none;padding:0;margin:0;font-size:14px;color:#2E2A26;line-height:1.65;">
                        @foreach($product->key_features as $feat)
                            <li style="display:flex;align-items:flex-start;gap:10px;padding:6px 0;">
                                <span style="color:#D9B56D;font-weight:700;flex-shrink:0;">✦</span>
                                <span>{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Recomendado para --}}
                @if($product->suitable_for)
                <div style="margin-bottom:20px;padding:12px 16px;background:#FBF4E6;border-left:3px solid #D9B56D;border-radius:4px;">
                    <p style="font-size:10px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:#BE9A53;margin:0 0 4px;">Recomendado para</p>
                    <p style="font-size:14px;color:#2E2A26;line-height:1.5;margin:0;">{{ $product->suitable_for }}</p>
                </div>
                @endif

                {{-- 8. Botón agregar al carrito --}}
                <div style="margin-top:8px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        {{-- Quantity --}}
                        <div style="display:flex;align-items:center;border:1.5px solid #e5e5e5;border-radius:10px;overflow:hidden;">
                            <button @click="qty > 1 && qty--"
                                    :disabled="qty <= 1"
                                    :style="{
                                        width:'40px',height:'40px',display:'flex',alignItems:'center',
                                        justifyContent:'center',background:'none',border:'none',
                                        cursor: qty <= 1 ? 'not-allowed' : 'pointer',
                                        color: qty <= 1 ? '#d1d5db' : '#888',
                                        fontSize:'18px',
                                    }">
                                −
                            </button>
                            <span style="width:36px;text-align:center;font-size:14px;font-weight:600;color:#2E2A26;"
                                  x-text="qty"></span>
                            <button @click="increaseQty()"
                                    :disabled="qty >= currentMax"
                                    :title="qty >= currentMax ? ('Máximo disponible: ' + currentMax) : ''"
                                    :style="{
                                        width:'40px',height:'40px',display:'flex',alignItems:'center',
                                        justifyContent:'center',background:'none',border:'none',
                                        cursor: qty >= currentMax ? 'not-allowed' : 'pointer',
                                        color: qty >= currentMax ? '#d1d5db' : '#888',
                                        fontSize:'18px',
                                    }">
                                +
                            </button>
                        </div>

                        {{-- Stock --}}
                        @if($productHasStock)
                        <span x-show="!stockError" style="font-size:13px;color:#16a34a;display:flex;align-items:center;gap:5px;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#16a34a;display:inline-block;"></span>
                            En stock
                        </span>
                        <span x-show="stockError" x-cloak x-transition style="font-size:13px;color:#dc2626;display:flex;align-items:center;gap:5px;font-weight:500;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#dc2626;display:inline-block;flex-shrink:0;"></span>
                            <span x-text="stockError"></span>
                        </span>
                        @else
                        <span style="font-size:13px;color:#dc2626;display:flex;align-items:center;gap:5px;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#dc2626;display:inline-block;"></span>
                            Agotado
                        </span>
                        @endif
                    </div>

                    <button @click="addToCart()"
                            :disabled="adding || {{ $productHasStock ? 'false' : 'true' }}"
                            @mouseenter="hoverBtn = true" @mouseleave="hoverBtn = false"
                            :style="{
                                width: '100%',
                                background: adding ? '#2E2A26' : (hoverBtn && {{ $productHasStock ? 'true' : 'false' }} ? '#D9B56D' : '#2E2A26'),
                                color: '#fff',
                                border: 'none',
                                borderRadius: '10px',
                                padding: '14px',
                                fontSize: '16px',
                                fontWeight: '500',
                                cursor: adding ? 'wait' : ({{ $productHasStock ? 'true' : 'false' }} ? 'pointer' : 'not-allowed'),
                                transition: 'background .2s',
                                fontFamily: 'inherit',
                                opacity: (adding || {{ $productHasStock ? 'false' : 'true' }}) ? '0.6' : '1',
                            }">
                        <span x-show="!adding && !added">{{ $productHasStock ? 'Agregar al carrito' : 'Agotado' }}</span>
                        <span x-show="adding" x-cloak>Agregando...</span>
                        <span x-show="added" x-cloak>✓ Agregado</span>
                    </button>

                </div>

                {{-- 9. Beneficios rápidos --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:24px;">
                    @php
                        $beneficios = [
                            'Envío a toda Colombia',
                            'Productos 100 % originales',
                            'Pago seguro',
                            'Soporte WhatsApp',
                        ];
                    @endphp
                    @foreach($beneficios as $b)
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#666;">
                        <svg style="width:14px;height:14px;color:#A8B29A;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m4.5 12.75 6 6 9-13.5"/>
                        </svg>
                        {{ $b }}
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    {{-- ============================================================
         CONTENIDO ENRIQUECIDO — Modo de uso + Ingredientes (AI-ready)
         Visible para humanos, indexable por LLMs (texto plano + Schema HowTo)
         ============================================================ --}}
    @if($product->how_to_use || $product->ingredients)
    <section style="background:#FBF8F2;padding:64px 24px;">
        <div style="max-width:960px;margin:0 auto;">
            <div style="display:grid;grid-template-columns:{{ ($product->how_to_use && $product->ingredients) ? '1fr 1fr' : '1fr' }};gap:48px;">

                {{-- Modo de uso --}}
                @if($product->how_to_use)
                <article>
                    <p style="font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:#BE9A53;margin:0 0 14px;">Modo de uso</p>
                    <h2 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:500;color:#2E2A26;margin:0 0 24px;">Cómo aplicarlo</h2>
                    @php
                        // Acepta saltos reales, '\n' literales, o numeración '1. ', '2. '
                        $rawHow = preg_replace('/\s*(?:\\\\n|\r\n|\r|\n)\s*/u', "\n", $product->how_to_use);
                        $rawHow = preg_replace('/(?<=[.;])\s+(?=\d+\.\s)/u', "\n", $rawHow);
                        $steps = collect(preg_split('/\n+/', $rawHow))
                            ->map(fn ($l) => trim(preg_replace('/^\d+\.\s*/', '', $l)))
                            ->filter()
                            ->values()
                            ->all();
                    @endphp
                    @if(count($steps) > 1)
                        <ol style="list-style:none;counter-reset:step;padding:0;margin:0;">
                            @foreach($steps as $step)
                            <li style="counter-increment:step;display:flex;align-items:flex-start;gap:18px;padding:12px 0;border-bottom:1px solid rgba(184,169,153,.18);">
                                <span style="flex-shrink:0;width:32px;height:32px;border-radius:50%;background:#FBF4E6;border:1px solid #E8CC92;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;color:#BE9A53;font-weight:600;">{{ $loop->iteration }}</span>
                                <span style="font-size:15px;line-height:1.7;color:#2E2A26;padding-top:5px;">{{ $step }}</span>
                            </li>
                            @endforeach
                        </ol>
                    @else
                        <p style="font-size:15px;line-height:1.75;color:#2E2A26;margin:0;">{{ $product->how_to_use }}</p>
                    @endif
                </article>
                @endif

                {{-- Ingredientes --}}
                @if($product->ingredients)
                <article>
                    <p style="font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:#BE9A53;margin:0 0 14px;">Composición</p>
                    <h2 style="font-family:'Playfair Display',serif;font-size:28px;font-weight:500;color:#2E2A26;margin:0 0 24px;">Ingredientes</h2>
                    <div style="font-size:13px;line-height:1.85;color:#6B6157;font-family:'Montserrat',sans-serif;background:#FFFFFF;padding:24px;border-radius:8px;border:1px solid rgba(184,169,153,.2);">
                        {{ $product->ingredients }}
                    </div>
                    <p style="font-size:11px;color:#9CA3AF;margin-top:10px;font-style:italic;">
                        Composición declarada por el fabricante. Para alergias específicas, consulta con tu profesional.
                    </p>
                </article>
                @endif

            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         PRODUCTOS RELACIONADOS (misma categoría)
         ============================================================ --}}
    @if(($relatedProducts ?? collect())->count() > 0)
    <section style="background:#FBF8F2;padding:64px 24px;">
        <div style="max-width:1100px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:32px;">
                <p style="font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:#BE9A53;margin:0 0 8px;">También te puede gustar</p>
                <h2 style="font-family:'Playfair Display',serif;font-size:26px;font-weight:500;color:#2E2A26;margin:0;">
                    Más en {{ $product->category?->name ?? 'esta categoría' }}
                </h2>
            </div>

            <div class="related-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;">
                @foreach($relatedProducts as $rel)
                @php $rImg = $rel->images[0] ?? null; @endphp
                <a href="{{ route('products.show', $rel->slug) }}"
                   style="background:#fff;border-radius:12px;overflow:hidden;
                          border:0.5px solid rgba(0,0,0,0.08);text-decoration:none;color:inherit;
                          transition:transform .2s ease,box-shadow .2s ease;display:block;"
                   onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.08)'"
                   onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

                    <div style="height:180px;background:#f8f9fa;overflow:hidden;">
                        @if($rImg)
                            <img src="{{ asset('storage/' . $rImg) }}" alt="{{ $rel->name }}"
                                 loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#F7F3ED,#E8D1C5);"></div>
                        @endif
                    </div>

                    <div style="padding:14px 16px 18px;">
                        @if($rel->brand)
                        <p style="font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#BE9A53;margin:0 0 4px;">
                            {{ $rel->brand->name }}
                        </p>
                        @endif
                        <h4 style="font-size:14px;font-weight:500;color:#2E2A26;margin:0 0 10px;line-height:1.35;
                                   overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            {{ $rel->name }}
                        </h4>
                        <div style="display:flex;align-items:baseline;gap:6px;">
                            <span style="font-size:16px;font-weight:700;color:#2E2A26;">
                                ${{ number_format($rel->price, 0, ',', '.') }}
                            </span>
                            @if($rel->compare_price && $rel->compare_price > $rel->price)
                            <span style="font-size:12px;color:#bbb;text-decoration:line-through;">
                                ${{ number_format($rel->compare_price, 0, ',', '.') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         LIGHTBOX
         ============================================================ --}}
    @if(! empty($displayImages))
    <div x-show="lightboxOpen" x-cloak
         style="position:fixed;inset:0;z-index:50;background:rgba(0,0,0,0.9);
                display:flex;align-items:center;justify-content:center;"
         @keydown.escape.window="lightboxOpen = false">
        <button @click="lightboxOpen = false"
                style="position:absolute;top:16px;right:16px;background:none;border:none;
                       color:rgba(255,255,255,0.7);cursor:pointer;z-index:10;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
            <svg style="width:32px;height:32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>

        @if(count($displayImages) > 1)
        <button @click="activeImage = (activeImage - 1 + {{ count($displayImages) }}) % {{ count($displayImages) }}"
                style="position:absolute;left:16px;background:none;border:none;
                       color:rgba(255,255,255,0.7);cursor:pointer;z-index:10;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
            <svg style="width:40px;height:40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </button>
        <button @click="activeImage = (activeImage + 1) % {{ count($displayImages) }}"
                style="position:absolute;right:16px;background:none;border:none;
                       color:rgba(255,255,255,0.7);cursor:pointer;z-index:10;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
            <svg style="width:40px;height:40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </button>
        @endif

        @foreach($displayImages as $i => $image)
        <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
             style="max-height:85vh;max-width:90vw;object-fit:contain;"
             x-show="activeImage === {{ $i }}">
        @endforeach
    </div>
    @endif

@endsection

@push('scripts')
<script>
/* ── Variant images map (color → image URL) ── */
window.variantImagesByColor = @json($variantImagesByColor);

/* ── Lista de variantes activas con stock para calcular disponibilidad por color ── */
@php
    $variantStockData = $product->variants->where('is_active', true)->map(fn ($v) => [
        'color' => $v->color,
        'stock' => (int) $v->stock,
    ])->values();
@endphp
window.variantStockData = @json($variantStockData);
window.currentSelection = { color: null };

/**
 * Recalcula qué colores siguen disponibles según el stock total de cada uno.
 */
function refreshVariantAvailability() {
    var data = window.variantStockData || [];

    document.querySelectorAll('.color-btn').forEach(function (btn) {
        var color = btn.dataset.color;
        var stock = data
            .filter(function (v) { return v.color === color; })
            .reduce(function (sum, v) { return sum + (v.stock || 0); }, 0);

        var outOfStock = stock <= 0;
        btn.dataset.outOfStock = outOfStock ? '1' : '0';
        btn.style.opacity = outOfStock ? '0.4' : '1';
        btn.style.cursor = outOfStock ? 'not-allowed' : 'pointer';
        btn.title = color + (outOfStock ? ' (Agotado)' : '');

        var xMark = btn.querySelector('.color-out-x');
        if (outOfStock && !xMark) {
            var span = document.createElement('span');
            span.className = 'color-out-x';
            span.textContent = '×';
            span.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#dc2626;font-weight:700;font-size:18px;line-height:1;';
            btn.appendChild(span);
        } else if (!outOfStock && xMark) {
            xMark.remove();
        }

        if (outOfStock) {
            btn.onclick = null;
        } else {
            btn.onclick = (function (c) { return function () { selectColor(c); }; })(color);
        }
    });
}

/* ── Image zoom on hover ── */
function productZoomMove(e, container) {
    var imgs = container.querySelectorAll('img');
    var rect = container.getBoundingClientRect();
    var x = ((e.clientX - rect.left) / rect.width) * 100;
    var y = ((e.clientY - rect.top) / rect.height) * 100;
    imgs.forEach(function(img) {
        if (img.offsetParent !== null || img.style.display !== 'none') {
            img.style.transformOrigin = x + '% ' + y + '%';
            img.style.transform = 'scale(2)';
        }
    });
}

function productZoomLeave(container) {
    var imgs = container.querySelectorAll('img');
    imgs.forEach(function(img) {
        img.style.transform = 'scale(1)';
        img.style.transformOrigin = 'center center';
    });
}

function hideVariantImage() {
    var overlay = document.getElementById('variant-color-image');
    if (overlay) {
        overlay.style.display = 'none';
        overlay.src = '';
    }
}

/* ── Helper para mostrar/ocultar el botón "Quitar" de color ── */
function updateClearButtons() {
    var sel = window.currentSelection;
    var clearColorBtn = document.getElementById('clear-color-btn');
    if (clearColorBtn) {
        clearColorBtn.style.display = sel.color ? 'inline-block' : 'none';
    }
}

/* ── Color selector ── */
function selectColor(color) {
    // Toggle: si el color ya está seleccionado, lo deseleccionamos.
    if (window.currentSelection.color === color) {
        clearColor();
        return;
    }

    document.querySelectorAll('.color-btn').forEach(function(b) {
        b.style.borderColor = 'transparent';
        b.style.boxShadow = 'none';
    });
    var btn = document.querySelector('[data-color="' + color + '"]');
    if (btn) {
        btn.style.borderColor = '#D9B56D';
        btn.style.boxShadow = '0 0 0 2px rgba(55,138,221,0.3)';
    }
    var label = document.getElementById('selected-color-name');
    if (label) label.textContent = color;

    // Swap main image to the variant image for this color (if any)
    var overlay = document.getElementById('variant-color-image');
    if (overlay) {
        var url = window.variantImagesByColor[color];
        if (url) {
            overlay.src = url;
            overlay.style.display = 'block';
        } else {
            overlay.src = '';
            overlay.style.display = 'none';
        }
    }

    window.currentSelection.color = color;
    refreshVariantAvailability();
    updateClearButtons();
    window.dispatchEvent(new CustomEvent('variant-selection-changed'));
}

/* ── Limpiar color ── */
function clearColor() {
    document.querySelectorAll('.color-btn').forEach(function (b) {
        b.style.borderColor = 'transparent';
        b.style.boxShadow = 'none';
    });
    var label = document.getElementById('selected-color-name');
    if (label) label.textContent = '— Selecciona un color';

    var overlay = document.getElementById('variant-color-image');
    if (overlay) { overlay.src = ''; overlay.style.display = 'none'; }

    window.currentSelection.color = null;
    refreshVariantAvailability();
    updateClearButtons();
    window.dispatchEvent(new CustomEvent('variant-selection-changed'));
}

/* ── Auto-select first in-stock color ── */
document.addEventListener('DOMContentLoaded', function() {
    refreshVariantAvailability();

    var buttons = document.querySelectorAll('.color-btn');
    for (var i = 0; i < buttons.length; i++) {
        if (buttons[i].dataset.outOfStock !== '1') {
            selectColor(buttons[i].dataset.color);
            break;
        }
    }

    updateClearButtons();
});

/* ──────────────────────────────────────────────────────────
   Selector de variantes genéricas (Tamaño / Aroma / Acabado…)
   El cliente puede tener N grupos de opciones. Por simplicidad,
   la última opción clickeada define el variant_id que se envía
   al carrito. Si quieres combinaciones (color + tamaño),
   se necesita un modelo de "combinations" aparte.
   ────────────────────────────────────────────────────────── */
window.selectedGenericVariantId = null;
window.selectedGenericVariants = {}; // { 'Tamaño': {value, modifier}, ... }
window.baseProductPrice = {{ (float) $product->price }};

(function () {
    function fmtPrice(n) {
        return '$' + Number(n).toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
    }
    function updatePriceDisplay() {
        var totalMod = 0;
        Object.values(window.selectedGenericVariants).forEach(function (g) {
            totalMod += g.modifier || 0;
        });
        var newPrice = window.baseProductPrice + totalMod;
        var el = document.getElementById('product-current-price');
        if (el) el.textContent = fmtPrice(newPrice);
    }
    function selectOption(btn) {
        if (btn.dataset.outOfStock === '1') return;

        var group = btn.closest('.ba-option-group');
        if (!group) return;

        // Limpia hermanos
        group.querySelectorAll('.ba-opt').forEach(function (b) {
            if (b.dataset.optionType === 'color' || group.dataset.optionType === 'color') {
                b.style.boxShadow = '';
                b.style.borderColor = 'rgba(184,169,153,.35)';
                b.style.borderWidth = '2px';
                b.style.transform = '';
            } else {
                b.style.background = '#FFFFFF';
                b.style.borderColor = '#D1C7BC';
                b.style.color = '#2E2A26';
                b.style.fontWeight = '400';
            }
        });

        // Marca seleccionado
        if (group.dataset.optionType === 'color') {
            btn.style.boxShadow = '0 0 0 2px #FFFFFF, 0 0 0 4px #D9B56D';
            btn.style.transform = 'scale(1.08)';
        } else {
            btn.style.background = '#2E2A26';
            btn.style.borderColor = '#2E2A26';
            btn.style.color = '#FFFFFF';
            btn.style.fontWeight = '500';
        }

        var label = group.querySelector('.ba-option-selected');
        if (label) label.textContent = btn.dataset.variantValue;

        // Guarda selección
        var optionLabel = group.dataset.optionLabel;
        window.selectedGenericVariants[optionLabel] = {
            value:    btn.dataset.variantValue,
            modifier: parseFloat(btn.dataset.variantPriceMod) || 0,
            id:       btn.dataset.variantId,
        };
        window.selectedGenericVariantId = parseInt(btn.dataset.variantId, 10);

        // Si la variante tiene su propia imagen, la mostramos sobre el visor
        // usando el overlay #variant-color-image que ya existe en el blade.
        if (btn.dataset.variantImage) {
            var overlay = document.getElementById('variant-color-image');
            if (overlay) {
                overlay.src = btn.dataset.variantImage;
                overlay.style.display = 'block';
            }
        } else {
            var overlay2 = document.getElementById('variant-color-image');
            if (overlay2 && overlay2.dataset.controlledBy !== 'color') {
                overlay2.style.display = 'none';
            }
        }

        updatePriceDisplay();
        window.dispatchEvent(new CustomEvent('variant-selection-changed'));
    }

    // Event delegation
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.ba-opt');
        if (btn) selectOption(btn);
    });
})();

/* ── Alpine component ── */
function productDetail() {
    return {
        activeImage: 0,
        lightboxOpen: false,
        qty: 1,
        currentMax: 10,
        adding: false,
        added: false,
        hoverBtn: false,
        stockError: '',

        init() {
            // Limpiar el mensaje de error cuando el usuario cambia la cantidad
            // (porque pudo haber cambiado el stock disponible).
            this.$watch('qty', () => { this.stockError = ''; });
            // Calculo inicial del maximo segun la variante por defecto.
            this.recomputeMax();
        },

        /**
         * Calcula el stock disponible para la combinacion actualmente seleccionada
         * (color + graduacion) y lo guarda en currentMax para que Alpine reactivamente
         * actualice los bindings :disabled/:title del boton +.
         * Si qty actual ya excede el nuevo max, la bajamos al max para no quedar invalida.
         */
        recomputeMax() {
            var data = window.variantStockData || [];
            var sel = window.currentSelection || {};

            // Sin variantes: usa el stock del producto.
            if (data.length === 0) {
                this.currentMax = Math.min({{ (int) ($product->stock ?? 10) }}, 10);
            } else {
                var stock = data.reduce(function (sum, v) {
                    if (sel.color && v.color !== sel.color) return sum;
                    return sum + (v.stock || 0);
                }, 0);
                // Cap superior de 10 (limite de la API).
                this.currentMax = Math.min(Math.max(stock, 0), 10);
            }

            // Clampear qty al nuevo maximo (siempre >= 1).
            if (this.qty > this.currentMax) {
                this.qty = Math.max(this.currentMax, 1);
            }
        },

        /**
         * Incrementa qty pero no permite pasar del maximo disponible.
         * Si el cliente clickea cuando ya esta en el maximo, mostramos
         * un hint inline en lugar de subir el contador.
         */
        increaseQty() {
            // Recalculamos por si las dudas (la selecccion pudo cambiar
            // entre eventos).
            this.recomputeMax();
            if (this.qty < this.currentMax) {
                this.qty++;
                this.stockError = '';
            } else {
                this.stockError = this.currentMax > 0
                    ? 'Máximo disponible: ' + this.currentMax + ' unidad(es).'
                    : 'Sin stock disponible.';
            }
        },

        openLightbox() {
            this.lightboxOpen = true;
        },

        async addToCart() {
            if (this.adding) return;
            this.adding = true;
            this.added = false;

            // Buscar la variante seleccionada (por color o por opción genérica)
            var selectedColor = document.getElementById('selected-color-name');
            var colorName = selectedColor ? selectedColor.textContent : null;

            var variantId = null;
            @if($product->variants->count())
            @php
                $variantData = $product->variants->where('is_active', true)->map(fn ($v) => [
                    'id' => $v->id, 'color' => $v->color,
                ])->values();
            @endphp
            var variants = @json($variantData);

            // Match por color si el cliente eligió uno
            for (var i = 0; i < variants.length; i++) {
                if (colorName && variants[i].color === colorName) {
                    variantId = variants[i].id;
                    break;
                }
            }

            // Si el cliente eligió una variante genérica (Tamaño / Aroma / Acabado…),
            // ésa tiene prioridad.
            if (window.selectedGenericVariantId) {
                variantId = window.selectedGenericVariantId;
            }
            @endif

            try {
                var res = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        product_id: {{ $product->id }},
                        variant_id: variantId,
                        qty: this.qty,
                    }),
                });

                var data = await res.json();

                if (res.ok) {
                    this.added = true;
                    this.stockError = '';
                    var badge = document.getElementById('cart-badge');
                    var count = document.getElementById('cart-count');
                    if (badge && count) {
                        badge.classList.remove('hidden');
                        count.textContent = data.cart_count;
                    }
                    window.dispatchEvent(new CustomEvent('open-cart-drawer', { detail: data }));
                    var self = this;
                    setTimeout(function() { self.added = false; }, 2000);
                } else {
                    // Backend rechazo (p. ej. stock insuficiente). Mostramos
                    // el mensaje inline en lugar de un alert intrusivo.
                    this.stockError = data.message || 'No se pudo agregar al carrito.';
                }
            } catch (e) {
                console.error(e);
                this.stockError = 'Error de conexión. Intenta de nuevo.';
            } finally {
                this.adding = false;
            }
        },
    };
}
</script>

<style>
.product-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: start;
}
@media (max-width: 900px) {
    .related-grid { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 768px) {
    .product-layout {
        grid-template-columns: 1fr;
        gap: 32px;
    }
}
@media (max-width: 480px) {
    .related-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush
