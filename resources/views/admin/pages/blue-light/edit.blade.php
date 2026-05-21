@extends('layouts.admin')

@section('title', 'Editar página de luz azul')
@section('page_title', 'Página — ¿Qué es la luz azul?')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="{
        openSection: 'hero',
        toggle(s) { this.openSection = this.openSection === s ? null : s; }
     }">

    <p class="text-sm text-gray-500 mb-6">Edita todo el texto de la página «¿Qué es la luz azul?». Los cambios se reflejan inmediatamente en la tienda.</p>

    <form method="POST" action="{{ route('admin.pages.blue-light.update') }}">
        @method('PUT')
        @csrf

        {{-- ═══════════ 1. HERO ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('hero')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">1. Hero</h3>
                <svg :class="openSection === 'hero' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'hero'" x-collapse class="px-6 pb-6 space-y-4">
                <p class="text-xs text-gray-400">El título se compone de 3 partes: <strong>prefijo</strong> + <strong>palabra destacada</strong> (en degradado) + <strong>sufijo</strong>.</p>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Prefijo del título</label>
                        <input type="text" name="hero_title_prefix" value="{{ $page->hero_title_prefix }}"
                               placeholder="¿Qué es la "
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Palabra destacada</label>
                        <input type="text" name="hero_title_accent" value="{{ $page->hero_title_accent }}"
                               placeholder="luz azul"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sufijo del título</label>
                        <input type="text" name="hero_title_suffix" value="{{ $page->hero_title_suffix }}"
                               placeholder="?"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <textarea name="hero_subtitle" rows="2"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Todo lo que necesitas saber sobre la luz que emiten tus pantallas...">{{ $page->hero_subtitle }}</textarea>
                </div>
            </div>
        </div>

        {{-- ═══════════ 2. CIENCIA VISUAL ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('science')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">2. Ciencia visual</h3>
                <svg :class="openSection === 'science' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'science'" x-collapse class="px-6 pb-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                        <input type="text" name="science_label" value="{{ $page->science_label }}"
                               placeholder="Ciencia visual"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input type="text" name="science_title" value="{{ $page->science_title }}"
                               placeholder="La luz que no ves, pero tus ojos sí sienten"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Párrafo 1</label>
                    <textarea name="science_paragraph1" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="La luz azul es una porción del espectro visible...">{{ $page->science_paragraph1 }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Puedes usar &lt;strong class="text-text-dark"&gt;texto&lt;/strong&gt; para negritas.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Párrafo 2</label>
                    <textarea name="science_paragraph2" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Aunque cierta cantidad de luz azul es natural y necesaria...">{{ $page->science_paragraph2 }}</textarea>
                </div>
            </div>
        </div>

        {{-- ═══════════ 3. SÍNTOMAS ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('symptoms')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">3. Síntomas — ¿Por qué es dañina?</h3>
                <svg :class="openSection === 'symptoms' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'symptoms'" x-collapse class="px-6 pb-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                        <input type="text" name="symptoms_label" value="{{ $page->symptoms_label }}"
                               placeholder="Efectos en tu salud"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input type="text" name="symptoms_title" value="{{ $page->symptoms_title }}"
                               placeholder="¿Por qué es dañina?"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <input type="text" name="symptoms_subtitle" value="{{ $page->symptoms_subtitle }}"
                           placeholder="La exposición prolongada a luz azul artificial puede provocar estos síntomas:"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div class="border-t pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Tarjetas de síntomas</h4>
                    <div x-data="symptomsRepeater()" x-init="init()">
                        <template x-for="(card, idx) in items" :key="idx">
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-gray-500" x-text="'Síntoma ' + (idx + 1)"></span>
                                    <button type="button" @click="items.splice(idx, 1)" class="text-xs text-red-500 hover:text-red-700">Eliminar</button>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                                        <input type="text" :name="'symptoms_cards[' + idx + '][title]'" x-model="card.title"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                                        <input type="text" :name="'symptoms_cards[' + idx + '][desc]'" x-model="card.desc"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mt-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Clase color (ej: text-red-500)</label>
                                        <input type="text" :name="'symptoms_cards[' + idx + '][color]'" x-model="card.color"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Clase fondo (ej: bg-red-50)</label>
                                        <input type="text" :name="'symptoms_cards[' + idx + '][bg]'" x-model="card.bg"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="mt-2" x-data="{ showSvg: false }">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-medium text-gray-600">Icono SVG</label>
                                        <button type="button" @click="showSvg = !showSvg"
                                                class="text-xs text-blue-500 hover:text-blue-700"
                                                x-text="showSvg ? 'Ocultar SVG' : 'Editar SVG'"></button>
                                    </div>
                                    <div x-show="showSvg" x-collapse>
                                        <textarea :name="'symptoms_cards[' + idx + '][icon]'" x-model="card.icon" rows="2"
                                                  class="w-full rounded-lg border-gray-300 shadow-sm text-xs font-mono focus:border-blue-500 focus:ring-blue-500"
                                                  placeholder="Pega los <path> del SVG aquí"></textarea>
                                    </div>
                                    <input x-show="!showSvg" type="hidden" :name="'symptoms_cards[' + idx + '][icon]'" x-model="card.icon">
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="items.push({icon:'', title:'', desc:'', color:'text-red-500', bg:'bg-red-50'})"
                                class="mt-2 flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Agregar síntoma
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ 4. PROTECCIÓN NUVION ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('protection')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">4. Protección nuvion</h3>
                <svg :class="openSection === 'protection' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'protection'" x-collapse class="px-6 pb-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                        <input type="text" name="protection_label" value="{{ $page->protection_label }}"
                               placeholder="Tecnología nuvion"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input type="text" name="protection_title" value="{{ $page->protection_title }}"
                               placeholder="Protección real para tus ojos"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="protection_description" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Nuestros lentes están equipados con un filtro especializado...">{{ $page->protection_description }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Puedes usar &lt;strong&gt; para negritas.</p>
                </div>

                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Escudo / Porcentaje central</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Porcentaje</label>
                            <input type="text" name="shield_percentage" value="{{ $page->shield_percentage }}"
                                   placeholder="30-50%"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                            <input type="text" name="shield_label" value="{{ $page->shield_label }}"
                                   placeholder="de bloqueo"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Sub-etiqueta</label>
                            <input type="text" name="shield_sublabel" value="{{ $page->shield_sublabel }}"
                                   placeholder="de luz azul dañina"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Beneficios (uno por línea)</label>
                    <textarea name="protection_benefits_text" rows="4"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Reduce la fatiga visual tras largas jornadas&#10;Mejora la calidad de tu sueño&#10;Disminuye dolores de cabeza&#10;Protección con o sin graduación">{{ implode("\n", $page->protection_benefits ?? []) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ═══════════ 5. PERFILES ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('profiles')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">5. ¿Quién debería usarlos?</h3>
                <svg :class="openSection === 'profiles' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'profiles'" x-collapse class="px-6 pb-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                        <input type="text" name="profiles_label" value="{{ $page->profiles_label }}"
                               placeholder="Para ti"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input type="text" name="profiles_title" value="{{ $page->profiles_title }}"
                               placeholder="¿Quién debería usarlos?"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <input type="text" name="profiles_subtitle" value="{{ $page->profiles_subtitle }}"
                           placeholder="Si te identificas con alguno de estos perfiles, los lentes nuvion son para ti."
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div class="border-t pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Tarjetas de perfiles</h4>
                    <div x-data="profilesRepeater()" x-init="init()">
                        <template x-for="(card, idx) in items" :key="idx">
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-gray-500" x-text="'Perfil ' + (idx + 1)"></span>
                                    <button type="button" @click="items.splice(idx, 1)" class="text-xs text-red-500 hover:text-red-700">Eliminar</button>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                                        <input type="text" :name="'profiles_cards[' + idx + '][title]'" x-model="card.title"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                                        <input type="text" :name="'profiles_cards[' + idx + '][desc]'" x-model="card.desc"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="mt-2" x-data="{ showSvg: false }">
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-xs font-medium text-gray-600">Icono SVG</label>
                                        <button type="button" @click="showSvg = !showSvg"
                                                class="text-xs text-blue-500 hover:text-blue-700"
                                                x-text="showSvg ? 'Ocultar SVG' : 'Editar SVG'"></button>
                                    </div>
                                    <div x-show="showSvg" x-collapse>
                                        <textarea :name="'profiles_cards[' + idx + '][icon]'" x-model="card.icon" rows="2"
                                                  class="w-full rounded-lg border-gray-300 shadow-sm text-xs font-mono focus:border-blue-500 focus:ring-blue-500"
                                                  placeholder="Pega los <path> del SVG aquí"></textarea>
                                    </div>
                                    <input x-show="!showSvg" type="hidden" :name="'profiles_cards[' + idx + '][icon]'" x-model="card.icon">
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="items.push({icon:'', title:'', desc:''})"
                                class="mt-2 flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Agregar perfil
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ 6. FAQ ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('faq')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">6. Preguntas frecuentes</h3>
                <svg :class="openSection === 'faq' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'faq'" x-collapse class="px-6 pb-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                        <input type="text" name="faq_label" value="{{ $page->faq_label }}"
                               placeholder="Resolvemos tus dudas"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input type="text" name="faq_title" value="{{ $page->faq_title }}"
                               placeholder="Preguntas frecuentes"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <div class="border-t pt-4 mt-2">
                    <div x-data="faqRepeater()" x-init="init()">
                        <template x-for="(faq, idx) in items" :key="idx">
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-gray-500" x-text="'Pregunta ' + (idx + 1)"></span>
                                    <button type="button" @click="items.splice(idx, 1)" class="text-xs text-red-500 hover:text-red-700">Eliminar</button>
                                </div>
                                <div class="mb-2">
                                    <input type="text" :name="'faqs[' + idx + '][q]'" x-model="faq.q"
                                           class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Pregunta">
                                </div>
                                <div>
                                    <textarea :name="'faqs[' + idx + '][a]'" x-model="faq.a" rows="3"
                                              class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Respuesta"></textarea>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="items.push({q:'', a:''})"
                                class="mt-2 flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Agregar pregunta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ 7. COMPARATIVO ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('compare')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">7. Toggle comparativo + Métricas</h3>
                <svg :class="openSection === 'compare' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'compare'" x-collapse class="px-6 pb-6 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                        <input type="text" name="compare_label" value="{{ $page->compare_label }}"
                               placeholder="Compara la diferencia"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input type="text" name="compare_title" value="{{ $page->compare_title }}"
                               placeholder="¿Qué pasa con tus ojos sin protección?"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t pt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">❌ Sin filtro (uno por línea)</label>
                        <textarea name="compare_without_items_text" rows="4"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                  placeholder="Fatiga visual constante&#10;Ojos secos e irritados&#10;Insomnio digital&#10;Dolores de cabeza frecuentes">{{ implode("\n", $page->compare_without_items ?? []) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">✅ Con nuvion glass (uno por línea)</label>
                        <textarea name="compare_with_items_text" rows="4"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                  placeholder="Descanso visual prolongado&#10;Ojos hidratados y cómodos&#10;Mejor calidad del sueño&#10;Sin cefaleas">{{ implode("\n", $page->compare_with_items ?? []) }}</textarea>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Tarjetas de métricas</h4>
                    <div x-data="metricsRepeater()" x-init="init()">
                        <template x-for="(m, idx) in items" :key="idx">
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-gray-500" x-text="'Métrica ' + (idx + 1)"></span>
                                    <button type="button" @click="items.splice(idx, 1)" class="text-xs text-red-500 hover:text-red-700">Eliminar</button>
                                </div>
                                <div class="grid grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Número</label>
                                        <input type="text" :name="'compare_metrics[' + idx + '][number]'" x-model="m.number"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500"
                                               placeholder="66%">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Descripción</label>
                                        <input type="text" :name="'compare_metrics[' + idx + '][description]'" x-model="m.description"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500"
                                               placeholder="menos parpadeos frente a pantalla">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                                        <input type="text" :name="'compare_metrics[' + idx + '][label]'" x-model="m.label"
                                               class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500"
                                               placeholder="sin protección">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                                        <select :name="'compare_metrics[' + idx + '][type]'" x-model="m.type"
                                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="red">Problema (rojo)</option>
                                            <option value="green">Solución (verde)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="items.push({number:'', description:'', label:'', type:'red'})"
                                class="mt-2 flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Agregar métrica
                        </button>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fuentes</label>
                        <input type="text" name="compare_sources" value="{{ $page->compare_sources }}"
                               placeholder="Fuentes: Vision Council, Harvard Health Publishing..."
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Texto del botón</label>
                        <input type="text" name="compare_btn_text" value="{{ $page->compare_btn_text }}"
                               placeholder="Ver lentes nuvion glass"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ 8. CTA FINAL ═══════════ --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <button type="button" @click="toggle('cta')"
                    class="w-full flex items-center justify-between px-6 py-4 text-left">
                <h3 class="text-base font-semibold text-gray-900">8. CTA final</h3>
                <svg :class="openSection === 'cta' && 'rotate-180'" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="openSection === 'cta'" x-collapse class="px-6 pb-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="cta_title" value="{{ $page->cta_title }}"
                           placeholder="Protege tus ojos hoy"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                    <textarea name="cta_subtitle" rows="2"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                              placeholder="Elige los lentes que cuidan tu vista sin sacrificar estilo.">{{ $page->cta_subtitle }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Texto del botón</label>
                    <input type="text" name="cta_btn_text" value="{{ $page->cta_btn_text }}"
                           placeholder="Ver nuestros lentes"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
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

@push('scripts')
<script>
function symptomsRepeater() {
    return {
        items: [],
        init() {
            this.items = @json($page->symptoms_cards ?? []);
            if (this.items.length === 0) {
                this.items = [
                    { icon: 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z', title: 'Fatiga visual', desc: 'Ojos cansados, visión borrosa y dificultad para enfocar después de horas frente a la pantalla.', color: 'text-red-500', bg: 'bg-red-50' },
                    { icon: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z', title: 'Dolores de cabeza', desc: 'Cefaleas frecuentes causadas por el esfuerzo visual y la sobreestimulación lumínica.', color: 'text-orange-500', bg: 'bg-orange-50' },
                    { icon: 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z', title: 'Insomnio', desc: 'La luz azul suprime la melatonina, afectando tu ciclo de sueño y la calidad de descanso.', color: 'text-indigo-500', bg: 'bg-indigo-50' },
                    { icon: 'M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z', title: 'Ojos secos', desc: 'Reducción del parpadeo frente a pantallas que provoca sequedad e irritación ocular.', color: 'text-blue-500', bg: 'bg-blue-50' },
                ];
            }
        }
    };
}

function profilesRepeater() {
    return {
        items: [],
        init() {
            this.items = @json($page->profiles_cards ?? []);
            if (this.items.length === 0) {
                this.items = [
                    { icon: 'M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25', title: 'Oficina / Home office', desc: '6+ horas diarias frente a la computadora' },
                    { icon: 'M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.491 48.491 0 01-4.163-.3c.186 1.613.466 3.2.838 4.752a.643.643 0 01-.057.523 3 3 0 000 3.162.644.644 0 01.057.523c-.372 1.553-.652 3.14-.838 4.753a48.627 48.627 0 014.163-.3.64.64 0 01.657.643v0c0 .355-.186.676-.401.959a1.647 1.647 0 00-.349 1.003c0 1.035 1.007 1.875 2.25 1.875s2.25-.84 2.25-1.875c0-.369-.128-.713-.349-1.003-.215-.283-.401-.604-.401-.959v0c0-.368.312-.664.657-.643a48.651 48.651 0 014.163.3c-.186-1.613-.466-3.2-.838-4.753a.644.644 0 01.057-.523 3 3 0 000-3.162.643.643 0 01-.057-.523c.372-1.553.652-3.14.838-4.753a48.558 48.558 0 01-4.163.3.64.64 0 01-.657-.643v0z', title: 'Gamers', desc: 'Sesiones intensas con monitores de alta luminosidad' },
                    { icon: 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5', title: 'Estudiantes', desc: 'Clases en línea y horas de estudio con dispositivos' },
                    { icon: 'M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3', title: 'Usuarios de celular', desc: 'Uso constante del smartphone en el día a día' },
                    { icon: 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42', title: 'Diseñadores / Creativos', desc: 'Trabajo creativo que requiere máxima precisión visual' },
                    { icon: 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z', title: 'Uso nocturno', desc: 'Si usas pantallas antes de dormir y te cuesta conciliar el sueño' },
                ];
            }
        }
    };
}

function faqRepeater() {
    return {
        items: [],
        init() {
            this.items = @json($page->faqs ?? []);
            if (this.items.length === 0) {
                this.items = [
                    { q: '¿Los lentes nuvion tienen graduación?', a: 'Sí, ofrecemos lentes con y sin graduación. Puedes elegir la opción que mejor se adapte a tus necesidades al momento de la compra.' },
                    { q: '¿Cuánta luz azul bloquean los lentes?', a: 'Nuestros lentes bloquean entre el 30% y 50% de la luz azul de alta energía (380-500 nm), que es el rango dañino emitido por pantallas y luces LED.' },
                    { q: '¿Puedo usarlos todo el día?', a: 'Por supuesto. Los lentes nuvion están diseñados para uso prolongado. Son ligeros, cómodos y no alteran significativamente la percepción del color.' },
                    { q: '¿Son útiles si ya uso lentes de contacto?', a: 'Sí. Si usas lentes de contacto sin filtro de luz azul, nuestros lentes sin graduación te brindan una capa adicional de protección.' },
                    { q: '¿Los niños pueden usar lentes con filtro de luz azul?', a: 'Sí, especialmente si pasan tiempo frente a pantallas para clases o entretenimiento. Consulta con un oftalmólogo para recomendaciones específicas según la edad.' },
                ];
            }
        }
    };
}

function metricsRepeater() {
    return {
        items: [],
        init() {
            this.items = @json($page->compare_metrics ?? []);
            if (this.items.length === 0) {
                this.items = [
                    { number: '66%', description: 'menos parpadeos frente a pantalla', label: 'sin protección', type: 'red' },
                    { number: '3h', description: 'más de sueño profundo recuperado', label: 'con nuvion glass', type: 'green' },
                    { number: '90%', description: 'de usuarios con fatiga visual digital', label: 'tras 2h de pantalla sin filtro', type: 'red' },
                    { number: '40%', description: 'reducción de fatiga ocular reportada', label: 'con filtro de luz azul activo', type: 'green' },
                ];
            }
        }
    };
}
</script>
@endpush
@endsection
