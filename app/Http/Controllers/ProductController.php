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
        $tipoFiltro = $request->input('type', 'todos');
        $colorFiltro = $request->input('color');
        $graduacionFiltro = $request->input('graduation');

        // Allow comma-separated types (e.g. "miopia,lectura" → match either).
        $tipos = is_string($tipoFiltro)
            ? array_values(array_filter(array_map('trim', explode(',', $tipoFiltro))))
            : [];
        $tiposEfectivos = array_values(array_filter($tipos, fn ($t) => $t !== '' && $t !== 'todos'));

        $query = Product::active()->with('variants')->orderBy('sort_order')
            ->where(function ($q) {
                $q->where('stock', '>', 0)
                  ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
            });

        if (!empty($tiposEfectivos)) {
            $query->where(function ($q) use ($tiposEfectivos) {
                foreach ($tiposEfectivos as $t) {
                    $q->orWhereJsonContains('type', $t);
                }
            });
        }

        if ($colorFiltro) {
            $query->whereHas('variants', fn ($q) => $q->where('color', $colorFiltro)->where('is_active', true)->where('stock', '>', 0));
        }

        if ($graduacionFiltro) {
            $query->whereHas('variants', fn ($q) => $q->where('graduation', $graduacionFiltro)->where('is_active', true)->where('stock', '>', 0));
        }

        $products = $query->get()->filter(fn ($p) => $p->hasStock())->values();

        // Available colors (only from variants with stock)
        $variantsWithStock = ProductVariant::whereHas('product', fn ($q) => $q->active())
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('color')
            ->get();

        $coloresDisponibles = $variantsWithStock->pluck('color')->unique()->filter()->sort()->values();

        // Map of color_name => color_hex (first non-empty, non-default-black hex wins)
        $colorHexMap = $variantsWithStock
            ->filter(function ($v) {
                if (! $v->color_hex) {
                    return false;
                }
                // Treat the HTML color-picker default (#000000) as "not set" unless the color is "Negro".
                $isBlackDefault = strtolower($v->color_hex) === '#000000'
                    && stripos($v->color, 'negro') === false;
                return ! $isBlackDefault;
            })
            ->groupBy('color')
            ->map(fn ($g) => $g->first()->color_hex)
            ->toArray();

        // Available graduations sorted numerically (only variants with stock)
        $graduacionesDisponibles = ProductVariant::whereHas('product', fn ($q) => $q->active()->where(fn ($qq) => $qq->whereJsonContains('type', 'miopia')->orWhereJsonContains('type', 'lectura')->orWhereJsonContains('type', 'sin_graduacion')))
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('graduation')
            ->pluck('graduation')
            ->unique()
            ->filter()
            ->sortBy(fn ($g) => (float) $g)
            ->values();

        // Toallitas always available separately
        $toallitas = Product::active()
            ->whereJsonContains('type', 'toallitas')
            ->with('variants')
            ->get()
            ->filter(fn ($p) => $p->hasStock())
            ->values();

        $breadcrumbs = $this->seo->breadcrumbSchema([
            ['name' => 'Inicio', 'url' => url('/')],
            ['name' => 'Lentes', 'url' => route('products.index')],
        ]);

        $lentesPage = LentesPageSetting::getCurrent();

        return view('storefront.products.index', [
            'products' => $products,
            'toallitas' => $toallitas,
            'coloresDisponibles' => $coloresDisponibles,
            'colorHexMap' => $colorHexMap,
            'graduacionesDisponibles' => $graduacionesDisponibles,
            'tipoFiltro' => $tipoFiltro,
            'colorFiltro' => $colorFiltro,
            'graduacionFiltro' => $graduacionFiltro,
            'breadcrumbs' => $breadcrumbs,
            'colorHelper' => ColorHelper::all(),
            'lentesPage' => $lentesPage,
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with('variants')
            ->firstOrFail();

        $activeVariants = $product->variants->where('is_active', true);

        // Unique colors
        $colores = $activeVariants->pluck('color')->unique()->filter()->values();

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
        $breadcrumbs = $this->seo->breadcrumbSchema([
            ['name' => 'Inicio', 'url' => url('/')],
            ['name' => 'Lentes', 'url' => route('products.index')],
            ['name' => $product->name, 'url' => route('products.show', $product->slug)],
        ]);

        $lentesPage = LentesPageSetting::getCurrent();

        return view('storefront.products.show', compact(
            'product', 'colores',
            'graduacionesMiopia', 'graduacionesLectura', 'graduacionesSinGrad',
            'toallitas', 'seo', 'schema', 'breadcrumbs', 'lentesPage',
        ));
    }
}
