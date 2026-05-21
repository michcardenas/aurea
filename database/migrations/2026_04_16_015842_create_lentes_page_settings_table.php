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
        Schema::create('lentes_page_settings', function (Blueprint $table) {
            $table->id();

            // Header del catálogo
            $table->string('catalog_title')->nullable();
            $table->string('catalog_subtitle')->nullable();

            // Beneficios rápidos en ficha de producto
            $table->json('product_benefits')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lentes_page_settings');
    }
};
