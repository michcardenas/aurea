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
                'name' => 'Sin Graduación',
                'slug' => 'sin-graduacion',
                'description' => 'Lentes de protección contra luz azul sin graduación. Ideales para quienes no necesitan corrección visual pero buscan proteger sus ojos.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Con Graduación',
                'slug' => 'con-graduacion',
                'description' => 'Lentes con graduación y filtro de luz azul. Combina tu corrección visual con la máxima protección.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Gaming & Sport',
                'slug' => 'gaming-sport',
                'description' => 'Lentes diseñados para gamers y deportistas digitales. Máxima protección durante sesiones intensas.',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
