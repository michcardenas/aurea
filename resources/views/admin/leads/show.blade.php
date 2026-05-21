@extends('layouts.admin')

@section('title', 'Lead: ' . ($lead->name ?? $lead->email))
@section('page_title', 'Detalle de lead')

@section('content')
    <div class="max-w-xl space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.leads.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Volver al listado</a>
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('¿Eliminar este lead?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Eliminar</button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Información del lead</h2>
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-500">ID</dt>
                    <dd class="font-medium">#{{ $lead->id }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium">{{ $lead->name ?? '—' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-500">Email</dt>
                    <dd>
                        <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:underline">{{ $lead->email }}</a>
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-500">Fuente</dt>
                    <dd>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $lead->source }}</span>
                    </dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-gray-500">Registrado</dt>
                    <dd class="text-gray-700">{{ $lead->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
