@extends('layouts.admin')

@section('title', 'Nuevo testimonio')
@section('page_title', 'Nuevo testimonio')

@section('content')
    <form method="POST" action="{{ route('admin.testimonials.store') }}" class="max-w-3xl space-y-6">
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
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Información del cliente</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="María G.">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol / Ocupación</label>
                        <input type="text" id="role" name="role" value="{{ old('role') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Diseñadora gráfica">
                    </div>
                </div>
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Testimonio *</label>
                    <textarea id="body" name="body" rows="3" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Escribe el testimonio del cliente...">{{ old('body') }}</textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating (1-5) *</label>
                        <select id="rating" name="rating"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>{{ $i }} estrella{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="avatar_color" class="block text-sm font-medium text-gray-700 mb-1">Color avatar</label>
                        <input type="color" id="avatar_color" name="avatar_color" value="{{ old('avatar_color', '#378ADD') }}"
                               class="w-full h-[38px] border border-gray-300 rounded-lg px-1 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Crear testimonio
            </button>
        </div>
    </form>
@endsection
