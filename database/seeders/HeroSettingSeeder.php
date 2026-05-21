<?php

namespace Database\Seeders;

use App\Models\HeroSetting;
use Illuminate\Database\Seeder;

class HeroSettingSeeder extends Seeder
{
    public function run(): void
    {
        HeroSetting::firstOrCreate(['id' => 1], [
            'media_type' => 'gradient',
            'overlay_opacity' => 0.55,
            'eyebrow_text' => 'Protección de luz azul',
            'title_line1' => 'Lentes que cuidan',
            'title_line2' => 'tus ojos de las',
            'title_line3' => 'pantallas',
            'title_highlight_word' => 'pantallas',
            'subtitle' => 'Con o sin graduación. Filtro de luz azul de alta eficiencia en todos los modelos.',
            'badge_text' => '2x1 en todos los lentes · $499.90 c/u',
            'btn_primary_text' => 'Ver lentes',
            'btn_primary_url' => '/lentes',
            'btn_secondary_text' => '¿Qué es la luz azul?',
            'btn_secondary_url' => '/que-es-la-luz-azul',
            'trust_items' => ['Envío gratis +$999', 'Garantía 6 meses', '30 días devolución'],
            'stat1_number' => '2x1',
            'stat1_label' => 'en todos los lentes',
            'stat2_number' => '6',
            'stat2_label' => 'modelos disponibles',
            'is_active' => true,
        ]);
    }
}
