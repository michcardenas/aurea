@extends('layouts.admin')

@section('title', 'Nueva categoría')
@section('page_title', 'Nueva categoría')

@section('content')
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Información</h2>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Ej: Lentes de sol"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción (opcional)</label>
                    <textarea id="description" name="description" rows="2"
                              placeholder="Breve descripción de la categoría"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filtro al hacer clic en la tarjeta</label>
                    <p class="text-xs text-gray-500 mb-2">Elige uno o más tipos de producto. El botón "Ver modelos" del home filtrará el catálogo por estos tipos. Si no eliges ninguno, lleva al catálogo completo.</p>
                    @php $oldTypes = old('type_filter', []); @endphp
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            'miopia' => 'Miopía',
                            'lectura' => 'Lectura',
                            'sin_graduacion' => 'Sin graduación',
                            'toallitas' => 'Toallitas',
                        ] as $value => $label)
                        <label class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="type_filter[]" value="{{ $value }}"
                                   {{ in_array($value, $oldTypes) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Imagen (opcional)</h2>
            <input type="file" name="image" accept="image/*"
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-2 text-xs text-gray-400">JPG, PNG o WebP. Máximo 2MB.</p>
            <p class="mt-1 text-xs text-blue-600">Tamaño recomendado: 800×450 px (proporción 16:9, horizontal). La imagen se recorta automáticamente a 180 px de alto en la card.</p>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Crear categoría
            </button>
        </div>
    </form>
@endsection
