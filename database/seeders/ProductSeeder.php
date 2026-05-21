<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure categories exist
        $catSinGrad = Category::updateOrCreate(
            ['slug' => 'sin-graduacion'],
            ['name' => 'Sin Graduación', 'sort_order' => 1],
        );
        $catLectura = Category::updateOrCreate(
            ['slug' => 'lectura'],
            ['name' => 'Lectura', 'sort_order' => 2],
        );
        $catMiopia = Category::updateOrCreate(
            ['slug' => 'miopia'],
            ['name' => 'Miopía', 'sort_order' => 3],
        );
        $catToallitas = Category::updateOrCreate(
            ['slug' => 'toallitas'],
            ['name' => 'Toallitas', 'sort_order' => 4],
        );

        // ─── 1. Cosmos (Sin Graduación) ───
        $cosmos = Product::updateOrCreate(['internal_code' => 'WUHAO'], [
            'category_id' => $catSinGrad->id,
            'name' => 'Cosmos',
            'slug' => 'cosmos',
            'type' => 'sin_graduacion',
            'description' => 'Lentes sin graduación con filtro de luz azul. Diseño versátil disponible en 7 colores. Ideal para uso diario frente a pantallas.',
            'price' => 499.90,
            'compare_price' => 899.00,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Cosmos — Lentes Sin Graduación con Filtro de Luz Azul',
            'meta_description' => 'Lentes Cosmos con filtro de luz azul. Sin graduación, 7 colores disponibles. Protege tus ojos de las pantallas.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => true,
            'sort_order' => 1,
        ]);
        $this->seedSimpleVariants($cosmos, ['Azul', 'Blanco', 'Café', 'Gris', 'Negro', 'Rosa', 'Verde'], 'sin_graduacion');

        // ─── 2. Nebula (Sin Graduación) ───
        $nebula = Product::updateOrCreate(['internal_code' => 'YT2212'], [
            'category_id' => $catSinGrad->id,
            'name' => 'Nebula',
            'slug' => 'nebula',
            'type' => 'sin_graduacion',
            'description' => 'Lentes sin graduación con filtro de luz azul. Estilo contemporáneo en 6 colores únicos.',
            'price' => 499.90,
            'compare_price' => 899.00,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Nebula — Lentes Sin Graduación con Filtro de Luz Azul',
            'meta_description' => 'Lentes Nebula con filtro de luz azul. Sin graduación, 6 colores disponibles. Estilo contemporáneo.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => true,
            'sort_order' => 2,
        ]);
        $this->seedSimpleVariants($nebula, ['Arena Oscuro', 'Cristal', 'Gris', 'Morado', 'Negro', 'Verde'], 'sin_graduacion');

        // ─── 3. Mercury (Lectura) ───
        $mercury = Product::updateOrCreate(['internal_code' => '8026'], [
            'category_id' => $catLectura->id,
            'name' => 'Mercury',
            'slug' => 'mercury',
            'type' => 'lectura',
            'description' => 'Lentes de lectura con filtro de luz azul. Disponibles en 7 graduaciones y 4 colores.',
            'price' => 499.90,
            'compare_price' => 899.00,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Mercury — Lentes de Lectura con Filtro de Luz Azul',
            'meta_description' => 'Lentes Mercury de lectura con filtro de luz azul. 7 graduaciones, 4 colores. Protección y claridad.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => true,
            'sort_order' => 3,
        ]);
        $this->seedGraduatedVariants($mercury, ['Amarillo', 'Blanco', 'Jaspe', 'Rosa'], 'lectura');

        // ─── 4. Nova (Lectura) ───
        $nova = Product::updateOrCreate(['internal_code' => 'TY564'], [
            'category_id' => $catLectura->id,
            'name' => 'Nova',
            'slug' => 'nova',
            'type' => 'lectura',
            'description' => 'Lentes de lectura con filtro de luz azul. Diseño moderno en 3 colores y 7 graduaciones.',
            'price' => 499.90,
            'compare_price' => 899.00,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Nova — Lentes de Lectura con Filtro de Luz Azul',
            'meta_description' => 'Lentes Nova de lectura con filtro de luz azul. 7 graduaciones, 3 colores. Diseño moderno.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => true,
            'sort_order' => 4,
        ]);
        $this->seedGraduatedVariants($nova, ['Negro', 'Rosa', 'Verde'], 'lectura');

        // ─── 5. Orion (Lectura) ───
        $orion = Product::updateOrCreate(['internal_code' => '158'], [
            'category_id' => $catLectura->id,
            'name' => 'Orion',
            'slug' => 'orion',
            'type' => 'lectura',
            'description' => 'Lentes de lectura con filtro de luz azul. Clásico atemporal disponible en negro.',
            'price' => 499.90,
            'compare_price' => 899.00,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Orion — Lentes de Lectura con Filtro de Luz Azul',
            'meta_description' => 'Lentes Orion de lectura con filtro de luz azul. Clásico atemporal en negro.',
            'is_active' => true,
            'is_featured' => false,
            'badge_2x1' => true,
            'sort_order' => 5,
        ]);
        $this->seedGraduatedVariants($orion, ['Negro'], 'lectura');

        // ─── 6. Titan (Miopía + Lectura) ───
        $titan = Product::updateOrCreate(['internal_code' => '8019'], [
            'category_id' => $catMiopia->id,
            'name' => 'Titan',
            'slug' => 'titan',
            'type' => 'miopia',
            'description' => 'El único modelo con graduación para miopía Y lectura. Filtro de luz azul integrado. Disponible en 8 colores y 14 graduaciones.',
            'price' => 499.90,
            'compare_price' => 899.00,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Titan — Lentes Miopía y Lectura con Filtro de Luz Azul',
            'meta_description' => 'Lentes Titan con graduación para miopía y lectura. Filtro de luz azul, 8 colores, 14 graduaciones.',
            'is_active' => true,
            'is_featured' => true,
            'badge_2x1' => true,
            'sort_order' => 6,
        ]);
        $titanColors = ['Azul', 'Café', 'Gris', 'Morado', 'Negro', 'Rojo', 'Rosa'];
        $this->seedGraduatedVariants($titan, $titanColors, 'miopia');
        $this->seedGraduatedVariants($titan, $titanColors, 'lectura');

        // ─── 7. Toallitas 25 piezas ───
        $toallitas25 = Product::updateOrCreate(['internal_code' => 'TOALLITAS-25'], [
            'category_id' => $catToallitas->id,
            'name' => 'Toallitas Limpiadoras 2en1 — 25 piezas',
            'slug' => 'toallitas-limpiadoras-25',
            'type' => 'toallitas',
            'description' => 'Kit limpiador 2 en 1: paño húmedo con fórmula sin alcohol + paño seco. Resultados inmediatos. Sirve para lentes, pantallas, cámaras y tablets. 25 piezas individuales.',
            'price' => 99.90,
            'compare_price' => null,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Toallitas Limpiadoras 2en1 — 25 piezas',
            'meta_description' => 'Kit limpiador 2 en 1 para lentes y pantallas. 25 piezas individuales. Fórmula sin alcohol.',
            'is_active' => true,
            'is_featured' => false,
            'badge_2x1' => false,
            'sort_order' => 7,
        ]);
        ProductVariant::updateOrCreate(
            ['product_id' => $toallitas25->id, 'color' => null, 'graduation' => null, 'graduation_type' => null],
            ['name' => 'Default', 'value' => 'Estándar', 'price_modifier' => 0, 'stock' => 999, 'is_active' => true],
        );

        // ─── 8. Toallitas 60 piezas ───
        $toallitas60 = Product::updateOrCreate(['internal_code' => 'TOALLITAS-60'], [
            'category_id' => $catToallitas->id,
            'name' => 'Toallitas Limpiadoras 2en1 — 60 piezas',
            'slug' => 'toallitas-limpiadoras-60',
            'type' => 'toallitas',
            'description' => 'Kit limpiador 2 en 1: paño húmedo con fórmula sin alcohol + paño seco. Pack ahorro con 60 piezas individuales. Ideal para toda la familia.',
            'price' => 199.90,
            'compare_price' => null,
            'stock' => 999,
            'images' => [],
            'meta_title' => 'Toallitas Limpiadoras 2en1 — 60 piezas',
            'meta_description' => 'Kit limpiador 2 en 1 para lentes y pantallas. Pack ahorro 60 piezas. Fórmula sin alcohol.',
            'is_active' => true,
            'is_featured' => false,
            'badge_2x1' => false,
            'sort_order' => 8,
        ]);
        ProductVariant::updateOrCreate(
            ['product_id' => $toallitas60->id, 'color' => null, 'graduation' => null, 'graduation_type' => null],
            ['name' => 'Default', 'value' => 'Estándar', 'price_modifier' => 0, 'stock' => 999, 'is_active' => true],
        );
    }

    /**
     * Seed color-only variants for sin_graduacion products.
     * $imageMap: optional ['Color' => 'variants/path.jpg'] to attach per-color images.
     */
    private function seedSimpleVariants(Product $product, array $colors, string $graduationType, array $imageMap = []): void
    {
        foreach ($colors as $color) {
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'color' => $color,
                    'graduation' => '+0',
                    'graduation_type' => $graduationType,
                ],
                [
                    'name' => 'Color',
                    'value' => $color,
                    'price_modifier' => 0,
                    'stock' => 99,
                    'image_path' => $imageMap[$color] ?? null,
                    'is_active' => true,
                ],
            );
        }
    }

    /**
     * Seed color×graduation variants for lectura/miopia products.
     * $imageMap: optional ['Color' => 'variants/path.jpg'] to attach per-color images.
     */
    private function seedGraduatedVariants(Product $product, array $colors, string $graduationType, array $imageMap = []): void
    {
        $graduations = $graduationType === 'miopia'
            ? ['-100', '-150', '-200', '-250', '-300', '-350', '-400']
            : ['+100', '+150', '+200', '+250', '+300', '+350', '+400'];

        foreach ($graduations as $graduation) {
            foreach ($colors as $color) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color' => $color,
                        'graduation' => $graduation,
                        'graduation_type' => $graduationType,
                    ],
                    [
                        'name' => 'Color',
                        'value' => $color,
                        'price_modifier' => 0,
                        'stock' => 99,
                        'image_path' => $imageMap[$color] ?? null,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
