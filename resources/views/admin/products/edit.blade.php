@extends('layouts.admin')

@section('title', 'Editar: ' . $product->name)
@section('page_title', 'Editar producto')

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="max-w-4xl space-y-6">
        @csrf @method('PUT')

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Basic info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Información básica</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div x-data="quickCategory()">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <div class="flex items-center gap-2">
                        <select id="category_id" name="category_id" required
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" @click="showModal = true"
                                class="shrink-0 inline-flex items-center justify-center w-9 h-9 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-blue-600 transition-colors"
                                title="Crear categoría rápida">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Quick create category modal --}}
                    <div x-show="showModal" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                         @keydown.escape.window="showModal = false">
                        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" @click.outside="showModal = false">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Nueva categoría</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                    <input type="text" x-model="newName"
                                           @keydown.enter.prevent="createCategory()"
                                           placeholder="Ej: Lentes de sol"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <p x-show="error" x-text="error" class="text-sm text-red-600"></p>
                            </div>
                            <div class="flex justify-end gap-2 mt-5">
                                <button type="button" @click="showModal = false; error = ''"
                                        class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">Cancelar</button>
                                <button type="button" @click="createCategory()" :disabled="saving"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-50">
                                    <span x-show="!saving">Crear</span>
                                    <span x-show="saving">Creando...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ── Precios ── --}}
                <div x-data="{
                        price: {{ (float) old('price', $product->price) }},
                        compare: {{ (float) old('compare_price', $product->compare_price ?? 0) }},
                        cost: {{ (float) old('cost_price', $product->cost_price ?? 0) }},
                        get margin() { return this.cost > 0 ? this.price - this.cost : null; },
                        get marginPct() { return (this.cost > 0 && this.price > 0) ? ((this.price - this.cost) / this.price * 100) : null; },
                        money(n) { return n === null ? '—' : '$' + Number(n).toLocaleString('es-CO', {minimumFractionDigits: 0, maximumFractionDigits: 2}); },
                     }"
                     class="rounded-xl p-5 border" style="background:#FBF8F2;border-color:#E8CC92;">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#BE9A53;letter-spacing:0.15em;">Precios</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="cost_price" class="block text-xs font-medium text-gray-600 mb-1">
                                Costo (PV Distribuidor)
                                <span class="text-gray-400" title="Lo que tú pagas por unidad. Solo visible en admin.">ⓘ</span>
                            </label>
                            <input type="number" id="cost_price" name="cost_price" x-model.number="cost" step="0.01" min="0"
                                   placeholder="0.00"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                        </div>
                        <div>
                            <label for="price" class="block text-xs font-medium text-gray-600 mb-1">
                                Precio venta web *
                                <span class="text-gray-400" title="Lo que el cliente paga en la tienda online.">ⓘ</span>
                            </label>
                            <input type="number" id="price" name="price" x-model.number="price" step="0.01" min="0" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white font-semibold">
                        </div>
                        <div>
                            <label for="compare_price" class="block text-xs font-medium text-gray-600 mb-1">
                                Precio anterior / PVP
                                <span class="text-gray-400" title="PV Centro de Exp. Se muestra tachado al lado del precio. Si está vacío o ≤ precio venta, no aparece.">ⓘ</span>
                            </label>
                            <input type="number" id="compare_price" name="compare_price" x-model.number="compare" step="0.01" min="0"
                                   placeholder="0.00"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                        </div>
                    </div>
                    {{-- Cálculo de margen en vivo --}}
                    <div class="mt-4 flex items-center justify-between text-xs" style="color:#6B6157;">
                        <span>
                            Margen bruto:
                            <strong style="color:#2E2A26;" x-text="money(margin)"></strong>
                            <span x-show="marginPct !== null" x-cloak>
                                (<span x-text="marginPct?.toFixed(1)+'%'" :style="marginPct < 20 ? 'color:#C97B6B' : (marginPct < 40 ? 'color:#BE9A53' : 'color:#7C9B7E')"></span>)
                            </span>
                        </span>
                        <span x-show="compare > 0 && compare > price" x-cloak style="color:#7C9B7E;">
                            Descuento mostrado: <strong x-text="(((compare - price) / compare * 100).toFixed(0) + '%')"></strong>
                        </span>
                    </div>
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-6 pt-6">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Destacado</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de producto *</label>
                    <p class="text-xs text-gray-400 mb-2">Puedes seleccionar más de uno si el producto es híbrido.</p>
                    <div class="flex flex-wrap gap-4">
                        @php $currentTypes = old('type', $product->type ?? []); @endphp
                        @foreach(['miopia' => 'Miopía', 'lectura' => 'Lectura', 'sin_graduacion' => 'Sin Graduación', 'toallitas' => 'Toallitas'] as $val => $label)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="type[]" value="{{ $val }}"
                                   {{ in_array($val, $currentTypes) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Imágenes</h2>
            @if($product->images && count($product->images) > 0)
                <div class="flex flex-wrap gap-4 mb-4">
                    @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image) }}" alt="" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                            <label class="absolute inset-0 bg-black/50 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <input type="checkbox" name="remove_images[]" value="{{ $image }}" class="sr-only peer">
                                <span class="text-white text-xs peer-checked:hidden">Eliminar</span>
                                <span class="text-red-400 text-xs hidden peer-checked:block font-bold">Marcado</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mb-4">Pasa el mouse sobre una imagen y haz clic para marcarla para eliminar.</p>
            @endif
            <input type="file" name="images[]" multiple accept="image/*"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-2 text-xs text-gray-400">Agrega más imágenes (JPG, PNG o WebP, máx. 2MB cada una).</p>
        </div>

        {{-- Variantes — genéricas para cualquier producto de belleza --}}
        @php
            $existingVariants = old('variants', $product->variants->map(fn($v) => [
                'id'             => $v->id,
                'option_type'    => $v->option_type ?: 'other',
                'name'           => $v->name,
                'value'          => $v->value,
                'color'          => $v->color,
                'color_hex'      => $v->color_hex,
                'graduation'     => $v->graduation,
                'graduation_type'=> $v->graduation_type,
                'price_modifier' => $v->price_modifier,
                'stock'          => $v->stock,
                'image_path'     => $v->image_path,
            ])->toArray());
            $variantTypes = \App\Models\ProductVariant::OPTION_TYPES;
            $variantLabels = \App\Models\ProductVariant::DEFAULT_LABELS;
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
             x-data="{
                variants: {{ json_encode(array_values($existingVariants)) }},
                storageUrl: '{{ asset('storage') }}',
                defaultLabels: {{ json_encode($variantLabels) }},
                addVariant() {
                    this.variants.push({
                        id: null, option_type: 'color', name: 'Tono', value: '',
                        color: '', color_hex: '#D9B56D',
                        graduation: '', graduation_type: '',
                        price_modifier: 0, stock: 0, image_path: null
                    });
                },
                onTypeChange(v) {
                    if (this.defaultLabels[v.option_type] && (!v.name || Object.values(this.defaultLabels).includes(v.name))) {
                        v.name = this.defaultLabels[v.option_type];
                    }
                    if (v.option_type !== 'color') { v.color_hex = ''; }
                    else if (!v.color_hex) { v.color_hex = '#D9B56D'; }
                },
             }">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-lg font-semibold text-gray-800" style="font-family:'Playfair Display',serif;">Variantes</h2>
                <button type="button" @click="addVariant()"
                        class="text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors"
                        style="background:#D9B56D;color:#2E2A26;"
                        onmouseover="this.style.background='#E8CC92'"
                        onmouseout="this.style.background='#D9B56D'">
                    + Agregar variante
                </button>
            </div>
            <p class="text-xs text-gray-500 mb-4">
                Define las opciones que el cliente puede elegir: color de esmalte, tamaño de envase, aroma, acabado, etc.
                Cada variante puede tener su propio precio, stock e imagen.
            </p>
            <template x-for="(variant, index) in variants" :key="index">
                <div class="rounded-xl p-4 mb-3" style="background:#FBF8F2;border:1px solid #E8CC92;">
                    <input type="hidden" :name="'variants['+index+'][id]'" :value="variant.id">
                    <input type="hidden" :name="'variants['+index+'][color]'" :value="variant.color || variant.value">

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        {{-- Tipo --}}
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                            <select :name="'variants['+index+'][option_type]'" x-model="variant.option_type"
                                    @change="onTypeChange(variant)"
                                    class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                                @foreach($variantTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Etiqueta --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                            <input type="text" :name="'variants['+index+'][name]'" x-model="variant.name"
                                   :placeholder="defaultLabels[variant.option_type]"
                                   class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                        </div>

                        {{-- Valor --}}
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Valor *</label>
                            <input type="text" :name="'variants['+index+'][value]'" x-model="variant.value"
                                   :placeholder="variant.option_type === 'color' ? 'Rojo Coral' : (variant.option_type === 'size' ? '50 ml' : (variant.option_type === 'scent' ? 'Rosa' : 'Valor'))"
                                   class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                        </div>

                        {{-- Color hex (solo si tipo color) --}}
                        <div class="md:col-span-2" x-show="variant.option_type === 'color'" x-cloak>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Hex</label>
                            <div class="flex items-center gap-1">
                                <input type="color" :value="variant.color_hex || '#D9B56D'"
                                       @input="variant.color_hex = $event.target.value"
                                       class="w-9 h-9 p-0.5 border border-gray-300 rounded-lg cursor-pointer">
                                <input type="text" :name="'variants['+index+'][color_hex]'" x-model="variant.color_hex"
                                       maxlength="7" placeholder="#D9B56D"
                                       class="flex-1 border border-gray-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="md:col-span-2 flex items-end justify-end">
                            <button type="button" @click="variants.splice(index, 1)"
                                    class="text-xs text-red-600 hover:text-red-800 py-2">Eliminar</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mt-3 items-end">
                        {{-- Precio mod --}}
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-600 mb-1">+/- Precio</label>
                            <input type="number" :name="'variants['+index+'][price_modifier]'" x-model="variant.price_modifier" step="0.01"
                                   placeholder="0"
                                   class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                        </div>
                        {{-- Stock --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Stock</label>
                            <input type="number" :name="'variants['+index+'][stock]'" x-model="variant.stock" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white">
                        </div>
                        {{-- Imagen --}}
                        <div class="md:col-span-7 flex items-end gap-3">
                            <div x-show="variant.image_path" class="flex items-center gap-2 shrink-0">
                                <img :src="variant.image_path ? storageUrl + '/' + variant.image_path : ''"
                                     class="w-14 h-14 object-cover rounded-lg border border-gray-200">
                                <label class="flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                                    <input type="checkbox" :name="'variants['+index+'][remove_image]'" value="1" class="w-3 h-3">
                                    Quitar
                                </label>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-600 mb-1" x-text="variant.image_path ? 'Reemplazar imagen' : 'Imagen específica de esta variante (opcional)'"></label>
                                <input type="file" :name="'variants['+index+'][image]'" accept="image/*"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="variants.length === 0">
                <p class="text-sm text-gray-400 italic">Sin variantes. Haz clic en "+ Agregar variante" para crear una.</p>
            </template>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">SEO</h2>
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta título</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="255"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta descripción</label>
                    <textarea id="meta_description" name="meta_description" rows="2" maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Guardar cambios
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
function quickCategory() {
    return {
        showModal: false,
        newName: '',
        error: '',
        saving: false,
        async createCategory() {
            if (!this.newName.trim()) {
                this.error = 'El nombre es obligatorio.';
                return;
            }
            this.saving = true;
            this.error = '';
            try {
                const res = await fetch('{{ route("admin.categories.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ name: this.newName.trim() }),
                });
                const data = await res.json();
                if (!res.ok) {
                    this.error = data.message || Object.values(data.errors || {}).flat()[0] || 'Error al crear.';
                    return;
                }
                const select = document.getElementById('category_id');
                const option = new Option(data.name, data.id, true, true);
                select.add(option);
                this.newName = '';
                this.showModal = false;
            } catch (e) {
                this.error = 'Error de conexión.';
            } finally {
                this.saving = false;
            }
        }
    };
}
</script>
@endpush
