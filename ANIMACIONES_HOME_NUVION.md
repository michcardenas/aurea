✨ PLAN DE ANIMACIONES — home.blade.php (archivo real)

Basado en: resources/views/storefront/home.blade.php
Stack: CSS puro + Alpine.js | Sin librerías externas
Estilo: Sutil y elegante


⚙️ PASO 1 — Agregar a app.css (variables y keyframes)
Agrega esto al inicio de resources/css/app.css:
css/* =============================================
   ANIMACIONES — nuvion - glass
   Solo transform + opacity (GPU, sin layout)
   ============================================= */

/* Keyframes base */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(28px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeInLeft {
  from { opacity: 0; transform: translateX(-28px); }
  to   { opacity: 1; transform: translateX(0); }
}
@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.93); }
  to   { opacity: 1; transform: scale(1); }
}
@keyframes floatY {
  0%, 100% { transform: translateY(0px); }
  50%       { transform: translateY(-10px); }
}
@keyframes gradientShift {
  0%   { background-position: 0% 50%; }
  50%  { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
@keyframes shimmer {
  from { left: -100%; }
  to   { left: 100%; }
}
@keyframes pulseDot {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.6; transform: scale(0.92); }
}

/* Clases utilitarias de animación */
.anim-fade-up    { animation: fadeInUp    0.6s cubic-bezier(0.4,0,0.2,1) both; }
.anim-fade-right { animation: fadeInRight 0.6s cubic-bezier(0.4,0,0.2,1) both; }
.anim-fade-left  { animation: fadeInLeft  0.6s cubic-bezier(0.4,0,0.2,1) both; }
.anim-scale-in   { animation: scaleIn     0.5s cubic-bezier(0.34,1.56,0.64,1) both; }
.anim-float      { animation: floatY      4s   cubic-bezier(0.4,0,0.2,1) infinite; }

/* Delays escalonados */
.delay-100 { animation-delay: 100ms; }
.delay-200 { animation-delay: 200ms; }
.delay-300 { animation-delay: 300ms; }
.delay-400 { animation-delay: 400ms; }
.delay-500 { animation-delay: 500ms; }
.delay-600 { animation-delay: 600ms; }

/* Scroll reveal — estado invisible por defecto */
.reveal {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s cubic-bezier(0.4,0,0.2,1),
              transform 0.6s cubic-bezier(0.4,0,0.2,1);
}
.reveal.visible {
  opacity: 1;
  transform: translateY(0);
}
.reveal.delay-150 { transition-delay: 150ms; }
.reveal.delay-300 { transition-delay: 300ms; }
.reveal.delay-450 { transition-delay: 450ms; }

/* Respetar preferencias del usuario */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
  }
}

📋 PASO 2 — Agregar directiva Alpine x-reveal en app.blade.php
Agrega este script antes de </body> en resources/views/layouts/app.blade.php:
html<script>
document.addEventListener('alpine:init', () => {
    // Directiva x-reveal: activa clase 'visible' al entrar al viewport
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
</script>

🎬 PASO 3 — Cambios en home.blade.php (sección por sección)

SECCIÓN 1 — HERO
Qué animar: eyebrow → h1 → párrafo → botones → trust badges → imagen (entra desde derecha + flota)
Cambios en el <section> del hero:
blade{{-- ANTES --}}
<section class="relative bg-bg overflow-hidden">

{{-- DESPUÉS: agregar gradiente animado en fondo --}}
<section class="relative bg-bg overflow-hidden"
         style="background: linear-gradient(135deg, #0A0E1A 0%, #001a40 50%, #0A0E1A 100%);
                background-size: 200% 200%;
                animation: gradientShift 10s ease infinite;">
Cambios en el glow decorativo (ya existe, solo ajustar):
blade{{-- ANTES --}}
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-secondary/5 rounded-full blur-[120px] pointer-events-none"></div>

{{-- DESPUÉS: añadir animación de pulso al glow --}}
<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-secondary/5 rounded-full blur-[120px] pointer-events-none"
     style="animation: pulseDot 6s ease-in-out infinite;"></div>
Cambios en el copy del hero (entrada escalonada):
blade{{-- Eyebrow --}}
{{-- ANTES --}}
<span class="inline-block text-secondary text-sm font-medium tracking-wider uppercase mb-4">

{{-- DESPUÉS --}}
<span class="inline-block text-secondary text-sm font-medium tracking-wider uppercase mb-4 anim-fade-up delay-100">

{{-- H1 --}}
{{-- ANTES --}}
<h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.1]">

{{-- DESPUÉS --}}
<h1 class="font-brand text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.1] anim-fade-up delay-200">

{{-- Párrafo --}}
{{-- ANTES --}}
<p class="mt-6 text-base sm:text-lg text-muted/80 leading-relaxed max-w-lg">

{{-- DESPUÉS --}}
<p class="mt-6 text-base sm:text-lg text-muted/80 leading-relaxed max-w-lg anim-fade-up delay-300">

{{-- Div de botones --}}
{{-- ANTES --}}
<div class="mt-8 flex flex-col sm:flex-row gap-4">

{{-- DESPUÉS --}}
<div class="mt-8 flex flex-col sm:flex-row gap-4 anim-fade-up delay-400">

{{-- Trust badges --}}
{{-- ANTES --}}
<div class="mt-10 flex flex-wrap items-center gap-6 text-sm text-muted/50">

{{-- DESPUÉS --}}
<div class="mt-10 flex flex-wrap items-center gap-6 text-sm text-muted/50 anim-fade-up delay-500">
Cambios en el botón CTA primario (hover con elevación + shimmer):
blade{{-- ANTES --}}
<a href="{{ route('products.index') }}"
   class="inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-8 py-3.5 rounded-xl font-semibold text-base transition-colors shadow-lg shadow-secondary/25">

{{-- DESPUÉS --}}
<a href="{{ route('products.index') }}"
   class="relative inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-8 py-3.5 rounded-xl font-semibold text-base transition-all duration-300 shadow-lg shadow-secondary/25 overflow-hidden
          hover:-translate-y-0.5 hover:shadow-xl hover:shadow-secondary/30 active:translate-y-0">
Imagen hero (entrada desde derecha + flotación):
blade{{-- ANTES --}}
<div class="order-1 lg:order-2 flex justify-center">
    <div class="relative w-full max-w-md lg:max-w-lg aspect-square rounded-3xl bg-gradient-to-br from-secondary/10 to-primary/10 border border-white/5 flex items-center justify-center">

{{-- DESPUÉS --}}
<div class="order-1 lg:order-2 flex justify-center anim-fade-right delay-300">
    <div class="relative w-full max-w-md lg:max-w-lg aspect-square rounded-3xl bg-gradient-to-br from-secondary/10 to-primary/10 border border-white/5 flex items-center justify-center anim-float">
Ring decorativo (ya existe, solo añadir animación):
blade{{-- ANTES --}}
<div class="absolute -inset-4 rounded-full border border-secondary/10 pointer-events-none"></div>

{{-- DESPUÉS --}}
<div class="absolute -inset-4 rounded-full border border-secondary/10 pointer-events-none"
     style="animation: pulseDot 4s ease-in-out infinite;"></div>

SECCIÓN 2 — EDUCACIÓN "¿Qué es la luz azul?"
Qué animar: ilustración entra desde izquierda, copy entra desde derecha al hacer scroll
blade{{-- Wrapper del grid — agregar x-reveal al padre --}}
{{-- ANTES --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

{{-- DESPUÉS --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

    {{-- Ilustración — fade desde izquierda --}}
    {{-- ANTES --}}
    <div class="relative">
        <div class="aspect-video rounded-2xl bg-white ...">

    {{-- DESPUÉS: agregar reveal al wrapper --}}
    <div class="relative"
         x-data x-reveal.once
         style="--reveal-transform: translateX(-20px)">
        <div class="aspect-video rounded-2xl bg-white ...">

    {{-- Copy — fade desde derecha --}}
    {{-- ANTES --}}
    <div>
        <span class="inline-block text-secondary ...">

    {{-- DESPUÉS --}}
    <div x-data x-reveal.once
         style="--reveal-transform: translateX(20px)">
        <span class="inline-block text-secondary ...">
Síntomas — cada <li> entra escalonado:
blade{{-- ANTES --}}
@foreach($symptoms as $symptom)
<li class="flex items-center gap-3 text-text-muted">

{{-- DESPUÉS --}}
@foreach($symptoms as $index => $symptom)
<li class="flex items-center gap-3 text-text-muted reveal"
    style="transition-delay: {{ $index * 100 }}ms">

⚠️ Para que los <li> con reveal funcionen sin Alpine, agregar este JS en app.blade.php:

js// Activar .reveal en elementos estáticos con IntersectionObserver puro
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.reveal');
    const obs = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }),
        { threshold: 0.1, rootMargin: '0px 0px -30px 0px' }
    );
    items.forEach(el => obs.observe(el));
});

SECCIÓN 3 — PRODUCTOS DESTACADOS
Qué animar: título con fadeInUp, cards con entrada escalonada + hover con elevación e imagen con zoom
Título de sección:
blade{{-- ANTES --}}
<div class="text-center max-w-2xl mx-auto">

{{-- DESPUÉS --}}
<div class="text-center max-w-2xl mx-auto" x-data x-reveal.once>
Cada card de producto:
blade{{-- ANTES --}}
<div class="group bg-bg-light rounded-2xl overflow-hidden border border-border-light hover:shadow-lg hover:shadow-secondary/5 transition-all duration-300">

{{-- DESPUÉS --}}
<div class="reveal group bg-bg-light rounded-2xl overflow-hidden border border-border-light
            hover:shadow-xl hover:shadow-secondary/10 hover:-translate-y-1.5 hover:border-secondary/30
            transition-all duration-300"
     style="transition-delay: {{ $index * 150 }}ms">
Imagen del producto (zoom en hover):
blade{{-- ANTES --}}
<div class="relative aspect-[4/3] bg-white flex items-center justify-center overflow-hidden">
    <span class="text-text-muted/30 text-sm">Imagen producto {{ $index + 1 }}</span>

{{-- DESPUÉS — agregar overflow-hidden al wrapper ya existe, solo añadir transform a la imagen --}}
<div class="relative aspect-[4/3] bg-white flex items-center justify-center overflow-hidden">
    {{-- Cuando uses imágenes reales, añadir: class="transition-transform duration-500 group-hover:scale-105" --}}
    <span class="text-text-muted/30 text-sm transition-transform duration-500 group-hover:scale-105">
        Imagen producto {{ $index + 1 }}
    </span>
Botón "Ver detalle" con hover:
blade{{-- ANTES --}}
<a href="{{ route('products.index') }}"
   class="bg-primary hover:bg-primary-light text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">

{{-- DESPUÉS --}}
<a href="{{ route('products.index') }}"
   class="bg-primary hover:bg-primary-light text-white px-5 py-2.5 rounded-xl text-sm font-semibold
          transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:shadow-primary/20 active:translate-y-0">

SECCIÓN 4 — BENEFICIOS
Qué animar: título reveal, cada card entra escalonado + hover con elevación del ícono
Título:
blade{{-- ANTES --}}
<div class="text-center max-w-2xl mx-auto">

{{-- DESPUÉS --}}
<div class="text-center max-w-2xl mx-auto" x-data x-reveal.once>
Cada card de beneficio:
blade{{-- ANTES --}}
<div class="bg-white rounded-2xl p-6 md:p-8 text-center border border-border-light hover:shadow-md transition-shadow">

{{-- DESPUÉS --}}
<div class="reveal bg-white rounded-2xl p-6 md:p-8 text-center border border-border-light
            hover:shadow-lg hover:shadow-secondary/5 hover:-translate-y-1 transition-all duration-300 group"
     style="transition-delay: {{ array_search($benefit, $benefits) * 100 }}ms">
Ícono de cada beneficio (bounce en hover):
blade{{-- ANTES --}}
<div class="w-14 h-14 mx-auto bg-secondary/10 rounded-2xl flex items-center justify-center">
    <svg class="w-7 h-7 text-secondary" ...>

{{-- DESPUÉS --}}
<div class="w-14 h-14 mx-auto bg-secondary/10 rounded-2xl flex items-center justify-center
            transition-transform duration-300 group-hover:scale-110 group-hover:bg-secondary/20">
    <svg class="w-7 h-7 text-secondary transition-transform duration-300 group-hover:-translate-y-0.5" ...>

SECCIÓN 5 — COMPARATIVO
Qué animar: columna izquierda entra desde izquierda, derecha desde derecha
blade{{-- Wrapper del grid --}}
{{-- ANTES --}}
<div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-4xl mx-auto">

{{-- DESPUÉS --}}
<div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-4xl mx-auto">

    {{-- Card izquierda (Sin protección) — añadir reveal --}}
    {{-- ANTES --}}
    <div class="rounded-2xl border-2 border-danger/20 bg-danger/5 p-6 md:p-8">

    {{-- DESPUÉS --}}
    <div class="reveal rounded-2xl border-2 border-danger/20 bg-danger/5 p-6 md:p-8">

    {{-- Card derecha (Con nuvion) — reveal con delay --}}
    {{-- ANTES --}}
    <div class="rounded-2xl border-2 border-success/20 bg-success/5 p-6 md:p-8">

    {{-- DESPUÉS --}}
    <div class="reveal delay-150 rounded-2xl border-2 border-success/20 bg-success/5 p-6 md:p-8">
Cada <li> de ambas listas entra escalonado:
blade{{-- Sin protección --}}
@foreach($noProtection as $index => $item)
<li class="reveal flex items-start gap-3" style="transition-delay: {{ 100 + $index * 80 }}ms">

{{-- Con nuvion --}}
@foreach($withProtection as $index => $item)
<li class="reveal flex items-start gap-3" style="transition-delay: {{ 150 + $index * 80 }}ms">

SECCIÓN 6 — TESTIMONIOS
Qué animar: título reveal, cards con entrada escalonada + hover con elevación + estrellitas escalonadas
Título:
blade{{-- ANTES --}}
<div class="text-center max-w-2xl mx-auto">

{{-- DESPUÉS --}}
<div class="text-center max-w-2xl mx-auto" x-data x-reveal.once>
Cada card de testimonio:
blade{{-- ANTES --}}
<div class="bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm">
    {{-- Stars --}}
    <div class="flex items-center gap-1">
        @for($i = 0; $i < 5; $i++)
        <svg class="w-5 h-5 text-warning" ...>

{{-- DESPUÉS --}}
<div class="reveal bg-white rounded-2xl p-6 md:p-8 border border-border-light shadow-sm
            hover:shadow-lg hover:shadow-secondary/5 hover:-translate-y-1 transition-all duration-300"
     style="transition-delay: {{ $loop->index * 150 }}ms">
    {{-- Stars escalonadas --}}
    <div class="flex items-center gap-1">
        @for($i = 0; $i < 5; $i++)
        <svg class="w-5 h-5 text-warning anim-scale-in"
             style="animation-delay: {{ 300 + ($i * 80) }}ms"
             ...>

SECCIÓN 7 — FAQ ACCORDION
El accordion ya tiene Alpine.js. Solo mejorar la transición y agregar reveal al wrapper:
blade{{-- ANTES --}}
<div class="mt-12 max-w-3xl mx-auto space-y-3" x-data="{ openFaq: null }">

{{-- DESPUÉS —  agregar reveal al contenedor y el texto del FAQ title --}}
<div class="mt-12 max-w-3xl mx-auto space-y-3 reveal visible" x-data="{ openFaq: null }">

Nota: se pone visible directamente porque ya está en viewport sin scroll.

Mejorar hover del botón trigger:
blade{{-- ANTES --}}
<button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
        class="w-full flex items-center justify-between px-5 md:px-6 py-4 text-left hover:bg-white transition-colors">

{{-- DESPUÉS --}}
<button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
        class="w-full flex items-center justify-between px-5 md:px-6 py-4 text-left
               hover:bg-white transition-all duration-200 group"
        :class="openFaq === {{ $index }} ? 'bg-white' : ''">

    {{-- Cambiar color del texto al abrir --}}
    <span class="font-semibold text-text-dark pr-4 transition-colors duration-200"
          :class="openFaq === {{ $index }} ? 'text-secondary' : ''">
        {{ $faq['q'] }}
    </span>

SECCIÓN 8 — CTA FINAL
Qué animar: glow animado, título reveal, botón principal con shimmer en hover
Glow decorativo (ya existe):
blade{{-- ANTES --}}
<div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-[100px] pointer-events-none"></div>

{{-- DESPUÉS --}}
<div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-[100px] pointer-events-none"
     style="animation: pulseDot 5s ease-in-out infinite;"></div>

{{-- Agregar segundo glow en el lado opuesto --}}
<div class="absolute bottom-0 left-0 w-64 h-64 bg-primary/20 rounded-full blur-[80px] pointer-events-none"
     style="animation: pulseDot 7s ease-in-out 1s infinite;"></div>
Título y párrafo con reveal:
blade{{-- ANTES --}}
<h2 class="font-brand text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight">
<p class="mt-5 text-lg text-white/80 max-w-xl mx-auto">

{{-- DESPUÉS --}}
<h2 class="font-brand text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight reveal visible anim-fade-up">
<p class="mt-5 text-lg text-white/80 max-w-xl mx-auto reveal visible anim-fade-up delay-200">
Botón "Comprar ahora" con shimmer:
blade{{-- ANTES --}}
<a href="{{ route('products.index') }}"
   class="inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-colors shadow-lg shadow-secondary/25">

{{-- DESPUÉS --}}
<a href="{{ route('products.index') }}"
   class="relative inline-flex items-center justify-center bg-secondary hover:bg-secondary/90 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300
          shadow-lg shadow-secondary/25 hover:shadow-xl hover:shadow-secondary/40 hover:-translate-y-0.5
          overflow-hidden group active:translate-y-0">
    {{-- Efecto shimmer --}}
    <span class="absolute inset-0 w-full h-full"
          style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
                 transform: translateX(-100%);
                 transition: transform 500ms ease;">
    </span>
    {{-- El span de shimmer se activa con CSS en hover --}}
    Comprar ahora
    <svg ...>
</a>
Agregar a app.css para el shimmer:
css/* Shimmer del botón CTA */
.btn-shimmer:hover span:first-child {
  transform: translateX(100%);
}
/* O como clase directa: */
a.group:hover .shimmer-inner {
  transform: translateX(100%) !important;
}