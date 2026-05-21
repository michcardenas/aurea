@extends('layouts.admin')

@section('title', 'Órdenes')
@section('page_title', 'Órdenes')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
        <p class="text-gray-500">{{ $orders->total() }} órdenes en total.</p>
        <a href="{{ route('admin.orders.export') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Exportar CSV
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por ID, nombre o email..."
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Enviada</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregada</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Filtrar</button>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
            </div>
        </div>
    </form>

    {{-- Orders table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($orders->isEmpty())
            <div class="p-8 text-center text-gray-500">No se encontraron órdenes.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Pago</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            @php
                                $statusColors = ['pending' => 'yellow', 'confirmed' => 'blue', 'shipped' => 'indigo', 'delivered' => 'green', 'cancelled' => 'red'];
                                $statusLabels = ['pending' => 'Pendiente', 'confirmed' => 'Confirmada', 'shipped' => 'Enviada', 'delivered' => 'Entregada', 'cancelled' => 'Cancelada'];
                                $payLabels = ['pending' => 'Pendiente', 'paid' => 'Pagado', 'failed' => 'Fallido'];
                                $sc = $statusColors[$order->status] ?? 'gray';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->customer->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $sc }}-100 text-{{ $sc }}-800">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $payLabels[$order->payment_status] ?? $order->payment_status }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sm text-blue-600 hover:underline">Ver detalle</a>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                              onsubmit="return confirm('¿Eliminar la orden #{{ $order->id }}? Esta acción no se puede deshacer.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
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
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
