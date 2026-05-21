@extends('layouts.app')

@section('title', $seoSettings->meta_title ?? 'Envíos y devoluciones | Nuvion Glass')
@section('meta_description', $seoSettings->meta_description ?? 'Información sobre envíos, devoluciones y garantía de Nuvion Glass. Envío gratis en compras mayores a $999.')

@section('content')

    {{-- Hero --}}
    <section class="relative bg-bg overflow-hidden">
        <div class="absolute inset-0 opacity-30" style="background:radial-gradient(ellipse at 50% 30%, #3A8DDE 0%, transparent 65%);"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 text-center">
            <nav class="mb-8 text-sm text-white/60">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
                <span class="mx-2">/</span>
                <span class="text-white/90">Envíos y devoluciones</span>
            </nav>
            <h1 class="font-brand text-4xl md:text-5xl font-bold text-white drop-shadow-lg">
                {{ $page->hero_title ?? 'Envíos y devoluciones' }}
            </h1>
            <p class="mt-5 text-lg text-white/80 max-w-2xl mx-auto">
                {{ $page->hero_subtitle ?? 'Todo lo que necesitas saber sobre nuestros tiempos de entrega y políticas de devolución.' }}
            </p>
            <div style="width:48px;height:3px;background:#3A8DDE;border-radius:2px;margin:24px auto 0;box-shadow:0 0 12px rgba(58,141,222,0.6);"></div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-16 md:py-24 bg-bg-light">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            {{-- Envíos --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                        </svg>
                    </div>
                    <h2 class="font-brand text-xl font-bold text-text-dark">
                        {{ $page->shipping_title ?? 'Política de envíos' }}
                    </h2>
                </div>
                <div class="prose prose-sm text-text-muted leading-relaxed max-w-none">
                    {!! $page->shipping_content ?? '<p>Realizamos envíos a toda la República Mexicana.</p>
                    <ul>
                        <li><strong>Envío gratis</strong> en compras mayores a $999 MXN.</li>
                        <li><strong>Costo de envío estándar:</strong> $99 MXN.</li>
                        <li><strong>Tiempo de entrega:</strong> 3 a 7 días hábiles dependiendo de tu ubicación.</li>
                        <li>Recibirás un correo con tu número de guía para rastrear tu paquete.</li>
                    </ul>' !!}
                </div>
            </div>

            {{-- Devoluciones --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>
                        </svg>
                    </div>
                    <h2 class="font-brand text-xl font-bold text-text-dark">
                        {{ $page->returns_title ?? 'Política de devoluciones' }}
                    </h2>
                </div>
                <div class="prose prose-sm text-text-muted leading-relaxed max-w-none">
                    {!! $page->returns_content ?? '<p>Tienes <strong>30 días</strong> a partir de la recepción de tu pedido para solicitar una devolución.</p>
                    <ul>
                        <li>El producto debe estar en su empaque original y sin uso.</li>
                        <li>Envía un correo a contacto@nuvionglass.com.mx con tu número de pedido.</li>
                        <li>Te enviaremos una guía de devolución sin costo.</li>
                        <li>El reembolso se procesa en 5 a 10 días hábiles después de recibir el producto.</li>
                    </ul>' !!}
                </div>
            </div>

            {{-- Garantía --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                        </svg>
                    </div>
                    <h2 class="font-brand text-xl font-bold text-text-dark">
                        {{ $page->warranty_title ?? 'Garantía' }}
                    </h2>
                </div>
                <div class="prose prose-sm text-text-muted leading-relaxed max-w-none">
                    {!! $page->warranty_content ?? '<p>Todos nuestros lentes cuentan con <strong>6 meses de garantía</strong> contra defectos de fabricación.</p>
                    <ul>
                        <li>Cubre defectos en el armazón, bisagras y lentes.</li>
                        <li>No cubre daños por mal uso, caídas o rayones.</li>
                        <li>Para hacer válida tu garantía, envía un correo con fotos del daño y tu número de pedido.</li>
                    </ul>' !!}
                </div>
            </div>

        </div>
    </section>

    {{-- CTA --}}
    <section class="py-12 bg-bg">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-brand text-2xl font-bold text-text">¿Tienes más dudas?</h2>
            <p class="mt-3 text-text/50">Estamos para ayudarte.</p>
            <a href="{{ route('contact') }}" class="inline-block mt-6 px-8 py-3 rounded-lg font-semibold transition-colors bg-secondary text-white hover:bg-secondary/90">
                Contáctanos
            </a>
        </div>
    </section>

@endsection
