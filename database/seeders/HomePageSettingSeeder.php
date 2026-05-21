<?php

namespace Database\Seeders;

use App\Models\HomePageSetting;
use Illuminate\Database\Seeder;

class HomePageSettingSeeder extends Seeder
{
    public function run(): void
    {
        HomePageSetting::firstOrCreate(['id' => 1], [
            'categories_label' => 'Categorías',
            'categories_title' => 'Encuentra tus lentes ideales',
            'categories_subtitle' => 'Con o sin graduación, tenemos el modelo perfecto para ti.',
            'category_cards' => [
                [
                    'name' => 'Sin Graduación',
                    'link_param' => 'sin_graduacion',
                    'description' => 'Protección de luz azul sin necesidad de receta. Ideales para uso diario frente a pantallas.',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
                ],
                [
                    'name' => 'Lectura',
                    'link_param' => 'lectura',
                    'description' => 'Lentes con graduación para lectura y filtro de luz azul. De +1.00 a +4.00.',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>',
                ],
                [
                    'name' => 'Miopía',
                    'link_param' => 'miopia',
                    'description' => 'Lentes con graduación para miopía y filtro de luz azul. De -1.00 a -4.00.',
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 3.75H6A2.25 2.25 0 0 0 3.75 6v1.5M16.5 3.75H18A2.25 2.25 0 0 1 20.25 6v1.5m0 9V18A2.25 2.25 0 0 1 18 20.25h-1.5m-9 0H6A2.25 2.25 0 0 1 3.75 18v-1.5M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
                ],
            ],

            'catalog_label' => 'Catálogo',
            'catalog_title' => 'Nuestros lentes',
            'catalog_subtitle' => 'Todos con filtro de luz azul y promoción 2×1.',

            'promo_label' => 'Promoción',
            'promo_title' => '2×1 en todos los lentes',
            'promo_description' => 'Compra un par y llévate el segundo completamente gratis. Todos los modelos, todos los colores, todas las graduaciones.',
            'promo_price' => '$499.90',
            'promo_price_note' => 'por par · el segundo es gratis',
            'promo_btn_text' => 'Aprovecha ahora',

            'wipes_label' => 'Accesorios',
            'wipes_title' => 'Cuida tus lentes',
            'wipes_description' => 'Toallitas limpiadoras 2 en 1: paño húmedo con fórmula sin alcohol + paño seco. Resultados inmediatos para lentes, pantallas, cámaras y tablets.',
            'wipes_features' => [
                'Fórmula sin alcohol — segura para cualquier lente',
                'Sistema 2 en 1 — limpieza húmeda + secado',
                'Resultados inmediatos sin residuos',
                'Sirve para lentes, pantallas y cámaras',
            ],

            'faqs' => [
                ['q' => '¿De verdad funcionan los lentes de luz azul?', 'a' => 'Sí. Nuestros lentes filtran entre el 30% y 50% de la luz azul de alta energía emitida por pantallas y focos LED. Esto reduce la fatiga visual, dolores de cabeza y mejora la calidad del sueño.'],
                ['q' => '¿Puedo usarlos si no tengo graduación?', 'a' => 'Por supuesto. Tenemos modelos sin graduación diseñados específicamente para personas que no necesitan corrección visual pero quieren proteger sus ojos de la luz azul de pantallas.'],
                ['q' => '¿Cómo funciona el 2×1?', 'a' => 'Al agregar 2 pares de lentes al carrito, el segundo par (de igual o menor valor) es completamente gratis. Aplica para todos los modelos y colores.'],
                ['q' => '¿Cuánto tarda el envío?', 'a' => 'El envío estándar tarda de 3 a 5 días hábiles a cualquier parte de México. Envío gratis en compras mayores a $999 MXN.'],
                ['q' => '¿Tienen garantía?', 'a' => 'Todos nuestros lentes incluyen 6 meses de garantía contra defectos de fabricación. Si no estás satisfecho, puedes devolverlos en los primeros 30 días.'],
            ],

            'trust_badges' => [
                [
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
                    'title' => 'Envío gratis',
                    'description' => 'En compras +$999',
                ],
                [
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>',
                    'title' => 'Garantía 6 meses',
                    'description' => 'Contra defectos',
                ],
                [
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/>',
                    'title' => '30 días',
                    'description' => 'Devolución sin costo',
                ],
                [
                    'icon_svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>',
                    'title' => 'Pago seguro',
                    'description' => 'Stripe encriptado',
                ],
            ],

            'cta_title' => '¿Listo para proteger tu visión?',
            'cta_subtitle' => 'Ve mejor, duerme mejor, rinde más. Únete a quienes ya cuidan sus ojos con nuvion.',
            'cta_btn_primary_text' => 'Comprar ahora',
            'cta_btn_secondary_text' => 'Aprende más',
            'cta_trust_items' => [
                'Envío gratis +$999',
                'Garantía 6 meses',
                '30 días de devolución',
            ],
        ]);
    }
}
