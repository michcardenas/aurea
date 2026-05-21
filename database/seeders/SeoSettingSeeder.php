<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingSeeder extends Seeder
{
    public function run(): void
    {
        SeoSetting::firstOrCreate(['page_key' => 'home'], [
            'meta_title' => 'nuvion - glass | Protege tus ojos de la luz azul',
            'meta_description' => 'Lentes con protección de luz azul. Con o sin graduación. Diseño moderno que querrás usar todo el día. Envío gratis a todo México.',
            'meta_keywords' => 'lentes luz azul, blue light glasses, protección pantallas, nuvion glass, lentes anti luz azul',
            'robots' => 'index, follow',
            'og_type' => 'website',
            'og_title' => 'nuvion - glass | Lentes con protección de luz azul',
            'og_description' => 'Protege tus ojos de las pantallas. Lentes con filtro de luz azul, con o sin graduación. Envío gratis.',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'nuvion - glass | Lentes con protección de luz azul',
            'twitter_description' => 'Protege tus ojos de las pantallas. Lentes con filtro de luz azul, con o sin graduación. Envío gratis.',
            'is_active' => true,
        ]);
    }
}
