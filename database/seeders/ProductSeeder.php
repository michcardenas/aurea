<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

/**
 * Belleza Áurea — Seeder de productos demo.
 *
 * Mapeo de `type` heredado del esqueleto:
 *   sin_graduacion → productos principales (skincare + fragancias)
 *   toallitas      → sets / rituales (mostrados como "accesorios" en home)
 *
 * Mapeo de internal_code:
 *   'WUHAO' / 'YT2212' los usa StorefrontController::home() para elegir el
 *   producto destacado del hero split — los conservo en los dos primeros.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catSkincare   = Category::updateOrCreate(['slug' => 'skincare'], ['name' => 'Skincare',   'sort_order' => 1]);
        $catFragancias = Category::updateOrCreate(['slug' => 'fragancias'], ['name' => 'Fragancias', 'sort_order' => 2]);
        $catRituales   = Category::updateOrCreate(['slug' => 'rituales'],   ['name' => 'Rituales',   'sort_order' => 3]);

        // ─── 1. Sérum Áureo (skincare, flagship) ───
        $serum = Product::updateOrCreate(['internal_code' => 'WUHAO'], [
            'category_id' => $catSkincare->id,
            'name' => 'Sérum Áureo',
            'slug' => 'serum-aureo',
            'type' => ['sin_graduacion'],
            'description' => 'Sérum facial con vitamina C estabilizada y aceite de rosa mosqueta. Ilumina, unifica el tono y suaviza líneas finas en 28 días. Textura ligera de rápida absorción, apto para todo tipo de piel.',
            'price' => 580.00,
            'compare_price' => 720.00,
            'stock' => 120,
            'images' => [],
            'meta_title' => 'Sérum Áureo — Vitamina C + Rosa Mosqueta | Belleza Áurea',
            'meta_description' => 'Sérum facial iluminador con vitamina C y rosa mosqueta. Unifica el tono y reduce líneas finas. Belleza natural, elegante y atemporal.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => false,
            'sort_order' => 1,
        ]);
        $this->seedFormatVariants($serum, ['30 ml' => 0, '50 ml' => 180.00]);

        // ─── 2. Crema Hidratante Botánica ───
        $crema = Product::updateOrCreate(['internal_code' => 'YT2212'], [
            'category_id' => $catSkincare->id,
            'name' => 'Crema Hidratante Botánica',
            'slug' => 'crema-hidratante-botanica',
            'type' => ['sin_graduacion'],
            'description' => 'Crema facial hidratante con manteca de karité, extracto de salvia y ácido hialurónico. Sella la humedad por 24 horas dejando la piel suave, calmada y luminosa.',
            'price' => 420.00,
            'compare_price' => 520.00,
            'stock' => 150,
            'images' => [],
            'meta_title' => 'Crema Hidratante Botánica — Karité + Salvia | Belleza Áurea',
            'meta_description' => 'Crema facial hidratante 24h con karité, salvia y ácido hialurónico. Suaviza y nutre todo tipo de piel.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => false,
            'sort_order' => 2,
        ]);
        $this->seedFormatVariants($crema, ['50 ml' => 0, '100 ml' => 160.00]);

        // ─── 3. Tónico Floral Reequilibrante ───
        $tonico = Product::updateOrCreate(['internal_code' => 'BA-TON-01'], [
            'category_id' => $catSkincare->id,
            'name' => 'Tónico Floral Reequilibrante',
            'slug' => 'tonico-floral-reequilibrante',
            'type' => ['sin_graduacion'],
            'description' => 'Tónico facial sin alcohol con agua de rosas, azahar y niacinamida. Reequilibra el pH, minimiza poros y prepara la piel para los pasos siguientes de tu ritual.',
            'price' => 380.00,
            'compare_price' => null,
            'stock' => 200,
            'images' => [],
            'meta_title' => 'Tónico Floral — Agua de Rosas y Niacinamida | Belleza Áurea',
            'meta_description' => 'Tónico facial sin alcohol con agua de rosas, azahar y niacinamida. Reequilibra y refresca.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => false,
            'sort_order' => 3,
        ]);
        $this->seedFormatVariants($tonico, ['200 ml' => 0]);

        // ─── 4. Mascarilla Nocturna de Oro ───
        $mascarilla = Product::updateOrCreate(['internal_code' => 'BA-MASK-01'], [
            'category_id' => $catSkincare->id,
            'name' => 'Mascarilla Nocturna de Oro',
            'slug' => 'mascarilla-nocturna-de-oro',
            'type' => ['sin_graduacion'],
            'description' => 'Mascarilla intensiva nocturna con micro-partículas de oro 24k, retinol vegetal y manzanilla. Regenera durante el sueño para despertar con piel luminosa, descansada y firme.',
            'price' => 520.00,
            'compare_price' => 640.00,
            'stock' => 90,
            'images' => [],
            'meta_title' => 'Mascarilla Nocturna de Oro — Oro 24k + Retinol Vegetal | Belleza Áurea',
            'meta_description' => 'Mascarilla nocturna con oro 24k, retinol vegetal y manzanilla. Regenera y aporta luminosidad.',
            'is_active' => true,
            'is_featured' => false,
            'badge_2x1' => false,
            'sort_order' => 4,
        ]);
        $this->seedFormatVariants($mascarilla, ['50 ml' => 0]);

        // ─── 5. Aceite Esencial de Rosa ───
        $aceite = Product::updateOrCreate(['internal_code' => 'BA-OIL-01'], [
            'category_id' => $catSkincare->id,
            'name' => 'Aceite Esencial de Rosa',
            'slug' => 'aceite-esencial-de-rosa',
            'type' => ['sin_graduacion'],
            'description' => 'Aceite facial puro de pétalos de rosa damascena prensados en frío. Restaura elasticidad, suaviza marcas y aporta un perfume sutil y atemporal.',
            'price' => 450.00,
            'compare_price' => null,
            'stock' => 110,
            'images' => [],
            'meta_title' => 'Aceite Esencial de Rosa — Damascena Prensada en Frío | Belleza Áurea',
            'meta_description' => 'Aceite facial puro de rosa damascena. Restaura elasticidad y suaviza la piel con aroma floral.',
            'is_active' => true,
            'is_featured' => false,
            'badge_2x1' => false,
            'sort_order' => 5,
        ]);
        $this->seedFormatVariants($aceite, ['30 ml' => 0, '50 ml' => 140.00]);

        // ─── 6. Eau de Parfum Áurea (fragancia) ───
        $perfume = Product::updateOrCreate(['internal_code' => 'BA-EDP-01'], [
            'category_id' => $catFragancias->id,
            'name' => 'Eau de Parfum Áurea',
            'slug' => 'eau-de-parfum-aurea',
            'type' => ['sin_graduacion'],
            'description' => 'Eau de Parfum de inspiración mediterránea. Salida cítrica (bergamota, mandarina), corazón floral (rosa, jazmín) y fondo cálido (vainilla, ámbar dorado). Estela elegante de larga duración.',
            'price' => 890.00,
            'compare_price' => 1080.00,
            'stock' => 75,
            'images' => [],
            'meta_title' => 'Eau de Parfum Áurea — Bergamota, Rosa y Ámbar | Belleza Áurea',
            'meta_description' => 'Perfume Áurea: cítrico floral con fondo de ámbar dorado. Larga duración, elegancia atemporal.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => false,
            'sort_order' => 6,
        ]);
        $this->seedFormatVariants($perfume, ['50 ml' => 0, '100 ml' => 280.00]);

        // ─── 7. Ritual Esencial (set / "toallitas" slot) ───
        $ritualEsencial = Product::updateOrCreate(['internal_code' => 'BA-SET-ESE'], [
            'category_id' => $catRituales->id,
            'name' => 'Ritual Esencial — Set de 3 piezas',
            'slug' => 'ritual-esencial-set',
            'type' => ['toallitas'],
            'description' => 'Tres pasos para tu ritual diario: Tónico Floral 200 ml + Crema Hidratante Botánica 50 ml + Sérum Áureo 30 ml. Presentación en estuche reutilizable.',
            'price' => 1200.00,
            'compare_price' => 1380.00,
            'stock' => 60,
            'images' => [],
            'meta_title' => 'Ritual Esencial — Set de 3 piezas | Belleza Áurea',
            'meta_description' => 'Set de 3 piezas para tu ritual diario: tónico, crema y sérum. Ahorra y disfruta tu rutina completa.',
            'is_active' => true,
            'is_featured' => false,
            'badge_2x1' => false,
            'sort_order' => 7,
        ]);
        $this->seedDefaultVariant($ritualEsencial);

        // ─── 8. Ritual Glow (set premium) ───
        $ritualGlow = Product::updateOrCreate(['internal_code' => 'BA-SET-GLOW'], [
            'category_id' => $catRituales->id,
            'name' => 'Ritual Glow — Set Premium de 5 piezas',
            'slug' => 'ritual-glow-set-premium',
            'type' => ['toallitas'],
            'description' => 'Ritual completo: Tónico, Crema, Sérum, Mascarilla Nocturna de Oro y Aceite Esencial de Rosa. Para una piel iluminada en 30 días. Empacado en caja regalo dorada.',
            'price' => 1800.00,
            'compare_price' => 2240.00,
            'stock' => 40,
            'images' => [],
            'meta_title' => 'Ritual Glow — Set Premium 5 piezas | Belleza Áurea',
            'meta_description' => 'Set premium de 5 piezas para piel iluminada en 30 días. Caja regalo dorada.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => false,
            'sort_order' => 8,
        ]);
        $this->seedDefaultVariant($ritualGlow);
    }

    /**
     * Crea variantes por formato (tamaño) con modificador de precio.
     *
     * @param  array<string,float>  $formats  ['50 ml' => 0, '100 ml' => 160.00]
     */
    private function seedFormatVariants(Product $product, array $formats): void
    {
        $i = 0;
        foreach ($formats as $size => $modifier) {
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'color' => $size,
                    'graduation' => '+0',
                    'graduation_type' => 'sin_graduacion',
                ],
                [
                    'name' => 'Formato',
                    'value' => $size,
                    'price_modifier' => $modifier,
                    'stock' => 50,
                    'is_active' => true,
                ],
            );
            $i++;
        }
    }

    private function seedDefaultVariant(Product $product): void
    {
        ProductVariant::updateOrCreate(
            [
                'product_id' => $product->id,
                'color' => null,
                'graduation' => null,
                'graduation_type' => null,
            ],
            [
                'name' => 'Default',
                'value' => 'Estándar',
                'price_modifier' => 0,
                'stock' => 40,
                'is_active' => true,
            ],
        );
    }
}
