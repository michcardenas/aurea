@extends('layouts.app')

@section('body_class', 'bg-bg-light text-text-dark')

@section('title', $seoSettings->meta_title ?? 'Nuvion Glass | Protege tus ojos de la luz azul')
@section('meta_description', $seoSettings->meta_description ?? 'Lentes con protección de luz azul. Con o sin graduación. Diseño moderno que querrás usar todo el día. Envío gratis a todo México.')
@section('robots', $seoSettings->robots ?? 'index, follow')
@section('canonical', $seoSettings->canonical_url ?? url()->current())
@section('og_type', $seoSettings->og_type ?? 'website')
@section('og_title', $seoSettings->og_title ?? $seoSettings->meta_title ?? 'Nuvion Glass | Lentes con protección de luz azul')
@section('og_description', $seoSettings->og_description ?? $seoSettings->meta_description ?? 'Protege tus ojos de las pantallas. Lentes con filtro de luz azul, con o sin graduación. Envío gratis.')
@section('og_image', $seoSettings->og_image_url ?? asset('images/og-default.jpg'))
@section('twitter_card', $seoSettings->twitter_card ?? 'summary_large_image')
@section('twitter_title', $seoSettings->twitter_title ?? $seoSettings->meta_title ?? 'Nuvion Glass | Lentes con protección de luz azul')
@section('twitter_description', $seoSettings->twitter_description ?? $seoSettings->meta_description ?? 'Protege tus ojos de las pantallas. Lentes con filtro de luz azul, con o sin graduación. Envío gratis.')
@section('twitter_image', $seoSettings->twitter_image_url ?? $seoSettings->og_image_url ?? asset('images/og-default.jpg'))

@push('schema')
    {!! $organizationSchema !!}
    {!! $faqSchema !!}
    @if($seoSettings && $seoSettings->custom_schema_markup)
        {!! $seoSettings->custom_schema_markup !!}
    @endif
@endpush

@section('content')

    @php
        // Normalize trust bar items (supports legacy string arrays / double-encoded JSON / fills defaults).
        $trustDefaults = [
            ['icon' => '✓', 'text' => 'Filtro certificado'],
            ['icon' => '📦', 'text' => 'Envío gratis +$999'],
            ['icon' => '↩', 'text' => '30 días devolución'],
            ['icon' => '★', 'text' => 'Garantía 6 meses'],
        ];
        $rawTrust = $hero->trust_items ?? [];

        // Guard against double-encoded JSON strings still sitting in the column.
        if (is_string($rawTrust)) {
            $decoded = json_decode($rawTrust, true);
            $rawTrust = is_array($decoded) ? $decoded : [];
        }

        $trustItems = [];
        foreach ((array) $rawTrust as $t) {
            // Legacy: a bare string in the list
            if (is_string($t)) {
                $text = trim($t);
                if ($text !== '') {
                    $trustItems[] = ['icon' => '✓', 'text' => $text];
                }
                continue;
            }
            // New: associative array with icon + text
            if (is_array($t)) {
                $text = isset($t['text']) ? trim((string) $t['text']) : '';
                if ($text !== '') {
                    $icon = isset($t['icon']) && trim((string) $t['icon']) !== ''
                        ? (string) $t['icon']
                        : '✓';
                    $trustItems[] = ['icon' => $icon, 'text' => $text];
                }
            }
        }

        if (empty($trustItems)) {
            $trustItems = $trustDefaults;
        }
    @endphp

    {{-- ============================================================
         1. HERO — split claro (default) o video full width
         ============================================================ --}}
    <style>
        @keyframes hSlideUp {
            from { opacity:0; transform:translateY(22px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes hSlideRight {
            from { opacity:0; transform:translateX(20px); }
            to   { opacity:1; transform:translateX(0); }
        }
        @keyframes hFloatIn {
            from { opacity:0; transform:scale(.94); }
            to   { opacity:1; transform:scale(1); }
        }

        .h-anim-1 { animation: hSlideUp .7s ease .05s both; }
        .h-anim-2 { animation: hSlideUp .7s ease .15s both; }
        .h-anim-3 { animation: hSlideUp .7s ease .25s both; }
        .h-anim-4 { animation: hSlideUp .7s ease .35s both; }
        .h-anim-5 { animation: hSlideUp .7s ease .45s both; }
        .h-anim-6 { animation: hSlideUp .7s ease .55s both; }
        .h-img-anim { animation: hSlideRight .8s ease .2s both; }
        .h-float-anim { animation: hFloatIn .6s ease both; }
        .h-float-anim:nth-child(2) { animation-delay:.5s; }
        .h-float-anim:nth-child(3) { animation-delay:.7s; }

        .h-btn-dark {
            background: #0d1117;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px 26px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background .2s, transform .15s;
        }
        .h-btn-dark:hover {
            background: #378ADD;
            transform: translateY(-1px);
        }

        .h-btn-outline {
            background: transparent;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 13px 26px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all .2s;
        }
        .h-btn-outline:hover {
            border-color: #378ADD;
            color: #378ADD;
        }

        .h-trust-bar {
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
            padding: 14px 6%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 28px;
            flex-wrap: wrap;
        }
        .h-trust-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
        }
        .h-trust-icon {
            width: 22px; height: 22px;
            background: #EBF4FF;
            border-radius: 50%;
            display: flex; align-items: center;
            justify-content: center;
            font-size: 11px; color: #378ADD;
            flex-shrink: 0;
        }

        .h-float-badge {
            position: absolute;
            background: rgba(255,255,255,0.95);
            border-radius: 10px;
            padding: 10px 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.18);
            backdrop-filter: blur(8px);
        }
        .h-float-num {
            font-size: 20px;
            font-weight: 700;
            color: #0d1117;
            line-height: 1;
        }
        .h-float-lbl {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .h-split-grid {
                grid-template-columns: 1fr !important;
            }
            .h-split-right {
                height: 300px !important;
                min-height: 0 !important;
            }
            .h-float-badge { display: none !important; }
            .h-split-left {
                padding: 48px 24px 32px !important;
            }
            .h-trust-bar { gap: 16px; }
            button[aria-label="Anterior"],
            button[aria-label="Siguiente"] {
                display: none !important;
            }
            /* Hero full width en mobile */
            .h-hero-fullwidth {
                height: 100svh !important;
                min-height: 520px !important;
                max-height: none !important;
            }
            .h-hero-fullwidth h1 {
                font-size: clamp(28px, 8vw, 40px) !important;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .h-anim-1,.h-anim-2,.h-anim-3,
            .h-anim-4,.h-anim-5,.h-anim-6,
            .h-img-anim,.h-float-anim {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
        .hero-carousel-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center center !important;
            display: block !important;
            position: absolute !important;
            inset: 0 !important;
        }
        @media (max-width: 640px) {
            /* Comparativo en mobile: una columna */
            .comparativo-grid {
                grid-template-columns: 1fr !important;
            }
        }
        /* Categorías responsive */
        @media (max-width: 768px) {
            .cats-grid {
                grid-template-columns: 1fr !important;
            }
        }
        @media (min-width: 640px) and (max-width: 1023px) {
            .cats-grid {
                grid-template-columns: repeat(2,1fr) !important;
            }
        }
        /* Fix Tailwind img height override */
        .cat-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }
        /* Flip card */
        .flip-card {
            perspective: 1000px;
            height: 440px;
        }
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.7s ease;
            transform-style: preserve-3d;
        }
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }
        .flip-card-face {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            border-radius: 16px;
            overflow: hidden;
        }
        .flip-card-back {
            transform: rotateY(180deg);
        }
        @media (max-width: 768px) {
            .flip-card { height: 400px; }
        }
    </style>

    @php
        $heroImages = $hero->hero_images ?? [];
        $useFullWidth = count($heroImages) >= 1;
    @endphp

    @if($heroMode === 'split' && $useFullWidth)
    {{-- ===== MODO A-FULLWIDTH: imagen(es) de fondo + texto encima ===== --}}
    <section class="h-hero-fullwidth" style="position:relative;width:100%;height:calc(100vh - 64px);min-height:580px;max-height:820px;overflow:hidden;background:#0d1117;">

        {{-- CARRUSEL / IMAGEN FULL WIDTH --}}
        @if(count($heroImages) > 1)
        <div x-data="{
                current: 0,
                total: {{ count($heroImages) }},
                paused: false,
                progress: 0,
                interval: null,
                raf: null,
                startX: 0,
                init() { this.startAutoplay(); },
                startAutoplay() {
                    this.resetProgress();
                    this.interval = setInterval(() => { if (!this.paused) this.next(); }, 5000);
                },
                resetProgress() {
                    this.progress = 0;
                    if (this.raf) cancelAnimationFrame(this.raf);
                    let start = performance.now();
                    const tick = (now) => {
                        if (this.paused) { start = now - (this.progress / 100 * 5000); this.raf = requestAnimationFrame(tick); return; }
                        this.progress = Math.min(((now - start) / 5000) * 100, 100);
                        if (this.progress < 100) this.raf = requestAnimationFrame(tick);
                    };
                    this.raf = requestAnimationFrame(tick);
                },
                next() { this.current = (this.current + 1) % this.total; this.restartTimer(); },
                prev() { this.current = (this.current - 1 + this.total) % this.total; this.restartTimer(); },
                goTo(i) { this.current = i; this.restartTimer(); },
                restartTimer() { clearInterval(this.interval); this.startAutoplay(); },
                touchStart(e) { this.startX = e.touches[0].clientX; },
                touchEnd(e) {
                    const diff = this.startX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 40) { diff > 0 ? this.next() : this.prev(); }
                }
             }"
             @mouseenter="paused = true" @mouseleave="paused = false"
             @touchstart.passive="touchStart($event)"
             @touchend.passive="touchEnd($event)"
             style="position:absolute;inset:0;width:100%;height:100%;user-select:none;-webkit-user-select:none;">

            {{-- Slides --}}
            @foreach($heroImages as $i => $img)
            <div style="position:absolute;top:0;left:0;width:100%;height:100%;transition:opacity .8s ease;{{ $i === 0 ? 'opacity:1;z-index:2;' : 'opacity:0;z-index:1;' }}"
                 :style="current === {{ $i }} ? 'opacity:1;z-index:2;' : 'opacity:0;z-index:1;'">
                <img src="{{ asset('storage/'.$img) }}"
                     alt="nuvion glass lentes filtro luz azul"
                     loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                     class="hero-carousel-img"
                     style="position:absolute !important;top:0 !important;left:0 !important;width:100% !important;height:100% !important;object-fit:cover !important;object-position:center center !important;display:block !important;"
                     :style="current === {{ $i }} ? 'transform:scale(1.04);transition:transform 8s ease;' : 'transform:scale(1);transition:transform 8s ease;'">
            </div>
            @endforeach

            {{-- Overlay izquierda --}}
            <div style="position:absolute;inset:0;background:linear-gradient(105deg,rgba(10,14,26,0.88) 0%,rgba(10,14,26,0.72) 30%,rgba(10,14,26,0.4) 55%,rgba(10,14,26,0.1) 80%,rgba(10,14,26,0.0) 100%);pointer-events:none;z-index:3;"></div>
            {{-- Overlay inferior para controles --}}
            <div style="position:absolute;bottom:0;left:0;right:0;height:160px;background:linear-gradient(to top,rgba(10,14,26,0.6),transparent);pointer-events:none;z-index:3;"></div>

            {{-- Contenido texto --}}
            <div style="position:absolute;inset:0;z-index:4;display:flex;align-items:center;">
                <div style="width:100%;max-width:1200px;margin:0 auto;padding:0 6%;">
                    <div style="max-width:600px;">

                        {{-- Eyebrow --}}
                        <div class="h-anim-1" style="display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.1);border:0.5px solid rgba(255,255,255,0.2);border-radius:20px;padding:5px 14px;font-size:11px;color:rgba(255,255,255,0.85);letter-spacing:.08em;text-transform:uppercase;margin-bottom:20px;width:fit-content;backdrop-filter:blur(4px);">
                            <span style="width:6px;height:6px;border-radius:50%;background:#378ADD;flex-shrink:0;"></span>
                            {{ $hero->eyebrow_text ?? 'Protección de luz azul' }}
                        </div>

                        {{-- Título --}}
                        @php
                            $t1 = $hero->title_line1 ?? 'Lentes que cuidan';
                            $t2 = $hero->title_line2 ?? 'tus ojos de las';
                            $t3 = $hero->title_line3 ?? 'pantallas';
                            $hl = $hero->title_highlight_word ?? 'pantallas';
                        @endphp
                        <h1 class="h-anim-2" style="font-size:clamp(36px,5vw,62px);font-weight:800;color:#ffffff;line-height:1.05;letter-spacing:-.025em;margin-bottom:18px;font-family:'Bai Jamjuree',sans-serif;">
                            {{ $t1 }}<br>{{ $t2 }}<br>
                            @if($hl && str_contains($t3, $hl))
                                {!! str_replace($hl, '<span style="color:#378ADD;">'.$hl.'</span>', e($t3)) !!}
                            @else
                                {{ $t3 }}
                            @endif
                        </h1>

                        {{-- Badge 2x1 --}}
                        @if($hero->badge_text ?? true)
                        <div class="h-anim-3" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:9px 16px;font-size:13px;color:rgba(255,255,255,0.9);margin-bottom:20px;width:fit-content;backdrop-filter:blur(4px);">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <rect x="1" y="3.5" width="12" height="9" rx="1.5" stroke="rgba(255,255,255,0.7)" stroke-width="1.2"/>
                                <path d="M5 3.5V3a2 2 0 014 0v.5" stroke="rgba(255,255,255,0.7)" stroke-width="1.2"/>
                            </svg>
                            {!! str_replace(
                                ['2x1', '$499.90'],
                                ['<strong style="font-weight:700;color:#fff;">2x1</strong>', '<strong style="font-weight:700;color:#378ADD;">$499.90</strong>'],
                                e($hero->badge_text ?? '2x1 en todos los lentes · $499.90 c/u')
                            ) !!}
                        </div>
                        @endif

                        {{-- Subtítulo --}}
                        <p class="h-anim-4" style="font-size:16px;color:rgba(255,255,255,0.6);line-height:1.65;margin-bottom:28px;max-width:420px;">
                            {{ $hero->subtitle ?? 'Con o sin graduación. Filtro de luz azul de alta eficiencia en todos los modelos.' }}
                        </p>

                        {{-- Botones --}}
                        <div class="h-anim-5" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
                            <a href="{{ $hero->btn_primary_url ?? '/lentes' }}" class="h-btn-dark" style="background:#378ADD;">
                                {{ $hero->btn_primary_text ?? 'Ver lentes' }} →
                            </a>
                            <a href="{{ $hero->btn_secondary_url ?? '/que-es-la-luz-azul' }}"
                               style="background:rgba(255,255,255,0.08);color:#fff;border:1.5px solid rgba(255,255,255,0.22);border-radius:8px;padding:13px 26px;font-size:15px;text-decoration:none;display:inline-block;transition:all .2s;"
                               onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.borderColor='rgba(255,255,255,0.4)'"
                               onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.22)'">
                                {{ $hero->btn_secondary_text ?? '¿Qué es la luz azul?' }}
                            </a>
                        </div>

                        {{-- Trust items --}}
                        <div class="h-anim-6" style="display:flex;gap:16px;flex-wrap:wrap;">
                            @foreach($trustItems as $item)
                            <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:rgba(255,255,255,0.4);">
                                <span style="color:#22c55e;font-size:11px;">✓</span>
                                {{ $item['text'] }}
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- Flecha izquierda --}}
            <button @click="prev()" aria-label="Anterior"
                    style="position:absolute;top:50%;left:16px;transform:translateY(-50%);z-index:5;width:40px;height:40px;border-radius:50%;border:0.5px solid rgba(255,255,255,0.2);cursor:pointer;background:rgba(255,255,255,0.12);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;transition:all .2s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.22)';this.style.transform='translateY(-50%) scale(1.08)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.12)';this.style.transform='translateY(-50%) scale(1)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
            </button>

            {{-- Flecha derecha --}}
            <button @click="next()" aria-label="Siguiente"
                    style="position:absolute;top:50%;right:16px;transform:translateY(-50%);z-index:5;width:40px;height:40px;border-radius:50%;border:0.5px solid rgba(255,255,255,0.2);cursor:pointer;background:rgba(255,255,255,0.12);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;transition:all .2s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.22)';this.style.transform='translateY(-50%) scale(1.08)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.12)';this.style.transform='translateY(-50%) scale(1)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Dots + Progress bar --}}
            <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);z-index:5;display:flex;flex-direction:column;align-items:center;gap:8px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    @foreach($heroImages as $i => $img)
                    <button @click="goTo({{ $i }})"
                            :style="current === {{ $i }} ? 'width:22px;background:#fff;' : 'width:7px;background:rgba(255,255,255,0.3);'"
                            style="height:7px;border-radius:4px;border:none;cursor:pointer;transition:all .3s ease;"></button>
                    @endforeach
                </div>
                <div style="width:64px;height:2px;border-radius:2px;background:rgba(255,255,255,0.15);overflow:hidden;">
                    <div :style="'width:' + progress + '%;'" style="height:100%;background:#fff;border-radius:2px;"></div>
                </div>
            </div>

            {{-- Indicador scroll --}}
            <div style="position:absolute;bottom:22px;right:32px;z-index:5;display:flex;flex-direction:column;align-items:center;gap:5px;">
                <div style="font-size:9px;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,0.25);">Scroll</div>
                <div style="width:1px;height:32px;background:linear-gradient(to bottom,rgba(255,255,255,0.2),transparent);"></div>
            </div>
        </div>

        @else
        {{-- Imagen única full width --}}
        <img src="{{ asset('storage/'.$heroImages[0]) }}"
             alt="nuvion glass lentes filtro luz azul"
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;">

        {{-- Overlay izquierda --}}
        <div style="position:absolute;inset:0;background:linear-gradient(105deg,rgba(10,14,26,0.88) 0%,rgba(10,14,26,0.72) 30%,rgba(10,14,26,0.4) 55%,rgba(10,14,26,0.1) 80%,rgba(10,14,26,0.0) 100%);pointer-events:none;z-index:3;"></div>

        {{-- Contenido texto --}}
        <div style="position:absolute;inset:0;z-index:4;display:flex;align-items:center;">
            <div style="width:100%;max-width:1200px;margin:0 auto;padding:0 6%;">
                <div style="max-width:600px;">

                    <div class="h-anim-1" style="display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.1);border:0.5px solid rgba(255,255,255,0.2);border-radius:20px;padding:5px 14px;font-size:11px;color:rgba(255,255,255,0.85);letter-spacing:.08em;text-transform:uppercase;margin-bottom:20px;width:fit-content;backdrop-filter:blur(4px);">
                        <span style="width:6px;height:6px;border-radius:50%;background:#378ADD;flex-shrink:0;"></span>
                        {{ $hero->eyebrow_text ?? 'Protección de luz azul' }}
                    </div>

                    @php
                        $t1 = $hero->title_line1 ?? 'Lentes que cuidan';
                        $t2 = $hero->title_line2 ?? 'tus ojos de las';
                        $t3 = $hero->title_line3 ?? 'pantallas';
                        $hl = $hero->title_highlight_word ?? 'pantallas';
                    @endphp
                    <h1 class="h-anim-2" style="font-size:clamp(36px,5vw,62px);font-weight:800;color:#ffffff;line-height:1.05;letter-spacing:-.025em;margin-bottom:18px;font-family:'Bai Jamjuree',sans-serif;">
                        {{ $t1 }}<br>{{ $t2 }}<br>
                        @if($hl && str_contains($t3, $hl))
                            {!! str_replace($hl, '<span style="color:#378ADD;">'.$hl.'</span>', e($t3)) !!}
                        @else
                            {{ $t3 }}
                        @endif
                    </h1>

                    @if($hero->badge_text ?? true)
                    <div class="h-anim-3" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:8px;padding:9px 16px;font-size:13px;color:rgba(255,255,255,0.9);margin-bottom:20px;width:fit-content;backdrop-filter:blur(4px);">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <rect x="1" y="3.5" width="12" height="9" rx="1.5" stroke="rgba(255,255,255,0.7)" stroke-width="1.2"/>
                            <path d="M5 3.5V3a2 2 0 014 0v.5" stroke="rgba(255,255,255,0.7)" stroke-width="1.2"/>
                        </svg>
                        {!! str_replace(
                            ['2x1', '$499.90'],
                            ['<strong style="font-weight:700;color:#fff;">2x1</strong>', '<strong style="font-weight:700;color:#378ADD;">$499.90</strong>'],
                            e($hero->badge_text ?? '2x1 en todos los lentes · $499.90 c/u')
                        ) !!}
                    </div>
                    @endif

                    <p class="h-anim-4" style="font-size:16px;color:rgba(255,255,255,0.6);line-height:1.65;margin-bottom:28px;max-width:420px;">
                        {{ $hero->subtitle ?? 'Con o sin graduación. Filtro de luz azul de alta eficiencia en todos los modelos.' }}
                    </p>

                    <div class="h-anim-5" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
                        <a href="{{ $hero->btn_primary_url ?? '/lentes' }}" class="h-btn-dark" style="background:#378ADD;">
                            {{ $hero->btn_primary_text ?? 'Ver lentes' }} →
                        </a>
                        <a href="{{ $hero->btn_secondary_url ?? '/que-es-la-luz-azul' }}"
                           style="background:rgba(255,255,255,0.08);color:#fff;border:1.5px solid rgba(255,255,255,0.22);border-radius:8px;padding:13px 26px;font-size:15px;text-decoration:none;display:inline-block;transition:all .2s;"
                           onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.borderColor='rgba(255,255,255,0.4)'"
                           onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.borderColor='rgba(255,255,255,0.22)'">
                            {{ $hero->btn_secondary_text ?? '¿Qué es la luz azul?' }}
                        </a>
                    </div>

                    <div class="h-anim-6" style="display:flex;gap:16px;flex-wrap:wrap;">
                        @foreach($trustItems as $item)
                        <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:rgba(255,255,255,0.4);">
                            <span style="color:#22c55e;font-size:11px;">✓</span>
                            {{ $item['text'] }}
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        @endif

        {{-- Trust bar fija al fondo del hero --}}
        @if(count($trustItems))
        <div style="position:absolute;bottom:0;left:0;right:0;z-index:6;">
            <div class="h-trust-bar" style="background:rgba(255,255,255,0.06);backdrop-filter:blur(12px);border-top:0.5px solid rgba(255,255,255,0.1);padding:12px 6%;">
                @foreach($trustItems as $t)
                <div class="h-trust-item" style="color:rgba(255,255,255,0.55);">
                    <div class="h-trust-icon">{{ $t['icon'] }}</div>
                    {{ $t['text'] }}
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </section>

    @elseif($heroMode === 'split' && !$useFullWidth)
    {{-- ===== MODO A-SPLIT: dos columnas (sin imágenes en galería) ===== --}}
    <section style="background:#ffffff;overflow:hidden;">
        <div class="h-split-grid" style="display:grid;grid-template-columns:1fr 1fr;min-height:580px;">

            {{-- COLUMNA IZQUIERDA: Texto --}}
            <div class="h-split-left" style="display:flex;flex-direction:column;justify-content:center;padding:72px 48px 72px 6%;background:#ffffff;">

                <div class="h-anim-1" style="display:inline-flex;align-items:center;gap:7px;background:#EBF4FF;border:0.5px solid #B5D4F4;border-radius:20px;padding:5px 14px;font-size:11px;color:#185FA5;letter-spacing:.08em;text-transform:uppercase;margin-bottom:20px;width:fit-content;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#378ADD;flex-shrink:0;"></span>
                    {{ $hero->eyebrow_text ?? 'Protección de luz azul' }}
                </div>

                @php
                    $t1 = $hero->title_line1 ?? 'Lentes que cuidan';
                    $t2 = $hero->title_line2 ?? 'tus ojos de las';
                    $t3 = $hero->title_line3 ?? 'pantallas';
                    $hl = $hero->title_highlight_word ?? 'pantallas';
                @endphp
                <h1 class="h-anim-2" style="font-size:clamp(34px,4.2vw,54px);font-weight:800;color:#0d1117;line-height:1.05;letter-spacing:-.025em;margin-bottom:18px;font-family:'Bai Jamjuree',sans-serif;">
                    {{ $t1 }}<br>{{ $t2 }}<br>
                    @if($hl && str_contains($t3, $hl))
                        {!! str_replace($hl, '<span style="color:#378ADD;">'.$hl.'</span>', e($t3)) !!}
                    @else
                        {{ $t3 }}
                    @endif
                </h1>

                @if($hero->badge_text ?? true)
                <div class="h-anim-3" style="display:inline-flex;align-items:center;gap:8px;background:#F0F7FF;border:1px solid #BFDBFE;border-radius:8px;padding:9px 16px;font-size:13px;color:#1e40af;margin-bottom:20px;width:fit-content;">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <rect x="1" y="3.5" width="12" height="9" rx="1.5" stroke="#1e40af" stroke-width="1.2"/>
                        <path d="M5 3.5V3a2 2 0 014 0v.5" stroke="#1e40af" stroke-width="1.2"/>
                    </svg>
                    {!! str_replace(
                        ['2x1', '$499.90'],
                        ['<strong style="font-weight:700;">2x1</strong>', '<strong style="font-weight:700;">$499.90</strong>'],
                        e($hero->badge_text ?? '2x1 en todos los lentes · $499.90 c/u')
                    ) !!}
                </div>
                @endif

                <p class="h-anim-4" style="font-size:15px;color:#6b7280;line-height:1.65;margin-bottom:28px;max-width:400px;">
                    {{ $hero->subtitle ?? 'Con o sin graduación. Filtro de luz azul de alta eficiencia en todos los modelos.' }}
                </p>

                <div class="h-anim-5" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
                    <a href="{{ $hero->btn_primary_url ?? '/lentes' }}" class="h-btn-dark">
                        {{ $hero->btn_primary_text ?? 'Ver lentes' }} →
                    </a>
                    <a href="{{ $hero->btn_secondary_url ?? '/que-es-la-luz-azul' }}" class="h-btn-outline">
                        {{ $hero->btn_secondary_text ?? '¿Qué es la luz azul?' }}
                    </a>
                </div>

                <div class="h-anim-6" style="display:flex;gap:16px;flex-wrap:wrap;">
                    @foreach($trustItems as $item)
                    <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:#9ca3af;">
                        <span style="color:#22c55e;font-size:11px;">✓</span>
                        {{ $item['text'] }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- COLUMNA DERECHA: Producto estrella --}}
            <div class="h-split-right h-img-anim" style="position:relative;overflow:hidden;background:linear-gradient(135deg,#e0f2fe 0%,#dbeafe 50%,#ede9fe 100%);min-height:580px;height:100%;">
                @if($heroProduct && $heroProduct->featured_image)
                <img src="{{ asset('storage/'.$heroProduct->featured_image) }}"
                     alt="{{ $heroProduct->name }} nuvion glass filtro luz azul"
                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;object-position:center;padding:40px;transition:transform .4s ease;"
                     onmouseover="this.style.transform='scale(1.03)'"
                     onmouseout="this.style.transform='scale(1)'">
                @else
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                    <div style="width:220px;height:110px;border:4px solid rgba(13,17,23,0.15);border-radius:50%;position:relative;">
                        <div style="position:absolute;inset:12px;border-radius:50%;background:linear-gradient(135deg,rgba(56,130,221,0.15),rgba(139,92,246,0.1));"></div>
                    </div>
                </div>
                @endif

                {{-- Float badges --}}
                <div class="h-float-badge h-float-anim" style="top:14%;left:6%;">
                    <div class="h-float-num">{{ $hero->stat1_number ?? '2x1' }}</div>
                    <div class="h-float-lbl">{{ $hero->stat1_label ?? 'combinables' }}</div>
                </div>
                <div class="h-float-badge h-float-anim" style="bottom:20%;right:8%;">
                    <div class="h-float-num">{{ $hero->stat2_number ?? '6' }}</div>
                    <div class="h-float-lbl">{{ $hero->stat2_label ?? 'modelos' }}</div>
                </div>

                @if($heroProduct)
                <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);background:rgba(255,255,255,0.9);backdrop-filter:blur(8px);border-radius:20px;padding:6px 16px;font-size:12px;color:#374151;white-space:nowrap;border:0.5px solid rgba(0,0,0,0.06);">
                    {{ $heroProduct->name }} · con filtro luz azul
                </div>
                @endif
            </div>
        </div>

        @if(count($trustItems))
        <div class="h-trust-bar">
            @foreach($trustItems as $t)
            <div class="h-trust-item">
                <div class="h-trust-icon">{{ $t['icon'] }}</div>
                {{ $t['text'] }}
            </div>
            @endforeach
        </div>
        @endif
    </section>

    @else
    {{-- ===== MODO B: VIDEO FULL WIDTH ===== --}}
    <section style="position:relative;min-height:100vh;min-height:100svh;max-height:900px;overflow:hidden;background:#f9fafb;">

        {{-- Video de fondo --}}
        @php
            $vp = $hero->video_position ?? 50;
            $translateX = round(($vp - 50) * -0.4, 1);
        @endphp
        <video autoplay muted loop playsinline preload="metadata"
               style="position:absolute;inset:0;width:120%;height:100%;object-fit:cover;transform:translateX({{ $translateX }}%);">
            <source src="{{ asset('storage/'.$hero->media_path) }}" type="video/mp4">
        </video>

        {{-- Overlay claro de izquierda a derecha --}}
        @php
            $op = $hero->overlay_opacity ?? 0.55;
        @endphp
        <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(255,255,255,{{ round(0.5 + $op * 0.5, 2) }}) 0%,rgba(255,255,255,{{ round(0.4 + $op * 0.48, 2) }}) 30%,rgba(255,255,255,{{ round($op * 0.67, 2) }}) 55%,rgba(255,255,255,{{ round($op * 0.17, 2) }}) 80%,rgba(255,255,255,0.0) 100%);pointer-events:none;"></div>

        {{-- Contenido --}}
        <div style="position:relative;z-index:2;width:100%;max-width:1200px;margin:0 auto;padding:0 6%;min-height:inherit;display:flex;align-items:center;">
            <div style="max-width:580px;padding:80px 0;">

                {{-- Eyebrow --}}
                <div class="h-anim-1" style="font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#378ADD;font-weight:500;margin-bottom:14px;">
                    {{ $hero->eyebrow_text ?? 'nuvion glass · protección visual' }}
                </div>

                {{-- Título --}}
                @php
                    $t1 = $hero->title_line1 ?? 'Tus ojos merecen';
                    $t2 = $hero->title_line2 ?? 'protección';
                    $t3 = $hero->title_line3 ?? 'real';
                    $hl = $hero->title_highlight_word ?? 'real';
                @endphp
                <h1 class="h-anim-2" style="font-size:clamp(36px,5vw,62px);font-weight:800;color:#0d1117;line-height:1.04;letter-spacing:-.025em;margin-bottom:20px;font-family:'Bai Jamjuree',sans-serif;">
                    {{ $t1 }}<br>{{ $t2 }}<br>
                    @if($hl && str_contains($t3, $hl))
                        {!! str_replace($hl, '<span style="color:#378ADD;">'.$hl.'</span>', e($t3)) !!}
                    @else
                        {{ $t3 }}
                    @endif
                </h1>

                {{-- Badge 2x1 --}}
                <div class="h-anim-3" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.85);border:1px solid #BFDBFE;border-radius:8px;padding:9px 16px;font-size:13px;color:#1e40af;margin-bottom:20px;backdrop-filter:blur(4px);width:fit-content;">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <rect x="1" y="3.5" width="12" height="9" rx="1.5" stroke="#1e40af" stroke-width="1.2"/>
                        <path d="M5 3.5V3a2 2 0 014 0v.5" stroke="#1e40af" stroke-width="1.2"/>
                    </svg>
                    {!! str_replace(
                        ['2x1', '$499.90'],
                        ['<strong>2x1</strong>', '<strong>$499.90</strong>'],
                        e($hero->badge_text ?? '2x1 en todos los lentes · $499.90 c/u')
                    ) !!}
                </div>

                {{-- Subtítulo --}}
                <p class="h-anim-4" style="font-size:16px;color:#4b5563;line-height:1.65;margin-bottom:30px;max-width:420px;">
                    {{ $hero->subtitle ?? 'Con o sin graduación. Filtro de luz azul de alta eficiencia.' }}
                </p>

                {{-- Botones --}}
                <div class="h-anim-5" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
                    <a href="{{ $hero->btn_primary_url ?? '/lentes' }}" class="h-btn-dark">
                        {{ $hero->btn_primary_text ?? 'Ver lentes' }} →
                    </a>
                    <a href="{{ $hero->btn_secondary_url ?? '/que-es-la-luz-azul' }}" class="h-btn-outline">
                        {{ $hero->btn_secondary_text ?? '¿Qué es la luz azul?' }}
                    </a>
                </div>

                {{-- Trust items --}}
                <div class="h-anim-6" style="display:flex;gap:16px;flex-wrap:wrap;">
                    @foreach($trustItems as $item)
                    <div style="display:flex;align-items:center;gap:5px;font-size:12px;color:#6b7280;">
                        <span style="color:#22c55e;font-size:11px;">✓</span>
                        {{ $item['text'] }}
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- Trust bar sobre el borde inferior --}}
        @if(count($trustItems))
        <div style="position:absolute;bottom:0;left:0;right:0;z-index:3;">
            <div class="h-trust-bar" style="background:rgba(255,255,255,0.92);backdrop-filter:blur(8px);border-top:1px solid rgba(0,0,0,0.06);">
                @foreach($trustItems as $t)
                <div class="h-trust-item">
                    <div class="h-trust-icon">{{ $t['icon'] }}</div>
                    {{ $t['text'] }}
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>
    @endif

    {{-- ============================================================
         COMPARATIVO — Con vs. sin protección
         ============================================================ --}}
    <section class="py-16 md:py-20" style="background:#ffffff;">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @php
                $cmpWithoutItems = $homePage->comparison_without_items ?? [
                    'Ojos cansados y secos después de 2 horas',
                    'Dolores de cabeza frecuentes al final del día',
                    'Dificultad para conciliar el sueño',
                    'Visión borrosa y tensión constante',
                ];
                $cmpWithItems = $homePage->comparison_with_items ?? [
                    'Vista cómoda todo el día sin fatiga',
                    'Menos dolores de cabeza y migrañas',
                    'Mejor calidad de sueño y descanso',
                    'Mayor rendimiento y concentración',
                ];
            @endphp

            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto mb-12 reveal">
                <span style="display:inline-block;font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#378ADD;margin-bottom:12px;">{{ $homePage->comparison_label ?? 'Comparativo' }}</span>
                <h2 style="font-size:clamp(26px,4vw,40px);font-weight:700;color:#0d1117;line-height:1.15;margin-bottom:12px;font-family:'Bai Jamjuree',sans-serif;">{{ $homePage->comparison_title ?? 'Con vs. sin protección' }}</h2>
                <p style="font-size:15px;color:#6b7280;line-height:1.6;">{{ $homePage->comparison_subtitle ?? 'Mira la diferencia real de usar lentes con protección de luz azul.' }}</p>
            </div>

            {{-- Dos columnas --}}
            <div class="comparativo-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

                {{-- Columna SIN protección --}}
                <div class="reveal" style="background:#fff5f5;border:1px solid #fecaca;border-radius:16px;padding:28px 32px;">
                    {{-- Header col --}}
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                        <div style="width:22px;height:22px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 2l6 6M8 2L2 8" stroke="#ef4444" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#dc2626;">{{ $homePage->comparison_without_label ?? 'Sin protección' }}</span>
                    </div>
                    {{-- Items --}}
                    @foreach($cmpWithoutItems as $item)
                    <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:0.5px solid rgba(239,68,68,0.12);">
                        <div style="width:18px;height:18px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M1.5 1.5l5 5M6.5 1.5l-5 5" stroke="#ef4444" stroke-width="1.6" stroke-linecap="round"/></svg>
                        </div>
                        <span style="font-size:14px;color:#6b7280;line-height:1.5;">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Columna CON nuvion glass --}}
                <div class="reveal" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:16px;padding:28px 32px;">
                    {{-- Header col --}}
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                        <div style="width:22px;height:22px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span style="font-size:14px;font-weight:600;color:#16a34a;">{{ $homePage->comparison_with_label ?? 'Con nuvion glass' }}</span>
                    </div>
                    {{-- Items --}}
                    @foreach($cmpWithItems as $item)
                    <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:0.5px solid rgba(22,163,74,0.12);">
                        <div style="width:18px;height:18px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                            <svg width="8" height="8" viewBox="0 0 8 8" fill="none"><path d="M1 4l2 2 4-4" stroke="#16a34a" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <span style="font-size:14px;color:#374151;line-height:1.5;">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- CTA link al final --}}
            <div style="text-align:center;margin-top:28px;">
                <a href="{{ route('blue-light') }}" style="font-size:13px;font-weight:500;color:#378ADD;text-decoration:none;display:inline-flex;align-items:center;gap:5px;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                    Aprende más sobre la luz azul
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

        </div>
    </section>

    {{-- ============================================================
         2. CATEGORÍAS — imagen arriba, texto abajo
         ============================================================ --}}
    <section class="py-16 md:py-20" style="background:#F4F6F9;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto mb-12 reveal">
                <span style="display:inline-block;font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#378ADD;margin-bottom:12px;">{{ $homePage->categories_label ?? 'Categorías' }}</span>
                <h2 style="font-size:clamp(26px,4vw,40px);font-weight:700;color:#1a1a2e;line-height:1.15;font-family:'Bai Jamjuree',sans-serif;">{{ $homePage->categories_title ?? 'Encuentra tus lentes ideales' }}</h2>
                <p style="margin-top:12px;font-size:15px;color:#6b7280;">{{ $homePage->categories_subtitle ?? 'Con o sin graduación, tenemos el modelo perfecto para ti.' }}</p>
            </div>

            @php
                // Las tarjetas vienen de Admin → Páginas → Inicio → "Tarjetas de categoría".
                // Si una tarjeta no trae imagen propia, intentamos heredar la que el cliente
                // haya subido en /admin/categories: primero matcheando por nombre/slug, y si
                // tampoco hay match, usando la categoría en la misma posición.
                $configuredCards = collect($homePage->category_cards ?? [])
                    ->filter(fn ($c) => !empty($c['name'] ?? ''))
                    ->values();

                $nonToallitasCategories = $categories
                    ->filter(fn ($c) => $c->slug !== 'toallitas')
                    ->values();

                $categoryBySlug = $categories->keyBy('slug');
                $categoryByNameSlug = $categories->keyBy(fn ($c) => \Illuminate\Support\Str::slug($c->name));

                $resolveImage = function (array $card, int $index)
                    use ($categoryBySlug, $categoryByNameSlug, $nonToallitasCategories) {
                    // 1) Imagen propia de la tarjeta.
                    if (!empty($card['image'])) {
                        return $card['image'];
                    }

                    // 2) Match por slug del "Tipo a filtrar" (ej. card link_param = "sin_graduacion"
                    //    → categoría con slug "sin-graduacion").
                    $linkParam = $card['link_param'] ?? '';
                    if ($linkParam) {
                        $candidateSlug = \Illuminate\Support\Str::slug(str_replace('_', '-', $linkParam));
                        $match = $categoryBySlug[$candidateSlug] ?? $categoryByNameSlug[$candidateSlug] ?? null;
                        if ($match && $match->image) {
                            return $match->image;
                        }
                    }

                    // 3) Match por slug del nombre de la tarjeta vs nombre de la categoría.
                    $nameSlug = \Illuminate\Support\Str::slug($card['name'] ?? '');
                    $match = $categoryByNameSlug[$nameSlug] ?? $categoryBySlug[$nameSlug] ?? null;
                    if ($match && $match->image) {
                        return $match->image;
                    }

                    // 4) Último recurso: la categoría (sin toallitas) en la misma posición.
                    $byPosition = $nonToallitasCategories[$index] ?? null;
                    return $byPosition?->image;
                };

                if ($configuredCards->isNotEmpty()) {
                    $cardsToRender = $configuredCards->values()->map(function ($card, $i) use ($resolveImage) {
                        $card['image'] = $resolveImage($card, $i);
                        return $card;
                    });
                } else {
                    $cardsToRender = $categories->filter(fn ($c) => $c->slug !== 'toallitas')
                        ->values()
                        ->map(function ($c) {
                            return [
                                'name' => $c->name,
                                'description' => $c->description ?? '',
                                'image' => $c->image ?? null,
                                'link_param' => method_exists($c, 'typeFilterList')
                                    ? implode(',', $c->typeFilterList())
                                    : '',
                                'icon_svg' => '',
                            ];
                        });
                }
            @endphp

            {{-- Grid 3 columnas — flip cards --}}
            <div class="cats-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">

                @foreach($cardsToRender as $catIndex => $cat)

                @php
                    $gradients = [
                        'linear-gradient(135deg,#0f2d5e,#1a4f9f)',
                        'linear-gradient(135deg,#0d3320,#1a6e3a)',
                        'linear-gradient(135deg,#1a0d37,#4a1a6e)',
                    ];
                    $iconColors = ['#378ADD','#16a34a','#7c3aed'];
                    $iconBgs = ['#EBF4FF','#f0fdf4','#faf5ff'];
                    $accentColors = ['#378ADD','#16a34a','#7c3aed'];
                    $grad = $gradients[$catIndex % 3];
                    $iconColor = $iconColors[$catIndex % 3];
                    $iconBg = $iconBgs[$catIndex % 3];
                    $accent = $accentColors[$catIndex % 3];

                    $catName = is_object($cat) ? $cat->name : ($cat['name'] ?? '');
                    $catDesc = is_object($cat) ? ($cat->description ?? '') : ($cat['description'] ?? '');
                    $catImage = is_object($cat) ? ($cat->image ?? null) : ($cat['image'] ?? null);
                    $catIconSvg = is_object($cat) ? ($cat->icon_svg ?? '') : ($cat['icon_svg'] ?? '');
                    $catLinkParam = is_object($cat) ? ($cat->link_param ?? '') : ($cat['link_param'] ?? '');
                    $catUrlParams = !empty($catLinkParam) ? ['type' => $catLinkParam] : [];
                @endphp

                <div class="flip-card reveal" style="animation-delay:{{ $catIndex * 100 }}ms;">
                    <div class="flip-card-inner">

                        {{-- ▸ FRONT: imagen completa + nombre como tag --}}
                        <div class="flip-card-face" style="background:#0d1117;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                            @if($catImage)
                            <img src="{{ asset('storage/'.$catImage) }}"
                                 alt="{{ $catName }}"
                                 class="cat-img"
                                 style="width:100% !important;height:100% !important;object-fit:cover !important;object-position:center !important;display:block !important;">
                            @else
                            <div style="width:100%;height:100%;background:{{ $grad }};display:flex;align-items:center;justify-content:center;">
                                <svg width="52" height="26" viewBox="0 0 52 26" fill="none">
                                    <rect x="1" y="4" width="20" height="18" rx="9" stroke="rgba(255,255,255,0.25)" stroke-width="2"/>
                                    <rect x="31" y="4" width="20" height="18" rx="9" stroke="rgba(255,255,255,0.25)" stroke-width="2"/>
                                    <line x1="21" y1="13" x2="31" y2="13" stroke="rgba(255,255,255,0.25)" stroke-width="2"/>
                                </svg>
                            </div>
                            @endif
                            {{-- Gradient overlay bottom --}}
                            <div style="position:absolute;bottom:0;left:0;right:0;height:50%;background:linear-gradient(to top,rgba(0,0,0,0.55),transparent);pointer-events:none;border-radius:0 0 16px 16px;"></div>
                            {{-- Nombre como botón/tag --}}
                            <div style="position:absolute;bottom:20px;left:50%;transform:translateX(-50%);">
                                <span style="display:inline-block;background:rgba(255,255,255,0.95);color:#1a1a2e;font-size:14px;font-weight:600;padding:8px 22px;border-radius:50px;font-family:'Bai Jamjuree',sans-serif;white-space:nowrap;box-shadow:0 2px 10px rgba(0,0,0,0.15);">{{ $catName }}</span>
                            </div>
                        </div>

                        {{-- ▸ BACK: descripción + botón --}}
                        <div class="flip-card-face flip-card-back" style="background:{{ $grad }};border:1px solid rgba(255,255,255,0.1);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px 28px;text-align:center;">
                            <div style="width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;margin-bottom:18px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    @if(!empty($catIconSvg))
                                        {!! $catIconSvg !!}
                                    @elseif($catIndex % 3 === 0)
                                    <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    @elseif($catIndex % 3 === 1)
                                    <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                    @else
                                    <path d="M7.5 3.75H6A2.25 2.25 0 003.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0120.25 6v1.5m0 9V18A2.25 2.25 0 0118 20.25h-1.5m-9 0H6A2.25 2.25 0 013.75 18v-1.5"/>
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    @endif
                                </svg>
                            </div>
                            <h3 style="font-size:18px;font-weight:700;color:#ffffff;margin-bottom:10px;line-height:1.2;font-family:'Bai Jamjuree',sans-serif;">{{ $catName }}</h3>
                            <p style="font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;margin-bottom:22px;">{{ $catDesc }}</p>
                            <a href="{{ route('products.index', $catUrlParams) }}"
                               style="display:inline-flex;align-items:center;gap:6px;background:#ffffff;color:{{ $accent }};font-size:13px;font-weight:600;padding:10px 24px;border-radius:50px;text-decoration:none;transition:transform .2s,box-shadow .2s;"
                               onmouseover="this.style.transform='scale(1.05)';this.style.boxShadow='0 6px 20px rgba(0,0,0,0.2)'"
                               onmouseout="this.style.transform='scale(1)';this.style.boxShadow='none'">
                                Ver modelos
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- ============================================================
         3. CATÁLOGO DE PRODUCTOS CON FILTROS (client-side)
         ============================================================ --}}
    <section class="py-16 md:py-24 bg-white"
             x-data="{
                activeType: 'all',
                activeColor: null,
                filterProducts() {
                    const cards = document.querySelectorAll('[data-product-card]');
                    cards.forEach(card => {
                        const types = card.dataset.type ? card.dataset.type.split(',') : [];
                        const colors = card.dataset.colors ? card.dataset.colors.split(',') : [];
                        const matchType = this.activeType === 'all' || types.includes(this.activeType);
                        const matchColor = !this.activeColor || colors.includes(this.activeColor);
                        card.style.display = (matchType && matchColor) ? '' : 'none';
                    });
                }
             }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center max-w-2xl mx-auto reveal">
                <span class="inline-block text-sm font-semibold tracking-wider uppercase mb-3" style="color: #378ADD;">{{ $homePage->catalog_label ?? 'Catálogo' }}</span>
                <h2 class="font-brand text-3xl md:text-4xl font-bold" style="color: #1a1a2e;">{{ $homePage->catalog_title ?? 'Nuestros lentes' }}</h2>
                <p class="mt-4" style="color: #6b7280;">{{ $homePage->catalog_subtitle ?? 'Todos con filtro de luz azul y promoción 2×1.' }}</p>
            </div>

            {{-- Filtros --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-6 reveal">
                {{-- Filtro por tipo --}}
                <div class="flex flex-wrap justify-center gap-2">
                    @php
                    $typeFilters = [
                        ['key' => 'all', 'label' => 'Todos'],
                        ['key' => 'sin_graduacion', 'label' => 'Sin Graduación'],
                        ['key' => 'lectura', 'label' => 'Lectura'],
                        ['key' => 'miopia', 'label' => 'Miopía'],
                    ];
                    @endphp
                    @foreach($typeFilters as $filter)
                    <button @click="activeType = '{{ $filter['key'] }}'; filterProducts()"
                            class="px-4 py-2 rounded-full text-sm font-medium border transition-all duration-200"
                            :style="activeType === '{{ $filter['key'] }}'
                                ? 'background: #378ADD; color: white; border-color: #378ADD;'
                                : 'background: white; color: #6b7280; border-color: #e5e7eb;'"
                            :class="activeType === '{{ $filter['key'] }}' ? 'shadow-md' : 'hover:border-gray-400'">
                        {{ $filter['label'] }}
                    </button>
                    @endforeach
                </div>

                {{-- Separador --}}
                <div class="hidden sm:block w-px h-8" style="background: #e5e7eb;"></div>

                {{-- Filtro por color --}}
                <div class="flex flex-wrap justify-center items-center gap-2">
                    <span class="text-xs font-medium mr-1" style="color: #9ca3af;">Color:</span>
                    <button @click="activeColor = null; filterProducts()"
                            class="w-7 h-7 rounded-full border-2 transition-all duration-200 flex items-center justify-center"
                            :style="!activeColor ? 'border-color: #378ADD;' : 'border-color: #e5e7eb;'"
                            title="Todos los colores">
                        <span class="text-xs font-bold" style="color: #6b7280;">∅</span>
                    </button>
                    @foreach(\App\Helpers\ColorHelper::all() as $colorName => $hex)
                        @if($coloresDisponibles->contains($colorName))
                        <button @click="activeColor = activeColor === '{{ $colorName }}' ? null : '{{ $colorName }}'; filterProducts()"
                                class="w-7 h-7 rounded-full border-2 transition-all duration-200 hover:scale-110"
                                :style="activeColor === '{{ $colorName }}' ? 'background: {{ $hex }}; border-color: #378ADD; box-shadow: 0 0 0 2px rgba(55,138,221,0.3);' : 'background: {{ $hex }}; border-color: #e5e7eb;'"
                                title="{{ $colorName }}">
                        </button>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Grid de productos --}}
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($lentes as $index => $product)
                <div data-product-card
                     data-type="{{ implode(',', $product->type ?? []) }}"
                     data-colors="{{ $product->variants->where('is_active', true)->pluck('color')->unique()->filter()->implode(',') }}"
                     class="reveal group bg-white rounded-2xl overflow-hidden border transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
                     style="border-color: #e5e7eb; transition-delay: {{ ($index % 6) * 100 }}ms;"
                     onmouseover="this.style.borderColor='rgba(55,138,221,0.3)';this.style.boxShadow='0 20px 40px rgba(55,138,221,0.1)'"
                     onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    {{-- Imagen --}}
                    <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-[4/3] overflow-hidden">
                        @if($product->images && count($product->images) > 0)
                            <div class="w-full h-full bg-white flex items-center justify-center">
                                <img src="{{ asset('storage/' . $product->images[0]) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center relative"
                                 style="background: linear-gradient(135deg, rgba(55,138,221,0.08), rgba(0,47,109,0.12));">
                                <div class="absolute inset-0 overflow-hidden">
                                    <div class="absolute top-6 right-6 w-20 h-20 rounded-full blur-xl" style="background: rgba(55,138,221,0.1);"></div>
                                    <div class="absolute bottom-8 left-8 w-16 h-16 rounded-full blur-lg" style="background: rgba(0,47,109,0.1);"></div>
                                </div>
                                <div class="relative text-center transition-transform duration-500 group-hover:scale-110">
                                    <svg class="w-20 h-20 mx-auto" style="color: rgba(55,138,221,0.25);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <p class="mt-1 text-xs font-semibold tracking-wide" style="color: rgba(55,138,221,0.4);">Foto próximamente</p>
                                </div>
                            </div>
                        @endif

                        {{-- Badges --}}
                        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                            @if($product->compare_price && $product->compare_price > $product->price)
                                @php $discount = round((1 - $product->price / $product->compare_price) * 100); @endphp
                                <span class="text-white text-xs font-bold px-3 py-1 rounded-full" style="background: #378ADD;">-{{ $discount }}%</span>
                            @endif
                            @if($product->badge_2x1)
                                <span class="text-white text-xs font-bold px-3 py-1 rounded-full" style="background: #002F6D;">2×1</span>
                            @endif
                        </div>
                    </a>

                    {{-- Info --}}
                    <div class="p-5 md:p-6">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color: #378ADD;">
                            {{ $product->type_labels }}
                        </p>
                        <h3 class="mt-1 font-brand text-lg font-semibold" style="color: #1a1a2e;">{{ $product->name }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed line-clamp-2" style="color: #6b7280;">{{ $product->description }}</p>

                        {{-- Colores disponibles --}}
                        @php $productColors = $product->variants->where('is_active', true)->pluck('color')->unique()->filter(); @endphp
                        @if($productColors->count() > 0)
                        <div class="mt-3 flex items-center gap-1.5">
                            @foreach($productColors->take(6) as $colorName)
                            <span class="w-4 h-4 rounded-full border" style="background: {{ \App\Helpers\ColorHelper::hex($colorName) }}; border-color: #e5e7eb;" title="{{ $colorName }}"></span>
                            @endforeach
                            @if($productColors->count() > 6)
                            <span class="text-xs" style="color: #9ca3af;">+{{ $productColors->count() - 6 }}</span>
                            @endif
                        </div>
                        @endif

                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold" style="color: #002F6D;">${{ number_format($product->price, 0, '.', ',') }}</span>
                                @if($product->compare_price && $product->compare_price > $product->price)
                                    <span class="ml-1.5 text-sm line-through" style="color: #9ca3af;">${{ number_format($product->compare_price, 0, '.', ',') }}</span>
                                @endif
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}"
                               class="text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0"
                               style="background: #002F6D;"
                               onmouseover="this.style.background='#003d8f';this.style.boxShadow='0 4px 12px rgba(0,47,109,0.3)'"
                               onmouseout="this.style.background='#002F6D';this.style.boxShadow='none'">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 font-semibold text-base hover:underline" style="color: #378ADD;">
                    Ver catálogo completo
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         4. BANNER 2×1
         ============================================================ --}}
    @php $promoBg = $homePage->promo_background ?? null; @endphp
    <section class="relative py-16 md:py-24 overflow-hidden"
             style="background: linear-gradient(135deg, #002F6D 0%, #001a40 100%);">

        @if($promoBg)
            @php $promoExt = strtolower(pathinfo($promoBg, PATHINFO_EXTENSION)); @endphp
            @if(in_array($promoExt, ['mp4','webm']))
            <video autoplay muted loop playsinline style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;">
                <source src="{{ asset('storage/'.$promoBg) }}" type="video/{{ $promoExt }}">
            </video>
            @else
            <img src="{{ asset('storage/'.$promoBg) }}" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;">
            @endif
            {{-- Overlay oscuro para legibilidad --}}
            <div style="position:absolute;inset:0;background:rgba(0,20,50,0.7);z-index:1;"></div>
        @else
            {{-- Decorativos (solo si no hay fondo custom) --}}
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-[100px] pointer-events-none"
                 style="background: rgba(55,138,221,0.15); animation: pulseDot 5s ease-in-out infinite;"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full blur-[80px] pointer-events-none"
                 style="background: rgba(0,47,109,0.3); animation: pulseDot 7s ease-in-out 1s infinite;"></div>
        @endif

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" style="z-index:2;">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="reveal">
                    <span class="inline-block text-sm font-bold tracking-wider uppercase mb-4 px-3 py-1 rounded-full"
                          style="color: #378ADD; background: rgba(55,138,221,0.15);">{{ $homePage->promo_label ?? 'Promoción' }}</span>
                    <h2 class="font-brand text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-[1.1]">
                        {!! nl2br(e($homePage->promo_title ?? '2×1 en todos los lentes')) !!}
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed" style="color: rgba(255,255,255,0.7);">
                        {{ $homePage->promo_description ?? 'Compra un par y llévate el segundo completamente gratis. Todos los modelos, todos los colores, todas las graduaciones.' }}
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <span class="text-3xl font-bold text-white">{{ $homePage->promo_price ?? '$499.90' }}</span>
                        <span class="text-sm" style="color: rgba(255,255,255,0.5);">{{ $homePage->promo_price_note ?? 'por par · el segundo es gratis' }}</span>
                    </div>
                    <a href="{{ route('products.index') }}"
                       class="mt-8 inline-flex items-center justify-center text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0"
                       style="background: #378ADD; box-shadow: 0 10px 30px rgba(55,138,221,0.3);"
                       onmouseover="this.style.background='#2d7acc';this.style.boxShadow='0 14px 35px rgba(55,138,221,0.4)'"
                       onmouseout="this.style.background='#378ADD';this.style.boxShadow='0 10px 30px rgba(55,138,221,0.3)'">
                        {{ $homePage->promo_btn_text ?? 'Aprovecha ahora' }}
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>

                {{-- Visual decorativo --}}
                <div class="reveal delay-200 hidden lg:flex items-center justify-center">
                    <div class="relative">
                        <div class="w-64 h-64 rounded-full flex items-center justify-center"
                             style="background: rgba(55,138,221,0.1); border: 1px solid rgba(55,138,221,0.2);">
                            <div class="w-48 h-48 rounded-full flex items-center justify-center"
                                 style="background: rgba(55,138,221,0.15); border: 1px solid rgba(55,138,221,0.25);">
                                <span class="font-brand text-7xl font-bold text-white">2×1</span>
                            </div>
                        </div>
                        <div class="absolute -top-4 -right-4 px-4 py-2 rounded-xl text-white text-sm font-bold"
                             style="background: #378ADD; animation: pulseDot 3s ease-in-out infinite;">
                            ¡GRATIS!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         5. TOALLITAS
         ============================================================ --}}
    <section class="py-16 md:py-24" style="background: #F4F6F9;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Texto --}}
                <div class="reveal">
                    <span class="inline-block text-sm font-semibold tracking-wider uppercase mb-3" style="color: #378ADD;">{{ $homePage->wipes_label ?? 'Accesorios' }}</span>
                    <h2 class="font-brand text-3xl md:text-4xl font-bold" style="color: #1a1a2e;">{{ $homePage->wipes_title ?? 'Cuida tus lentes' }}</h2>
                    <p class="mt-4 leading-relaxed" style="color: #6b7280;">
                        {{ $homePage->wipes_description ?? 'Toallitas limpiadoras 2 en 1: paño húmedo con fórmula sin alcohol + paño seco. Resultados inmediatos para lentes, pantallas, cámaras y tablets.' }}
                    </p>
                    <div class="mt-6 space-y-3">
                        @foreach(($homePage->wipes_features ?? []) as $fIdx => $feature)
                        <div class="flex items-center gap-3 reveal" style="transition-delay: {{ $fIdx * 80 }}ms;">
                            <span class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center"
                                  style="background: rgba(55,138,221,0.1);">
                                <svg class="w-3 h-3" style="color: #378ADD;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            </span>
                            <span class="text-sm" style="color: #4b5563;">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Cards de toallitas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($toallitas as $tIdx => $toallita)
                    <div class="reveal group bg-white rounded-2xl overflow-hidden border transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
                         style="border-color: #e5e7eb; transition-delay: {{ $tIdx * 120 }}ms;"
                         onmouseover="this.style.borderColor='rgba(55,138,221,0.3)';this.style.boxShadow='0 20px 40px rgba(55,138,221,0.1)'"
                         onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                        {{-- Imagen --}}
                        <a href="{{ route('products.show', $toallita->slug) }}" class="block relative aspect-square overflow-hidden">
                            @if($toallita->images && count($toallita->images) > 0)
                                <div class="w-full h-full bg-white flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $toallita->images[0]) }}"
                                         alt="{{ $toallita->name }}"
                                         class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                                         loading="lazy">
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                     style="background: linear-gradient(135deg, rgba(55,138,221,0.06), rgba(0,47,109,0.1));">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto" style="color: rgba(55,138,221,0.2);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.75" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </a>
                        {{-- Info --}}
                        <div class="p-4">
                            <h3 class="font-brand text-sm font-semibold leading-tight" style="color: #1a1a2e;">{{ $toallita->name }}</h3>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xl font-bold" style="color: #002F6D;">${{ number_format($toallita->price, 0, '.', ',') }}</span>
                                <a href="{{ route('products.show', $toallita->slug) }}"
                                   class="text-white px-4 py-2 rounded-lg text-xs font-semibold transition-all duration-200 hover:-translate-y-0.5"
                                   style="background: #002F6D;"
                                   onmouseover="this.style.background='#003d8f'"
                                   onmouseout="this.style.background='#002F6D'">
                                    Ver producto
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         6. BENEFICIOS — ¿Por qué elegir nuvion?
         ============================================================ --}}
    @php
        $benefitCards = $homePage->benefits_cards ?? [
            ['icon_svg' => '<path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>', 'title' => 'Reduce fatiga visual', 'description' => 'Filtra entre el 50% de la luz azul dañina para que los ojos descansen.'],
            ['icon_svg' => '<path d="M21.752 15.002A9.718 9.718 0 0118 15.75 9.75 9.75 0 018.25 6c0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25 9.75 9.75 0 0012.75 21a9.753 9.753 0 009.002-5.998z"/>', 'title' => 'Mejora tu sueño', 'description' => 'Bloquea la luz azul que altera tu ritmo circadiano y tu calidad de descanso.'],
            ['icon_svg' => '<path d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/>', 'title' => 'Menos dolores de cabeza', 'description' => 'Reduce la tensión ocular que causa migrañas y dolores frecuentes.'],
        ];
    @endphp
    @if(count($benefitCards))
    <section style="background:#F4F6F9;padding:80px 0 90px;">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px;">

            {{-- Header --}}
            <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto 60px;">
                <span style="display:inline-block;font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#378ADD;margin-bottom:14px;">{{ $homePage->benefits_label ?? 'Beneficios' }}</span>
                <h2 style="font-size:clamp(28px,4.5vw,42px);font-weight:700;color:#1a1a2e;line-height:1.15;font-family:'Bai Jamjuree',sans-serif;margin:0 0 16px;">{{ $homePage->benefits_title ?? '¿Por qué elegir nuvion?' }}</h2>
                <p style="font-size:16px;color:#6b7280;line-height:1.6;margin:0;">{{ $homePage->benefits_subtitle ?? 'Tecnología que cuida tu visión. Diseño que querrás usar todo el día.' }}</p>
                {{-- Línea decorativa --}}
                <div style="width:48px;height:3px;background:#378ADD;border-radius:2px;margin:24px auto 0;"></div>
            </div>

            {{-- Grid de cards --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:24px;margin:0 auto;">
                @foreach($benefitCards as $bIdx => $benefit)
                <div class="reveal"
                     style="background:#ffffff;border-radius:16px;padding:40px 28px;text-align:center;border:1px solid #e5e7eb;transition:transform .25s ease,box-shadow .25s ease;animation-delay:{{ $bIdx * 100 }}ms;"
                     onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 40px rgba(0,47,109,0.08)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

                    {{-- Ícono --}}
                    <div style="width:60px;height:60px;border-radius:50%;background:rgba(55,138,221,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#378ADD" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            {!! $benefit['icon_svg'] ?? '' !!}
                        </svg>
                    </div>

                    {{-- Título --}}
                    <h3 style="font-size:17px;font-weight:700;color:#1a1a2e;margin-bottom:10px;font-family:'Bai Jamjuree',sans-serif;">{{ $benefit['title'] ?? '' }}</h3>

                    {{-- Descripción --}}
                    <p style="font-size:14px;color:#6b7280;line-height:1.65;margin:0;">{{ $benefit['description'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         7. FAQ + CONFIANZA + CTA FINAL
         ============================================================ --}}
    {{-- FAQ --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">
                <span class="inline-block text-sm font-semibold tracking-wider uppercase mb-3" style="color: #378ADD;">FAQ</span>
                <h2 class="font-brand text-3xl md:text-4xl font-bold" style="color: #1a1a2e;">Preguntas frecuentes</h2>
                <p class="mt-4" style="color: #6b7280;">Todo lo que necesitas saber antes de proteger tu visión.</p>
            </div>

            <div class="mt-12 max-w-3xl mx-auto space-y-3 reveal visible" x-data="{ openFaq: null }">
                @foreach(($homePage->faqs ?? []) as $index => $faq)
                <div class="border rounded-xl overflow-hidden" style="border-color: #e5e7eb; background: #F4F6F9;">
                    <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                            class="w-full flex items-center justify-between px-5 md:px-6 py-4 text-left transition-all duration-200 group"
                            :style="openFaq === {{ $index }} ? 'background: white;' : ''"
                            onmouseover="if(!this.classList.contains('bg-white'))this.style.background='white'"
                            onmouseout="this.style.background=''">
                        <span class="font-semibold pr-4 transition-colors duration-200"
                              :style="openFaq === {{ $index }} ? 'color: #378ADD;' : 'color: #1a1a2e;'">{{ $faq['q'] }}</span>
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-white border flex items-center justify-center"
                              style="border-color: #e5e7eb;">
                            <svg :class="openFaq === {{ $index }} ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" style="color: #002F6D;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </span>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-96"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="px-5 md:px-6 pb-5">
                        <p class="leading-relaxed" style="color: #6b7280;">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         TESTIMONIOS — Lo que dicen nuestros clientes
         ============================================================ --}}
    @php
        $displayTestimonials = array_slice(
            $testimonials->count() ? $testimonials->toArray() : [
                ['name' => 'María G.', 'role' => 'Diseñadora gráfica', 'body' => 'Desde que uso mis nuvion, ya no termino el día con los ojos rojos. Son súper cómodos y se ven increíbles.', 'rating' => 5, 'avatar_color' => '#378ADD'],
                ['name' => 'Carlos R.', 'role' => 'Programador', 'body' => 'Trabajo 10 horas frente a la pantalla y estos lentes cambiaron todo. Menos dolor de cabeza y duermo mejor.', 'rating' => 5, 'avatar_color' => '#16a34a'],
                ['name' => 'Ana L.', 'role' => 'Estudiante', 'body' => 'Los compré sin graduación y me encantan. El diseño es moderno y realmente sientes la diferencia.', 'rating' => 5, 'avatar_color' => '#7c3aed'],
            ], 0, 3);
    @endphp
    <section style="background:#F4F6F9;padding:80px 0 90px;">
        <div style="max-width:1200px;margin:0 auto;padding:0 24px;">

            {{-- Header --}}
            <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto 60px;">
                <span style="display:inline-block;font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#378ADD;margin-bottom:14px;">TESTIMONIOS</span>
                <h2 style="font-size:clamp(28px,4.5vw,42px);font-weight:700;color:#1a1a2e;line-height:1.15;font-family:'Bai Jamjuree',sans-serif;margin:0 0 16px;">Lo que dicen nuestros clientes</h2>
                <div style="width:48px;height:3px;background:#378ADD;border-radius:2px;margin:24px auto 0;"></div>
            </div>

            {{-- Grid de testimonios --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;max-width:1000px;margin:0 auto;">
                @foreach($displayTestimonials as $tIdx => $t)
                @php
                    $tName = is_array($t) ? ($t['name'] ?? '') : $t->name;
                    $tRole = is_array($t) ? ($t['role'] ?? '') : ($t->role ?? '');
                    $tBody = is_array($t) ? ($t['body'] ?? '') : $t->body;
                    $tRating = is_array($t) ? ($t['rating'] ?? 5) : $t->rating;
                    $tColor = is_array($t) ? ($t['avatar_color'] ?? '#378ADD') : ($t->avatar_color ?? '#378ADD');
                @endphp
                <div class="reveal"
                     style="background:#ffffff;border-radius:16px;padding:36px 28px;border:1px solid #e5e7eb;transition:transform .25s ease,box-shadow .25s ease;animation-delay:{{ $tIdx * 100 }}ms;"
                     onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 40px rgba(0,47,109,0.08)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none'">

                    {{-- Estrellas --}}
                    <div style="display:flex;gap:4px;margin-bottom:20px;">
                        @for($s = 1; $s <= 5; $s++)
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="{{ $s <= $tRating ? '#f59e0b' : '#e5e7eb' }}">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>

                    {{-- Cuerpo --}}
                    <p style="font-size:14px;color:#374151;line-height:1.7;margin-bottom:20px;font-style:italic;">"{{ $tBody }}"</p>

                    {{-- Avatar + info --}}
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:{{ $tColor }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(mb_substr($tName, 0, 1)) }}
                        </div>
                        <div>
                            <p style="font-size:14px;font-weight:600;color:#1a1a2e;">{{ $tName }}</p>
                            @if($tRole)
                            <p style="font-size:12px;color:#9ca3af;">{{ $tRole }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Confianza + CTA Final --}}
    <section class="relative overflow-hidden"
             style="background: linear-gradient(135deg, #0A0E1A 0%, #001a40 50%, #0A0E1A 100%);padding:80px 0 90px;">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full blur-[100px] pointer-events-none"
             style="background: rgba(55,138,221,0.08); animation: pulseDot 5s ease-in-out infinite;"></div>

        <div style="position:relative;max-width:1000px;margin:0 auto;padding:0 24px;">

            {{-- Header de sección --}}
            <div class="reveal" style="text-align:center;max-width:600px;margin:0 auto 60px;">
                <span style="display:inline-block;font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#378ADD;margin-bottom:14px;">CONFIANZA</span>
                <h2 style="font-size:clamp(28px,4.5vw,42px);font-weight:700;color:#ffffff;line-height:1.15;font-family:'Bai Jamjuree',sans-serif;margin:0;">
                    {{ $homePage->cta_title ?? '¿Listo para proteger tu visión?' }}
                </h2>
                <p style="font-size:16px;color:rgba(255,255,255,0.6);line-height:1.6;margin:16px 0 0;">
                    {{ $homePage->cta_subtitle ?? 'Ve mejor, duerme mejor, rinde más. Únete a quienes ya cuidan sus ojos con nuvion.' }}
                </p>
                <div style="width:48px;height:3px;background:#378ADD;border-radius:2px;margin:24px auto 0;"></div>
            </div>

            {{-- Trust badges --}}
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px;max-width:900px;margin:0 auto 60px;">
                @foreach(($homePage->trust_badges ?? []) as $bIdx => $badge)
                <div class="reveal" style="text-align:center;transition-delay:{{ $bIdx * 100 }}ms;">
                    <div style="width:56px;height:56px;border-radius:14px;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <svg style="width:26px;height:26px;color:#378ADD;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $badge['icon_svg'] ?? '' !!}
                        </svg>
                    </div>
                    <h3 style="font-size:14px;font-weight:600;color:#ffffff;font-family:'Bai Jamjuree',sans-serif;margin:0 0 8px;">{{ $badge['title'] }}</h3>
                    <p style="font-size:12px;color:rgba(255,255,255,0.45);line-height:1.5;margin:0;">{{ $badge['description'] ?? '' }}</p>
                </div>
                @endforeach
            </div>

            {{-- Separador --}}
            <div style="display:flex;justify-content:center;margin:0 auto 50px;">
                <div style="width:96px;height:1px;background:linear-gradient(90deg,transparent,rgba(55,138,221,0.3),transparent);"></div>
            </div>

            {{-- CTA buttons --}}
            <div class="reveal" style="text-align:center;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:16px;">
                    <a href="{{ route('products.index') }}"
                       style="display:inline-flex;align-items:center;justify-content:center;color:#fff;padding:16px 40px;border-radius:12px;font-weight:600;font-size:18px;font-family:inherit;text-decoration:none;
                              background:#378ADD;box-shadow:0 10px 30px rgba(55,138,221,0.3);transition:all .3s;"
                       onmouseover="this.style.background='#2d7acc';this.style.boxShadow='0 14px 35px rgba(55,138,221,0.4)';this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.background='#378ADD';this.style.boxShadow='0 10px 30px rgba(55,138,221,0.3)';this.style.transform='translateY(0)'">
                        {{ $homePage->cta_btn_primary_text ?? 'Comprar ahora' }}
                        <svg style="margin-left:8px;width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="{{ route('blue-light') }}"
                       style="display:inline-flex;align-items:center;justify-content:center;padding:16px 40px;border-radius:12px;font-weight:500;font-size:18px;font-family:inherit;text-decoration:none;
                              border:1px solid rgba(255,255,255,0.25);color:#fff;transition:all .2s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.08)'"
                       onmouseout="this.style.background='transparent'">
                        {{ $homePage->cta_btn_secondary_text ?? 'Aprende más' }}
                    </a>
                </div>

                <div style="margin-top:40px;display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:16px;font-size:13px;color:rgba(255,255,255,0.35);">
                    @foreach(($homePage->cta_trust_items ?? []) as $ctaIdx => $trustItem)
                        @if($ctaIdx > 0)
                            <span style="display:none;" class="sm:inline-block">·</span>
                        @endif
                        <span>{{ $trustItem }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

@endsection
