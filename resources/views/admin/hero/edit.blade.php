@extends('layouts.admin')

@section('title', 'Gestionar Hero')
@section('page_title', 'Gestionar Hero')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <form method="POST" action="{{ route('admin.hero.update') }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start;">

            {{-- ============ COLUMNA IZQUIERDA: FORMULARIO ============ --}}
            <div style="display:flex;flex-direction:column;gap:20px;">

                {{-- Card: Contenido multimedia --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Contenido multimedia</h3>

                    {{-- Tipo de fondo --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de fondo</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="media_mode" value="video"
                                       {{ $hero->media_type === 'video' ? 'checked' : '' }}
                                       onchange="toggleMediaMode('video')">
                                <span class="text-sm text-gray-700">Video</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="media_mode" value="image"
                                       {{ $hero->media_type === 'image' ? 'checked' : '' }}
                                       onchange="toggleMediaMode('image')">
                                <span class="text-sm text-gray-700">Imagen</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="media_mode" value="gradient"
                                       {{ $hero->media_type === 'gradient' ? 'checked' : '' }}
                                       onchange="toggleMediaMode('gradient')">
                                <span class="text-sm text-gray-700">Gradiente (sin archivo)</span>
                            </label>
                        </div>
                    </div>

                    {{-- Preview actual --}}
                    @if($hero->media_path)
                    <div class="mb-4" id="current-media-preview">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Media actual</label>
                        @if($hero->media_type === 'video')
                        <video src="{{ asset('storage/' . $hero->media_path) }}"
                               style="width:100%;max-height:160px;border-radius:8px;object-fit:cover;" muted autoplay loop playsinline></video>
                        @else
                        <img src="{{ asset('storage/' . $hero->media_path) }}"
                             style="width:100%;max-height:160px;border-radius:8px;object-fit:cover;">
                        @endif
                        <p class="text-xs text-gray-400 mt-1">Archivo actual: {{ $hero->media_path }}</p>
                        <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer mt-2">
                            <input type="checkbox" name="use_gradient" value="1">
                            Eliminar archivo y usar gradiente
                        </label>
                    </div>
                    @endif

                    {{-- Info gradiente --}}
                    <div id="gradient-info" style="{{ $hero->media_type === 'gradient' && !$hero->media_path ? 'display:block' : 'display:none' }}" class="mb-3">
                        <div class="flex items-center gap-2 text-sm text-gray-500 bg-gray-50 rounded-lg p-3">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Usando gradiente CSS y producto estrella como fondo. Sube un video o imagen para cambiar.
                        </div>
                    </div>

                    {{-- File upload --}}
                    <div id="media-upload" style="{{ $hero->media_type !== 'gradient' || $hero->media_path ? 'display:block' : 'display:none' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir nuevo archivo</label>
                        <input type="file" name="media_file"
                               accept="video/mp4,video/webm,image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">Video: .mp4 recomendado, max 50MB · Imagen: .jpg o .png, min 1920px de ancho</p>
                    </div>

                    {{-- Overlay opacity --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Oscuridad del overlay: <span id="op-val" class="text-gray-500">{{ round($hero->overlay_opacity * 100) }}%</span>
                        </label>
                        <input type="range" name="overlay_opacity"
                               min="0.2" max="0.9" step="0.05"
                               value="{{ $hero->overlay_opacity }}"
                               class="w-full"
                               oninput="document.getElementById('op-val').textContent=Math.round(this.value*100)+'%';updateSliderPreviews()">
                        <p class="text-xs text-gray-400 mt-1">Mas alto = texto mas legible sobre el video/imagen</p>
                    </div>

                    {{-- Video horizontal position --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Posición horizontal del video/imagen: <span id="vp-val" class="text-gray-500">{{ $hero->video_position ?? 50 }}%</span>
                        </label>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span class="text-xs text-gray-400">← Izq</span>
                            <input type="range" name="video_position"
                                   min="0" max="100" step="5"
                                   value="{{ $hero->video_position ?? 50 }}"
                                   class="w-full"
                                   oninput="document.getElementById('vp-val').textContent=this.value+'%';updateSliderPreviews()">
                            <span class="text-xs text-gray-400">Der →</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Controla qué parte del video/imagen se muestra. 0% = extremo izquierdo, 50% = centro, 100% = extremo derecho</p>
                    </div>
                </div>

                {{-- Card: Galería de imágenes (Hero Split) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                     x-data="{
                        existing: @json($hero->hero_images ?? []),
                        previews: [],
                        removeImage(idx) { this.existing.splice(idx, 1); },
                        previewFiles(e) {
                            this.previews = [];
                            for (const f of e.target.files) {
                                this.previews.push(URL.createObjectURL(f));
                            }
                        },
                        get total() { return this.existing.length + this.previews.length; }
                     }">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Galería de imágenes (Hero Split)</h3>
                    <p class="text-xs text-gray-400 mb-4">Máximo 10 imágenes. Si hay más de 1, se mostrará un carrusel automático en el hero.</p>

                    {{-- Imágenes existentes --}}
                    <div class="flex flex-wrap gap-3 mb-4">
                        <template x-for="(img, idx) in existing" :key="'ex-'+idx">
                            <div class="relative group">
                                <img :src="'{{ asset('storage') }}/' + img"
                                     class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                                <input type="hidden" name="hero_images_existing[]" :value="img">
                                <button type="button" @click="removeImage(idx)"
                                        class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                        title="Eliminar">&times;</button>
                            </div>
                        </template>

                        {{-- Previews de archivos nuevos --}}
                        <template x-for="(src, idx) in previews" :key="'new-'+idx">
                            <div class="relative">
                                <img :src="src" class="w-24 h-24 rounded-lg object-cover border-2 border-blue-300">
                                <span class="absolute bottom-1 right-1 bg-blue-500 text-white text-[9px] px-1.5 py-0.5 rounded">Nuevo</span>
                            </div>
                        </template>
                    </div>

                    {{-- Contador --}}
                    <p class="text-xs mb-2" :class="total > 10 ? 'text-red-500 font-medium' : 'text-gray-400'"
                       x-text="total + '/10 imágenes' + (total > 10 ? ' — se guardarán solo las primeras 10' : '')"></p>

                    {{-- Upload --}}
                    <input type="file" name="hero_images_files[]" multiple accept="image/jpeg,image/png,image/webp"
                           @change="previewFiles($event)"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">Selecciona varias imágenes a la vez. .jpg, .png o .webp</p>
                </div>

                {{-- Card: Textos del hero --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Textos del hero</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow (pill superior)</label>
                            <input type="text" name="eyebrow_text" value="{{ $hero->eyebrow_text }}"
                                   placeholder="Ej: Proteccion de luz azul"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Linea 1 del titulo</label>
                                <input type="text" name="title_line1" value="{{ $hero->title_line1 }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Linea 2 del titulo</label>
                                <input type="text" name="title_line2" value="{{ $hero->title_line2 }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Linea 3 del titulo</label>
                                <input type="text" name="title_line3" value="{{ $hero->title_line3 }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Palabra a resaltar en azul</label>
                            <input type="text" name="title_highlight_word" value="{{ $hero->title_highlight_word }}"
                                   placeholder="Debe ser una palabra de la linea 3"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <p class="text-xs text-gray-400 mt-1">Esta palabra aparecera en azul (#378ADD) en el titulo</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitulo</label>
                            <textarea name="subtitle" rows="2"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ $hero->subtitle }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Texto del badge promocional</label>
                            <input type="text" name="badge_text" value="{{ $hero->badge_text }}"
                                   placeholder="Dejar vacio para ocultar el badge"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Card: Botones --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Botones</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boton principal — texto</label>
                            <input type="text" name="btn_primary_text" value="{{ $hero->btn_primary_text }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boton principal — URL</label>
                            <input type="text" name="btn_primary_url" value="{{ $hero->btn_primary_url }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boton secundario — texto</label>
                            <input type="text" name="btn_secondary_text" value="{{ $hero->btn_secondary_text }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Boton secundario — URL</label>
                            <input type="text" name="btn_secondary_url" value="{{ $hero->btn_secondary_url }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Card: Estadisticas flotantes --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Estadisticas flotantes</h3>
                    <p class="text-xs text-gray-400 mb-3">Cards glassmorphism que flotan a la derecha del hero. Solo visibles en desktop. Dejar vacio para ocultar.</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stat 1 — numero</label>
                            <input type="text" name="stat1_number" value="{{ $hero->stat1_number }}"
                                   placeholder="Ej: 2x1"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stat 1 — etiqueta</label>
                            <input type="text" name="stat1_label" value="{{ $hero->stat1_label }}"
                                   placeholder="Ej: en todos los lentes"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stat 2 — numero</label>
                            <input type="text" name="stat2_number" value="{{ $hero->stat2_number }}"
                                   placeholder="Ej: 6"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stat 2 — etiqueta</label>
                            <input type="text" name="stat2_label" value="{{ $hero->stat2_label }}"
                                   placeholder="Ej: modelos disponibles"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Card: Badges de confianza --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                     x-data="trustBarRepeater()" x-init="init()">
                    <h3 class="text-base font-semibold text-gray-900 mb-1">Badges de confianza (trust bar del hero)</h3>
                    <p class="text-xs text-gray-400 mb-4">Aparecen como franja inferior del hero en la home. Máximo 6. Deja vacío para ocultar la franja.</p>

                    {{-- JSON payload (bulletproof) --}}
                    <input type="hidden" name="trust_items_json" :value="JSON.stringify(items)">

                    <template x-for="(item, idx) in items" :key="idx">
                        <div class="grid grid-cols-[80px_1fr_auto] gap-2 items-center mb-2">
                            <input type="text" x-model="item.icon"
                                   placeholder="✓ 📦 ↩ ★"
                                   maxlength="4"
                                   class="rounded-lg border-gray-300 shadow-sm text-sm text-center focus:border-blue-500 focus:ring-blue-500">
                            <input type="text" x-model="item.text"
                                   placeholder="Ej: Envío gratis +$999"
                                   class="rounded-lg border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <button type="button" @click="items.splice(idx, 1)"
                                    class="text-red-500 hover:text-red-700 text-xs px-2">Eliminar</button>
                        </div>
                    </template>

                    <div class="flex items-center gap-3 mt-3">
                        <button type="button" @click="if (items.length < 6) items.push({icon:'✓', text:''})"
                                class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium"
                                :class="items.length >= 6 ? 'opacity-40 cursor-not-allowed' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Agregar item
                        </button>
                        <button type="button" @click="if (confirm('¿Restablecer los 4 items por defecto?')) items = JSON.parse(JSON.stringify(defaults))"
                                class="text-xs text-gray-500 hover:text-gray-700">Restablecer por defecto</button>
                    </div>
                </div>

                <script>
                    function trustBarRepeater() {
                        return {
                            items: [],
                            defaults: [
                                { icon: '✓', text: 'Filtro certificado' },
                                { icon: '📦', text: 'Envío gratis +$999' },
                                { icon: '↩', text: '30 días devolución' },
                                { icon: '★', text: 'Garantía 6 meses' },
                            ],
                            init() {
                                const saved = @json($hero->trust_items ?? []);
                                if (Array.isArray(saved) && saved.length > 0) {
                                    // Migrate legacy string array → {icon, text}
                                    this.items = saved.map(v => {
                                        if (typeof v === 'string') return { icon: '✓', text: v };
                                        return {
                                            icon: (v && v.icon) ? v.icon : '✓',
                                            text: (v && v.text) ? v.text : '',
                                        };
                                    });
                                } else {
                                    this.items = JSON.parse(JSON.stringify(this.defaults));
                                }
                            }
                        };
                    }
                </script>

                {{-- Boton guardar --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl text-white font-medium text-base transition-colors"
                        style="background:#378ADD;"
                        onmouseover="this.style.background='#185FA5'"
                        onmouseout="this.style.background='#378ADD'">
                    Guardar cambios
                </button>
            </div>

            {{-- ============ COLUMNA DERECHA: PREVIEW EN VIVO ============ --}}
            <div style="position:sticky;top:90px;">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Preview en vivo</h3>

                    <div id="hero-preview"
                         style="position:relative;height:320px;border-radius:12px;overflow:hidden;background:#ffffff;display:grid;grid-template-columns:1fr 1fr;">

                        {{-- Left: text content --}}
                        <div style="padding:20px 16px;display:flex;flex-direction:column;justify-content:center;">

                            <div id="prev-eyebrow"
                                 style="display:inline-flex;align-items:center;gap:4px;background:#EBF4FF;border:0.5px solid #B5D4F4;border-radius:12px;padding:2px 10px;font-size:7px;color:#185FA5;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;width:fit-content;">
                                {{ $hero->eyebrow_text }}
                            </div>

                            <div id="prev-title"
                                 style="font-size:16px;font-weight:800;color:#0d1117;line-height:1.08;letter-spacing:-.02em;margin-bottom:6px;font-family:'Bai Jamjuree',sans-serif;">
                                @php
                                    $prevLine3 = e($hero->title_line3);
                                    if ($hero->title_highlight_word && str_contains($hero->title_line3, $hero->title_highlight_word)) {
                                        $prevLine3 = str_replace(e($hero->title_highlight_word), '<span style="color:#378ADD;">' . e($hero->title_highlight_word) . '</span>', $prevLine3);
                                    }
                                @endphp
                                {{ $hero->title_line1 }}<br>{{ $hero->title_line2 }}<br>{!! $prevLine3 !!}
                            </div>

                            <div id="prev-badge"
                                 style="display:inline-block;background:#F0F7FF;border:0.5px solid #BFDBFE;border-radius:4px;padding:3px 8px;font-size:7px;color:#1e40af;margin-bottom:6px;width:fit-content;{{ !$hero->badge_text ? 'display:none;' : '' }}">
                                {{ $hero->badge_text }}
                            </div>

                            <p id="prev-subtitle"
                               style="font-size:7px;color:#6b7280;line-height:1.5;margin-bottom:10px;max-width:160px;">
                                {{ $hero->subtitle }}
                            </p>

                            <div style="display:flex;gap:4px;">
                                <div id="prev-btn1"
                                     style="background:#0d1117;color:#fff;border-radius:4px;padding:4px 10px;font-size:7px;font-weight:500;">
                                    {{ $hero->btn_primary_text }} →
                                </div>
                                <div id="prev-btn2"
                                     style="background:transparent;color:#374151;border:0.5px solid #e5e7eb;border-radius:4px;padding:4px 10px;font-size:7px;">
                                    {{ $hero->btn_secondary_text }}
                                </div>
                            </div>
                        </div>

                        {{-- Right: product image / video preview area --}}
                        <div id="prev-media-area" style="position:relative;background:linear-gradient(135deg,#e0f2fe 0%,#dbeafe 50%,#ede9fe 100%);overflow:hidden;">
                            {{-- Overlay preview (for video mode) --}}
                            <div id="prev-overlay" style="display:none;position:absolute;inset:0;background:linear-gradient(to right,rgba(255,255,255,0.95) 0%,rgba(255,255,255,0.88) 30%,rgba(255,255,255,0.6) 55%,rgba(255,255,255,0.15) 80%,rgba(255,255,255,0.0) 100%);z-index:1;pointer-events:none;"></div>

                            {{-- Video position indicator --}}
                            <div id="prev-position-indicator" style="display:none;position:absolute;bottom:6px;z-index:3;background:rgba(0,0,0,0.6);color:#fff;border-radius:4px;padding:2px 6px;font-size:6px;white-space:nowrap;left:50%;transform:translateX(-50%);">
                                Posición: <span id="prev-pos-val">{{ $hero->video_position ?? 50 }}%</span>
                            </div>

                            {{-- Vertical line showing video focus point --}}
                            <div id="prev-focus-line" style="display:none;position:absolute;top:0;bottom:0;width:1px;background:rgba(55,138,221,0.5);z-index:2;left:{{ $hero->video_position ?? 50 }}%;pointer-events:none;">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:8px;height:8px;border-radius:50%;background:#378ADD;border:1.5px solid #fff;"></div>
                            </div>

                            {{-- Float badges --}}
                            <div id="prev-stat1" style="position:absolute;left:6px;top:14%;background:#fff;border-radius:6px;padding:5px 8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);z-index:2;{{ !$hero->stat1_number ? 'display:none;' : '' }}">
                                <div style="font-size:12px;font-weight:700;color:#0d1117;line-height:1;" id="prev-stat1-num">{{ $hero->stat1_number }}</div>
                                <div style="font-size:5px;margin-top:1px;color:#9ca3af;" id="prev-stat1-label">{{ $hero->stat1_label }}</div>
                            </div>
                            <div id="prev-stat2" style="position:absolute;right:8px;bottom:20%;background:#fff;border-radius:6px;padding:5px 8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);z-index:2;{{ !$hero->stat2_number ? 'display:none;' : '' }}">
                                <div style="font-size:12px;font-weight:700;color:#0d1117;line-height:1;" id="prev-stat2-num">{{ $hero->stat2_number }}</div>
                                <div style="font-size:5px;margin-top:1px;color:#9ca3af;" id="prev-stat2-label">{{ $hero->stat2_label }}</div>
                            </div>
                            {{-- Product silhouette --}}
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                                <svg width="80" height="40" viewBox="0 0 80 40" fill="none" style="opacity:0.2;">
                                    <ellipse cx="40" cy="20" rx="38" ry="16" stroke="#0d1117" stroke-width="2"/>
                                    <ellipse cx="26" cy="20" rx="14" ry="12" stroke="#0d1117" stroke-width="1.5"/>
                                    <ellipse cx="54" cy="20" rx="14" ry="12" stroke="#0d1117" stroke-width="1.5"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Mode indicator --}}
                    <div id="prev-mode-label" style="margin-top:8px;text-align:center;font-size:10px;color:#9ca3af;">
                        Vista previa: modo <strong id="prev-mode-text">{{ $hero->media_type === 'video' ? 'video' : 'split' }}</strong>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>

<script>
function toggleMediaMode(mode) {
    var upload = document.getElementById('media-upload');
    var gradientInfo = document.getElementById('gradient-info');
    var overlay = document.getElementById('prev-overlay');
    var focusLine = document.getElementById('prev-focus-line');
    var posIndicator = document.getElementById('prev-position-indicator');
    var modeText = document.getElementById('prev-mode-text');
    var mediaArea = document.getElementById('prev-media-area');

    if (mode === 'gradient') {
        upload.style.display = 'none';
        if (gradientInfo) gradientInfo.style.display = 'block';
    } else {
        upload.style.display = 'block';
        if (gradientInfo) gradientInfo.style.display = 'none';
    }

    // Update preview to reflect mode
    if (mode === 'video') {
        overlay.style.display = 'block';
        focusLine.style.display = 'block';
        posIndicator.style.display = 'block';
        mediaArea.style.background = '#1a1a2e';
        modeText.textContent = 'video';
    } else {
        overlay.style.display = 'none';
        focusLine.style.display = 'none';
        posIndicator.style.display = 'none';
        mediaArea.style.background = 'linear-gradient(135deg,#e0f2fe 0%,#dbeafe 50%,#ede9fe 100%)';
        modeText.textContent = 'split';
    }
}

function updateSliderPreviews() {
    var opacity = document.querySelector('[name="overlay_opacity"]').value;
    var position = document.querySelector('[name="video_position"]').value;
    var overlay = document.getElementById('prev-overlay');
    var focusLine = document.getElementById('prev-focus-line');
    var posVal = document.getElementById('prev-pos-val');

    // Update overlay opacity in preview
    var op = parseFloat(opacity);
    overlay.style.background = 'linear-gradient(to right,' +
        'rgba(255,255,255,' + (0.5 + op * 0.5) + ') 0%,' +
        'rgba(255,255,255,' + (0.4 + op * 0.48) + ') 30%,' +
        'rgba(255,255,255,' + (op * 0.67) + ') 55%,' +
        'rgba(255,255,255,' + (op * 0.17) + ') 80%,' +
        'rgba(255,255,255,0.0) 100%)';

    // Update position line
    focusLine.style.left = position + '%';
    posVal.textContent = position + '%';
}

function updatePreview() {
    var eyebrow = document.querySelector('[name="eyebrow_text"]').value;
    var t1 = document.querySelector('[name="title_line1"]').value;
    var t2 = document.querySelector('[name="title_line2"]').value;
    var t3 = document.querySelector('[name="title_line3"]').value;
    var highlight = document.querySelector('[name="title_highlight_word"]').value;
    var subtitle = document.querySelector('[name="subtitle"]').value;
    var badge = document.querySelector('[name="badge_text"]').value;
    var btn1 = document.querySelector('[name="btn_primary_text"]').value;
    var btn2 = document.querySelector('[name="btn_secondary_text"]').value;
    var s1n = document.querySelector('[name="stat1_number"]').value;
    var s1l = document.querySelector('[name="stat1_label"]').value;
    var s2n = document.querySelector('[name="stat2_number"]').value;
    var s2l = document.querySelector('[name="stat2_label"]').value;

    document.getElementById('prev-eyebrow').textContent = eyebrow;

    var titleHtml = escapeHtml(t1) + '<br>' + escapeHtml(t2) + '<br>';
    if (highlight && t3.includes(highlight)) {
        titleHtml += escapeHtml(t3).replace(escapeHtml(highlight), '<span style="color:#378ADD;">' + escapeHtml(highlight) + '</span>');
    } else {
        titleHtml += escapeHtml(t3);
    }
    document.getElementById('prev-title').innerHTML = titleHtml;

    document.getElementById('prev-subtitle').textContent = subtitle;

    var badgeEl = document.getElementById('prev-badge');
    badgeEl.textContent = badge;
    badgeEl.style.display = badge ? 'inline-block' : 'none';

    document.getElementById('prev-btn1').innerHTML = escapeHtml(btn1) + ' →';
    document.getElementById('prev-btn2').textContent = btn2;

    document.getElementById('prev-stat1-num').textContent = s1n;
    document.getElementById('prev-stat1-label').textContent = s1l;
    document.getElementById('prev-stat1').style.display = s1n ? 'block' : 'none';

    document.getElementById('prev-stat2-num').textContent = s2n;
    document.getElementById('prev-stat2-label').textContent = s2l;
    document.getElementById('prev-stat2').style.display = s2n ? 'block' : 'none';
}

function escapeHtml(text) {
    var d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

document.querySelectorAll('input[type="text"], textarea').forEach(function(el) {
    el.addEventListener('input', updatePreview);
});

// Connect range sliders to preview
document.querySelectorAll('input[type="range"]').forEach(function(el) {
    el.addEventListener('input', updateSliderPreviews);
});

// Initialize preview state based on current media mode
(function() {
    var checked = document.querySelector('[name="media_mode"]:checked');
    if (checked && checked.value === 'video') {
        toggleMediaMode('video');
    }
})();
</script>

<style>
@media (max-width: 1024px) {
    div[style*="grid-template-columns:1fr 380px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
