<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\View\View;

class BrandController extends Controller
{
    /**
     * /marcas — listado de todas las marcas activas.
     */
    public function index(): View
    {
        $brands = Brand::active()->ordered()->withCount('activeProducts')->get();

        $seo = [
            'title'       => 'Marcas que distribuimos | Belleza Áurea',
            'description' => 'Descubre todas las marcas premium de cosmética, skincare y rituales que distribuimos en Belleza Áurea.',
            'canonical'   => url('/marcas'),
        ];

        return view('storefront.brands.index', compact('brands', 'seo'));
    }

    /**
     * /marcas/{slug} — página de una marca con sus productos.
     */
    public function show(string $slug): View
    {
        $brand = Brand::active()->where('slug', $slug)->firstOrFail();

        $products = Product::active()->where('brand_id', $brand->id)
            ->with('category')
            ->orderBy('sort_order')
            ->paginate(24);

        $seo = [
            'title'       => $brand->meta_title ?: ($brand->name.' | Belleza Áurea'),
            'description' => $brand->meta_description
                ?: ($brand->short_description ?: 'Conoce todos los productos de '.$brand->name.' disponibles en Belleza Áurea.'),
            'canonical'   => route('brands.show', $brand->slug),
        ];

        // Schema.org Brand — chr(64) evita que Blade procese @context/@type como directivas
        $K_CTX = chr(64).'context';
        $K_TYP = chr(64).'type';
        $brandSchema = json_encode([
            $K_CTX => 'https://schema.org',
            $K_TYP => 'Brand',
            'name' => $brand->name,
            'url'  => route('brands.show', $brand->slug),
            'logo' => $brand->logo_url,
            'description' => $brand->short_description,
            'sameAs' => array_values(array_filter([$brand->website_url])),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('storefront.brands.show', compact('brand', 'products', 'seo', 'brandSchema'));
    }
}
