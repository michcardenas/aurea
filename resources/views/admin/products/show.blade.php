@extends('layouts.admin')

@section('title', $product->name)
@section('page_title', $product->name)

@section('content')
    <div class="max-w-4xl space-y-6">
        {{-- Actions --}}
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Editar</a>
            <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $product->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                    {{ $product->is_active ? 'Desactivar' : 'Activar' }}
                </button>
            </form>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 ml-auto">← Volver al listado</a>
        </div>

        {{-- Product info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Información</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Categoría</dt>
                            <dd class="font-medium">{{ $product->category->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Precio</dt>
                            <dd class="font-medium">${{ number_format($product->price, 2) }}
                                @if($product->compare_price)
                                    <span class="text-gray-400 line-through ml-1">${{ number_format($product->compare_price, 2) }}</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Stock</dt>
                            <dd class="{{ $product->stock < 10 ? 'text-red-600' : '' }} font-medium">{{ $product->stock }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Estado</dt>
                            <dd>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                                @if($product->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-1">Destacado</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Slug</dt>
                            <dd class="font-mono text-xs">{{ $product->slug }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Imágenes</h2>
                    @if($product->images && count($product->images) > 0)
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400">Sin imágenes.</p>
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-800 mb-2">Descripción</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>
        </div>

        {{-- Variants --}}
        @if($product->variants->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Variantes ({{ $product->variants->count() }})</h2>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Imagen</th>
                            <th class="px-6 py-3">Atributo</th>
                            <th class="px-6 py-3">Valor</th>
                            <th class="px-6 py-3">Color</th>
                            <th class="px-6 py-3">Mod. precio</th>
                            <th class="px-6 py-3">Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($product->variants as $variant)
                            <tr>
                                <td class="px-6 py-3">
                                    @if($variant->image_path)
                                        <img src="{{ asset('storage/' . $variant->image_path) }}" alt="" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm">{{ $variant->name }}</td>
                                <td class="px-6 py-3 text-sm font-medium">{{ $variant->value }}</td>
                                <td class="px-6 py-3 text-sm">{{ $variant->color ?? '—' }}</td>
                                <td class="px-6 py-3 text-sm">{{ $variant->price_modifier > 0 ? '+' : '' }}${{ number_format($variant->price_modifier, 2) }}</td>
                                <td class="px-6 py-3 text-sm">{{ $variant->stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- SEO --}}
        @if($product->meta_title || $product->meta_description)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">SEO</h2>
                <dl class="space-y-3 text-sm">
                    @if($product->meta_title)
                        <div>
                            <dt class="text-gray-500">Meta título</dt>
                            <dd class="mt-1 font-medium">{{ $product->meta_title }}</dd>
                        </div>
                    @endif
                    @if($product->meta_description)
                        <div>
                            <dt class="text-gray-500">Meta descripción</dt>
                            <dd class="mt-1 text-gray-600">{{ $product->meta_description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        @endif
    </div>
@endsection
