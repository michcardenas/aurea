@extends('layouts.admin')
@section('title', 'Marcas')
@section('page_title', 'Marcas que distribuimos')

@section('content')
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <p class="text-gray-500">{{ $brands->total() }} marca(s).</p>
    <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center text-white px-4 py-2 rounded-lg text-sm font-medium"
       style="background:#D9B56D;" onmouseover="this.style.background='#BE9A53'" onmouseout="this.style.background='#D9B56D'">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Nueva marca
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background:#FBF4E6;">
            <tr style="color:#2E2A26;">
                <th class="text-left px-4 py-3 font-semibold">Logo</th>
                <th class="text-left px-4 py-3 font-semibold">Marca</th>
                <th class="text-left px-4 py-3 font-semibold">País</th>
                <th class="text-left px-4 py-3 font-semibold">Productos</th>
                <th class="text-left px-4 py-3 font-semibold">Estado</th>
                <th class="text-left px-4 py-3 font-semibold">Home</th>
                <th class="text-right px-4 py-3 font-semibold">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
            <tr class="border-t border-gray-100">
                <td class="px-4 py-3">
                    @if($brand->logo_path)
                        <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="h-10 w-10 object-contain rounded">
                    @else
                        <div class="h-10 w-10 rounded flex items-center justify-center text-xs font-bold" style="background:#FBF4E6;color:#BE9A53;">{{ Str::upper(Str::substr($brand->name, 0, 2)) }}</div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $brand->name }}</p>
                    @if($brand->short_description)
                    <p class="text-xs text-gray-500 truncate max-w-xs">{{ $brand->short_description }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $brand->country_origin ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $brand->products_count }}</td>
                <td class="px-4 py-3">
                    @if($brand->is_active)
                        <span class="px-2 py-0.5 rounded text-xs" style="background:#E8F5E9;color:#2E7D32;">Activa</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-500">Inactiva</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($brand->is_featured)
                        <span class="px-2 py-0.5 rounded text-xs" style="background:#FBF4E6;color:#BE9A53;">★ Destacada</span>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.brands.edit', $brand) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-3">Editar</a>
                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar marca?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                    No hay marcas todavía. <a href="{{ route('admin.brands.create') }}" style="color:#BE9A53;text-decoration:underline;">Crea la primera</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $brands->links() }}</div>
@endsection
