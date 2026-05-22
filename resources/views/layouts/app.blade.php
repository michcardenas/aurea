<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta (overridable per page) --}}
    <title>@yield('title', 'Belleza Áurea | Cosmética natural, elegante y atemporal')</title>
    <meta name="description" content="@yield('meta_description', 'Belleza natural, elegante y atemporal. Skincare, fragancias y rituales de belleza premium en Belleza Áurea.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'Belleza Áurea')">
    <meta property="og:description" content="@yield('og_description', 'Belleza natural, elegante y atemporal')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('img/brand/logo-principal.png'))">
    <meta property="og:site_name" content="Belleza Áurea">
    <meta property="og:locale" content="es_ES">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('twitter_title', 'Belleza Áurea')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Belleza natural, elegante y atemporal')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('img/brand/logo-principal.png'))">

    {{-- Theme color --}}
    <meta name="theme-color" content="#F7F3ED">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/brand/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/brand/favicon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/brand/favicon-192.png') }}">

    {{-- Google Fonts: Playfair Display + Montserrat (Belleza Áurea) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Schema.org JSON-LD --}}
    @stack('schema')

    {{-- Page-specific head content --}}
    @stack('head')
</head>

<body class="font-body min-h-screen flex flex-col antialiased @yield('body_class', 'bg-cream text-ink')">
    {{-- Skip to content (accessibility) --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-gold focus:text-white focus:px-4 focus:py-2 focus:rounded">
        Saltar al contenido
    </a>

    {{-- Navigation --}}
    @include('partials.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition class="fixed top-20 right-4 z-50 bg-success text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition class="fixed top-20 right-4 z-50 bg-danger text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main Content --}}
    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- WhatsApp floating button --}}
    <a href="{{ \App\Models\ContactPageSetting::whatsappUrl() }}"
       target="_blank"
       rel="noopener"
       aria-label="Contactar por WhatsApp"
       style="position:fixed !important;bottom:24px !important;right:24px !important;top:auto !important;left:auto !important;z-index:9999;width:60px;height:60px;border-radius:50%;background-color:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(37,211,102,0.5);text-decoration:none;opacity:1;transition:transform .2s ease, box-shadow .2s ease;"
       onmouseover="this.style.transform='scale(1.1)';this.style.backgroundColor='#1ebe5d';"
       onmouseout="this.style.transform='scale(1)';this.style.backgroundColor='#25D366';">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
        </svg>
    </a>

    {{-- Alpine x-reveal directive + vanilla reveal observer --}}
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.directive('reveal', (el, { modifiers }, { cleanup }) => {
            el.classList.add('reveal');
            const observer = new IntersectionObserver(
                ([entry]) => {
                    if (entry.isIntersecting) {
                        el.classList.add('visible');
                        if (modifiers.includes('once')) observer.disconnect();
                    }
                },
                { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
            );
            observer.observe(el);
            cleanup(() => observer.disconnect());
        });
    });
    /* Hero scroll-driven image gallery */
    window.heroGallery = () => ({
        current: 0,
        total: 8,
        onScroll() {
            const step = 120; // px de scroll por cambio de imagen
            this.current = Math.min(Math.floor(window.scrollY / step), this.total - 1);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const items = document.querySelectorAll('.reveal');
        const obs = new IntersectionObserver(
            entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }),
            { threshold: 0.1, rootMargin: '0px 0px -30px 0px' }
        );
        items.forEach(el => obs.observe(el));
    });
    </script>

    @stack('scripts')
</body>
</html>
