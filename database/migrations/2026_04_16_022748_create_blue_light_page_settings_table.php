<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blue_light_page_settings', function (Blueprint $table) {
            $table->id();

            // Section 1: Hero
            $table->string('hero_title_prefix')->nullable();
            $table->string('hero_title_accent')->nullable();
            $table->string('hero_title_suffix')->nullable();
            $table->text('hero_subtitle')->nullable();

            // Section 2: Ciencia visual
            $table->string('science_label')->nullable();
            $table->string('science_title')->nullable();
            $table->text('science_paragraph1')->nullable();
            $table->text('science_paragraph2')->nullable();

            // Section 3: Síntomas
            $table->string('symptoms_label')->nullable();
            $table->string('symptoms_title')->nullable();
            $table->text('symptoms_subtitle')->nullable();
            $table->json('symptoms_cards')->nullable();

            // Section 4: Protección nuvion
            $table->string('protection_label')->nullable();
            $table->string('protection_title')->nullable();
            $table->text('protection_description')->nullable();
            $table->string('shield_percentage')->nullable();
            $table->string('shield_label')->nullable();
            $table->string('shield_sublabel')->nullable();
            $table->json('protection_benefits')->nullable();

            // Section 5: ¿Quién debería usarlos?
            $table->string('profiles_label')->nullable();
            $table->string('profiles_title')->nullable();
            $table->text('profiles_subtitle')->nullable();
            $table->json('profiles_cards')->nullable();

            // Section 6: FAQ
            $table->string('faq_label')->nullable();
            $table->string('faq_title')->nullable();
            $table->json('faqs')->nullable();

            // Section 7: Toggle comparativo
            $table->string('compare_label')->nullable();
            $table->string('compare_title')->nullable();
            $table->json('compare_without_items')->nullable();
            $table->json('compare_with_items')->nullable();
            $table->json('compare_metrics')->nullable();
            $table->text('compare_sources')->nullable();
            $table->string('compare_btn_text')->nullable();

            // Section 8: CTA final
            $table->string('cta_title')->nullable();
            $table->text('cta_subtitle')->nullable();
            $table->string('cta_btn_text')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blue_light_page_settings');
    }
};
