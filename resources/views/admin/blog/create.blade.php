@extends('layouts.admin')

@section('title', 'Nuevo artículo')
@section('page_title', 'Nuevo artículo')

@section('content')
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="max-w-4xl space-y-6"
          x-data="blogForm()">
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

        {{-- Content --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Contenido</h2>
            <div class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           x-model="title"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-400">Slug: <span class="font-mono" x-text="slug"></span></p>
                </div>
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Extracto</label>
                    <textarea id="excerpt" name="excerpt" rows="2" maxlength="160"
                              x-model="excerpt"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('excerpt') }}</textarea>
                    <p class="mt-1 text-xs" :class="excerpt.length > 160 ? 'text-red-500' : 'text-gray-400'">
                        <span x-text="excerpt.length"></span>/160 caracteres
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenido *</label>
                    <textarea id="content" name="content" style="display:none;">{{ old('content') }}</textarea>
                    <div id="quill-editor" style="height:400px;background:#fff;border:1px solid #d1d5db;border-radius:0 0 8px 8px;"></div>
                </div>
            </div>
        </div>

        {{-- Image --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Imagen destacada</h2>
            <div class="space-y-4">
                <input type="file" name="image" accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <div>
                    <label for="featured_image_alt" class="block text-sm font-medium text-gray-700 mb-1">Alt text (SEO)</label>
                    <input type="text" id="featured_image_alt" name="featured_image_alt" value="{{ old('featured_image_alt') }}"
                           placeholder="Descripción de la imagen para buscadores y accesibilidad"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">SEO</h2>
            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta título</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" maxlength="60"
                           x-model="metaTitle"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="mt-1 flex items-center gap-2">
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-200"
                                 :class="metaTitle.length === 0 ? 'bg-gray-300' : metaTitle.length <= 60 ? 'bg-green-500' : 'bg-red-500'"
                                 :style="'width:' + Math.min(100, (metaTitle.length / 60) * 100) + '%'"></div>
                        </div>
                        <span class="text-xs" :class="metaTitle.length > 60 ? 'text-red-500' : 'text-gray-400'">
                            <span x-text="metaTitle.length"></span>/60
                        </span>
                    </div>
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta descripción</label>
                    <textarea id="meta_description" name="meta_description" rows="2" maxlength="160"
                              x-model="metaDescription"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('meta_description') }}</textarea>
                    <div class="mt-1 flex items-center gap-2">
                        <div class="h-1.5 flex-1 rounded-full bg-gray-200 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-200"
                                 :class="metaDescription.length === 0 ? 'bg-gray-300' : metaDescription.length <= 160 ? 'bg-green-500' : 'bg-red-500'"
                                 :style="'width:' + Math.min(100, (metaDescription.length / 160) * 100) + '%'"></div>
                        </div>
                        <span class="text-xs" :class="metaDescription.length > 160 ? 'text-red-500' : 'text-gray-400'">
                            <span x-text="metaDescription.length"></span>/160
                        </span>
                    </div>
                </div>
                <div>
                    <label for="focus_keyword" class="block text-sm font-medium text-gray-700 mb-1">Keyword principal</label>
                    <input type="text" id="focus_keyword" name="focus_keyword" value="{{ old('focus_keyword') }}"
                           placeholder="Ej: lentes luz azul"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoría del blog</label>
                    <select id="category" name="category"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @php
                            $blogCategories = [
                                '' => '— Detectar automáticamente por keyword —',
                                'salud-visual' => 'Salud visual',
                                'luz-azul' => 'Luz azul',
                                'habitos' => 'Hábitos digitales',
                                'lentes' => 'Lentes',
                            ];
                        @endphp
                        @foreach($blogCategories as $val => $label)
                            <option value="{{ $val }}" {{ old('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Determina en qué filtro aparece el artículo en <code>/blog</code>.</p>
                </div>
                <div>
                    <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-1">URL canónica</label>
                    <input type="url" id="canonical_url" name="canonical_url" value="{{ old('canonical_url') }}"
                           placeholder="Dejar vacío para usar la URL del post"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Google snippet preview --}}
            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs font-medium text-gray-500 mb-3 uppercase tracking-wider">Vista previa en Google</p>
                <div class="space-y-0.5">
                    <p class="text-sm text-green-700 font-normal truncate" x-text="'nuvionglass.com.mx/blog/' + slug"></p>
                    <p class="text-lg text-blue-700 font-medium leading-snug hover:underline cursor-pointer truncate"
                       x-text="metaTitle || (title + ' | nuvion glass')"></p>
                    <p class="text-sm text-gray-600 line-clamp-2"
                       x-text="metaDescription || excerpt || 'Agrega una meta descripción para controlar cómo se ve tu artículo en los resultados de búsqueda.'"></p>
                </div>
            </div>
        </div>

        {{-- Open Graph --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Open Graph</h2>
            <p class="text-xs text-gray-400 mb-4">Personaliza cómo se ve tu artículo al compartirlo en redes sociales. Si lo dejas vacío, se usarán los valores de SEO.</p>
            <div class="space-y-4">
                <div>
                    <label for="og_title" class="block text-sm font-medium text-gray-700 mb-1">OG Título</label>
                    <input type="text" id="og_title" name="og_title" value="{{ old('og_title') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="og_description" class="block text-sm font-medium text-gray-700 mb-1">OG Descripción</label>
                    <textarea id="og_description" name="og_description" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('og_description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Publish --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Publicación</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="status" name="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archivado</option>
                    </select>
                </div>
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Fecha de publicación</label>
                    <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="mt-1 text-xs text-gray-400">Dejar vacío para publicar ahora (si estado = Publicado).</p>
                </div>
                <div>
                    <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                    <input type="text" id="author_name" name="author_name" value="{{ old('author_name', 'nuvion glass') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="schema_type" class="block text-sm font-medium text-gray-700 mb-1">Schema type</label>
                    <select id="schema_type" name="schema_type"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="BlogPosting" {{ old('schema_type', 'BlogPosting') === 'BlogPosting' ? 'selected' : '' }}>BlogPosting</option>
                        <option value="Article" {{ old('schema_type') === 'Article' ? 'selected' : '' }}>Article</option>
                        <option value="NewsArticle" {{ old('schema_type') === 'NewsArticle' ? 'selected' : '' }}>NewsArticle</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.blog.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Crear artículo
            </button>
        </div>
    </form>

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow { border: 1px solid #d1d5db; border-radius: 8px 8px 0 0; background: #f9fafb; }
        .ql-container.ql-snow { border: none; font-size: 14px; font-family: 'Montserrat', sans-serif; }
        .ql-editor { min-height: 360px; }
        .ql-editor h2 { font-size: 1.4em; font-weight: 700; margin: 1em 0 0.4em; }
        .ql-editor h3 { font-size: 1.15em; font-weight: 600; margin: 0.8em 0 0.3em; }
        .ql-editor p { margin-bottom: 0.6em; }
        .ql-editor ul, .ql-editor ol { margin-bottom: 0.6em; }
        .ql-editor blockquote { border-left: 4px solid #D9B56D; padding-left: 12px; color: #475569; }
    </style>

    <script>
        function blogForm() {
            return {
                title: '{{ old("title", "") }}',
                excerpt: '{{ old("excerpt", "") }}',
                metaTitle: '{{ old("meta_title", "") }}',
                metaDescription: '{{ old("meta_description", "") }}',
                get slug() {
                    return this.title
                        .toLowerCase()
                        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');
                }
            };
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var contentInput = document.getElementById('content');
            var editorEl = document.getElementById('quill-editor');
            if (!contentInput || !editorEl) return;

            var quill = new Quill(editorEl, {
                theme: 'snow',
                placeholder: 'Escribe el contenido del artículo...',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link', 'image'],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            });

            function syncContent() {
                var html = quill.root.innerHTML;
                if (html === '<p><br></p>') html = '';
                contentInput.value = html;
            }

            // Load existing content (from old() on validation error)
            var existing = contentInput.value;
            if (existing && existing.trim().length > 0) {
                quill.root.innerHTML = existing;
            }

            // Mantener el textarea siempre actualizado, no solo al submit.
            quill.on('text-change', syncContent);

            // Targetear el form del blog (no el form de logout del layout).
            var form = editorEl.closest('form');
            if (form) {
                form.addEventListener('submit', syncContent);
            }
        });
    </script>
@endsection
