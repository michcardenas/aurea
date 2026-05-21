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

@push('schema')
    {!! $schema !!}
    {!! $breadcrumbs !!}
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

        $stockByGradMiopia = $product->variants->where('is_active', true)->where('graduation_type', 'miopia')
            ->groupBy('graduation')
            ->map(fn ($group) => (int) $group->sum('stock'))
            ->toArray();

        $stockByGradLectura = $product->variants->where('is_active', true)->where('graduation_type', 'lectura')
            ->groupBy('graduation')
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

                        {{-- Badge 2x1 --}}
                        @if($product->badge_2x1)
                        <div style="position:absolute;top:16px;left:16px;background:#378ADD;
                                    color:#fff;font-size:13px;font-weight:600;padding:6px 16px;
                                    border-radius:20px;z-index:2;">
                            2 × 1
                        </div>
                        @endif
                    </div>

                    {{-- Thumbnails --}}
                    @if(count($displayImages) > 1)
                    <div style="display:flex;gap:10px;margin-top:12px;overflow-x:auto;padding-bottom:4px;">
                        @foreach($displayImages as $i => $image)
                        <button @click="activeImage = {{ $i }}; hideVariantImage()"
                                style="flex-shrink:0;width:72px;height:72px;border-radius:10px;
                                       overflow:hidden;cursor:pointer;transition:all .2s;
                                       opacity:0.5;"
                                :style="activeImage === {{ $i }} ? 'opacity:1;box-shadow:0 0 0 2px #378ADD;' : 'opacity:0.5;'">
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

                        @if($product->badge_2x1)
                        <div style="position:absolute;top:16px;left:16px;background:#378ADD;
                                    color:#fff;font-size:13px;font-weight:600;padding:6px 16px;
                                    border-radius:20px;">
                            2 × 1
                        </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ==================== COLUMNA DERECHA: DATOS ==================== --}}
            <div>
                {{-- 1. Breadcrumb --}}
                <nav style="font-size:12px;color:#aaa;margin-bottom:20px;">
                    <a href="{{ route('home') }}" style="color:#aaa;text-decoration:none;"
                       onmouseover="this.style.color='#378ADD'" onmouseout="this.style.color='#aaa'">Inicio</a>
                    <span style="margin:0 6px;">·</span>
                    <a href="{{ route('products.index') }}" style="color:#aaa;text-decoration:none;"
                       onmouseover="this.style.color='#378ADD'" onmouseout="this.style.color='#aaa'">Lentes</a>
                    <span style="margin:0 6px;">·</span>
                    <span style="color:#666;">{{ $product->name }}</span>
                </nav>

                {{-- 2. Nombre --}}
                <h1 style="font-family:'Bai Jamjuree',sans-serif;font-size:28px;font-weight:700;
                           color:#1a1a2e;margin:0 0 8px;">
                    {{ $product->name }}
                </h1>

                {{-- 3. Badge tipo --}}
                @if($product->type && !$product->hasType('toallitas'))
                <div style="display:inline-block;background:#EBF4FF;color:#185FA5;font-size:12px;
                            padding:4px 12px;border-radius:20px;margin-bottom:16px;">
                    {{ $product->type_labels }}
                </div>
                @endif

                {{-- 4. Precio --}}
                <div style="display:flex;align-items:baseline;gap:10px;">
                    <span style="font-size:28px;font-weight:700;color:#1a1a2e;">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    @if($product->compare_price && $product->compare_price > $product->price)
                    <span style="font-size:16px;color:#bbb;text-decoration:line-through;">
                        ${{ number_format($product->compare_price, 2) }}
                    </span>
                    @endif
                </div>
                @if($product->badge_2x1)
                <p style="font-size:13px;color:#888;margin-top:4px;">
                    Precio por lente · 2×1 aplicado al segundo
                </p>
                @endif

                {{-- 5. Banner 2x1 --}}
                @if($product->badge_2x1)
                <div style="background:linear-gradient(135deg,#EBF4FF,#DBEAFE);
                            border:1px solid #B5D4F4;border-radius:12px;
                            padding:16px 20px;margin:20px 0;">
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <svg style="width:24px;height:24px;color:#185FA5;flex-shrink:0;margin-top:2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                        </svg>
                        <div>
                            <p style="font-size:15px;font-weight:600;color:#1a1a2e;margin:0;">
                                ¡Llévate el segundo par gratis!
                            </p>
                            <p style="font-size:13px;color:#555;margin:4px 0 0;">
                                Agrega dos lentes al carrito y el más económico va sin costo. Combinables entre todos los modelos.
                            </p>
                            <a href="{{ route('products.index') }}"
                               style="font-size:13px;color:#378ADD;text-decoration:none;display:inline-block;margin-top:6px;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Ver todos los lentes →
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- 6. Selector de color --}}
                @if($colores->count() > 0)
                <div style="margin-bottom:20px;{{ $product->badge_2x1 ? '' : 'margin-top:20px;' }}">
                    <p style="font-size:14px;font-weight:500;color:#1a1a2e;margin:0 0 10px;display:flex;align-items:center;gap:8px;">
                        <span>Color: <span id="selected-color-name" style="font-weight:400;color:#666;">{{ $colores->first() }}</span></span>
                        <button type="button" id="clear-color-btn" onclick="clearColor()" style="display:none;background:none;border:none;color:#378ADD;font-size:12px;font-weight:500;cursor:pointer;text-decoration:underline;padding:0;">Quitar</button>
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

                {{-- 7. Selector de graduación --}}
                @if($graduacionesMiopia->count() > 0)
                <div style="margin-bottom:20px;">
                    <p style="font-size:14px;font-weight:500;color:#1a1a2e;margin:0 0 10px;display:flex;align-items:center;gap:8px;">
                        <span>Graduación Miopía: <span id="selected-grad-miopia" style="font-weight:400;color:#666;">— Selecciona</span></span>
                        <button type="button" id="clear-grad-miopia-btn" onclick="clearGraduation()" style="display:none;background:none;border:none;color:#378ADD;font-size:12px;font-weight:500;cursor:pointer;text-decoration:underline;padding:0;">Quitar</button>
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach($graduacionesMiopia as $grad)
                            @php $gradOutOfStock = ($stockByGradMiopia[$grad] ?? 0) <= 0; @endphp
                            <div class="grad-btn grad-miopia"
                                 data-grad="{{ $grad }}"
                                 data-tipo="miopia"
                                 data-grad-type="miopia"
                                 data-out-of-stock="{{ $gradOutOfStock ? '1' : '0' }}"
                                 @if(!$gradOutOfStock) onclick="selectGrad(this,'miopia')" @endif
                                 title="{{ $gradOutOfStock ? 'Agotado' : '' }}"
                                 style="padding:6px 14px;border-radius:8px;border:1px solid #ddd;
                                        background:#fff;font-size:13px;color:#444;
                                        transition:all .15s;
                                        {{ $gradOutOfStock ? 'opacity:0.45;cursor:not-allowed;text-decoration:line-through;' : 'cursor:pointer;' }}">
                                {{ $grad }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($graduacionesLectura->count() > 0)
                <div style="margin-bottom:20px;">
                    <p style="font-size:14px;font-weight:500;color:#1a1a2e;margin:0 0 10px;display:flex;align-items:center;gap:8px;">
                        <span>Graduación Lectura: <span id="selected-grad-lectura" style="font-weight:400;color:#666;">— Selecciona</span></span>
                        <button type="button" id="clear-grad-lectura-btn" onclick="clearGraduation()" style="display:none;background:none;border:none;color:#378ADD;font-size:12px;font-weight:500;cursor:pointer;text-decoration:underline;padding:0;">Quitar</button>
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach($graduacionesLectura as $grad)
                            @php $gradOutOfStock = ($stockByGradLectura[$grad] ?? 0) <= 0; @endphp
                            <div class="grad-btn grad-lectura"
                                 data-grad="{{ $grad }}"
                                 data-tipo="lectura"
                                 data-grad-type="lectura"
                                 data-out-of-stock="{{ $gradOutOfStock ? '1' : '0' }}"
                                 @if(!$gradOutOfStock) onclick="selectGrad(this,'lectura')" @endif
                                 title="{{ $gradOutOfStock ? 'Agotado' : '' }}"
                                 style="padding:6px 14px;border-radius:8px;border:1px solid #ddd;
                                        background:#fff;font-size:13px;color:#444;
                                        transition:all .15s;
                                        {{ $gradOutOfStock ? 'opacity:0.45;cursor:not-allowed;text-decoration:line-through;' : 'cursor:pointer;' }}">
                                {{ $grad }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($graduacionesSinGrad->count() > 0 || ($product->hasType('sin_graduacion') && $graduacionesMiopia->count() === 0 && $graduacionesLectura->count() === 0))
                <div style="margin-bottom:20px;">
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;
                                padding:10px 14px;font-size:13px;color:#166534;">
                        ✓ Sin graduación — protección de luz azul pura
                    </div>
                </div>
                @endif

                {{-- Descripción corta --}}
                @if($product->description)
                <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:20px;">
                    {{ $product->description }}
                </p>
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
                            <span style="width:36px;text-align:center;font-size:14px;font-weight:600;color:#1a1a2e;"
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
                                background: adding ? '#1a1a2e' : (hoverBtn && {{ $productHasStock ? 'true' : 'false' }} ? '#378ADD' : '#1a1a2e'),
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

                    @if($product->badge_2x1)
                    <p style="font-size:12px;color:#888;text-align:center;margin-top:8px;">
                        ¿Buscas el 2×1? Agrega dos lentes al carrito
                    </p>
                    @endif
                </div>

                {{-- 9. Beneficios rápidos --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:24px;">
                    @php
                        $beneficios = $lentesPage->product_benefits ?? [
                            'Envío gratis +$999',
                            'Garantía 6 meses',
                            '30 días devolución',
                            'Filtro luz azul certificado',
                        ];
                    @endphp
                    @foreach($beneficios as $b)
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#666;">
                        <svg style="width:14px;height:14px;color:#16a34a;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
         SUGERENCIA DE TOALLITAS
         ============================================================ --}}
    @if($toallitas->count() > 0 && !$product->hasType('toallitas'))
    <section style="background:#f8f9fa;padding:48px 24px;">
        <div style="max-width:1100px;margin:0 auto;">

            {{-- Separador --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:32px;">
                <div style="flex:1;height:1px;background:#e5e5e5;"></div>
                <span style="font-size:13px;color:#aaa;white-space:nowrap;">Te sugerimos complementar tu compra con...</span>
                <div style="flex:1;height:1px;background:#e5e5e5;"></div>
            </div>

            <h2 style="font-size:20px;font-weight:600;color:#1a1a2e;text-align:center;margin:0 0 8px;">
                Mantén tus lentes impecables
            </h2>
            <p style="font-size:14px;color:#888;text-align:center;margin:0 0 32px;">
                Kit limpiador 2 en 1 — paño húmedo + seco
            </p>

            <div class="toallitas-grid" style="display:grid;gap:20px;max-width:600px;margin:0 auto;">
                @foreach($toallitas as $toallita)
                @php $tImg = $toallita->images[0] ?? null; @endphp
                <div style="background:#fff;border-radius:12px;overflow:hidden;
                            border:0.5px solid rgba(0,0,0,0.08);cursor:pointer;
                            transition:transform .2s ease,box-shadow .2s ease;"
                     onclick="location.href='{{ route('products.show', $toallita->slug) }}'"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

                    <div style="height:140px;overflow:hidden;">
                        @if($tImg)
                            <img src="{{ asset('storage/' . $tImg) }}" alt="{{ $toallita->name }}"
                                 loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;
                                background:linear-gradient(135deg,#2d1a0d,#6e3d1a);
                                display:flex;align-items:center;justify-content:center;">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                    <rect x="8" y="14" width="32" height="22" rx="4"
                                          stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                                    <path d="M14 20h20M14 26h14"
                                          stroke="rgba(255,255,255,0.3)" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div style="padding:14px 16px;">
                        <h4 style="font-size:14px;font-weight:600;color:#1a1a2e;margin:0 0 6px;">
                            {{ $toallita->name }}
                        </h4>
                        @if($toallita->description)
                        <p style="font-size:12px;color:#888;margin:0 0 12px;line-height:1.5;">
                            {{ Str::limit($toallita->description, 80) }}
                        </p>
                        @endif
                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:18px;font-weight:700;color:#1a1a2e;">
                                ${{ number_format($toallita->price, 2) }}
                            </span>
                            <a href="{{ route('products.show', $toallita->slug) }}"
                               onclick="event.stopPropagation()"
                               style="font-size:13px;color:#378ADD;font-weight:500;text-decoration:none;"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Ver detalle →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <p style="font-size:12px;color:#aaa;text-align:center;margin-top:20px;font-style:italic;">
                Compatibles con todos los modelos de lentes nuvion glass y cualquier superficie óptica
            </p>

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

/* ── Lista completa de variantes activas con stock para calcular disponibilidad cruzada ── */
@php
    $variantStockData = $product->variants->where('is_active', true)->map(function ($v) {
        return [
            'color' => $v->color,
            'graduation' => $v->graduation,
            'graduation_type' => $v->graduation_type,
            'stock' => (int) $v->stock,
        ];
    })->values();
@endphp
window.variantStockData = @json($variantStockData);
window.currentSelection = { color: null, gradType: null, grad: null };

/**
 * Recalcula qué colores y qué graduaciones siguen disponibles dado lo que ya se eligió.
 * - Un color está disponible si existe alguna variante con ese color y la graduación
 *   seleccionada (si la hay) que tenga stock > 0.
 * - Una graduación está disponible si existe alguna variante con esa graduación y el
 *   color seleccionado (si lo hay) que tenga stock > 0.
 */
function refreshVariantAvailability() {
    var sel = window.currentSelection;
    var data = window.variantStockData || [];

    // ── Colores ──
    document.querySelectorAll('.color-btn').forEach(function (btn) {
        var color = btn.dataset.color;
        var stock = data
            .filter(function (v) {
                if (v.color !== color) return false;
                if (sel.gradType && sel.grad) {
                    return v.graduation_type === sel.gradType && v.graduation === sel.grad;
                }
                return true;
            })
            .reduce(function (sum, v) { return sum + (v.stock || 0); }, 0);

        var outOfStock = stock <= 0;
        btn.dataset.outOfStock = outOfStock ? '1' : '0';
        btn.style.opacity = outOfStock ? '0.4' : '1';
        btn.style.cursor = outOfStock ? 'not-allowed' : 'pointer';
        btn.title = color + (outOfStock ? ' (Agotado para esta combinación)' : '');

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

    // ── Graduaciones ──
    document.querySelectorAll('.grad-btn').forEach(function (btn) {
        var grad = btn.dataset.grad;
        var gradType = btn.dataset.gradType || (btn.closest('[data-grad-type]') && btn.closest('[data-grad-type]').dataset.gradType);
        // Fallback: deducir gradType del onclick original (miopia/lectura).
        if (!gradType) {
            var oc = btn.getAttribute('onclick') || '';
            if (oc.indexOf("'miopia'") !== -1) gradType = 'miopia';
            else if (oc.indexOf("'lectura'") !== -1) gradType = 'lectura';
        }

        var stock = data
            .filter(function (v) {
                if (v.graduation !== grad || v.graduation_type !== gradType) return false;
                if (sel.color) {
                    return v.color === sel.color;
                }
                return true;
            })
            .reduce(function (sum, v) { return sum + (v.stock || 0); }, 0);

        var outOfStock = stock <= 0;
        btn.dataset.outOfStock = outOfStock ? '1' : '0';
        btn.style.opacity = outOfStock ? '0.45' : '1';
        btn.style.textDecoration = outOfStock ? 'line-through' : 'none';
        btn.style.cursor = outOfStock ? 'not-allowed' : 'pointer';
        btn.title = outOfStock ? 'Agotado para esta combinación' : '';

        if (outOfStock) {
            btn.onclick = null;
        } else {
            btn.onclick = (function (b, t) { return function () { selectGrad(b, t); }; })(btn, gradType);
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

/* ── Helpers para mostrar/ocultar los botones "Quitar" ── */
function updateClearButtons() {
    var sel = window.currentSelection;
    var clearColorBtn = document.getElementById('clear-color-btn');
    if (clearColorBtn) {
        clearColorBtn.style.display = sel.color ? 'inline-block' : 'none';
    }
    var clearMiopiaBtn = document.getElementById('clear-grad-miopia-btn');
    var clearLecturaBtn = document.getElementById('clear-grad-lectura-btn');
    if (clearMiopiaBtn) {
        clearMiopiaBtn.style.display = (sel.gradType === 'miopia') ? 'inline-block' : 'none';
    }
    if (clearLecturaBtn) {
        clearLecturaBtn.style.display = (sel.gradType === 'lectura') ? 'inline-block' : 'none';
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
        btn.style.borderColor = '#378ADD';
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

/* ── Graduation selector ── */
function selectGrad(el, tipo) {
    // Toggle: si la graduación ya está seleccionada, la deseleccionamos.
    if (window.currentSelection.gradType === tipo && window.currentSelection.grad === el.dataset.grad) {
        clearGraduation();
        return;
    }

    // Reset ALL graduation buttons (both miopia and lectura) — only one can be selected at a time
    document.querySelectorAll('.grad-btn').forEach(function(b) {
        b.style.background = '#fff';
        b.style.borderColor = '#ddd';
        b.style.color = '#444';
    });

    // Reset both labels
    var labelMiopia = document.getElementById('selected-grad-miopia');
    var labelLectura = document.getElementById('selected-grad-lectura');
    if (labelMiopia) labelMiopia.textContent = '— Selecciona';
    if (labelLectura) labelLectura.textContent = '— Selecciona';

    // Activate the clicked one
    el.style.background = '#1a1a2e';
    el.style.borderColor = '#1a1a2e';
    el.style.color = '#fff';
    var label = document.getElementById('selected-grad-' + tipo);
    if (label) label.textContent = el.dataset.grad;

    window.currentSelection.gradType = tipo;
    window.currentSelection.grad = el.dataset.grad;

    // Si el color actualmente seleccionado no tiene stock para esta graduación, lo deseleccionamos.
    var sel = window.currentSelection;
    var data = window.variantStockData || [];
    if (sel.color) {
        var stillValid = data.some(function (v) {
            return v.color === sel.color && v.graduation_type === sel.gradType && v.graduation === sel.grad && v.stock > 0;
        });
        if (!stillValid) {
            sel.color = null;
            var lbl = document.getElementById('selected-color-name');
            if (lbl) lbl.textContent = '— Selecciona un color';
            document.querySelectorAll('.color-btn').forEach(function (b) {
                b.style.borderColor = 'transparent';
                b.style.boxShadow = 'none';
            });
        }
    }

    refreshVariantAvailability();
    updateClearButtons();
    window.dispatchEvent(new CustomEvent('variant-selection-changed'));
}

/* ── Limpiar graduación ── */
function clearGraduation() {
    document.querySelectorAll('.grad-btn').forEach(function (b) {
        b.style.background = '#fff';
        b.style.borderColor = '#ddd';
        b.style.color = '#444';
    });
    var labelMiopia = document.getElementById('selected-grad-miopia');
    var labelLectura = document.getElementById('selected-grad-lectura');
    if (labelMiopia) labelMiopia.textContent = '— Selecciona';
    if (labelLectura) labelLectura.textContent = '— Selecciona';

    window.currentSelection.gradType = null;
    window.currentSelection.grad = null;

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
                    if (sel.gradType && sel.grad) {
                        if (v.graduation_type !== sel.gradType || v.graduation !== sel.grad) return sum;
                    }
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

            // Find selected variant
            var selectedColor = document.getElementById('selected-color-name');
            var colorName = selectedColor ? selectedColor.textContent : null;

            // Find the matching variant ID
            var variantId = null;
            @if($product->variants->count())
            @php
                $variantData = $product->variants->where('is_active', true)->map(function($v) {
                    return ['id' => $v->id, 'color' => $v->color, 'graduation' => $v->graduation, 'graduation_type' => $v->graduation_type];
                })->values();
            @endphp
            var variants = @json($variantData);

            // Try to match by color + selected graduation
            var selectedGradMiopia = document.getElementById('selected-grad-miopia');
            var selectedGradLectura = document.getElementById('selected-grad-lectura');
            var gradMiopia = selectedGradMiopia && selectedGradMiopia.textContent !== '— Selecciona' ? selectedGradMiopia.textContent : null;
            var gradLectura = selectedGradLectura && selectedGradLectura.textContent !== '— Selecciona' ? selectedGradLectura.textContent : null;

            for (var i = 0; i < variants.length; i++) {
                var v = variants[i];
                if (colorName && v.color === colorName) {
                    if (gradMiopia && v.graduation === gradMiopia && v.graduation_type === 'miopia') {
                        variantId = v.id;
                        break;
                    }
                    if (gradLectura && v.graduation === gradLectura && v.graduation_type === 'lectura') {
                        variantId = v.id;
                        break;
                    }
                    if (!gradMiopia && !gradLectura) {
                        variantId = v.id;
                        break;
                    }
                }
            }
            // Fallback to first variant if none matched
            if (!variantId && variants.length > 0) {
                variantId = variants[0].id;
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
.toallitas-grid {
    grid-template-columns: repeat(2, 1fr);
}
@media (max-width: 768px) {
    .product-layout {
        grid-template-columns: 1fr;
        gap: 32px;
    }
    .toallitas-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
