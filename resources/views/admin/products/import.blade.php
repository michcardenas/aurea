@extends('layouts.admin')

@section('title', 'Importar productos')
@section('page_title', 'Importar productos desde Excel')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-5 flex items-start justify-between gap-4" style="background:#FBF4E6;border-bottom:1px solid #E8CC92;">
            <div>
                <h2 class="text-lg font-semibold" style="color:#2E2A26;font-family:'Playfair Display',serif;">Carga masiva de productos</h2>
                <p class="mt-1 text-sm" style="color:#6b6157;">
                    Sube un archivo Excel (.xlsx, .xls o .csv). Las categorías que no existan se crearán automáticamente.
                    Productos existentes con la misma <code>Referencia</code> se actualizarán.
                </p>
            </div>
            <a href="{{ route('admin.products.import-template') }}"
               class="inline-flex items-center shrink-0 px-3 py-2 rounded-lg text-xs font-semibold transition-colors"
               style="background:#FFFFFF;color:#BE9A53;border:1px solid #D9B56D;"
               onmouseover="this.style.background='#D9B56D';this.style.color='#FFFFFF'"
               onmouseout="this.style.background='#FFFFFF';this.style.color='#BE9A53'">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25"/></svg>
                Descargar plantilla
            </a>
        </div>

        <form action="{{ route('admin.products.import.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Archivo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Archivo Excel</label>
                <input type="file" name="file" required
                       accept=".xlsx,.xls,.csv"
                       class="block w-full text-sm text-gray-700
                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                <p class="mt-1 text-xs text-gray-500">Tamaño máximo: 10 MB.</p>
                @error('file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Tipo por defecto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo por defecto</label>
                <select name="default_type" class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="sin_graduacion" selected>Skincare / Producto principal</option>
                    <option value="toallitas">Ritual / Set</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">Determina dónde aparece el producto en el storefront. Editable después.</p>
            </div>

            <!-- Stock por defecto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock inicial por defecto</label>
                <input type="number" name="default_stock" value="50" min="0" required
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-yellow-500 focus:ring-yellow-500">
                <p class="mt-1 text-xs text-gray-500">Solo se aplica a productos NUEVOS. A los actualizados no se les toca el stock.</p>
            </div>

            <!-- Opciones -->
            <div class="space-y-2">
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="fallback_pv_when_no_venta" value="1" checked
                           class="mt-1 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                    <span class="text-sm text-gray-700">
                        Si el producto no tiene <strong>Venta</strong>, usar <strong>PV Centro de Exp</strong> como precio.
                    </span>
                </label>
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="mark_active" value="1" checked
                           class="mt-1 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                    <span class="text-sm text-gray-700">
                        Marcar todos los productos importados como <strong>activos</strong>.
                    </span>
                </label>
            </div>

            <!-- Formato esperado -->
            <details class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <summary class="text-sm font-medium text-gray-700 cursor-pointer">Formato esperado del Excel</summary>
                <div class="mt-3 text-xs text-gray-600 space-y-2">
                    <p>El importador detecta las columnas por su encabezado (no importa el orden). Reconoce:</p>
                    <table class="w-full mt-2 text-left">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="py-1 pr-2 font-semibold">Encabezado</th>
                                <th class="py-1 pr-2 font-semibold">Mapea a</th>
                                <th class="py-1 font-semibold">Requerido</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-[11px]">
                            <tr><td class="py-1 pr-2">Referencia</td><td class="py-1 pr-2">internal_code</td><td class="py-1">✓</td></tr>
                            <tr><td class="py-1 pr-2">Nombre</td><td class="py-1 pr-2">name</td><td class="py-1">✓</td></tr>
                            <tr><td class="py-1 pr-2">Categoría</td><td class="py-1 pr-2">category (find or create)</td><td class="py-1">opcional</td></tr>
                            <tr><td class="py-1 pr-2">Descripción</td><td class="py-1 pr-2">description (— = vacío)</td><td class="py-1">opcional</td></tr>
                            <tr><td class="py-1 pr-2">PV Centro de Exp</td><td class="py-1 pr-2">compare_price (tachado)</td><td class="py-1">opcional</td></tr>
                            <tr><td class="py-1 pr-2">PV Distribuidor</td><td class="py-1 pr-2">solo lectura</td><td class="py-1">opcional</td></tr>
                            <tr><td class="py-1 pr-2">Venta</td><td class="py-1 pr-2">price (precio público)</td><td class="py-1">✓ (o fallback)</td></tr>
                        </tbody>
                    </table>
                    <p class="mt-3">Precios admiten formatos: <code>10000</code>, <code>10,000.00</code>, <code>10.000,00</code>.</p>
                </div>
            </details>

            <!-- Botones -->
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg font-semibold text-sm text-white transition"
                        style="background:#D9B56D;"
                        onmouseover="this.style.background='#BE9A53'"
                        onmouseout="this.style.background='#D9B56D'">
                    Importar productos
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
            </div>
        </form>
    </div>

    @if(session('import_errors'))
    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-amber-800 mb-2">Filas con observaciones ({{ count(session('import_errors')) }}):</p>
        <ul class="text-xs text-amber-700 space-y-1 max-h-60 overflow-y-auto">
            @foreach(session('import_errors') as $err)
                <li>• {{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
