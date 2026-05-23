@extends('layouts.app')

{{-- ================================================================
     SEO — meta + JSON-LD
     Todo el copy se hereda de SeoSetting (admin) y se complementa con
     schemas estructurados para Organization, WebSite, ItemList y FAQ.
     ================================================================ --}}
@section('title', $seoSettings->meta_title ?? 'Belleza Áurea | Cosmética natural, elegante y atemporal')
@section('meta_description', $seoSettings->meta_description ?? 'Skincare, fragancias y rituales premium con ingredientes botánicos.')
@section('og_title', $seoSettings->og_title ?? $seoSettings->meta_title ?? 'Belleza Áurea')
@section('og_description', $seoSettings->og_description ?? $seoSettings->meta_description ?? 'Belleza natural, elegante y atemporal.')
@section('twitter_title', $seoSettings->twitter_title ?? $seoSettings->meta_title ?? 'Belleza Áurea')
@section('twitter_description', $seoSettings->twitter_description ?? $seoSettings->meta_description ?? 'Belleza natural, elegante y atemporal.')

@push('schema')
    {{-- Organization JSON-LD (vienen como <script>...</script> completos del SeoService) --}}
    @if(!empty($organizationSchema))
        {!! $organizationSchema !!}
    @endif

    {{-- FAQ JSON-LD --}}
    @if(!empty($faqSchema))
        {!! $faqSchema !!}
    @endif

    {{-- WebSite + SearchAction + ItemList — keys con chr(64) para evitar que
         el preprocesador de Blade interprete @context / @type como directivas. --}}
    @php
        $K_CTX = chr(64).'context';
        $K_TYP = chr(64).'type';

        $websiteLd = json_encode([
            $K_CTX => 'https://schema.org',
            $K_TYP => 'WebSite',
            'name' => 'Belleza Áurea',
            'url' => url('/'),
            'inLanguage' => 'es',
            'potentialAction' => [
                $K_TYP => 'SearchAction',
                'target' => url('/productos') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $itemListLd = $lentes->isNotEmpty()
            ? json_encode([
                $K_CTX => 'https://schema.org',
                $K_TYP => 'ItemList',
                'name' => 'Productos destacados',
                'itemListElement' => $lentes->take(8)->values()->map(fn ($p, $i) => [
                    $K_TYP => 'ListItem',
                    'position' => $i + 1,
                    'url' => route('products.show', ['slug' => $p->slug]),
                    'name' => $p->name,
                ])->all(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            : null;
    @endphp
    <script type="application/ld+json">{!! $websiteLd !!}</script>
    @if($itemListLd)
    <script type="application/ld+json">{!! $itemListLd !!}</script>
    @endif
@endpush

@push('head')
<style>
    /* ============================================
       Belleza Áurea — Home rediseño minimalista
       Tokens viven en resources/css/app.css (@theme).
       Aquí solo estilos específicos del home.
       ============================================ */

    .ba-hero {
        position: relative;
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        min-height: clamp(560px, 92vh, 880px);
        background: #FFFFFF;
        overflow: hidden;
    }

    .ba-hero__left {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: clamp(48px, 8vw, 120px) clamp(24px, 6vw, 96px);
        position: relative;
        z-index: 2;
    }

    .ba-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #BE9A53;
        margin-bottom: 28px;
    }
    .ba-eyebrow::before {
        content: "";
        display: block;
        width: 32px;
        height: 1px;
        background: #D9B56D;
    }

    .ba-hero__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(40px, 5vw, 72px);
        font-weight: 500;
        line-height: 1.04;
        letter-spacing: -0.015em;
        color: #2E2A26;
        margin: 0 0 28px;
    }
    .ba-hero__title em {
        font-style: italic;
        font-weight: 500;
        color: #D9B56D;
    }

    .ba-hero__sub {
        font-size: 16px;
        line-height: 1.65;
        color: #6B6157;
        max-width: 460px;
        margin: 0 0 36px;
    }

    .ba-hero__actions {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
        margin-bottom: 48px;
    }

    /* ─────────────────────────────────────────
       CTAs — Pill premium con shimmer y arrow micro-anim
       Estilo Aesop / Le Labo / NET-A-PORTER
       ───────────────────────────────────────── */
    .ba-btn-primary,
    .ba-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        text-decoration: none;
        transition: all .45s cubic-bezier(.2,.7,.3,1);
        font-family: 'Montserrat', sans-serif;
        position: relative;
        white-space: nowrap;
    }

    /* Primary: pill negra → gold al hover, con shimmer diagonal */
    .ba-btn-primary {
        background: #2E2A26;
        color: #FFFFFF;
        padding: 18px 36px;
        border-radius: 999px;
        overflow: hidden;
        box-shadow: 0 8px 24px -8px rgba(46, 42, 38, 0.4);
    }
    .ba-btn-primary::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(110deg, #D9B56D 0%, #E8CC92 50%, #D9B56D 100%);
        transform: translateX(-101%);
        transition: transform .55s cubic-bezier(.2,.7,.3,1);
        z-index: 0;
    }
    .ba-btn-primary > * { position: relative; z-index: 1; }
    .ba-btn-primary:hover {
        color: #2E2A26;
        box-shadow: 0 14px 32px -10px rgba(217, 181, 109, 0.55);
        transform: translateY(-2px);
    }
    .ba-btn-primary:hover::before { transform: translateX(0); }
    .ba-btn-primary svg { transition: transform .35s cubic-bezier(.2,.7,.3,1); }
    .ba-btn-primary:hover svg { transform: translateX(4px); }

    /* Secondary: pill outline taupe → gold + ink al hover */
    .ba-btn-ghost {
        color: #2E2A26;
        padding: 18px 32px;
        border-radius: 999px;
        border: 1px solid rgba(184, 169, 153, 0.6);
        background: transparent;
    }
    .ba-btn-ghost:hover {
        color: #2E2A26;
        border-color: #D9B56D;
        background: rgba(217, 181, 109, 0.08);
    }
    .ba-btn-ghost::after {
        content: "";
        display: inline-block;
        width: 18px;
        height: 1px;
        background: currentColor;
        transition: width .35s cubic-bezier(.2,.7,.3,1);
        margin-left: 2px;
    }
    .ba-btn-ghost:hover::after { width: 28px; background: #D9B56D; }

    .ba-hero__meta {
        display: flex;
        gap: 32px;
        flex-wrap: wrap;
    }
    .ba-hero__meta-item {
        display: flex;
        flex-direction: column;
    }
    .ba-hero__meta-num {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 600;
        color: #2E2A26;
        line-height: 1;
    }
    .ba-hero__meta-lbl {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #B8A999;
        margin-top: 6px;
    }

    .ba-hero__right {
        position: relative;
        background: radial-gradient(circle at 30% 25%, #FBF4E6 0%, #F7F3ED 50%, #E8D1C5 100%);
        overflow: hidden;
    }
    .ba-hero__right::before,
    .ba-hero__right::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: .55;
        pointer-events: none;
    }
    .ba-hero__right::before {
        width: 340px; height: 340px;
        top: -80px; right: -80px;
        background: rgba(217,181,109,.35);
    }
    .ba-hero__right::after {
        width: 280px; height: 280px;
        bottom: -60px; left: -40px;
        background: rgba(168,178,154,.35);
    }
    .ba-hero__product {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
    }
    /* Si hay video, quitar padding para fullbleed cinematográfico */
    .ba-hero__product:has(video) { padding: 0; }
    .ba-hero__product img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 40px 80px rgba(190,154,83,.25));
        transition: transform 1.2s cubic-bezier(.2,.7,.3,1);
    }
    .ba-hero__product img:hover { transform: scale(1.03); }
    /* Video b-roll fullbleed dentro del panel derecho */
    .ba-hero__video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 0;
        display: block;
    }

    .ba-hero__leaf {
        position: absolute;
        opacity: .4;
        pointer-events: none;
    }

    /* Marquee trust */
    .ba-marquee {
        background: #F7F3ED;
        padding: 22px 0;
        overflow: hidden;
        border-top: 1px solid rgba(184,169,153,.18);
        border-bottom: 1px solid rgba(184,169,153,.18);
    }
    .ba-marquee__track {
        display: flex;
        gap: 64px;
        white-space: nowrap;
        animation: ba-marquee 38s linear infinite;
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        color: #B8A999;
        font-style: italic;
        letter-spacing: 0.04em;
    }
    .ba-marquee:hover .ba-marquee__track { animation-play-state: paused; }
    .ba-marquee__item { display: inline-flex; align-items: center; gap: 12px; }
    .ba-marquee__dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #D9B56D; flex-shrink: 0;
    }
    @keyframes ba-marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* Containers */
    .ba-container {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 clamp(24px, 5vw, 80px);
    }

    .ba-section {
        padding: clamp(72px, 10vw, 140px) 0;
    }
    .ba-section--cream { background: #F7F3ED; }
    .ba-section--ink   { background: #2E2A26; color: #F7F3ED; }
    .ba-section--cream-soft { background: #FBF8F2; }

    /* Section heads */
    .ba-section-head {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: clamp(48px, 7vw, 88px);
    }
    .ba-section-head__label {
        font-size: 11px;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: #BE9A53;
        margin-bottom: 16px;
        font-weight: 500;
    }
    .ba-section-head__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(30px, 4vw, 52px);
        font-weight: 500;
        line-height: 1.1;
        letter-spacing: -0.01em;
        color: inherit;
        margin: 0 0 16px;
        max-width: 720px;
    }
    .ba-section-head__sub {
        font-size: 16px;
        line-height: 1.7;
        color: #6B6157;
        max-width: 560px;
        margin: 0;
    }
    .ba-section--ink .ba-section-head__title { color: #FBF8F2; }
    .ba-section--ink .ba-section-head__sub   { color: rgba(247,243,237,.65); }

    .ba-divider {
        width: 36px;
        height: 1px;
        background: #D9B56D;
        margin: 24px auto 0;
    }

    /* ─────────────────────────────────────────
       Categories — Magazine grid premium
       Layout asimétrico: primera card 2x, resto 1x
       Hover sofisticado: zoom imagen + lift + overlay áureo
       ───────────────────────────────────────── */
    .ba-cats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-auto-rows: 320px;
        gap: 16px;
    }
    /* Primera card: doble ancho + alto x2 (hero de la sección) */
    .ba-cats > .ba-cat:first-child {
        grid-column: span 2;
        grid-row: span 2;
    }

    .ba-cat {
        position: relative;
        display: block;
        overflow: hidden;
        border-radius: 4px;
        text-decoration: none;
        background: #EDE6D8;
        box-shadow: 0 1px 3px rgba(46,42,38,.05);
        transition: transform .55s cubic-bezier(.2,.7,.3,1),
                    box-shadow .55s cubic-bezier(.2,.7,.3,1);
        isolation: isolate;
    }
    .ba-cat:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 50px -16px rgba(190,154,83,.35);
    }
    .ba-cat__bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transform: scale(1.02);
        transition: transform 1.4s cubic-bezier(.2,.7,.3,1), filter .55s ease;
        z-index: 0;
    }
    .ba-cat:hover .ba-cat__bg { transform: scale(1.12); }

    /* Overlay base — sutil para legibilidad */
    .ba-cat__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg,
            rgba(46,42,38,0) 0%,
            rgba(46,42,38,.15) 45%,
            rgba(46,42,38,.78) 100%);
        z-index: 1;
        transition: background .55s ease;
    }
    .ba-cat:hover .ba-cat__overlay {
        background: linear-gradient(180deg,
            rgba(190,154,83,.05) 0%,
            rgba(46,42,38,.30) 45%,
            rgba(46,42,38,.88) 100%);
    }

    /* Marco dorado interior aparece al hover */
    .ba-cat::after {
        content: "";
        position: absolute;
        inset: 12px;
        border: 1px solid transparent;
        border-radius: 2px;
        transition: border-color .45s ease;
        z-index: 2;
        pointer-events: none;
    }
    .ba-cat:hover::after {
        border-color: rgba(217,181,109,.6);
    }

    .ba-cat__body {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 28px;
        color: #FFFFFF;
        z-index: 3;
    }
    .ba-cats > .ba-cat:first-child .ba-cat__body { padding: 40px; }

    .ba-cat__count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: #D9B56D;
        font-weight: 600;
        margin-bottom: 12px;
        opacity: .95;
    }
    .ba-cat__count::before {
        content: "";
        width: 18px;
        height: 1px;
        background: #D9B56D;
    }

    .ba-cat__name {
        font-family: 'Playfair Display', serif;
        font-size: clamp(22px, 2.4vw, 28px);
        font-weight: 500;
        line-height: 1.1;
        margin: 0 0 8px;
        letter-spacing: -.005em;
        transform: translateY(0);
        transition: transform .45s cubic-bezier(.2,.7,.3,1);
    }
    .ba-cats > .ba-cat:first-child .ba-cat__name {
        font-size: clamp(32px, 3.2vw, 44px);
    }

    .ba-cat__desc {
        font-size: 13px;
        line-height: 1.55;
        opacity: 0;
        max-height: 0;
        margin: 0;
        transform: translateY(8px);
        transition: opacity .45s ease, max-height .45s ease,
                    transform .45s cubic-bezier(.2,.7,.3,1), margin .45s ease;
    }
    .ba-cat:hover .ba-cat__desc {
        opacity: .85;
        max-height: 80px;
        margin: 0 0 16px;
        transform: translateY(0);
    }

    .ba-cat__cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #FFFFFF;
        padding-top: 14px;
        border-top: 1px solid rgba(255,255,255,.18);
        transition: color .35s ease, border-color .35s ease;
    }
    .ba-cat:hover .ba-cat__cta {
        color: #D9B56D;
        border-color: rgba(217,181,109,.45);
    }
    .ba-cat__cta svg {
        transition: transform .45s cubic-bezier(.2,.7,.3,1);
    }
    .ba-cat:hover .ba-cat__cta svg {
        transform: translateX(8px);
    }

    @media (max-width: 900px) {
        .ba-cats {
            grid-template-columns: repeat(2, 1fr);
            grid-auto-rows: 280px;
        }
        .ba-cats > .ba-cat:first-child {
            grid-column: span 2;
            grid-row: span 1;
        }
        .ba-cats > .ba-cat:first-child .ba-cat__name {
            font-size: clamp(28px, 6vw, 36px);
        }
    }
    @media (max-width: 540px) {
        .ba-cats {
            grid-template-columns: 1fr;
            grid-auto-rows: 240px;
        }
        .ba-cats > .ba-cat:first-child { grid-column: span 1; }
        .ba-cat__desc { opacity: .85; max-height: 80px; margin: 0 0 14px; transform: none; }
    }

    /* Products grid */
    .ba-prods {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 32px 24px;
    }
    .ba-card {
        background: transparent;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .ba-card__img {
        position: relative;
        aspect-ratio: 4/5;
        background: #FBF8F2;
        overflow: hidden;
        border-radius: 2px;
        margin-bottom: 18px;
    }
    .ba-card__img img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 1.4s cubic-bezier(.2,.7,.3,1);
    }
    .ba-card:hover .ba-card__img img { transform: scale(1.06); }
    .ba-card__img--ph {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #D9B56D;
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-size: 14px;
        background: radial-gradient(circle at 50% 40%, #FBF4E6, #F7F3ED 75%);
    }
    .ba-card__cat {
        font-size: 10px;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #B8A999;
        margin: 0 0 6px;
    }
    .ba-card__name {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 500;
        color: #2E2A26;
        line-height: 1.25;
        margin: 0 0 10px;
        transition: color .25s ease;
    }
    .ba-card:hover .ba-card__name { color: #BE9A53; }
    .ba-card__price-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
    }
    .ba-card__price {
        font-size: 16px;
        font-weight: 500;
        color: #2E2A26;
    }
    .ba-card__compare {
        font-size: 13px;
        color: #B8A999;
        text-decoration: line-through;
    }

    /* Star product split */
    .ba-star {
        display: grid;
        grid-template-columns: 1.05fr 1fr;
        gap: 64px;
        align-items: center;
    }
    .ba-star__visual {
        position: relative;
        aspect-ratio: 4/5;
        background: radial-gradient(circle at 30% 30%, #FBF4E6, #E8D1C5 80%);
        overflow: hidden;
        border-radius: 2px;
    }
    .ba-star__visual img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ba-star__body { padding: 16px 0; }
    .ba-star__label {
        font-size: 11px;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: #BE9A53;
        font-weight: 500;
        margin-bottom: 18px;
    }
    .ba-star__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(28px, 3.4vw, 44px);
        font-weight: 500;
        line-height: 1.1;
        color: #2E2A26;
        margin: 0 0 24px;
    }
    .ba-star__desc {
        font-size: 16px;
        line-height: 1.75;
        color: #6B6157;
        margin: 0 0 32px;
        max-width: 480px;
    }
    .ba-star__price-block {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 32px;
    }
    .ba-star__price {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 500;
        color: #2E2A26;
    }
    .ba-star__note {
        font-size: 12px;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #B8A999;
    }

    /* Benefits */
    .ba-benefits {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 32px;
    }
    .ba-benefit {
        position: relative;
        padding: 32px 24px 32px 0;
        border-top: 1px solid rgba(184,169,153,.32);
    }
    .ba-benefit__num {
        font-family: 'Playfair Display', serif;
        font-size: 13px;
        font-style: italic;
        color: #D9B56D;
        margin-bottom: 18px;
    }
    .ba-benefit__title {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 500;
        color: #2E2A26;
        line-height: 1.2;
        margin: 0 0 12px;
    }
    .ba-benefit__desc {
        font-size: 14px;
        line-height: 1.65;
        color: #6B6157;
        margin: 0;
    }

    /* Editorial quote */
    .ba-quote {
        text-align: center;
        max-width: 880px;
        margin: 0 auto;
        padding: 0 24px;
    }
    .ba-quote__text {
        font-family: 'Playfair Display', serif;
        font-style: italic;
        font-size: clamp(26px, 3.4vw, 44px);
        font-weight: 400;
        line-height: 1.35;
        color: #2E2A26;
        margin: 0 0 32px;
    }
    .ba-quote__text::before,
    .ba-quote__text::after {
        content: "";
        display: block;
        width: 24px;
        height: 1px;
        background: #D9B56D;
        margin: 0 auto;
    }
    .ba-quote__text::before { margin-bottom: 32px; }
    .ba-quote__text::after  { margin-top: 32px; }
    .ba-quote__author {
        font-size: 11px;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: #B8A999;
    }

    /* Sets split */
    .ba-sets {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 64px;
        align-items: center;
    }
    .ba-sets__list {
        list-style: none;
        padding: 0;
        margin: 28px 0 36px;
    }
    .ba-sets__list li {
        display: flex;
        align-items: baseline;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid rgba(184,169,153,.22);
        font-size: 14px;
        color: #2E2A26;
    }
    .ba-sets__list li::before {
        content: "—";
        color: #D9B56D;
        font-weight: 600;
    }
    .ba-sets__grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Comparison */
    .ba-compare {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        max-width: 980px;
        margin: 0 auto;
    }
    .ba-compare__col {
        padding: 40px 36px;
        border-radius: 2px;
    }
    .ba-compare__col--without {
        background: #FFFFFF;
        border: 1px solid rgba(184,169,153,.3);
    }
    .ba-compare__col--with {
        background: linear-gradient(160deg, #FBF4E6 0%, #E8CC92 100%);
    }
    .ba-compare__label {
        font-size: 11px;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        font-weight: 500;
        margin-bottom: 24px;
    }
    .ba-compare__col--without .ba-compare__label { color: #B8A999; }
    .ba-compare__col--with .ba-compare__label    { color: #2E2A26; }
    .ba-compare__list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .ba-compare__list li {
        display: flex;
        align-items: baseline;
        gap: 12px;
        padding: 12px 0;
        font-size: 15px;
        color: #2E2A26;
        line-height: 1.5;
    }
    .ba-compare__col--without .ba-compare__list li::before {
        content: "×"; color: #B8A999; font-size: 18px;
    }
    .ba-compare__col--with .ba-compare__list li::before {
        content: "✓"; color: #BE9A53; font-weight: 600;
    }

    /* Testimonials */
    .ba-tests {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px;
    }
    .ba-test {
        padding: 36px 32px;
        background: #FFFFFF;
        border: 1px solid rgba(184,169,153,.2);
        border-radius: 2px;
        position: relative;
        transition: transform .5s cubic-bezier(.2,.7,.3,1), box-shadow .5s ease;
    }
    .ba-test:hover {
        transform: translateY(-6px);
        box-shadow: 0 30px 60px rgba(190,154,83,.1);
    }
    .ba-test__mark {
        font-family: 'Playfair Display', serif;
        font-size: 56px;
        line-height: 1;
        color: #D9B56D;
        margin-bottom: 12px;
    }
    .ba-test__body {
        font-size: 15px;
        line-height: 1.7;
        color: #2E2A26;
        font-style: italic;
        margin: 0 0 28px;
    }
    .ba-test__author {
        font-family: 'Playfair Display', serif;
        font-size: 15px;
        color: #2E2A26;
    }
    .ba-test__role {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #B8A999;
        margin-top: 4px;
    }

    /* FAQ */
    .ba-faq {
        max-width: 820px;
        margin: 0 auto;
    }
    .ba-faq details {
        border-bottom: 1px solid rgba(184,169,153,.3);
    }
    .ba-faq details:first-of-type { border-top: 1px solid rgba(184,169,153,.3); }
    .ba-faq summary {
        list-style: none;
        cursor: pointer;
        padding: 28px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 500;
        color: #2E2A26;
        transition: color .25s ease;
    }
    .ba-faq summary::-webkit-details-marker { display: none; }
    .ba-faq summary:hover { color: #BE9A53; }
    .ba-faq summary::after {
        content: "+";
        font-family: 'Playfair Display', serif;
        font-size: 24px;
        color: #D9B56D;
        font-weight: 500;
        transition: transform .35s ease;
    }
    .ba-faq details[open] summary::after {
        content: "−";
        transform: rotate(0);
    }
    .ba-faq__answer {
        padding: 0 0 28px;
        font-size: 15px;
        line-height: 1.75;
        color: #6B6157;
    }

    /* Final CTA */
    .ba-cta {
        text-align: center;
        max-width: 720px;
        margin: 0 auto;
        padding: 0 24px;
    }
    .ba-cta__title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(32px, 4.5vw, 56px);
        font-weight: 500;
        line-height: 1.1;
        color: #FBF8F2;
        margin: 0 0 24px;
    }
    .ba-cta__title em {
        font-style: italic;
        color: #D9B56D;
    }
    .ba-cta__sub {
        font-size: 16px;
        line-height: 1.7;
        color: rgba(247,243,237,.7);
        margin: 0 auto 44px;
        max-width: 520px;
    }
    .ba-cta__actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 56px;
    }
    .ba-btn-gold {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #D9B56D;
        color: #2E2A26;
        padding: 18px 36px;
        border-radius: 2px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-decoration: none;
        text-transform: uppercase;
        transition: background .35s ease, transform .35s ease;
    }
    .ba-btn-gold:hover { background: #E8CC92; transform: translateY(-2px); }
    .ba-btn-outline-light {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #FBF8F2;
        padding: 18px 36px;
        border: 1px solid rgba(247,243,237,.3);
        border-radius: 2px;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-decoration: none;
        text-transform: uppercase;
        transition: all .35s ease;
    }
    .ba-btn-outline-light:hover { background: rgba(247,243,237,.08); border-color: #D9B56D; }
    .ba-cta__trust {
        display: flex;
        gap: 36px;
        justify-content: center;
        flex-wrap: wrap;
        font-size: 12px;
        letter-spacing: 0.1em;
        color: rgba(247,243,237,.5);
    }
    .ba-cta__trust span { display: inline-flex; align-items: center; gap: 8px; }
    .ba-cta__trust span::before {
        content: "✓";
        color: #D9B56D;
    }

    /* =====================================================
       Scroll Animations — fade-up/left/right/in + stagger
       ===================================================== */
    [data-anim] {
        opacity: 0;
        transition: opacity 1s cubic-bezier(.2,.7,.3,1),
                    transform 1s cubic-bezier(.2,.7,.3,1);
        will-change: opacity, transform;
        transition-delay: calc(var(--stagger, 0) * 90ms);
    }
    [data-anim="fade-up"]    { transform: translateY(40px); }
    [data-anim="fade-down"]  { transform: translateY(-40px); }
    [data-anim="fade-left"]  { transform: translateX(40px); }
    [data-anim="fade-right"] { transform: translateX(-40px); }
    [data-anim="scale-in"]   { transform: scale(.94); }
    [data-anim="fade-in"]    { transform: none; }
    [data-anim].is-inview {
        opacity: 1;
        transform: none;
    }

    /* Hero word reveal */
    .ba-hero__title .word {
        display: inline-block;
        overflow: hidden;
        vertical-align: bottom;
        padding-bottom: 0.08em;
        margin-right: 0.18em;
    }
    .ba-hero__title .word > span {
        display: inline-block;
        transform: translateY(100%);
        opacity: 0;
        transition: transform 1s cubic-bezier(.2,.7,.3,1), opacity 1s ease;
        transition-delay: calc(var(--w-i, 0) * 80ms);
    }
    .ba-hero__title.is-loaded .word > span {
        transform: translateY(0);
        opacity: 1;
    }

    /* ===========================
       Responsive
       =========================== */
    @media (max-width: 1024px) {
        .ba-hero { grid-template-columns: 1fr; min-height: auto; }
        .ba-hero__right { aspect-ratio: 4/3; min-height: 320px; }
        .ba-prods { grid-template-columns: repeat(3, 1fr); }
        .ba-benefits { grid-template-columns: repeat(2, 1fr); }
        .ba-cats { grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .ba-star, .ba-sets { grid-template-columns: 1fr; gap: 40px; }
    }
    @media (max-width: 720px) {
        .ba-hero__left { padding: 56px 24px 40px; }
        .ba-prods { grid-template-columns: repeat(2, 1fr); gap: 24px 12px; }
        .ba-cats { grid-template-columns: 1fr; }
        .ba-benefits { grid-template-columns: 1fr; gap: 0; }
        .ba-benefit { padding: 28px 0; }
        .ba-tests { grid-template-columns: 1fr; }
        .ba-compare { grid-template-columns: 1fr; }
        .ba-sets__grid { grid-template-columns: 1fr; }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-anim], .ba-hero__title .word > span,
        .ba-marquee__track {
            opacity: 1 !important;
            transform: none !important;
            animation: none !important;
            transition: none !important;
        }
    }
</style>
@endpush

@section('content')

@php
    // Trust items (string o array según seed)
    $trustItems = collect($hero->trust_items ?? [])
        ->map(fn ($i) => is_array($i) ? ($i['text'] ?? '') : $i)
        ->filter()
        ->values();

    // Hero title — split en palabras para animación
    $heroLines = array_filter([
        $hero->title_line1 ?? '',
        $hero->title_line2 ?? '',
        $hero->title_line3 ?? '',
    ]);
    $highlight = $hero->title_highlight_word ?? '';

    // Producto destacado del star section
    $starProduct = $heroProduct;
@endphp

{{-- ============================================================
     1. HERO — Asymmetric editorial
     ============================================================ --}}
<section class="ba-hero" aria-label="Bienvenida a Belleza Áurea">
    <div class="ba-hero__left">
        @if($hero->eyebrow_text ?? null)
        <span class="ba-eyebrow" data-anim="fade-up">{{ $hero->eyebrow_text }}</span>
        @endif

        <h1 class="ba-hero__title">
            @php $wordIndex = 0; @endphp
            @foreach($heroLines as $line)
                @foreach(explode(' ', $line) as $word)
                    @php
                        $isHighlight = $highlight !== '' && mb_strtolower(trim($word, ',.!?')) === mb_strtolower($highlight);
                    @endphp
                    <span class="word"><span style="--w-i: {{ $wordIndex }};">@if($isHighlight)<em>{{ $word }}</em>@else{{ $word }}@endif</span></span>
                    @php $wordIndex++; @endphp
                @endforeach
                @if(!$loop->last)<br>@endif
            @endforeach
        </h1>

        @if($hero->subtitle ?? null)
        <p class="ba-hero__sub" data-anim="fade-up" style="--stagger: 8;">{{ $hero->subtitle }}</p>
        @endif

        <div class="ba-hero__actions" data-anim="fade-up" style="--stagger: 10;">
            <a href="{{ $hero->btn_primary_url ?? route('products.index') }}" class="ba-btn-primary">
                <span>{{ $hero->btn_primary_text ?? 'Descubrir productos' }}</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <a href="{{ $hero->btn_secondary_url ?? route('blue-light') }}" class="ba-btn-ghost">
                {{ $hero->btn_secondary_text ?? 'Conocer rituales' }}
            </a>
        </div>

        @if($hero->stat1_number || $hero->stat2_number)
        <div class="ba-hero__meta" data-anim="fade-up" style="--stagger: 12;">
            @if($hero->stat1_number)
            <div class="ba-hero__meta-item">
                <span class="ba-hero__meta-num">{{ $hero->stat1_number }}</span>
                <span class="ba-hero__meta-lbl">{{ $hero->stat1_label }}</span>
            </div>
            @endif
            @if($hero->stat2_number)
            <div class="ba-hero__meta-item">
                <span class="ba-hero__meta-num">{{ $hero->stat2_number }}</span>
                <span class="ba-hero__meta-lbl">{{ $hero->stat2_label }}</span>
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="ba-hero__right" aria-hidden="true">
        {{-- Hojas botánicas decorativas --}}
        <svg class="ba-hero__leaf" style="top:10%;left:6%;width:130px;" viewBox="0 0 120 120" fill="none">
            <path d="M20 100 Q 60 60 40 20 M 20 100 Q 70 80 90 40" stroke="#A8B29A" stroke-width="1" stroke-linecap="round"/>
            <ellipse cx="48" cy="50" rx="11" ry="4" transform="rotate(-30 48 50)" fill="#A8B29A" opacity=".6"/>
            <ellipse cx="68" cy="32" rx="9" ry="3.5" transform="rotate(-15 68 32)" fill="#A8B29A" opacity=".6"/>
        </svg>
        <svg class="ba-hero__leaf" style="bottom:8%;right:6%;width:160px;transform:scaleX(-1);" viewBox="0 0 120 120" fill="none">
            <path d="M20 100 Q 60 60 40 20 M 20 100 Q 70 80 90 40 M 38 70 Q 70 60 70 30" stroke="#A8B29A" stroke-width="1" stroke-linecap="round"/>
            <ellipse cx="48" cy="50" rx="11" ry="4" transform="rotate(-30 48 50)" fill="#A8B29A" opacity=".5"/>
            <ellipse cx="68" cy="32" rx="9" ry="3.5" transform="rotate(-15 68 32)" fill="#A8B29A" opacity=".5"/>
        </svg>

        <div class="ba-hero__product">
            @php
                // Detectar si HeroSetting tiene un video subido. Soportamos
                // media_type='video' o media_path con extensión de video.
                $videoExts = ['mp4', 'webm', 'mov'];
                $heroMedia = $hero->media_path ?? null;
                $heroExt = $heroMedia ? strtolower(pathinfo($heroMedia, PATHINFO_EXTENSION)) : null;
                $isHeroVideo = ($hero->media_type ?? null) === 'video'
                    || ($heroMedia && in_array($heroExt, $videoExts, true));
                $heroVideoUrl = $isHeroVideo && $heroMedia ? asset('storage/'.$heroMedia) : null;
                $heroImageUrl = (! $isHeroVideo && $heroMedia) ? asset('storage/'.$heroMedia) : null;
            @endphp

            @if($heroVideoUrl)
                {{-- Video b-roll: autoplay muted loop. Sin controles para no distraer. --}}
                <video class="ba-hero__video"
                       autoplay muted loop playsinline preload="metadata"
                       aria-hidden="true"
                       @if($starProduct && !empty($starProduct->images)) poster="{{ asset('storage/'.$starProduct->images[0]) }}" @endif>
                    <source src="{{ $heroVideoUrl }}" type="video/{{ $heroExt === 'mov' ? 'quicktime' : $heroExt }}">
                </video>
            @elseif($heroImageUrl)
                <img src="{{ $heroImageUrl }}" alt="{{ $hero->title_line1 ?? 'Belleza Áurea' }}">
            @elseif($starProduct && !empty($starProduct->images))
                <img src="{{ asset('storage/'.$starProduct->images[0]) }}" alt="{{ $starProduct->name }} — producto destacado de Belleza Áurea">
            @else
                <img src="{{ asset('img/brand/logo-transparent.png') }}" alt="Belleza Áurea — cosmética natural">
            @endif
        </div>
    </div>
</section>

{{-- ============================================================
     2. MARQUEE — Trust strip
     ============================================================ --}}
@if($trustItems->isNotEmpty())
<aside class="ba-marquee" aria-label="Beneficios principales">
    <div class="ba-marquee__track">
        @for($mIter = 0; $mIter < 2; $mIter++)
            @foreach($trustItems as $item)
                <span class="ba-marquee__item">
                    <span class="ba-marquee__dot"></span>{{ $item }}
                </span>
            @endforeach
            <span class="ba-marquee__item"><span class="ba-marquee__dot"></span>Belleza natural, elegante y atemporal</span>
        @endfor
    </div>
</aside>
@endif

{{-- ============================================================
     3. CATEGORÍAS — Magazine grid asimétrico
     Las 8 categorías top por sort_order (editable en /admin/categories).
     Primera card ocupa 2x2 (hero); las otras 6 ocupan 1x1.
     ============================================================ --}}
@if(($categories ?? collect())->isNotEmpty())
<section class="ba-section ba-section--cream-soft" aria-labelledby="cats-title">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">{{ $homePage->categories_label ?? 'Categorías' }}</span>
            <h2 id="cats-title" class="ba-section-head__title">{{ $homePage->categories_title ?? 'Encuentra lo que buscas' }}</h2>
            @if($homePage->categories_subtitle)
            <p class="ba-section-head__sub">{{ $homePage->categories_subtitle }}</p>
            @endif
            <div class="ba-divider"></div>
        </header>

        @php
            // Gradientes áureos como fallback cuando la categoría no tiene imagen.
            // Sage / blush / gold / cream / taupe / olive
            $catGradients = [
                'linear-gradient(135deg,#BE9A53 0%,#E8CC92 60%,#FBF4E6 100%)',  // gold cream
                'linear-gradient(135deg,#8A9680 0%,#A8B29A 60%,#E5EBD8 100%)',  // sage
                'linear-gradient(135deg,#C9A693 0%,#E8D1C5 60%,#F7E8DE 100%)',  // blush taupe
                'linear-gradient(135deg,#A8825A 0%,#D9B56D 60%,#F0DEB0 100%)',  // bronze
                'linear-gradient(135deg,#7A8470 0%,#9DA890 60%,#D9E0CC 100%)',  // moss
                'linear-gradient(135deg,#B89A7C 0%,#D4BC9E 60%,#F2E5D2 100%)',  // sand
                'linear-gradient(135deg,#704A3B 0%,#A47659 60%,#E8C9A8 100%)',  // chocolate
                'linear-gradient(135deg,#9C7A8E 0%,#C9A8B5 60%,#EBD3DA 100%)',  // mauve
            ];
        @endphp

        <div class="ba-cats">
            @foreach($categories as $i => $cat)
                @php
                    $count = $cat->products_count ?? 0;
                    $catLink = route('products.index', ['category' => $cat->slug]);
                    $fallback = $catGradients[$i % count($catGradients)];
                @endphp
                <a href="{{ $catLink }}"
                   class="ba-cat"
                   data-anim="fade-up"
                   style="--stagger: {{ $i }};"
                   aria-label="Explorar {{ $cat->name }} — {{ $count }} producto{{ $count === 1 ? '' : 's' }}">
                    @if($cat->image)
                        <div class="ba-cat__bg" style="background-image:url('{{ asset('storage/'.$cat->image) }}');"></div>
                    @else
                        <div class="ba-cat__bg" style="background-image:{{ $fallback }};"></div>
                    @endif
                    <div class="ba-cat__overlay"></div>
                    <div class="ba-cat__body">
                        @if($count > 0)
                        <span class="ba-cat__count">{{ $count }} producto{{ $count === 1 ? '' : 's' }}</span>
                        @endif
                        <h3 class="ba-cat__name">{{ $cat->name }}</h3>
                        @if($cat->description)
                        <p class="ba-cat__desc">{{ \Illuminate\Support\Str::limit($cat->description, 110) }}</p>
                        @endif
                        <span class="ba-cat__cta">
                            Explorar
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:48px;" data-anim="fade-up">
            <a href="{{ route('products.index') }}" class="ba-btn-ghost">Ver todas las categorías</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     4. CATÁLOGO — Productos destacados
     ============================================================ --}}
@if($lentes->isNotEmpty())
<section class="ba-section" aria-labelledby="catalog-title">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">{{ $homePage->catalog_label ?? 'Catálogo' }}</span>
            <h2 id="catalog-title" class="ba-section-head__title">{{ $homePage->catalog_title ?? 'Nuestros productos' }}</h2>
            @if($homePage->catalog_subtitle)
            <p class="ba-section-head__sub">{{ $homePage->catalog_subtitle }}</p>
            @endif
            <div class="ba-divider"></div>
        </header>

        <div class="ba-prods">
            @foreach($lentes->take(8) as $i => $p)
                <a href="{{ route('products.show', ['slug' => $p->slug]) }}"
                   class="ba-card"
                   data-anim="fade-up"
                   style="--stagger: {{ $i % 4 }};">
                    <div class="ba-card__img {{ empty($p->images) ? 'ba-card__img--ph' : '' }}">
                        @if(!empty($p->images))
                            <img src="{{ asset('storage/'.$p->images[0]) }}" alt="{{ $p->name }} — Belleza Áurea" loading="lazy">
                        @else
                            <span>Próximamente</span>
                        @endif
                    </div>
                    @if($p->category)
                    <p class="ba-card__cat">{{ $p->category->name }}</p>
                    @endif
                    <h3 class="ba-card__name">{{ $p->name }}</h3>
                    <div class="ba-card__price-row">
                        <span class="ba-card__price">${{ number_format($p->price, 0, ',', '.') }}</span>
                        @if($p->compare_price && $p->compare_price > $p->price)
                        <span class="ba-card__compare">${{ number_format($p->compare_price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:64px;" data-anim="fade-up">
            <a href="{{ route('products.index') }}" class="ba-btn-ghost">Ver catálogo completo</a>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     5. STAR PRODUCT — Split layout editorial
     ============================================================ --}}
@if($starProduct)
<section class="ba-section ba-section--cream" aria-labelledby="star-title">
    <div class="ba-container">
        <div class="ba-star">
            <div class="ba-star__visual" data-anim="fade-right">
                @if(!empty($starProduct->images))
                <img src="{{ asset('storage/'.$starProduct->images[0]) }}" alt="{{ $starProduct->name }}">
                @else
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                    <img src="{{ asset('img/brand/logo-transparent.png') }}" alt="Belleza Áurea" style="width:55%;opacity:.85;">
                </div>
                @endif
            </div>
            <div class="ba-star__body" data-anim="fade-left" style="--stagger: 2;">
                <span class="ba-star__label">{{ $homePage->promo_label ?? 'Producto estrella' }}</span>
                <h2 id="star-title" class="ba-star__title">{{ $homePage->promo_title ?? $starProduct->name }}</h2>
                <p class="ba-star__desc">{{ $homePage->promo_description ?? \Illuminate\Support\Str::limit(strip_tags($starProduct->description), 220) }}</p>
                <div class="ba-star__price-block">
                    <span class="ba-star__price">{{ $homePage->promo_price ?? '$'.number_format($starProduct->price, 0, ',', '.') }}</span>
                    <span class="ba-star__note">{{ $homePage->promo_price_note ?? 'Envío gratis en compras +$899' }}</span>
                </div>
                <a href="{{ route('products.show', ['slug' => $starProduct->slug]) }}" class="ba-btn-primary">
                    <span>{{ $homePage->promo_btn_text ?? 'Descubrir el ritual' }}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     6. BENEFITS — Belleza con propósito
     ============================================================ --}}
@php
    $benefitsCards = collect($homePage->benefits_cards ?? [])->filter(fn ($b) => !empty($b['title'] ?? ''));
@endphp
@if($benefitsCards->isNotEmpty())
<section class="ba-section" aria-labelledby="benefits-title">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">{{ $homePage->benefits_label ?? 'Por qué elegirnos' }}</span>
            <h2 id="benefits-title" class="ba-section-head__title">{{ $homePage->benefits_title ?? 'Belleza con propósito' }}</h2>
            @if($homePage->benefits_subtitle)
            <p class="ba-section-head__sub">{{ $homePage->benefits_subtitle }}</p>
            @endif
            <div class="ba-divider"></div>
        </header>

        <div class="ba-benefits">
            @foreach($benefitsCards->values() as $i => $b)
                <div class="ba-benefit" data-anim="fade-up" style="--stagger: {{ $i }};">
                    <div class="ba-benefit__num">— {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <h3 class="ba-benefit__title">{{ $b['title'] }}</h3>
                    <p class="ba-benefit__desc">{{ $b['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     7. EDITORIAL QUOTE — Manifesto
     ============================================================ --}}
<section class="ba-section ba-section--cream-soft" aria-label="Filosofía de marca">
    <div class="ba-quote" data-anim="fade-in">
        <p class="ba-quote__text">
            "Belleza natural, elegante y atemporal — un ritual cuidado que cambia tu piel sin cambiar tu esencia."
        </p>
        <p class="ba-quote__author">— Belleza Áurea</p>
    </div>
</section>

{{-- ============================================================
     8. SETS / RITUALES — Split con sets
     ============================================================ --}}
@if($toallitas->isNotEmpty())
<section class="ba-section" aria-labelledby="sets-title">
    <div class="ba-container">
        <div class="ba-sets">
            <div data-anim="fade-right">
                <span class="ba-section-head__label">{{ $homePage->wipes_label ?? 'Sets' }}</span>
                <h2 id="sets-title" class="ba-section-head__title" style="text-align:left;margin-top:16px;">{{ $homePage->wipes_title ?? 'Rituales completos' }}</h2>
                @if($homePage->wipes_description)
                <p class="ba-star__desc" style="margin-top:24px;">{{ $homePage->wipes_description }}</p>
                @endif

                @if(!empty($homePage->wipes_features))
                <ul class="ba-sets__list">
                    @foreach($homePage->wipes_features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                @endif

                <a href="{{ route('products.index', ['type' => 'toallitas']) }}" class="ba-btn-ghost">Ver todos los sets</a>
            </div>

            <div class="ba-sets__grid">
                @foreach($toallitas->take(2) as $i => $set)
                    <a href="{{ route('products.show', ['slug' => $set->slug]) }}"
                       class="ba-card"
                       data-anim="fade-left"
                       style="--stagger: {{ $i + 1 }};">
                        <div class="ba-card__img {{ empty($set->images) ? 'ba-card__img--ph' : '' }}">
                            @if(!empty($set->images))
                                <img src="{{ asset('storage/'.$set->images[0]) }}" alt="{{ $set->name }}" loading="lazy">
                            @else
                                <span>Próximamente</span>
                            @endif
                        </div>
                        <p class="ba-card__cat">Set</p>
                        <h3 class="ba-card__name">{{ $set->name }}</h3>
                        <div class="ba-card__price-row">
                            <span class="ba-card__price">${{ number_format($set->price, 0, ',', '.') }}</span>
                            @if($set->compare_price && $set->compare_price > $set->price)
                            <span class="ba-card__compare">${{ number_format($set->compare_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     9. COMPARISON — Con vs Sin ritual
     ============================================================ --}}
@if(!empty($homePage->comparison_without_items) || !empty($homePage->comparison_with_items))
<section class="ba-section ba-section--cream" aria-labelledby="compare-title">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">{{ $homePage->comparison_label ?? 'El antes y después' }}</span>
            <h2 id="compare-title" class="ba-section-head__title">{{ $homePage->comparison_title ?? 'Con vs. sin tu ritual áureo' }}</h2>
            @if($homePage->comparison_subtitle)
            <p class="ba-section-head__sub">{{ $homePage->comparison_subtitle }}</p>
            @endif
            <div class="ba-divider"></div>
        </header>

        <div class="ba-compare">
            <div class="ba-compare__col ba-compare__col--without" data-anim="fade-right">
                <p class="ba-compare__label">{{ $homePage->comparison_without_label ?? 'Sin ritual' }}</p>
                <ul class="ba-compare__list">
                    @foreach(($homePage->comparison_without_items ?? []) as $item)
                        <li>{{ is_array($item) ? ($item['text'] ?? $item[0] ?? '') : $item }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="ba-compare__col ba-compare__col--with" data-anim="fade-left">
                <p class="ba-compare__label">{{ $homePage->comparison_with_label ?? 'Con Belleza Áurea' }}</p>
                <ul class="ba-compare__list">
                    @foreach(($homePage->comparison_with_items ?? []) as $item)
                        <li>{{ is_array($item) ? ($item['text'] ?? $item[0] ?? '') : $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     9b. MARCAS QUE DISTRIBUIMOS — carrusel infinito de logos
     SEO: cada logo es un <a> real con anchor text de la marca, lo
     cual ayuda a indexar /marcas/{slug} y refuerza la autoridad.
     ============================================================ --}}
@if(($featuredBrands ?? collect())->isNotEmpty())
<section class="ba-section" aria-labelledby="brands-title" style="padding:clamp(64px,8vw,100px) 0;">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">Marcas que distribuimos</span>
            <h2 id="brands-title" class="ba-section-head__title">Trabajamos con las mejores</h2>
            <p class="ba-section-head__sub">Selección curada de marcas premium con ingredientes botánicos, formulaciones limpias y resultados visibles.</p>
            <div class="ba-divider"></div>
        </header>
    </div>

    {{-- Carrusel infinito, full-bleed --}}
    <div class="ba-brands-marquee" data-anim="fade-in" style="--stagger: 2;">
        <div class="ba-brands-track">
            @for($iter = 0; $iter < 2; $iter++)
                @foreach($featuredBrands as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}"
                       class="ba-brand-logo"
                       title="{{ $brand->name }} — {{ $brand->short_description ?? 'Ver productos' }}">
                        @if($brand->logo_path)
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy">
                        @else
                            <span class="ba-brand-logo__text">{{ $brand->name }}</span>
                        @endif
                    </a>
                @endforeach
            @endfor
        </div>
    </div>

    <div class="ba-container" style="text-align:center;margin-top:48px;" data-anim="fade-up">
        <a href="{{ route('brands.index') }}" class="ba-btn-ghost">Ver todas las marcas</a>
    </div>
</section>

<style>
    .ba-brands-marquee {
        position: relative;
        overflow: hidden;
        padding: 32px 0;
        mask-image: linear-gradient(to right, transparent 0, #000 8%, #000 92%, transparent 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0, #000 8%, #000 92%, transparent 100%);
    }
    .ba-brands-track {
        display: flex;
        align-items: center;
        gap: 72px;
        white-space: nowrap;
        animation: ba-brands-scroll 42s linear infinite;
        width: max-content;
    }
    .ba-brands-marquee:hover .ba-brands-track {
        animation-play-state: paused;
    }
    @keyframes ba-brands-scroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .ba-brand-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 80px;
        min-width: 140px;
        padding: 0 8px;
        text-decoration: none;
        transition: filter .35s ease, opacity .35s ease, transform .35s ease;
        filter: grayscale(100%);
        opacity: .55;
        flex-shrink: 0;
    }
    .ba-brand-logo:hover {
        filter: none;
        opacity: 1;
        transform: scale(1.05);
    }
    .ba-brand-logo img {
        max-height: 60px;
        max-width: 160px;
        object-fit: contain;
        display: block;
    }
    .ba-brand-logo__text {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        font-weight: 500;
        color: #2E2A26;
        letter-spacing: .04em;
        white-space: nowrap;
    }
    @media (prefers-reduced-motion: reduce) {
        .ba-brands-track { animation: none; }
    }
</style>
@endif

{{-- ============================================================
     10. TESTIMONIALS
     ============================================================ --}}
@php
    $displayTestimonials = $testimonials->count() ? $testimonials->take(3) : collect([
        (object) ['name' => 'Camila R.', 'role' => 'Editora de moda', 'body' => 'Empecé con el Sérum Áureo y a las cuatro semanas mi piel está visiblemente más luminosa. Es mi ritual sagrado de las mañanas.'],
        (object) ['name' => 'Valentina M.', 'role' => 'Empresaria', 'body' => 'El Ritual Esencial me cambió la rutina por completo. La sensación de la crema con karité es como un mimo diario. Tres meses y mi piel nunca se vio mejor.'],
        (object) ['name' => 'Sofía L.', 'role' => 'Maquilladora profesional', 'body' => 'Recomiendo Belleza Áurea a todas mis clientas. Fórmulas limpias, activos en concentración correcta y el packaging es una belleza.'],
    ]);
@endphp
@if($displayTestimonials->count())
<section class="ba-section" aria-labelledby="tests-title">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">Testimonios</span>
            <h2 id="tests-title" class="ba-section-head__title">Lo que dicen nuestras clientas</h2>
            <div class="ba-divider"></div>
        </header>

        <div class="ba-tests">
            @foreach($displayTestimonials as $i => $t)
                @php
                    $tName = is_object($t) ? $t->name : ($t['name'] ?? '');
                    $tRole = is_object($t) ? ($t->role ?? '') : ($t['role'] ?? '');
                    $tBody = is_object($t) ? $t->body : ($t['body'] ?? '');
                @endphp
                <article class="ba-test" data-anim="fade-up" style="--stagger: {{ $i }};">
                    <div class="ba-test__mark">"</div>
                    <p class="ba-test__body">{{ $tBody }}</p>
                    <p class="ba-test__author">{{ $tName }}</p>
                    @if($tRole)
                    <p class="ba-test__role">{{ $tRole }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     11. FAQ — Accordion semántico (SEO)
     ============================================================ --}}
@if(!empty($homePage->faqs))
<section class="ba-section ba-section--cream-soft" aria-labelledby="faq-title">
    <div class="ba-container">
        <header class="ba-section-head" data-anim="fade-up">
            <span class="ba-section-head__label">FAQ</span>
            <h2 id="faq-title" class="ba-section-head__title">Preguntas frecuentes</h2>
            <div class="ba-divider"></div>
        </header>

        <div class="ba-faq" itemscope itemtype="https://schema.org/FAQPage">
            @foreach($homePage->faqs as $i => $faq)
                <details {{ $i === 0 ? 'open' : '' }} itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" data-anim="fade-up" style="--stagger: {{ $i % 3 }};">
                    <summary itemprop="name">{{ $faq['q'] ?? '' }}</summary>
                    <div class="ba-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">{{ $faq['a'] ?? '' }}</div>
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
     12. FINAL CTA — Dark ink
     ============================================================ --}}
<section class="ba-section ba-section--ink" aria-labelledby="cta-title">
    <div class="ba-cta">
        <h2 id="cta-title" class="ba-cta__title" data-anim="fade-up">
            @php
                $ctaTitle = $homePage->cta_title ?? '¿Lista para tu ritual áureo?';
                // Hacer italic la palabra "áureo" o última palabra significativa
                if (str_contains(mb_strtolower($ctaTitle), 'áureo')) {
                    $ctaTitle = preg_replace('/(áureo|Áureo)/u', '<em>$1</em>', $ctaTitle);
                }
            @endphp
            {!! $ctaTitle !!}
        </h2>
        @if($homePage->cta_subtitle)
        <p class="ba-cta__sub" data-anim="fade-up" style="--stagger: 2;">{{ $homePage->cta_subtitle }}</p>
        @endif

        <div class="ba-cta__actions" data-anim="fade-up" style="--stagger: 4;">
            <a href="{{ route('products.index') }}" class="ba-btn-gold">{{ $homePage->cta_btn_primary_text ?? 'Comprar ahora' }}</a>
            <a href="{{ route('landing.quiz') }}" class="ba-btn-outline-light">{{ $homePage->cta_btn_secondary_text ?? 'Quiz de piel' }}</a>
        </div>

        @if(!empty($homePage->cta_trust_items))
        <div class="ba-cta__trust" data-anim="fade-up" style="--stagger: 6;">
            @foreach($homePage->cta_trust_items as $t)
                <span>{{ $t }}</span>
            @endforeach
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    /* =============================================
       Belleza Áurea — Home animations on scroll
       Vanilla IntersectionObserver, sin librerías.
       ============================================= */
    (function () {
        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // 1. Hero word-by-word reveal (trigger inmediato)
        const heroTitle = document.querySelector('.ba-hero__title');
        if (heroTitle) {
            requestAnimationFrame(() => heroTitle.classList.add('is-loaded'));
        }

        if (reducedMotion) {
            document.querySelectorAll('[data-anim]').forEach(el => el.classList.add('is-inview'));
            return;
        }

        // 2. Generic scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-inview');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -60px 0px',
        });

        document.querySelectorAll('[data-anim]').forEach(el => observer.observe(el));

        // Failsafe — al cabo de 2.5s mostrar todo lo que no haya entrado en vista
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.querySelectorAll('[data-anim]:not(.is-inview)').forEach(el => el.classList.add('is-inview'));
            }, 2500);
        });

        // 3. Parallax sutil en imagen del hero
        const heroImg = document.querySelector('.ba-hero__product img');
        if (heroImg) {
            let raf = null;
            const onScroll = () => {
                if (raf) return;
                raf = requestAnimationFrame(() => {
                    const y = window.scrollY;
                    if (y < 800) {
                        heroImg.style.transform = `translateY(${y * 0.08}px)`;
                    }
                    raf = null;
                });
            };
            window.addEventListener('scroll', onScroll, { passive: true });
        }
    })();
</script>
@endpush
