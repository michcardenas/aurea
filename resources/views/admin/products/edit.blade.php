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
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="compare_price" class="block text-sm font-medium text-gray-700 mb-1">Precio anterior</label>
                        <input type="number" id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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

        {{-- Variants --}}
        @php
            $existingVariants = old('variants', $product->variants->map(fn($v) => [
                'id' => $v->id, 'name' => $v->name, 'value' => $v->value,
                'color' => $v->color, 'color_hex' => $v->color_hex,
                'graduation' => $v->graduation, 'graduation_type' => $v->graduation_type,
                'price_modifier' => $v->price_modifier, 'stock' => $v->stock,
                'image_path' => $v->image_path,
            ])->toArray());
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
             x-data="{ variants: {{ json_encode(array_values($existingVariants)) }}, storageUrl: '{{ asset('storage') }}' }">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Variantes</h2>
                <button type="button" @click="variants.push({ id: null, name: 'Color', value: '', color: '', color_hex: '', graduation: '', graduation_type: '', price_modifier: 0, stock: 0, image_path: null })"
                        class="text-sm text-blue-600 hover:text-blue-800">+ Agregar variante</button>
            </div>
            <p class="text-xs text-gray-500 mb-3">Agrega una imagen por variante para que se muestre al seleccionar el color en la tienda.</p>
            <template x-for="(variant, index) in variants" :key="index">
                <div class="border border-gray-100 rounded-lg p-3 mb-3 bg-gray-50/50">
                    <input type="hidden" :name="'variants['+index+'][id]'" :value="variant.id">
                    <div class="grid grid-cols-2 md:grid-cols-7 gap-3 items-end">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Atributo</label>
                            <input type="text" :name="'variants['+index+'][name]'" x-model="variant.name" placeholder="Color"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Valor</label>
                            <input type="text" :name="'variants['+index+'][value]'" x-model="variant.value" placeholder="Negro Mate"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Color (filtro)</label>
                            <input type="text" :name="'variants['+index+'][color]'" x-model="variant.color" placeholder="Negro"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Hex del círculo</label>
                            <div class="flex items-center gap-1">
                                <input type="color" :value="variant.color_hex || '#000000'"
                                       @input="variant.color_hex = $event.target.value"
                                       class="w-10 h-9 p-1 border border-gray-300 rounded-lg cursor-pointer"
                                       title="Elegir color">
                                <input type="text" :name="'variants['+index+'][color_hex]'" x-model="variant.color_hex"
                                       placeholder="(auto)" maxlength="7"
                                       class="flex-1 border border-gray-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Déjalo vacío para usar el color por nombre.</p>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Mod. precio</label>
                            <input type="number" :name="'variants['+index+'][price_modifier]'" x-model="variant.price_modifier" step="0.01"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Stock</label>
                            <input type="number" :name="'variants['+index+'][stock]'" x-model="variant.stock" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <button type="button" @click="variants.splice(index, 1)"
                                    class="text-red-500 hover:text-red-700 text-sm py-2">Eliminar</button>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Graduación</label>
                            <input type="text" :name="'variants['+index+'][graduation]'" x-model="variant.graduation" placeholder="-2.00, +1.50, etc."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Tipo de graduación</label>
                            <select :name="'variants['+index+'][graduation_type]'" x-model="variant.graduation_type"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">—</option>
                                <option value="miopia">Miopía</option>
                                <option value="lectura">Lectura</option>
                                <option value="sin_graduacion">Sin graduación</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 flex items-end gap-4">
                        <div x-show="variant.image_path" class="flex items-center gap-2">
                            <img :src="variant.image_path ? storageUrl + '/' + variant.image_path : ''" alt=""
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            <label class="flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                                <input type="checkbox" :name="'variants['+index+'][remove_image]'" value="1" class="w-3 h-3">
                                Eliminar
                            </label>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs text-gray-500 mb-1" x-text="variant.image_path ? 'Reemplazar imagen' : 'Imagen de esta variante'"></label>
                            <input type="file" :name="'variants['+index+'][image]'" accept="image/*"
                                   class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>
            </template>
            <template x-if="variants.length === 0">
                <p class="text-sm text-gray-400">Sin variantes. Haz clic en "+ Agregar variante" para añadir una.</p>
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
