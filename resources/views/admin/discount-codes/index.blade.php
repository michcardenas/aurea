@extends('layouts.admin')

@section('title', 'Códigos de descuento')
@section('page_title', 'Códigos de descuento')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <p class="text-gray-500">{{ $codes->count() }} códigos en total.</p>
        <a href="{{ route('admin.discount-codes.create') }}"
           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Nuevo código
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($codes->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z"/>
                </svg>
                <p>No hay códigos de descuento aún.</p>
                <p class="text-sm mt-1">Crea códigos para ofrecer descuentos a tus clientes.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Código</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Valor</th>
                            <th class="px-6 py-3">Usos</th>
                            <th class="px-6 py-3">Vigencia</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($codes as $code)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-100 text-sm font-mono font-semibold text-gray-800">
                                    {{ $code->code }}
                                </span>
                                @if($code->description)
                                    <p class="text-xs text-gray-400 mt-1">{{ $code->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">
                                    {{ $code->type === 'percentage' ? 'Porcentaje' : 'Monto fijo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $code->type === 'percentage' ? $code->value . '%' : '$' . number_format($code->value, 2) }}
                                </span>
                                @if($code->min_order_amount)
                                    <p class="text-xs text-gray-400">Min: ${{ number_format($code->min_order_amount, 2) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">
                                    {{ $code->times_used }}{{ $code->max_uses ? ' / ' . $code->max_uses : '' }}
                                </span>
                                @if($code->max_uses && $code->times_used >= $code->max_uses)
                                    <p class="text-xs text-red-500">Agotado</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-500 space-y-0.5">
                                    @if($code->starts_at)
                                        <p>Desde: {{ $code->starts_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                    @if($code->expires_at)
                                        <p class="{{ $code->expires_at->isPast() ? 'text-red-500' : '' }}">
                                            Hasta: {{ $code->expires_at->format('d/m/Y H:i') }}
                                            @if($code->expires_at->isPast()) (Expirado) @endif
                                        </p>
                                    @endif
                                    @if(!$code->starts_at && !$code->expires_at)
                                        <p>Sin límite</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($code->is_active && (!$code->expires_at || !$code->expires_at->isPast()) && (!$code->max_uses || $code->times_used < $code->max_uses))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                @elseif(!$code->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Inactivo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        {{ $code->expires_at?->isPast() ? 'Expirado' : 'Agotado' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.discount-codes.edit', $code) }}"
                                       class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.discount-codes.destroy', $code) }}"
                                          onsubmit="return confirm('{{ $code->times_used > 0 ? '¿Desactivar este código? (tiene usos registrados)' : '¿Eliminar este código?' }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"
                                                title="{{ $code->times_used > 0 ? 'Desactivar' : 'Eliminar' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
