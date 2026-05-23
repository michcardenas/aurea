@extends('layouts.admin')

@section('title', 'Categorías')
@section('page_title', 'Categorías')

@push('head')
<style>
    .cat-row { transition: background .15s ease; }
    .cat-row:hover { background: #FBF8F2; }
    .cat-thumb {
        width: 56px; height: 56px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: linear-gradient(135deg, #FBF4E6 0%, #E8CC92 100%);
        display: flex; align-items: center; justify-content: center;
        color: #BE9A53;
        font-family: 'Playfair Display', serif;
        font-weight: 600;
        font-size: 18px;
    }
    .cat-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .order-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        background: #FBF4E6;
        color: #BE9A53;
        border: 1px solid #E8CC92;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
    }
    .order-badge--home {
        background: linear-gradient(135deg, #D9B56D, #BE9A53);
        color: #FFFFFF;
        border-color: #BE9A53;
    }
    .home-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #BE9A53;
        background: #FBF4E6;
        padding: 3px 8px;
        border-radius: 999px;
        border: 1px solid #E8CC92;
    }
    .prod-count {
        display: inline-block;
        padding: 4px 10px;
        background: #F0F2EB;
        color: #6B7766;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .prod-count--zero { background: #F5F5F5; color: #9CA3AF; }
    .icon-btn {
        display: inline-flex;
        width: 32px; height: 32px;
        align-items: center; justify-content: center;
        border-radius: 8px;
        color: #6B6157;
        transition: all .2s;
    }
    .icon-btn:hover { background: #FBF4E6; color: #BE9A53; }
    .icon-btn--danger:hover { background: #FCE8E2; color: #C97B6B; }
</style>
@endpush

@section('content')
<div class="max-w-6xl">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-6">
        <div>
            <p class="text-sm" style="color:#6B6157;">
                <strong style="color:#2E2A26;">{{ $categories->count() }}</strong> categorías ·
                <strong style="color:#BE9A53;">{{ $categories->take(8)->count() }}</strong> visibles en el home
            </p>
            <p class="text-xs mt-1" style="color:#9CA3AF;">
                Las primeras 8 por orden aparecen en la página principal. La #1 ocupa la card destacada grande.
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center text-white px-4 py-2.5 rounded-full text-sm font-semibold transition-all"
           style="background:#D9B56D;letter-spacing:.04em;"
           onmouseover="this.style.background='#BE9A53';this.style.transform='translateY(-1px)'"
           onmouseout="this.style.background='#D9B56D';this.style.transform=''">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nueva categoría
        </a>
    </div>

    {{-- Lista --}}
    <div class="bg-white rounded-2xl overflow-hidden" style="border:1px solid #E8DCC6;">
        @if($categories->isEmpty())
            <div class="p-12 text-center">
                <div class="cat-thumb mx-auto mb-4" style="width:80px;height:80px;font-size:28px;">∅</div>
                <p style="color:#6B6157;">No hay categorías aún.</p>
                <p class="text-sm mt-1" style="color:#9CA3AF;">Crea categorías para organizar tu catálogo de belleza.</p>
                <a href="{{ route('admin.categories.create') }}"
                   class="inline-flex items-center mt-4 px-4 py-2 rounded-full text-sm font-semibold text-white"
                   style="background:#D9B56D;">
                    + Crear la primera categoría
                </a>
            </div>
        @else
            <table class="w-full">
                <thead>
                    <tr style="background:#FBF4E6;border-bottom:1px solid #E8CC92;">
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Orden</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Productos</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">En home</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:#BE9A53;letter-spacing:.12em;">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#F0EAE0;">
                    @foreach($categories as $i => $category)
                    <tr class="cat-row">
                        <td class="px-6 py-4">
                            <span class="order-badge {{ $i < 8 ? 'order-badge--home' : '' }}">
                                {{ $category->sort_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="cat-thumb">
                                    @if($category->image)
                                        <img src="{{ asset('storage/'.$category->image) }}" alt="">
                                    @else
                                        {{ \Str::upper(\Str::substr($category->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold" style="color:#2E2A26;">{{ $category->name }}</p>
                                    @if($category->description)
                                        <p class="text-xs mt-0.5" style="color:#9CA3AF;">{{ \Str::limit($category->description, 60) }}</p>
                                    @else
                                        <p class="text-xs mt-0.5 italic" style="color:#D1C7BC;">Sin descripción</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="prod-count {{ $category->products_count === 0 ? 'prod-count--zero' : '' }}">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($i < 8)
                                <span class="home-pill">★ Visible</span>
                            @else
                                <span class="text-xs" style="color:#9CA3AF;">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="icon-btn" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline"
                                      onsubmit="return confirm('¿Eliminar la categoría \'{{ $category->name }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="icon-btn icon-btn--danger" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Ayuda --}}
    <div class="mt-6 p-4 rounded-xl" style="background:#FBF4E6;border:1px solid #E8CC92;">
        <div class="flex items-start gap-3">
            <div style="color:#BE9A53;font-size:18px;line-height:1;">💡</div>
            <div class="text-sm" style="color:#6B6157;line-height:1.6;">
                <strong style="color:#2E2A26;">Cómo organizar tus categorías:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1" style="color:#6B6157;">
                    <li>El campo <strong>Orden</strong> define qué tan arriba aparece. <strong>1 = primera</strong>, números mayores = más abajo.</li>
                    <li>Las <strong>8 primeras</strong> aparecen en el home. La #1 ocupa la card grande destacada.</li>
                    <li>Sube una <strong>imagen 800×450</strong> a cada categoría top para que se vea premium en el home.</li>
                    <li>Si no hay imagen, se muestra un gradiente áureo como fondo.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
