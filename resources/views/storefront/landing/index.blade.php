@extends('layouts.app')

@section('body_class', 'bg-bg text-text')

@section('title', 'Lentes anti luz azul — Envío gratis | Nuvion Glass')
@section('meta_description', 'Protege tus ojos de las pantallas. Lentes con filtro de luz azul, con o sin graduación. Envío gratis en pedidos mayores a $99.')
@section('og_title', 'Lentes anti luz azul — Envío gratis | Nuvion Glass')
@section('og_description', 'Protege tus ojos de las pantallas. Lentes con filtro de luz azul. Envío gratis.')

@section('content')

    {{-- Hero -- full dark --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-bg via-primary/10 to-bg pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-secondary/5 rounded-full blur-[150px] pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-20 md:py-32 text-center">
            <span class="inline-block text-secondary text-sm font-medium tracking-wider uppercase mb-4">Protección para tus ojos</span>
            <h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.1]">
                Tus ojos merecen<br>
                <span class="text-secondary">mejor protección</span>
            </h1>
            <p class="mt-6 text-lg text-muted/80 max-w-2xl mx-auto leading-relaxed">
                Pasas más de 6 horas al día frente a pantallas. La luz azul causa fatiga visual, dolores de cabeza y problemas para dormir.
                Los lentes nuvion filtran la luz dañina para que veas mejor, duermas mejor y rindas más.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors shadow-lg shadow-secondary/25">
                    Ver lentes — Envío gratis
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
                <a href="{{ route('landing.quiz') }}"
                   class="inline-flex items-center justify-center border border-white/20 text-white/90 hover:bg-white/5 hover:border-white/40 px-8 py-4 rounded-xl font-medium text-lg transition-colors">
                    ¿Qué lentes necesito?
                </a>
            </div>
        </div>
    </section>

    {{-- Pain points --}}
    <section class="py-16 md:py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <h2 class="font-brand text-2xl md:text-3xl font-bold text-center mb-12">¿Te identificas con esto?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-surface rounded-2xl p-6 border border-border">
                    <div class="w-12 h-12 bg-danger/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Ojos cansados</h3>
                    <p class="text-muted/70 text-sm leading-relaxed">Terminas el día con los ojos rojos, secos o con sensación de pesadez después de horas frente a la pantalla.</p>
                </div>
                <div class="bg-surface rounded-2xl p-6 border border-border">
                    <div class="w-12 h-12 bg-warning/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Dolores de cabeza</h3>
                    <p class="text-muted/70 text-sm leading-relaxed">Dolores frecuentes que aparecen después de largas sesiones de trabajo, estudio o gaming.</p>
                </div>
                <div class="bg-surface rounded-2xl p-6 border border-border">
                    <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Mal sueño</h3>
                    <p class="text-muted/70 text-sm leading-relaxed">Te cuesta dormirte o no descansas bien porque la luz azul altera tu ritmo circadiano.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Solution / Products --}}
    <section class="py-16 md:py-24 bg-surface/50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <h2 class="font-brand text-2xl md:text-3xl font-bold">La solución es simple</h2>
                <p class="mt-4 text-muted/70 max-w-xl mx-auto">Lentes con filtro de luz azul que protegen tus ojos sin sacrificar estilo. Con o sin graduación.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="group bg-surface rounded-2xl border border-border overflow-hidden hover:border-secondary/50 transition-colors">
                        <div class="aspect-[4/3] bg-bg flex items-center justify-center">
                            @if($product->images && is_array($product->images) && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <span class="text-muted/30 text-sm">{{ $product->name }}</span>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold group-hover:text-secondary transition-colors">{{ $product->name }}</h3>
                            <p class="text-sm text-muted/60 mt-1">{{ $product->category->name ?? '' }}</p>
                            <div class="mt-3 flex items-baseline gap-2">
                                <span class="text-xl font-bold text-secondary">${{ number_format($product->price, 2) }}</span>
                                @if($product->compare_price)
                                    <span class="text-sm text-muted/40 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('products.index') }}" class="inline-flex items-center text-secondary hover:text-secondary/80 font-medium transition-colors">
                    Ver todo el catálogo
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Benefits strip --}}
    <section class="py-12 border-y border-border">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-secondary">30-50%</div>
                    <p class="mt-1 text-sm text-muted/60">Luz azul filtrada</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-secondary">UV400</div>
                    <p class="mt-1 text-sm text-muted/60">Protección UV</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-secondary">6 meses</div>
                    <p class="mt-1 text-sm text-muted/60">Garantía</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-secondary">Gratis</div>
                    <p class="mt-1 text-sm text-muted/60">Envío +$99</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Lead capture CTA --}}
    <section class="py-16 md:py-24">
        <div class="max-w-xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="font-brand text-2xl md:text-3xl font-bold">¿No estás seguro? Haz el quiz</h2>
            <p class="mt-4 text-muted/70">Responde 4 preguntas rápidas y te diremos qué lentes son perfectos para ti.</p>
            <a href="{{ route('landing.quiz') }}"
               class="mt-8 inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors shadow-lg shadow-secondary/25">
                Iniciar quiz — 1 minuto
            </a>

            <div class="mt-12 relative">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-border"></div></div>
                <div class="relative flex justify-center"><span class="bg-bg px-4 text-sm text-muted/40">o recibe ofertas por email</span></div>
            </div>

            <form method="POST" action="{{ route('leads.store') }}" class="mt-6 flex gap-2 max-w-md mx-auto">
                @csrf
                <input type="hidden" name="source" value="landing">
                <input type="email" name="email" placeholder="Tu email" required
                       class="flex-1 bg-surface border border-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent placeholder-muted/40">
                <button type="submit" class="bg-secondary hover:bg-secondary/90 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors whitespace-nowrap">
                    Suscribirme
                </button>
            </form>
        </div>
    </section>

@endsection
