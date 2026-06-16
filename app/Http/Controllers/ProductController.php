<?php

namespace App\Http\Controllers;

use App\Helpers\ColorHelper;
use App\Models\Category;
use App\Models\LentesPageSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private SeoService $seo,
    ) {}

    public function index(Request $request): View
    {
        // ── Filtros de belleza ──
        $catFiltro    = $request->input('category', '');   // slug categoría
        $brandFiltro  = $request->input('brand', '');      // slug marca
        $priceFiltro  = $request->input('price', '');      // ej: '0-10000', '10000-25000', etc.
        $sortFiltro   = $request->input('sort', 'relevant'); // relevant|price-asc|price-desc|new|az

        $query = Product::active()->with(['variants', 'category', 'brand'])
            ->where(function ($q) {
                $q->where('stock', '>', 0)
                  ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
            });

        if ($catFiltro) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $catFiltro));
        }

        if ($brandFiltro) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $brandFiltro));
        }

        if ($priceFiltro && preg_match('/^(\d+)-(\d+|max)$/', $priceFiltro, $m)) {
            $query->where('price', '>=', (int) $m[1]);
            if ($m[2] !== 'max') {
                $query->where('price', '<=', (int) $m[2]);
            }
        }

        // ── Ordenamiento ──
        // Base: con imagen primero siempre (sin foto al final)
        $query->orderByRaw('CASE WHEN images IS NOT NULL AND JSON_LENGTH(images) > 0 THEN 0 ELSE 1 END');

        match ($sortFiltro) {
            'price-asc'  => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            'new'        => $query->orderByDesc('created_at'),
            'az'         => $query->orderBy('name', 'asc'),
            default      => $query->orderByDesc('is_featured')->orderBy('sort_order')->orderByDesc('created_at'),
        };

        $products = $query->get()->filter(fn ($p) => $p->hasStock())->values();

        // ── Datos para los filtros ──
        // Categorías con conteo de productos activos con stock
        $categoriasFiltro = Category::orderBy('sort_order')->orderBy('name')->get()
            ->map(function ($c) {
                $count = Product::active()
                    ->where('category_id', $c->id)
                    ->where(function ($q) {
                        $q->where('stock', '>', 0)
                          ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
                    })->count();
                $c->products_count = $count;
                return $c;
            })
            ->filter(fn ($c) => $c->products_count > 0)
            ->values();

        // Marcas con conteo (solo si hay productos con marca)
        $marcasFiltro = \App\Models\Brand::orderBy('name')->get()
            ->map(function ($b) {
                $count = Product::active()
                    ->where('brand_id', $b->id)
                    ->where(function ($q) {
                        $q->where('stock', '>', 0)
                          ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
                    })->count();
                $b->products_count = $count;
                return $b;
            })
            ->filter(fn ($b) => $b->products_count > 0)
            ->values();

        // Rangos de precio (configurados pensando en el catálogo de belleza CO)
        $rangosPrecios = [
            '0-10000'        => 'Hasta $10.000',
            '10000-25000'    => '$10.000 – $25.000',
            '25000-50000'    => '$25.000 – $50.000',
            '50000-100000'   => '$50.000 – $100.000',
            '100000-max'     => 'Más de $100.000',
        ];

        $opcionesOrden = [
            'relevant'   => 'Más relevantes',
            'price-asc'  => 'Precio: menor a mayor',
            'price-desc' => 'Precio: mayor a menor',
            'new'        => 'Más recientes',
            'az'         => 'Nombre A → Z',
        ];

        $breadcrumbs = $this->seo->breadcrumbSchema([
            ['name' => 'Inicio',    'url' => url('/')],
            ['name' => 'Productos', 'url' => route('products.index')],
        ]);

        return view('storefront.products.index', [
            'products'         => $products,
            'categoriasFiltro' => $categoriasFiltro,
            'marcasFiltro'     => $marcasFiltro,
            'rangosPrecios'    => $rangosPrecios,
            'opcionesOrden'    => $opcionesOrden,
            'catFiltro'        => $catFiltro,
            'brandFiltro'      => $brandFiltro,
            'priceFiltro'      => $priceFiltro,
            'sortFiltro'       => $sortFiltro,
            'breadcrumbs'      => $breadcrumbs,
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with('variants')
            ->firstOrFail();

        $activeVariants = $product->variants->where('is_active', true);

        // Unique colors (variantes con color_hex o option_type=color)
        $colores = $activeVariants
            ->filter(fn ($v) => $v->option_type === 'color' || ! empty($v->color_hex))
            ->pluck('color')->unique()->filter()->values();

        // Variantes genéricas (no color, no graduación) agrupadas por etiqueta visible.
        // Estructura:
        //   ['Tamaño' => collection<variant>, 'Acabado' => collection<variant>, ...]
        $genericVariants = $activeVariants
            ->filter(fn ($v) => $v->option_type !== 'color' && empty($v->graduation_type))
            ->groupBy(fn ($v) => $v->name ?: \App\Models\ProductVariant::DEFAULT_LABELS[$v->option_type] ?? 'Opción');

        // Graduations grouped by type
        $graduacionesMiopia = $activeVariants
            ->where('graduation_type', 'miopia')
            ->pluck('graduation')->unique()->filter()
            ->sortBy(fn ($g) => (float) $g)->values();

        $graduacionesLectura = $activeVariants
            ->where('graduation_type', 'lectura')
            ->pluck('graduation')->unique()->filter()
            ->sortBy(fn ($g) => (float) $g)->values();

        $graduacionesSinGrad = $activeVariants
            ->where('graduation_type', 'sin_graduacion')
            ->pluck('graduation')->unique()->filter()->values();

        // Toallitas for suggestion
        $toallitas = Product::active()
            ->whereJsonContains('type', 'toallitas')
            ->with('variants')
            ->get()
            ->filter(fn ($p) => $p->hasStock())
            ->values();

        $seo = $this->seo->forProduct($product);
        $schema = $this->seo->productSchema($product);
        $howToSchema = $this->seo->howToSchema($product);
        $breadcrumbs = $this->seo->breadcrumbSchema([
            ['name' => 'Inicio',    'url' => url('/')],
            ['name' => 'Productos', 'url' => route('products.index')],
            ['name' => $product->name, 'url' => route('products.show', $product->slug)],
        ]);

        $lentesPage = LentesPageSetting::getCurrent();

        return view('storefront.products.show', compact(
            'product', 'colores', 'genericVariants',
            'graduacionesMiopia', 'graduacionesLectura', 'graduacionesSinGrad',
            'toallitas', 'seo', 'schema', 'howToSchema', 'breadcrumbs', 'lentesPage',
        ));
    }
}
