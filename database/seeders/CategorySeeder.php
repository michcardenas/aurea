<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Skincare',
                'slug' => 'skincare',
                'description' => 'Sérums, cremas, tónicos y mascarillas formuladas con ingredientes botánicos para una piel luminosa y saludable.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Fragancias',
                'slug' => 'fragancias',
                'description' => 'Perfumes y aguas aromáticas con notas naturales. Elegancia atemporal en cada gota.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Rituales',
                'slug' => 'rituales',
                'description' => 'Sets y kits seleccionados para tu ritual diario de belleza. Todo lo que necesitas en un solo gesto.',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
