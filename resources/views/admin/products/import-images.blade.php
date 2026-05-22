@extends('layouts.admin')

@section('title', 'Importar imágenes')
@section('page_title', 'Importar imágenes de producto')

@section('content')
<div class="max-w-3xl">
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
</div>
@endsection
