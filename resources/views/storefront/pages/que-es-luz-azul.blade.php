@extends('layouts.app')

@section('title', $seoSettings->meta_title ?? '¿Qué es la luz azul? | Nuvion Glass')
@section('meta_description', $seoSettings->meta_description ?? '¿Qué es la luz azul y por qué es dañina? Aprende cómo proteger tus ojos de pantallas, focos LED y más. Guía completa de Nuvion Glass.')
@section('canonical', $seoSettings->canonical_url ?? route('blue-light'))
@section('og_title', $seoSettings->og_title ?? $seoSettings->meta_title ?? '¿Qué es la luz azul? | Nuvion Glass')
@section('og_description', $seoSettings->og_description ?? 'Aprende cómo la luz azul afecta tus ojos y cómo protegerte con lentes especializados.')
@section('twitter_title', $seoSettings->twitter_title ?? $seoSettings->meta_title ?? '¿Qué es la luz azul? | Nuvion Glass')
@section('twitter_description', $seoSettings->twitter_description ?? 'Aprende cómo la luz azul afecta tus ojos y cómo protegerte con lentes especializados.')

@push('schema')
    {!! $faqSchema !!}
    {!! $breadcrumbSchema !!}
@endpush

@section('content')

    {{-- ============================================================
         1. HERO
    ============================================================= --}}
    <section class="relative bg-bg overflow-hidden">
        {{-- Radial glow --}}
        <div class="absolute inset-0 opacity-30" style="background:radial-gradient(ellipse at 50% 30%, #3A8DDE 0%, transparent 65%);"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-36 text-center">
            {{-- Breadcrumb --}}
            <nav class="mb-8 text-sm text-text/40">
                <a href="{{ route('home') }}" class="hover:text-text/70 transition-colors">Inicio</a>
                <span class="mx-2">/</span>
                <span class="text-text/70">Luz azul</span>
            </nav>

            <h1 class="font-brand text-4xl sm:text-5xl md:text-6xl font-bold leading-tight anim-fade-up">
                <span class="text-text">{{ $blueLightPage->hero_title_prefix ?? '¿Qué es la ' }}</span>
                <span class="bg-gradient-to-r from-secondary to-accent bg-clip-text text-transparent">{{ $blueLightPage->hero_title_accent ?? 'luz azul' }}</span>
                <span class="text-text">{{ $blueLightPage->hero_title_suffix ?? '?' }}</span>
            </h1>

            <p class="mt-6 text-lg md:text-xl text-text/60 max-w-2xl mx-auto leading-relaxed anim-fade-up delay-200">
                {{ $blueLightPage->hero_subtitle ?? 'Todo lo que necesitas saber sobre la luz que emiten tus pantallas, cómo afecta tu salud visual y qué puedes hacer para protegerte.' }}
            </p>

            {{-- Scroll indicator --}}
            <div class="mt-12 anim-fade-up delay-400">
                <svg class="w-6 h-6 mx-auto text-text/30 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </div>
        </div>
    </section>

    {{-- ============================================================
         2. ¿QUÉ ES LA LUZ AZUL? + ESPECTRO VISUAL
    ============================================================= --}}
    <section class="py-20 md:py-28 bg-bg-light">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 md:gap-16 items-center">
                {{-- Text --}}
                <div class="reveal">
                    <span class="inline-block text-xs font-bold uppercase tracking-widest text-secondary mb-4">{{ $blueLightPage->science_label ?? 'Ciencia visual' }}</span>
                    <h2 class="font-brand text-3xl md:text-4xl font-bold text-text-dark leading-tight">
                        {{ $blueLightPage->science_title ?? 'La luz que no ves, pero tus ojos sí sienten' }}
                    </h2>
                    <p class="mt-6 text-text-muted leading-relaxed">
                        {!! $blueLightPage->science_paragraph1 ?? 'La luz azul es una porción del espectro visible con longitud de onda entre
                        <strong class="text-text-dark">380 y 500 nanómetros</strong>. Es emitida por el sol,
                        pantallas de dispositivos electrónicos, focos LED y luces fluorescentes.' !!}
                    </p>
                    <p class="mt-4 text-text-muted leading-relaxed">
                        {!! $blueLightPage->science_paragraph2 ?? 'Aunque cierta cantidad de luz azul es natural y necesaria, la
                        <strong class="text-text-dark">exposición prolongada a fuentes artificiales</strong>
                        como pantallas y focos LED puede dañar tu salud visual.' !!}
                    </p>
                </div>

                {{-- Spectrum visual --}}
                <div class="reveal delay-150">
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-border-light">
                        <p class="text-xs font-bold uppercase tracking-widest text-text-muted mb-5">Espectro de luz visible</p>

                        {{-- Spectrum bars --}}
                        <div class="space-y-3">
                            @php
                                $spectrum = [
                                    ['label' => 'Ultravioleta', 'color' => '#7C3AED', 'nm' => '< 380 nm', 'width' => '15%', 'danger' => false],
                                    ['label' => 'Violeta', 'color' => '#8B5CF6', 'nm' => '380-420 nm', 'width' => '20%', 'danger' => true],
                                    ['label' => 'Azul', 'color' => '#3A8DDE', 'nm' => '420-500 nm', 'width' => '35%', 'danger' => true],
                                    ['label' => 'Verde', 'color' => '#10B981', 'nm' => '500-565 nm', 'width' => '45%', 'danger' => false],
                                    ['label' => 'Amarillo', 'color' => '#F59E0B', 'nm' => '565-590 nm', 'width' => '55%', 'danger' => false],
                                    ['label' => 'Naranja', 'color' => '#F97316', 'nm' => '590-625 nm', 'width' => '65%', 'danger' => false],
                                    ['label' => 'Rojo', 'color' => '#EF4444', 'nm' => '625-700 nm', 'width' => '80%', 'danger' => false],
                                ];
                            @endphp

                            @foreach($spectrum as $band)
                                <div class="flex items-center gap-3">
                                    <div class="w-20 text-xs font-medium {{ $band['danger'] ? 'text-text-dark font-bold' : 'text-text-muted' }}">
                                        {{ $band['label'] }}
                                    </div>
                                    <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden relative">
                                        <div class="h-full rounded-full transition-all duration-1000 {{ $band['danger'] ? 'animate-pulse' : '' }}"
                                             style="width:{{ $band['width'] }};background-color:{{ $band['color'] }};"></div>
                                    </div>
                                    <div class="w-20 text-right text-xs text-text-muted">{{ $band['nm'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Legend --}}
                        <div class="mt-5 flex items-center gap-2 text-xs">
                            <span class="w-3 h-3 rounded-full bg-secondary animate-pulse"></span>
                            <span class="text-text-muted">Las bandas pulsantes son <strong class="text-text-dark">luz azul de alta energía</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         3. SÍNTOMAS / ¿POR QUÉ ES DAÑINA?
    ============================================================= --}}
    <section class="py-20 md:py-28">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center reveal">
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-danger mb-4">{{ $blueLightPage->symptoms_label ?? 'Efectos en tu salud' }}</span>
                <h2 class="font-brand text-3xl md:text-4xl font-bold text-text-dark">
                    {{ $blueLightPage->symptoms_title ?? '¿Por qué es dañina?' }}
                </h2>
                <p class="mt-4 text-text-muted max-w-2xl mx-auto leading-relaxed">
                    {{ $blueLightPage->symptoms_subtitle ?? 'La exposición prolongada a luz azul artificial puede provocar estos síntomas:' }}
                </p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $symptoms = $blueLightPage->symptoms_cards ?? [
                        ['icon' => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Fatiga visual', 'desc' => 'Ojos cansados, visión borrosa y dificultad para enfocar después de horas frente a la pantalla.', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
                        ['icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z', 'title' => 'Dolores de cabeza', 'desc' => 'Cefaleas frecuentes causadas por el esfuerzo visual y la sobreestimulación lumínica.', 'color' => 'text-orange-500', 'bg' => 'bg-orange-50'],
                        ['icon' => 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z', 'title' => 'Insomnio', 'desc' => 'La luz azul suprime la melatonina, afectando tu ciclo de sueño y la calidad de descanso.', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50'],
                        ['icon' => 'M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z', 'title' => 'Ojos secos', 'desc' => 'Reducción del parpadeo frente a pantallas que provoca sequedad e irritación ocular.', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                    ];
                @endphp

                @foreach($symptoms as $i => $symptom)
                    <div class="reveal delay-{{ ($i + 1) * 150 }} group">
                        <div class="bg-white rounded-2xl p-6 border border-border-light shadow-sm h-full hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                            <div class="w-12 h-12 {{ $symptom['bg'] ?? 'bg-red-50' }} rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 {{ $symptom['color'] ?? 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $symptom['icon'] ?? '' }}"/>
                                </svg>
                            </div>
                            <h3 class="font-brand text-lg font-bold text-text-dark">{{ $symptom['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm text-text-muted leading-relaxed">{{ $symptom['desc'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         4. ¿CÓMO TE PROTEGEN LOS LENTES NUVION?
    ============================================================= --}}
    <section class="py-20 md:py-28 bg-bg overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 md:gap-16 items-center">
                {{-- Visual --}}
                <div class="reveal order-2 md:order-1">
                    <div class="relative">
                        {{-- Shield graphic --}}
                        <div class="aspect-square max-w-sm mx-auto relative">
                            {{-- Outer ring --}}
                            <div class="absolute inset-0 rounded-full border-2 border-secondary/20"></div>
                            {{-- Middle ring --}}
                            <div class="absolute inset-6 rounded-full border-2 border-secondary/30"></div>
                            {{-- Inner circle --}}
                            <div class="absolute inset-12 rounded-full bg-gradient-to-br from-secondary/20 to-primary/20 flex items-center justify-center">
                                <div class="text-center">
                                    <span class="block text-5xl md:text-6xl font-brand font-bold text-secondary">{{ $blueLightPage->shield_percentage ?? '30-50%' }}</span>
                                    <span class="block text-sm text-text/60 mt-1">{{ $blueLightPage->shield_label ?? 'de bloqueo' }}</span>
                                    <span class="block text-xs text-text/40 mt-0.5">{{ $blueLightPage->shield_sublabel ?? 'de luz azul dañina' }}</span>
                                </div>
                            </div>
                            {{-- Floating dots --}}
                            <div class="absolute top-4 right-8 w-3 h-3 rounded-full bg-secondary/50 anim-float"></div>
                            <div class="absolute bottom-12 left-4 w-2 h-2 rounded-full bg-accent/50 anim-float delay-200"></div>
                            <div class="absolute top-1/3 left-2 w-2 h-2 rounded-full bg-primary/30 anim-float delay-400"></div>
                        </div>
                    </div>
                </div>

                {{-- Text --}}
                <div class="reveal order-1 md:order-2">
                    <span class="inline-block text-xs font-bold uppercase tracking-widest text-secondary mb-4">{{ $blueLightPage->protection_label ?? 'Tecnología nuvion' }}</span>
                    <h2 class="font-brand text-3xl md:text-4xl font-bold text-text leading-tight">
                        {{ $blueLightPage->protection_title ?? 'Protección real para tus ojos' }}
                    </h2>
                    <p class="mt-6 text-text/60 leading-relaxed">
                        {!! $blueLightPage->protection_description ?? 'Nuestros lentes están equipados con un <strong class="text-text/80">filtro especializado</strong>
                        que bloquea entre el 30% y 50% de la luz azul de alta energía, reduciendo significativamente
                        la fatiga visual.' !!}
                    </p>

                    <div class="mt-8 space-y-4">
                        @php
                            $benefits = $blueLightPage->protection_benefits ?? [
                                'Reduce la fatiga visual tras largas jornadas',
                                'Mejora la calidad de tu sueño',
                                'Disminuye dolores de cabeza',
                                'Protección con o sin graduación',
                            ];
                        @endphp
                        @foreach($benefits as $benefit)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-secondary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-text/70">{{ $benefit }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         5. ¿QUIÉN DEBERÍA USARLOS?
    ============================================================= --}}
    <section class="py-20 md:py-28 bg-bg-light">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center reveal">
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-secondary mb-4">{{ $blueLightPage->profiles_label ?? 'Para ti' }}</span>
                <h2 class="font-brand text-3xl md:text-4xl font-bold text-text-dark">
                    {{ $blueLightPage->profiles_title ?? '¿Quién debería usarlos?' }}
                </h2>
                <p class="mt-4 text-text-muted max-w-2xl mx-auto">
                    {{ $blueLightPage->profiles_subtitle ?? 'Si te identificas con alguno de estos perfiles, los lentes nuvion son para ti.' }}
                </p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $profiles = $blueLightPage->profiles_cards ?? [
                        ['icon' => 'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25', 'title' => 'Oficina / Home office', 'desc' => '6+ horas diarias frente a la computadora'],
                        ['icon' => 'M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.491 48.491 0 01-4.163-.3c.186 1.613.466 3.2.838 4.752a.643.643 0 01-.057.523 3 3 0 000 3.162.644.644 0 01.057.523c-.372 1.553-.652 3.14-.838 4.753a48.627 48.627 0 014.163-.3.64.64 0 01.657.643v0c0 .355-.186.676-.401.959a1.647 1.647 0 00-.349 1.003c0 1.035 1.007 1.875 2.25 1.875s2.25-.84 2.25-1.875c0-.369-.128-.713-.349-1.003-.215-.283-.401-.604-.401-.959v0c0-.368.312-.664.657-.643a48.651 48.651 0 014.163.3c-.186-1.613-.466-3.2-.838-4.753a.644.644 0 01.057-.523 3 3 0 000-3.162.643.643 0 01-.057-.523c.372-1.553.652-3.14.838-4.753a48.558 48.558 0 01-4.163.3.64.64 0 01-.657-.643v0z', 'title' => 'Gamers', 'desc' => 'Sesiones intensas con monitores de alta luminosidad'],
                        ['icon' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5', 'title' => 'Estudiantes', 'desc' => 'Clases en línea y horas de estudio con dispositivos'],
                        ['icon' => 'M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3', 'title' => 'Usuarios de celular', 'desc' => 'Uso constante del smartphone en el día a día'],
                        ['icon' => 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42', 'title' => 'Diseñadores / Creativos', 'desc' => 'Trabajo creativo que requiere máxima precisión visual'],
                        ['icon' => 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z', 'title' => 'Uso nocturno', 'desc' => 'Si usas pantallas antes de dormir y te cuesta conciliar el sueño'],
                    ];
                @endphp

                @foreach($profiles as $i => $profile)
                    <div class="reveal delay-{{ min(($i + 1) * 150, 450) }}">
                        <div class="flex items-start gap-4 bg-white rounded-xl p-5 border border-border-light hover:border-secondary/30 hover:shadow-sm transition-all duration-300">
                            <div class="w-10 h-10 bg-primary/5 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $profile['icon'] ?? '' }}"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-brand font-bold text-text-dark">{{ $profile['title'] ?? '' }}</h3>
                                <p class="mt-1 text-sm text-text-muted leading-relaxed">{{ $profile['desc'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         6. FAQ ACCORDION
    ============================================================= --}}
    <section class="py-20 md:py-28">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center reveal">
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-secondary mb-4">{{ $blueLightPage->faq_label ?? 'Resolvemos tus dudas' }}</span>
                <h2 class="font-brand text-3xl md:text-4xl font-bold text-text-dark">
                    {{ $blueLightPage->faq_title ?? 'Preguntas frecuentes' }}
                </h2>
            </div>

            <div class="mt-12 space-y-3 reveal delay-150" x-data="{ open: null }">
                @php
                    $faqs = $blueLightPage->faqs ?? [
                        ['q' => '¿Los lentes nuvion tienen graduación?', 'a' => 'Sí, ofrecemos lentes con y sin graduación. Puedes elegir la opción que mejor se adapte a tus necesidades al momento de la compra.'],
                        ['q' => '¿Cuánta luz azul bloquean los lentes?', 'a' => 'Nuestros lentes bloquean entre el 30% y 50% de la luz azul de alta energía (380-500 nm), que es el rango dañino emitido por pantallas y luces LED.'],
                        ['q' => '¿Puedo usarlos todo el día?', 'a' => 'Por supuesto. Los lentes nuvion están diseñados para uso prolongado. Son ligeros, cómodos y no alteran significativamente la percepción del color.'],
                        ['q' => '¿Son útiles si ya uso lentes de contacto?', 'a' => 'Sí. Si usas lentes de contacto sin filtro de luz azul, nuestros lentes sin graduación te brindan una capa adicional de protección.'],
                        ['q' => '¿Los niños pueden usar lentes con filtro de luz azul?', 'a' => 'Sí, especialmente si pasan tiempo frente a pantallas para clases o entretenimiento. Consulta con un oftalmólogo para recomendaciones específicas según la edad.'],
                    ];
                @endphp

                @foreach($faqs as $i => $faq)
                    <div class="bg-white rounded-xl border border-border-light overflow-hidden transition-shadow"
                         :class="{ 'shadow-sm border-secondary/30': open === {{ $i }} }">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                                class="w-full flex items-center justify-between px-6 py-5 text-left">
                            <span class="font-brand font-semibold text-text-dark pr-4">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-secondary flex-shrink-0 transition-transform duration-300"
                                 :class="{ 'rotate-180': open === {{ $i }} }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open === {{ $i }}"
                             x-collapse
                             x-cloak>
                            <div class="px-6 pb-5 text-text-muted leading-relaxed">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================
         7. TOGGLE COMPARATIVO + MÉTRICAS DE IMPACTO
    ============================================================= --}}
    <section class="py-20 md:py-28 bg-bg overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center reveal">
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-secondary mb-4">{{ $blueLightPage->compare_label ?? 'Compara la diferencia' }}</span>
                <h2 class="font-brand text-3xl md:text-4xl font-bold text-text leading-tight">
                    {{ $blueLightPage->compare_title ?? '¿Qué pasa con tus ojos sin protección?' }}
                </h2>
            </div>

            {{-- Toggle + Panel --}}
            <div class="mt-12 max-w-xl mx-auto reveal delay-150" id="compare-toggle-wrap">

                {{-- Toggle buttons --}}
                <div class="flex rounded-xl overflow-hidden border border-white/10" role="tablist">
                    <button id="toggle-btn-off" role="tab" aria-selected="true" aria-controls="panel-off"
                            class="flex-1 py-3.5 px-4 text-sm font-bold uppercase tracking-wide text-center transition-all duration-250"
                            style="background:#1a3a6e;color:#7cb3f4;border-right:1px solid rgba(255,255,255,0.1);">
                        Sin filtro
                    </button>
                    <button id="toggle-btn-on" role="tab" aria-selected="false" aria-controls="panel-on"
                            class="flex-1 py-3.5 px-4 text-sm font-bold uppercase tracking-wide text-center transition-all duration-250"
                            style="background:transparent;color:rgba(255,255,255,0.35);">
                        Con nuvion glass
                    </button>
                </div>

                {{-- Panels container --}}
                <div class="relative mt-4" style="min-height:200px;">

                    {{-- Panel: Sin filtro --}}
                    <div id="panel-off" role="tabpanel"
                         class="rounded-xl p-6 transition-all duration-250"
                         style="background:rgba(26,58,110,0.15);border:1px solid rgba(59,130,246,0.2);opacity:1;transform:translateY(0);">
                        <ul class="space-y-4">
                            @foreach($blueLightPage->compare_without_items ?? [
                                'Fatiga visual constante',
                                'Ojos secos e irritados',
                                'Insomnio digital por supresión de melatonina',
                                'Dolores de cabeza frecuentes',
                            ] as $item)
                                <li class="flex items-center gap-3 text-sm text-text/70">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-red-500/15 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Panel: Con nuvion glass --}}
                    <div id="panel-on" role="tabpanel"
                         class="absolute inset-0 rounded-xl p-6 transition-all duration-250"
                         style="background:rgba(23,52,4,0.15);border:1px solid rgba(52,211,153,0.2);opacity:0;transform:translateY(6px);pointer-events:none;">
                        <ul class="space-y-4">
                            @foreach($blueLightPage->compare_with_items ?? [
                                'Descanso visual prolongado frente a pantallas',
                                'Ojos hidratados y cómodos todo el día',
                                'Mejor calidad y duración del sueño',
                                'Sin cefaleas relacionadas con pantallas',
                            ] as $item)
                                <li class="flex items-center gap-3 text-sm text-text/70">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-500/15 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Metrics grid --}}
            @php
                $compareMetrics = $blueLightPage->compare_metrics ?? [
                    ['number' => '66%', 'description' => 'menos parpadeos frente a pantalla', 'label' => 'sin protección', 'type' => 'red'],
                    ['number' => '3h', 'description' => 'más de sueño profundo recuperado', 'label' => 'con nuvion glass', 'type' => 'green'],
                    ['number' => '90%', 'description' => 'de usuarios con fatiga visual digital', 'label' => 'tras 2h de pantalla sin filtro', 'type' => 'red'],
                    ['number' => '40%', 'description' => 'reducción de fatiga ocular reportada', 'label' => 'con filtro de luz azul activo', 'type' => 'green'],
                ];
            @endphp
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto reveal delay-300">
                @foreach($compareMetrics as $metric)
                @if(($metric['type'] ?? 'red') === 'red')
                <div class="metric-card metric-red rounded-xl p-5 transition-all duration-300"
                     style="border:1px solid rgba(239,68,68,0.5);background:rgba(239,68,68,0.06);box-shadow:0 0 15px rgba(239,68,68,0.25),inset 0 0 15px rgba(239,68,68,0.05);">
                    <span class="block font-brand text-4xl font-bold text-red-400">{{ $metric['number'] ?? '' }}</span>
                    <p class="mt-1 text-sm text-white/70 leading-snug">{{ $metric['description'] ?? '' }}</p>
                    <span class="inline-block mt-2 text-[11px] font-semibold uppercase tracking-wider text-red-400">{{ $metric['label'] ?? '' }}</span>
                </div>
                @else
                <div class="metric-card metric-green rounded-xl p-5 transition-all duration-300"
                     style="border:1px solid rgba(52,211,153,0.1);background:transparent;box-shadow:none;">
                    <span class="block font-brand text-4xl font-bold text-emerald-400/30">{{ $metric['number'] ?? '' }}</span>
                    <p class="mt-1 text-sm text-white/20 leading-snug">{{ $metric['description'] ?? '' }}</p>
                    <span class="inline-block mt-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-400/20">{{ $metric['label'] ?? '' }}</span>
                </div>
                @endif
                @endforeach
            </div>

            {{-- Sources --}}
            <p class="mt-5 text-center text-[11px] text-text/25 max-w-2xl mx-auto reveal delay-450">
                {{ $blueLightPage->compare_sources ?? 'Fuentes: Vision Council, Harvard Health Publishing, American Journal of Ophthalmology' }}
            </p>

            {{-- CTA --}}
            <div class="mt-10 text-center reveal delay-450">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-8 py-3.5 rounded-lg font-bold transition-colors shadow-lg">
                    {{ $blueLightPage->compare_btn_text ?? 'Ver lentes nuvion glass' }}
                </a>
            </div>
        </div>
    </section>

    {{-- Toggle script (vanilla JS) --}}
    <script>
    (function() {
        var btnOff = document.getElementById('toggle-btn-off');
        var btnOn = document.getElementById('toggle-btn-on');
        var panelOff = document.getElementById('panel-off');
        var panelOn = document.getElementById('panel-on');
        if (!btnOff || !btnOn || !panelOff || !panelOn) return;

        var redCards = document.querySelectorAll('.metric-red');
        var greenCards = document.querySelectorAll('.metric-green');

        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var dur = reducedMotion ? '0ms' : '250ms';
        panelOff.style.transitionDuration = dur;
        panelOn.style.transitionDuration = dur;

        function setCards(activeColor) {
            redCards.forEach(function(c) {
                var num = c.querySelector('.font-brand');
                var desc = c.querySelector('p');
                var label = c.querySelector('span:last-child');
                if (activeColor === 'red') {
                    c.style.borderColor = 'rgba(239,68,68,0.5)';
                    c.style.background = 'rgba(239,68,68,0.06)';
                    c.style.boxShadow = '0 0 15px rgba(239,68,68,0.25), inset 0 0 15px rgba(239,68,68,0.05)';
                    if (num) num.style.color = '#f87171';
                    if (desc) desc.style.color = 'rgba(255,255,255,0.7)';
                    if (label) label.style.color = '#f87171';
                } else {
                    c.style.borderColor = 'rgba(239,68,68,0.1)';
                    c.style.background = 'transparent';
                    c.style.boxShadow = 'none';
                    if (num) num.style.color = 'rgba(248,113,113,0.3)';
                    if (desc) desc.style.color = 'rgba(255,255,255,0.2)';
                    if (label) label.style.color = 'rgba(248,113,113,0.2)';
                }
            });
            greenCards.forEach(function(c) {
                var num = c.querySelector('.font-brand');
                var desc = c.querySelector('p');
                var label = c.querySelector('span:last-child');
                if (activeColor === 'green') {
                    c.style.borderColor = 'rgba(52,211,153,0.6)';
                    c.style.background = 'rgba(16,185,129,0.1)';
                    c.style.boxShadow = '0 0 20px rgba(52,211,153,0.35), 0 0 40px rgba(52,211,153,0.15), inset 0 0 20px rgba(52,211,153,0.08)';
                    if (num) num.style.color = '#6ee7b7';
                    if (desc) desc.style.color = 'rgba(255,255,255,0.7)';
                    if (label) label.style.color = '#6ee7b7';
                } else {
                    c.style.borderColor = 'rgba(52,211,153,0.1)';
                    c.style.background = 'transparent';
                    c.style.boxShadow = 'none';
                    if (num) num.style.color = 'rgba(52,211,153,0.3)';
                    if (desc) desc.style.color = 'rgba(255,255,255,0.2)';
                    if (label) label.style.color = 'rgba(52,211,153,0.2)';
                }
            });
        }

        function activate(which) {
            if (which === 'off') {
                btnOff.style.background = '#1a3a6e';
                btnOff.style.color = '#7cb3f4';
                btnOff.setAttribute('aria-selected', 'true');
                btnOn.style.background = 'transparent';
                btnOn.style.color = 'rgba(255,255,255,0.35)';
                btnOn.setAttribute('aria-selected', 'false');

                panelOff.style.opacity = '1';
                panelOff.style.transform = 'translateY(0)';
                panelOff.style.pointerEvents = 'auto';
                panelOff.style.position = 'relative';

                panelOn.style.opacity = '0';
                panelOn.style.transform = 'translateY(6px)';
                panelOn.style.pointerEvents = 'none';
                panelOn.style.position = 'absolute';

                setCards('red');
            } else {
                btnOn.style.background = '#173404';
                btnOn.style.color = '#86efac';
                btnOn.setAttribute('aria-selected', 'true');
                btnOff.style.background = 'transparent';
                btnOff.style.color = 'rgba(255,255,255,0.35)';
                btnOff.setAttribute('aria-selected', 'false');

                panelOn.style.opacity = '1';
                panelOn.style.transform = 'translateY(0)';
                panelOn.style.pointerEvents = 'auto';
                panelOn.style.position = 'relative';

                panelOff.style.opacity = '0';
                panelOff.style.transform = 'translateY(6px)';
                panelOff.style.pointerEvents = 'none';
                panelOff.style.position = 'absolute';

                setCards('green');
            }
        }

        btnOff.addEventListener('click', function() { activate('off'); });
        btnOn.addEventListener('click', function() { activate('on'); });
    })();
    </script>

    {{-- ============================================================
         8. CTA FINAL
    ============================================================= --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary"></div>
        {{-- Pattern overlay --}}
        <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 1px 1px, white 1px, transparent 0);background-size:24px 24px;"></div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
            <h2 class="font-brand text-3xl md:text-4xl font-bold text-white leading-tight reveal">
                {{ $blueLightPage->cta_title ?? 'Protege tus ojos hoy' }}
            </h2>
            <p class="mt-4 text-lg text-white/70 max-w-xl mx-auto reveal delay-150">
                {{ $blueLightPage->cta_subtitle ?? 'Elige los lentes que cuidan tu vista sin sacrificar estilo. Con o sin graduación.' }}
            </p>
            <div class="mt-8 reveal delay-300">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center bg-white text-primary hover:bg-white/90 px-8 py-3.5 rounded-lg font-bold text-lg transition-colors shadow-lg">
                    {{ $blueLightPage->cta_btn_text ?? 'Ver nuestros lentes' }}
                </a>
            </div>
        </div>
    </section>

@endsection
