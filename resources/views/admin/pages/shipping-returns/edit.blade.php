@extends('layouts.admin')

@section('title', 'Editar envíos y devoluciones')
@section('page_title', 'Envíos y devoluciones')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="{
        openSection: 'hero',
        toggle(s) { this.openSection = this.openSection === s ? null : s; }
     }">

    <p class="text-sm text-gray-500 mb-6">Edita el contenido de la página de envíos y devoluciones. Puedes usar &lt;strong&gt; para negritas y &lt;br&gt; para saltos de línea.</p>

    <form method="POST" action="{{ route('admin.pages.shipping-returns.update') }}">
        @method('PUT')
        @csrf

        {{-- ═══════════ HERO ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('hero')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">Hero</h3>
                <svg :class="openSection === 'hero' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'hero'" x-collapse class="px-6 pb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="hero_title" value="{{ $page->hero_title }}"
                           placeholder="Envíos y devoluciones"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <textarea name="hero_subtitle" rows="2"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Todo lo que necesitas saber sobre nuestros tiempos de entrega y políticas de devolución.">{{ $page->hero_subtitle }}</textarea>
                </div>
            </div>
        </div>

        {{-- ═══════════ ENVÍOS ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('shipping')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">Envíos</h3>
                <svg :class="openSection === 'shipping' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'shipping'" x-collapse class="px-6 pb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título de la sección</label>
                    <input type="text" name="shipping_title" value="{{ $page->shipping_title }}"
                           placeholder="Política de envíos"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                    <textarea name="shipping_content" rows="8"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Escribe aquí toda la información sobre envíos...">{{ $page->shipping_content }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Puedes usar &lt;strong&gt; para negritas, &lt;br&gt; para saltos de línea, y &lt;ul&gt;&lt;li&gt; para listas.</p>
                </div>
            </div>
        </div>

        {{-- ═══════════ DEVOLUCIONES ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('returns')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">Devoluciones</h3>
                <svg :class="openSection === 'returns' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'returns'" x-collapse class="px-6 pb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título de la sección</label>
                    <input type="text" name="returns_title" value="{{ $page->returns_title }}"
                           placeholder="Política de devoluciones"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                    <textarea name="returns_content" rows="8"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Escribe aquí toda la información sobre devoluciones...">{{ $page->returns_content }}</textarea>
                </div>
            </div>
        </div>

        {{-- ═══════════ GARANTÍA ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('warranty')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">Garantía</h3>
                <svg :class="openSection === 'warranty' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'warranty'" x-collapse class="px-6 pb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título de la sección</label>
                    <input type="text" name="warranty_title" value="{{ $page->warranty_title }}"
                           placeholder="Garantía"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                    <textarea name="warranty_content" rows="6"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Escribe aquí toda la información sobre garantía...">{{ $page->warranty_content }}</textarea>
                </div>
            </div>
        </div>

        {{-- Botón guardar --}}
        <button type="submit"
                class="w-full py-3 rounded-xl text-white font-medium text-base transition-colors"
                style="background:#378ADD;"
                onmouseover="this.style.background='#185FA5'"
                onmouseout="this.style.background='#378ADD'">
            Guardar cambios
        </button>
    </form>
</div>
@endsection
