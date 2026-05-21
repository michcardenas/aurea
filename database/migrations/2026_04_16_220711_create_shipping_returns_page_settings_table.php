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
        Schema::create('shipping_returns_page_settings', function (Blueprint $table) {
            $table->id();

            // Hero
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();

            // Sección envíos
            $table->string('shipping_title')->nullable();
            $table->text('shipping_content')->nullable();

            // Sección devoluciones
            $table->string('returns_title')->nullable();
            $table->text('returns_content')->nullable();

            // Sección garantía
            $table->string('warranty_title')->nullable();
            $table->text('warranty_content')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_returns_page_settings');
    }
};
