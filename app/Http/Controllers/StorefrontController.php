<?php

namespace App\Http\Controllers;

use App\Helpers\ColorHelper;
use App\Models\BlogPost;
use App\Models\BlueLightPageSetting;
use App\Models\ContactPageSetting;
use App\Models\HeroSetting;
use App\Models\HomePageSetting;
use App\Models\ShippingReturnsPageSetting;
use App\Models\InfographicImage;
use App\Models\SeoSetting;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\SeoService;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function __construct(
        private SeoService $seo,
    ) {}

    public function home(): View
    {
        // Productos principales del home: featured primero, luego sort_order,
        // luego más recientes. Garantiza stock. Cargamos variantes + categoría
        // + brand para las cards.
        // Filtro de imagen: solo productos con al menos 1 imagen (campo images
        // es JSON array). En el home solo se ven productos "presentables".
        $lentes = Product::active()
            ->where(fn ($q) => $q->whereJsonContains('type', 'miopia')
                ->orWhereJsonContains('type', 'lectura')
                ->orWhereJsonContains('type', 'sin_graduacion'))
            ->where(function ($q) {
                $q->where('stock', '>', 0)
                  ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
            })
            ->whereNotNull('images')
            ->whereRaw('JSON_LENGTH(images) > 0')
            ->with(['variants', 'category', 'brand'])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($p) => $p->hasStock())
            ->values()
            ->take(8);

        $toallitas = Product::active()
            ->whereJsonContains('type', 'toallitas')
            ->where(function ($q) {
                $q->where('stock', '>', 0)
                  ->orWhereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0));
            })
            ->whereNotNull('images')
            ->whereRaw('JSON_LENGTH(images) > 0')
            ->with('variants')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn ($p) => $p->hasStock())
            ->values();

        $coloresDisponibles = ProductVariant::where('is_active', true)
            ->where('stock', '>', 0)
            ->whereNotNull('color')
            ->distinct()
            ->pluck('color')
            ->sort()
            ->values();

        $recentPosts = BlogPost::published()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $infographics = InfographicImage::active()
            ->orderBy('sort_order')
            ->get();

        $organizationSchema = $this->seo->organizationSchema();

        $hero = HeroSetting::getCurrent();
        $homePage = HomePageSetting::getCurrent();
        $seoSettings = SeoSetting::getForPage('home');

        $faqSchema = $this->seo->faqSchema(
            collect($homePage->faqs ?? [])->map(fn ($faq) => [
                'question' => $faq['q'] ?? '',
                'answer' => $faq['a'] ?? '',
            ])->all()
        );

        // Producto estrella (cascade):
        //   1. El elegido en HomePageSetting->star_product_id (admin lo configura)
        //   2. Primer producto activo, con stock y con imagen
        //   3. Fallback final: cualquier activo
        $heroProduct = null;
        if ($homePage->star_product_id) {
            $heroProduct = Product::active()
                ->where('id', $homePage->star_product_id)
                ->with('variants')
                ->first();
        }
        if (! $heroProduct) {
            $heroProduct = Product::active()
                ->where('stock', '>', 0)
                ->whereNotNull('images')
                ->whereRaw('JSON_LENGTH(images) > 0')
                ->orderByDesc('is_featured')
                ->with('variants')
                ->first()
                ?? Product::active()->first();
        }

        // Determinar modo del hero
        $heroMode = 'split';
        if ($hero && $hero->media_type === 'video'
            && $hero->media_path
            && \Storage::disk('public')->exists($hero->media_path)) {
            $heroMode = 'video';
        }

        // Categorías para el home: ordenadas por sort_order (editable en admin),
        // con conteo de productos activos. Limit 8 para el grid del home.
        $categories = Category::withCount(['products' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(8)
            ->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();
        $featuredBrands = \App\Models\Brand::active()->featured()->ordered()->get();

        return view('storefront.home', compact(
            'hero', 'heroProduct', 'heroMode', 'homePage', 'seoSettings',
            'lentes', 'toallitas', 'coloresDisponibles',
            'recentPosts', 'infographics', 'organizationSchema', 'faqSchema',
            'categories', 'testimonials', 'featuredBrands',
        ));
    }

    public function blueLight(): View
    {
        $blueLightPage = BlueLightPageSetting::getCurrent();

        $faqItems = collect($blueLightPage->faqs ?? [])->map(fn ($faq) => [
            'question' => $faq['q'] ?? '',
            'answer'   => $faq['a'] ?? '',
        ])->all();

        if (empty($faqItems)) {
            $faqItems = [
                [
                    'question' => '¿Qué es la luz azul?',
                    'answer' => 'La luz azul es una porción del espectro de luz visible con longitud de onda entre 380 y 500 nanómetros. Es emitida por el sol, pantallas digitales, LEDs y luces fluorescentes.',
                ],
                [
                    'question' => '¿Por qué es dañina la luz azul?',
                    'answer' => 'La exposición prolongada puede causar fatiga visual digital, dolores de cabeza, ojos secos y alteraciones en el ciclo circadiano que afectan la calidad del sueño.',
                ],
                [
                    'question' => '¿Quién debería usar lentes con protección de luz azul?',
                    'answer' => 'Cualquier persona que pase más de 4 horas diarias frente a pantallas: trabajadores de oficina, gamers, estudiantes y usuarios frecuentes de dispositivos móviles.',
                ],
            ];
        }

        $faqSchema = $this->seo->faqSchema($faqItems);

        $breadcrumbSchema = $this->seo->breadcrumbSchema([
            ['name' => 'Inicio', 'url' => url('/')],
            ['name' => '¿Qué es la luz azul?', 'url' => route('blue-light')],
        ]);

        $seoSettings = SeoSetting::getForPage('blue-light');

        return view('storefront.pages.que-es-luz-azul', compact('faqSchema', 'breadcrumbSchema', 'blueLightPage', 'seoSettings'));
    }

    public function contact(): View
    {
        $contactPage = ContactPageSetting::getCurrent();
        $seoSettings = SeoSetting::getForPage('contact');

        return view('storefront.pages.contacto', compact('contactPage', 'seoSettings'));
    }

    public function shippingReturns(): View
    {
        $page = ShippingReturnsPageSetting::getCurrent();
        $seoSettings = SeoSetting::getForPage('shipping-returns');

        return view('storefront.pages.envios-y-devoluciones', compact('page', 'seoSettings'));
    }
}
