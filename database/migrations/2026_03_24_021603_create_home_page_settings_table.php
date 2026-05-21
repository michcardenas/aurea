<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();

            // Sección Categorías
            $table->string('categories_label')->default('Categorías');
            $table->string('categories_title')->default('Encuentra tus lentes ideales');
            $table->string('categories_subtitle')->default('Con o sin graduación, tenemos el modelo perfecto para ti.');
            $table->json('category_cards')->nullable();

            // Sección Catálogo header
            $table->string('catalog_label')->default('Catálogo');
            $table->string('catalog_title')->default('Nuestros lentes');
            $table->string('catalog_subtitle')->default('Todos con filtro de luz azul y promoción 2×1.');

            // Sección Promo 2x1
            $table->string('promo_label')->default('Promoción');
            $table->string('promo_title')->default('2×1 en todos los lentes');
            $table->text('promo_description')->nullable();
            $table->string('promo_price')->default('$499.90');
            $table->string('promo_price_note')->default('por par · el segundo es gratis');
            $table->string('promo_btn_text')->default('Aprovecha ahora');

            // Sección Toallitas
            $table->string('wipes_label')->default('Accesorios');
            $table->string('wipes_title')->default('Cuida tus lentes');
            $table->text('wipes_description')->nullable();
            $table->json('wipes_features')->nullable();

            // FAQ
            $table->json('faqs')->nullable();

            // Trust badges
            $table->json('trust_badges')->nullable();

            // CTA final
            $table->string('cta_title')->default('¿Listo para proteger tu visión?');
            $table->text('cta_subtitle')->nullable();
            $table->string('cta_btn_primary_text')->default('Comprar ahora');
            $table->string('cta_btn_secondary_text')->default('Aprende más');
            $table->json('cta_trust_items')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_settings');
    }
};
