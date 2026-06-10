@extends('layouts.admin')

@section('title', 'Importar imágenes')
@section('page_title', 'Importar imágenes de producto')

@section('content')
<div class="max-w-5xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-5" style="background:#FBF4E6;border-bottom:1px solid #E8CC92;">
            <h2 class="text-lg font-semibold" style="color:#2E2A26;font-family:'Playfair Display',serif;">Carga masiva de imágenes</h2>
            <p class="mt-1 text-sm" style="color:#6b6157;">
                Sube un archivo <code>.zip</code> con tus imágenes nombradas por la Referencia del producto.
                Las imágenes se redimensionan a máx. 1200px y se convierten a WebP (calidad 85%) automáticamente.
            </p>
        </div>

        <form action="{{ route('admin.products.import-images.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Archivo ZIP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Archivo ZIP</label>
                <input type="file" name="file" required accept=".zip"
                       class="block w-full text-sm text-gray-700
                              file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                <p class="mt-1 text-xs text-gray-500">Tamaño máximo: 500 MB.</p>
                @error('file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Modo: reemplazar o agregar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">¿Qué hacer con las imágenes existentes del producto?</label>
                <div class="space-y-2">
                    <label class="flex items-start gap-2">
                        <input type="radio" name="mode" value="append" checked
                               class="mt-1 border-gray-300 text-yellow-600 focus:ring-yellow-500">
                        <div>
                            <span class="text-sm font-medium text-gray-800">Agregar a las existentes</span>
                            <p class="text-xs text-gray-500">Las nuevas se suman a las que ya tenga el producto.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-2">
                        <input type="radio" name="mode" value="replace"
                               class="mt-1 border-gray-300 text-yellow-600 focus:ring-yellow-500">
                        <div>
                            <span class="text-sm font-medium text-gray-800">Reemplazar todas</span>
                            <p class="text-xs text-gray-500">Borra del producto las imágenes anteriores y deja solo las del ZIP.</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Convención de nombres -->
            <details open class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <summary class="text-sm font-medium text-gray-700 cursor-pointer">Cómo nombrar tus archivos</summary>
                <div class="mt-3 text-xs text-gray-600 space-y-3">
                    <p>Cada imagen debe llamarse igual que la <code>Referencia</code> del producto. Aceptamos <code>.jpg</code>, <code>.jpeg</code>, <code>.png</code>, <code>.webp</code> y <code>.gif</code>.</p>
                    <div class="font-mono text-[11px] bg-white p-3 rounded border border-gray-200">
                        imagenes.zip<br>
                        ├── <span style="color:#BE9A53;">13.jpg</span>            <span class="text-gray-400">← única imagen del producto Referencia "13"</span><br>
                        ├── <span style="color:#BE9A53;">1223.webp</span>          <span class="text-gray-400">← producto "1223"</span><br>
                        ├── <span style="color:#BE9A53;">1004-B.jpg</span>         <span class="text-gray-400">← producto "1004-B"</span><br>
                        ├── <span style="color:#BE9A53;">1307-1.jpg</span>         <span class="text-gray-400">← producto "1307", imagen principal</span><br>
                        ├── <span style="color:#BE9A53;">1307-2.jpg</span>         <span class="text-gray-400">← producto "1307", segunda imagen</span><br>
                        └── <span style="color:#BE9A53;">1307-3.jpg</span>         <span class="text-gray-400">← producto "1307", tercera imagen</span><br>
                    </div>
                    <p class="text-amber-700"><strong>Tip:</strong> si necesitas múltiples imágenes por producto, agrega <code>-1</code>, <code>-2</code>, <code>-3</code> al final del nombre (antes del punto). Sin sufijo, se guarda como imagen única.</p>
                    <p class="text-gray-500">Las referencias que no coincidan con ningún producto se reportarán al final.</p>
                </div>
            </details>

            <!-- Botones -->
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg font-semibold text-sm text-white transition"
                        style="background:#D9B56D;"
                        onmouseover="this.style.background='#BE9A53'"
                        onmouseout="this.style.background='#D9B56D'">
                    Importar imágenes
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
            </div>
        </form>
    </div>

    @if(session('import_image_errors'))
    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-amber-800 mb-2">Imágenes con observaciones ({{ count(session('import_image_errors')) }}):</p>
        <ul class="text-xs text-amber-700 space-y-1 max-h-60 overflow-y-auto">
            @foreach(session('import_image_errors') as $err)
                <li>• {{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ───────────── PRODUCTOS PENDIENTES DE FOTO ───────────── --}}
    @if(isset($missingProducts))
    <div class="mt-8 bg-white rounded-xl shadow-sm overflow-hidden" style="border:1px solid #E8DCC6;">

        <div class="px-6 py-5 flex items-baseline justify-between gap-4" style="background:#FBF8F2;border-bottom:1px solid #F0EAE0;">
            <div>
                <p class="text-xs font-bold uppercase mb-1" style="color:#BE9A53;letter-spacing:.18em;">Pendientes</p>
                <h2 class="text-lg font-semibold" style="font-family:'Playfair Display',serif;color:#2E2A26;">
                    Productos sin foto
                </h2>
                <p class="text-xs mt-1" style="color:#6B6157;">
                    <strong style="color:#C97B6B;">{{ $missingTotal }}</strong> de
                    <strong>{{ $totalActive }}</strong> productos activos no tienen imagen
                    ({{ $totalActive > 0 ? round(($missingTotal / $totalActive) * 100) : 0 }}% del catálogo).
                </p>
            </div>
            <div style="background:#FCEFE6;border:1px solid #C97B6B;color:#A65A4D;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:600;">
                {{ $missingTotal }} sin foto
            </div>
        </div>

        {{-- Filtros --}}
        <form method="GET" class="px-6 py-4" style="background:#FFFFFF;border-bottom:1px solid #F0EAE0;">
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-xs font-medium mb-1" style="color:#4B4541;">Buscar por nombre o referencia</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="ej. cepillo, 4001, pestañas..."
                           class="w-full rounded-lg px-3 py-2 text-sm"
                           style="background:#FBF8F2;border:1px solid #E5DCC9;color:#2E2A26;">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1" style="color:#4B4541;">Categoría</label>
                    <select name="cat" class="rounded-lg px-3 py-2 text-sm"
                            style="background:#FBF8F2;border:1px solid #E5DCC9;color:#2E2A26;">
                        <option value="">Todas ({{ $missingTotal }})</option>
                        @foreach($categoriesWithMissing as $c)
                            <option value="{{ $c->id }}" {{ request('cat') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->missing_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white"
                            style="background:#D9B56D;"
                            onmouseover="this.style.background='#BE9A53'"
                            onmouseout="this.style.background='#D9B56D'">
                        Filtrar
                    </button>
                    @if(request('q') || request('cat'))
                        <a href="{{ route('admin.products.import-images') }}"
                           class="text-xs underline" style="color:#6B6157;">Limpiar</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Tabla --}}
        @if($missingProducts->isEmpty())
            <div class="px-6 py-12 text-center">
                <p class="text-5xl mb-3">🎉</p>
                <p class="text-sm" style="color:#7C9B7E;font-weight:600;">¡No quedan productos sin foto!</p>
                @if(request('q') || request('cat'))
                    <p class="text-xs mt-2" style="color:#9CA3AF;">No hay resultados con estos filtros.</p>
                @endif
            </div>
        @else
        <table class="w-full">
            <thead>
                <tr style="background:#FBF4E6;border-bottom:1px solid #E8CC92;">
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Ref.</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Producto</th>
                    <th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Categoría</th>
                    <th class="px-4 py-3 text-right text-[10px] font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Precio</th>
                    <th class="px-4 py-3 text-right text-[10px] font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color:#F0EAE0;">
                @foreach($missingProducts as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <code class="text-xs font-mono px-2 py-1 rounded"
                              style="background:#FBF8F2;border:1px solid #E5DCC9;color:#BE9A53;">{{ $p->internal_code }}</code>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium" style="color:#2E2A26;">{{ $p->name }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs" style="color:#6B6157;">{{ $p->category?->name ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="text-sm font-semibold" style="color:#2E2A26;font-family:'Playfair Display',serif;">
                            ${{ number_format($p->price, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.products.edit', $p) }}" target="_blank"
                           class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full transition-all"
                           style="background:#FFFFFF;border:1px solid #D9B56D;color:#BE9A53;"
                           onmouseover="this.style.background='#D9B56D';this.style.color='#FFFFFF'"
                           onmouseout="this.style.background='#FFFFFF';this.style.color='#BE9A53'">
                            Subir foto
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3h7v7M10 14L21 3M21 14v7H3V3h7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="px-6 py-4" style="background:#FBF8F2;border-top:1px solid #F0EAE0;">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs" style="color:#6B6157;">
                    Mostrando <strong>{{ $missingProducts->firstItem() }}</strong>–<strong>{{ $missingProducts->lastItem() }}</strong>
                    de <strong>{{ $missingProducts->total() }}</strong> productos sin foto
                </p>
                <div>
                    {{ $missingProducts->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
