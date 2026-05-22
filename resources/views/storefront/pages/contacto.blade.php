@extends('layouts.app')

@section('title', $seoSettings->meta_title ?? 'Contacto | Belleza Áurea')
@section('meta_description', $seoSettings->meta_description ?? 'Contáctanos para cualquier duda sobre nuestros lentes con filtro de luz azul. Email, WhatsApp y redes sociales.')

@section('content')

    {{-- Hero --}}
    <section class="relative bg-bg overflow-hidden">
        <div class="absolute inset-0 opacity-30" style="background:radial-gradient(ellipse at 50% 30%, #3A8DDE 0%, transparent 65%);"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 text-center">
            <nav class="mb-8 text-sm text-white/60">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Inicio</a>
                <span class="mx-2">/</span>
                <span class="text-white/90">Contacto</span>
            </nav>
            <h1 class="font-brand text-4xl md:text-5xl font-bold text-white drop-shadow-lg">
                {{ $contactPage->hero_title ?? 'Contáctanos' }}
            </h1>
            <p class="mt-5 text-lg text-white/80 max-w-2xl mx-auto">
                {{ $contactPage->hero_subtitle ?? '¿Tienes dudas? Estamos aquí para ayudarte.' }}
            </p>
            <div style="width:48px;height:3px;background:#3A8DDE;border-radius:2px;margin:24px auto 0;box-shadow:0 0 12px rgba(58,141,222,0.6);"></div>
        </div>
    </section>

    {{-- Contact cards --}}
    <section class="py-16 md:py-24 bg-bg-light">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-6">

                {{-- Email --}}
                @if($contactPage->email)
                <a href="mailto:{{ $contactPage->email }}" class="w-[240px] sm:w-[220px] h-[210px] flex flex-col items-center justify-center bg-white rounded-2xl p-5 border border-border-light shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 bg-secondary/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <h3 class="font-brand font-bold text-text-dark mb-1">Email</h3>
                    <p class="text-sm text-secondary group-hover:underline w-full truncate">{{ $contactPage->email }}</p>
                </a>
                @endif

                {{-- WhatsApp --}}
                @if($contactPage->whatsapp)
                <a href="{{ $contactPage->whatsapp }}" target="_blank" class="w-[240px] sm:w-[220px] h-[210px] flex flex-col items-center justify-center bg-white rounded-2xl p-5 border border-border-light shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                        </svg>
                    </div>
                    <h3 class="font-brand font-bold text-text-dark mb-1">WhatsApp</h3>
                    <p class="text-sm text-green-500 group-hover:underline">Enviar mensaje</p>
                </a>
                @endif

                {{-- Phone --}}
                @if($contactPage->phone)
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPage->phone) }}" class="w-[240px] sm:w-[220px] h-[210px] flex flex-col items-center justify-center bg-white rounded-2xl p-5 border border-border-light shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 text-center group">
                    <div class="w-14 h-14 bg-secondary/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                    </div>
                    <h3 class="font-brand font-bold text-text-dark mb-1">Teléfono</h3>
                    <p class="text-sm text-secondary group-hover:underline">{{ $contactPage->phone }}</p>
                </a>
                @endif
            </div>

            {{-- Schedule --}}
            @if($contactPage->schedule)
            <div class="mt-8 text-center">
                <p class="text-sm text-text-muted">
                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    {{ $contactPage->schedule }}
                </p>
            </div>
            @endif

            {{-- Social media --}}
            @if($contactPage->instagram_url || $contactPage->facebook_url || $contactPage->tiktok_url)
            <div class="mt-12 text-center">
                <p class="text-xs font-bold uppercase tracking-widest text-text-muted mb-4">Síguenos</p>
                <div class="flex items-center justify-center gap-4">
                    @if($contactPage->instagram_url)
                    <a href="{{ $contactPage->instagram_url }}" target="_blank"
                       class="w-12 h-12 bg-white rounded-xl border border-border-light flex items-center justify-center text-text-muted hover:text-secondary hover:border-secondary/30 hover:shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    @endif
                    @if($contactPage->facebook_url)
                    <a href="{{ $contactPage->facebook_url }}" target="_blank"
                       class="w-12 h-12 bg-white rounded-xl border border-border-light flex items-center justify-center text-text-muted hover:text-secondary hover:border-secondary/30 hover:shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($contactPage->tiktok_url)
                    <a href="{{ $contactPage->tiktok_url }}" target="_blank"
                       class="w-12 h-12 bg-white rounded-xl border border-border-light flex items-center justify-center text-text-muted hover:text-secondary hover:border-secondary/30 hover:shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>

@endsection
