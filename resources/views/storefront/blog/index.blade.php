@extends('layouts.app')

@section('title', 'Blog | nuvion glass')
@section('meta_description', 'Artículos sobre salud visual, luz azul y cómo proteger tus ojos de las pantallas. Blog nuvion glass.')
@section('canonical', route('blog.index'))
@section('og_title', 'Blog | nuvion glass')
@section('og_description', 'Artículos sobre salud visual, luz azul y cómo proteger tus ojos.')
@section('twitter_title', 'Blog | nuvion glass')
@section('twitter_description', 'Artículos sobre salud visual, luz azul y cómo proteger tus ojos.')

@push('schema')
    {!! $breadcrumbs !!}
@endpush

@section('content')
<style>
    .b-card {
        opacity: 0;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        border: 0.5px solid rgba(0,0,0,0.08);
        transition: transform .25s ease, box-shadow .25s ease, opacity .25s ease;
        cursor: pointer;
    }
    .b-card.animated {
        animation: fadeUp .5s ease both;
    }
    .b-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.12);
    }
    .b-card:hover .card-arrow {
        transform: translateX(4px);
    }
    .card-arrow {
        transition: transform .2s ease;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .b-card:nth-child(1).animated { animation-delay: 0.05s; }
    .b-card:nth-child(2).animated { animation-delay: 0.15s; }
    .b-card:nth-child(3).animated { animation-delay: 0.25s; }
    .b-card:nth-child(4).animated { animation-delay: 0.35s; }
    .b-card:nth-child(5).animated { animation-delay: 0.45s; }
    .b-card:nth-child(6).animated { animation-delay: 0.55s; }
    .b-card:nth-child(7).animated { animation-delay: 0.65s; }
    .b-card:nth-child(8).animated { animation-delay: 0.75s; }
    .b-card:nth-child(9).animated { animation-delay: 0.85s; }
    .b-card.is-hidden { display: none !important; }
    .blog-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 500;
        padding: 7px 14px;
        border: 1px solid rgba(255,255,255,0.18);
        color: rgba(255,255,255,0.65);
        background: rgba(255,255,255,0.03);
        cursor: pointer;
        transition: background .2s, border-color .2s, color .2s, transform .15s;
        white-space: nowrap;
    }
    .blog-filter-btn:hover {
        border-color: rgba(56,138,221,0.55);
        color: #fff;
        background: rgba(56,138,221,0.12);
    }
    .blog-filter-btn:focus-visible {
        outline: 2px solid #D9B56D;
        outline-offset: 2px;
    }
    .blog-filter-btn.active {
        background: #D9B56D;
        color: #fff;
        border-color: #D9B56D;
        box-shadow: 0 4px 14px rgba(56,138,221,0.35);
    }
    .blog-filter-btn .blog-filter-count {
        display: inline-block;
        min-width: 20px;
        padding: 1px 7px;
        border-radius: 999px;
        font-size: 10px;
        line-height: 1.4;
        background: rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.85);
    }
    .blog-filter-btn.active .blog-filter-count {
        background: rgba(255,255,255,0.25);
        color: #fff;
    }
    .blog-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 56px 16px;
        color: #64748b;
        font-size: 14px;
    }
    .blog-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 24px;
    }
    @media(min-width:640px) { .blog-grid { grid-template-columns: repeat(2,1fr); } }
    @media(min-width:1024px) { .blog-grid { grid-template-columns: repeat(3,1fr); } }
    .blog-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 48px;
    }
    .blog-pagination nav span,
    .blog-pagination nav a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        transition: background .15s;
    }
    .blog-pagination nav a:hover {
        background: #f1f5f9;
    }
    .blog-pagination nav span[aria-current="page"] {
        background: #D9B56D;
        color: #fff;
    }
    .blog-pagination nav span.dots {
        background: transparent;
    }
    @media (prefers-reduced-motion: reduce) {
        .b-card { animation: none !important; opacity: 1 !important; }
        .card-arrow { transition: none !important; }
    }
</style>

    {{-- Hero --}}
    <section class="relative overflow-hidden flex items-center justify-center" style="background:#0a0f1e;min-height:340px;">
        {{-- Glow --}}
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:500px;height:300px;background:radial-gradient(ellipse,rgba(56,130,220,0.18),transparent 70%);pointer-events:none;"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-16">
            <p style="font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#D9B56D;margin-bottom:20px;">{{ $blogPage->hero_label ?? 'NUVION GLASS · BLOG' }}</p>
            <h1 class="font-brand text-3xl md:text-5xl font-bold leading-tight" style="color:#fff;">{{ $blogPage->hero_title ?? 'Cuida tu visión.' }}<br>{{ $blogPage->hero_title_line2 ?? 'Lee, aprende,' }} <span style="color:#D9B56D;">{{ $blogPage->hero_title_accent ?? 'protégete.' }}</span></h1>
            <p class="mt-5 text-base md:text-lg max-w-2xl mx-auto" style="color:rgba(255,255,255,0.45);">{{ $blogPage->hero_subtitle ?? 'Consejos, guías y datos respaldados por ciencia para cuidar tu visión en la era digital.' }}</p>
            <div style="width:48px;height:3px;background:#D9B56D;border-radius:2px;margin:24px auto 0;"></div>
        </div>
    </section>

    {{-- Filter bar --}}
    @if(!$posts->isEmpty() && $availableCategories->count() > 0)
    <div style="background:#0a0f1e;padding:0 24px 32px;">
        <div class="flex flex-wrap items-center justify-center" style="gap:10px;">
            <button class="blog-filter-btn active" data-filter="todos" type="button">
                Todos
                <span class="blog-filter-count">{{ $totalPostsOnPage }}</span>
            </button>
            @foreach($availableCategories as $cat)
                <button class="blog-filter-btn" data-filter="{{ $cat['key'] }}" type="button">
                    {{ $cat['label'] }}
                    <span class="blog-filter-count">{{ $cat['count'] }}</span>
                </button>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Posts grid --}}
    <section style="background:#FBF8F2;">
        <div class="blog-grid">
            @if($posts->isEmpty())
                <div class="text-center py-20" style="grid-column:1/-1;">
                    <svg class="mx-auto mb-4" width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <circle cx="16" cy="24" r="8" stroke="#94a3b8" stroke-width="2"/>
                        <circle cx="32" cy="24" r="8" stroke="#94a3b8" stroke-width="2"/>
                        <path d="M24 22v4" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 20c-2-3-3-6-2-8M40 20c2-3 3-6 2-8" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <h2 class="font-brand font-bold" style="font-size:20px;color:#1e293b;">Próximamente</h2>
                    <p class="mt-2" style="font-size:14px;color:#94a3b8;">Estamos preparando contenido para cuidar tu visión</p>
                    <a href="{{ route('products.index') }}"
                       class="inline-block mt-8 px-7 py-3 rounded-lg text-sm font-semibold transition-colors"
                       style="background:#2E2A26;color:#fff;">
                        Ver lentes nuvion
                    </a>
                </div>
            @else
                @foreach($posts as $post)
                    @php
                        $catKey = $post->category_key ?? 'salud-visual';
                        $catLabel = match($catKey) {
                            'lentes' => 'Lentes',
                            'habitos' => 'Hábitos digitales',
                            'luz-azul' => 'Luz azul',
                            default => 'Salud visual',
                        };

                        $gradients = [
                            'linear-gradient(135deg, #0f1b3d, #1a3a6e)',
                            'linear-gradient(135deg, #0d2137, #0f4c75)',
                            'linear-gradient(135deg, #1a0a2e, #2d1b69)',
                        ];
                        $grad = $gradients[$loop->index % 3];
                    @endphp

                    <article class="b-card"
                             data-cat="{{ $catKey }}"
                             onclick="location.href='{{ route('blog.show', $post->slug) }}'">

                        {{-- Image --}}
                        <div class="relative" style="height:180px;overflow:hidden;">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     alt="{{ $post->featured_image_alt ?? $post->title }}"
                                     style="width:100%;height:100%;object-fit:cover;"
                                     loading="lazy">
                            @else
                                <div class="flex items-center justify-center" style="width:100%;height:100%;background:{{ $grad }};">
                                    <span class="font-brand font-bold" style="font-size:72px;color:rgba(255,255,255,0.06);user-select:none;">{{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            @endif

                            {{-- Category badge --}}
                            <span class="absolute" style="top:12px;left:12px;background:rgba(56,130,221,0.85);color:#fff;font-size:10px;padding:4px 10px;border-radius:20px;font-weight:500;">{{ $catLabel }}</span>

                            {{-- Reading time --}}
                            @if($post->reading_time)
                            <span class="absolute flex items-center" style="bottom:12px;right:12px;gap:4px;">
                                <span style="width:4px;height:4px;border-radius:50%;background:#D9B56D;"></span>
                                <span style="font-size:11px;color:rgba(255,255,255,0.6);">{{ $post->reading_time }} min</span>
                            </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div style="padding:16px 18px 0;">
                            <div style="font-size:11px;color:#94a3b8;margin-bottom:8px;">
                                {{ $post->published_at?->format('d M Y') }}
                                @if($post->reading_time)
                                    <span style="margin:0 4px;">·</span>
                                    {{ $post->reading_time }} min lectura
                                @endif
                            </div>

                            <h2 class="font-brand" style="font-size:15px;font-weight:500;line-height:1.4;color:#0f172a;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $post->title }}
                            </h2>

                            @if($post->excerpt)
                                <p style="font-size:12px;color:#64748b;line-height:1.6;margin-bottom:14px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ $post->excerpt }}</p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div style="padding:0 18px 16px;">
                            <div class="flex items-center justify-between" style="border-top:0.5px solid rgba(0,0,0,0.08);padding-top:12px;">
                                <span class="inline-flex items-center" style="color:#D9B56D;font-size:12px;font-weight:500;">
                                    Leer artículo
                                    <svg class="card-arrow ml-1" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </span>
                                @if($post->focus_keyword)
                                    <span style="font-size:10px;padding:3px 8px;border-radius:20px;background:#f1f5f9;color:#64748b;">{{ $post->focus_keyword }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            @endif
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="blog-pagination" style="padding-bottom:48px;">
                {{ $posts->links() }}
            </div>
        @endif
    </section>

    {{-- CTA --}}
    <section class="py-12" style="background:#0a0f1e;">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-brand text-2xl md:text-3xl font-bold" style="color:#fff;">Protege tus ojos hoy</h2>
            <p class="mt-3" style="color:rgba(255,255,255,0.55);">Lentes con cosmética natural diseñados para tu día a día.</p>
            <a href="{{ route('products.index') }}"
               class="inline-block mt-6 px-8 py-3 rounded-lg font-semibold transition-colors"
               style="background:#D9B56D;color:#fff;">
                Ver lentes nuvion glass
            </a>
        </div>
    </section>

<script>
(function(){
    var btns = document.querySelectorAll('.blog-filter-btn');
    var cards = Array.prototype.slice.call(document.querySelectorAll('.b-card'));
    var grid = document.querySelector('.blog-grid');
    if (!btns.length || !grid) return;

    // Empty state que se muestra si la categoría no tiene resultados.
    var emptyState = document.createElement('div');
    emptyState.className = 'blog-empty-state';
    emptyState.style.display = 'none';
    emptyState.innerHTML = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="margin:0 auto 12px;display:block;"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>'
        + '<p style="font-weight:600;color:#1e293b;font-size:15px;margin:0 0 4px;">No hay artículos en esta categoría</p>'
        + '<p style="margin:0;">Prueba con otra o vuelve a "Todos".</p>';
    grid.appendChild(emptyState);

    function applyFilter(filter) {
        var visible = 0;
        cards.forEach(function(c){
            var matches = (filter === 'todos' || c.getAttribute('data-cat') === filter);
            c.classList.toggle('is-hidden', !matches);
            if (matches) visible++;
        });
        emptyState.style.display = visible === 0 ? 'block' : 'none';

        btns.forEach(function(b){
            b.classList.toggle('active', b.getAttribute('data-filter') === filter);
        });

        // Persistir en URL para que los enlaces compartidos abran la categoría correcta.
        if (filter === 'todos') {
            history.replaceState(null, '', window.location.pathname + window.location.search);
        } else {
            history.replaceState(null, '', '#cat=' + encodeURIComponent(filter));
        }
    }

    btns.forEach(function(btn){
        btn.addEventListener('click', function(){
            applyFilter(btn.getAttribute('data-filter'));
        });
    });

    // Aplicar el filtro del hash al cargar (si viene de un enlace compartido).
    var hashMatch = window.location.hash.match(/cat=([^&]+)/);
    if (hashMatch) {
        var initial = decodeURIComponent(hashMatch[1]);
        var validKeys = Array.prototype.map.call(btns, function(b){ return b.getAttribute('data-filter'); });
        if (validKeys.indexOf(initial) !== -1) {
            applyFilter(initial);
            // Scroll suave a la zona de filtros para que se vea aplicado.
            var filterBar = document.querySelector('.blog-filter-btn');
            if (filterBar && filterBar.scrollIntoView) {
                setTimeout(function(){
                    filterBar.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        }
    }

    // Intersection Observer for fade-in
    var observer = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){
                e.target.classList.add('animated');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    cards.forEach(function(c){ observer.observe(c); });
})();
</script>
@endsection
