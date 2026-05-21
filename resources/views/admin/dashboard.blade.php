@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Ventas hoy</p>
                <span class="text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($todaySales, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Órdenes pendientes</p>
                <span class="text-yellow-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Leads hoy</p>
                <span class="text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $newLeads }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Productos activos</p>
                <span class="text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                </span>
            </div>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $activeProducts }}</p>
        </div>
    </div>

    {{-- Recent orders --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Órdenes recientes</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
        </div>
        @if($recentOrders->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p>No hay órdenes aún.</p>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $order->customer->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = ['pending' => 'yellow', 'confirmed' => 'blue', 'shipped' => 'indigo', 'delivered' => 'green', 'cancelled' => 'red'];
                                    $labels = ['pending' => 'Pendiente', 'confirmed' => 'Confirmada', 'shipped' => 'Enviada', 'delivered' => 'Entregada', 'cancelled' => 'Cancelada'];
                                    $c = $colors[$order->status] ?? 'gray';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $c }}-100 text-{{ $c }}-800">
                                    {{ $labels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                      onsubmit="return confirm('¿Eliminar la orden #{{ $order->id }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
