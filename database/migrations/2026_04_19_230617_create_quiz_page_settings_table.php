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
        Schema::create('quiz_page_settings', function (Blueprint $table) {
            $table->id();

            // Hero / textos
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();

            // Resultado
            $table->string('result_title')->nullable();
            $table->string('result_cta_text')->nullable();
            $table->text('default_reason')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            // Reglas y producto por defecto
            $table->json('recommendation_rules')->nullable();
            $table->unsignedBigInteger('default_product_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_page_settings');
    }
};
