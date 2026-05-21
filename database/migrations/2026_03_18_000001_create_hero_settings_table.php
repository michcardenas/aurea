<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('media_type', ['video', 'image', 'gradient'])->default('gradient');
            $table->string('media_path')->nullable();
            $table->decimal('overlay_opacity', 3, 2)->default(0.55);
            $table->string('eyebrow_text')->default('Protección de luz azul');
            $table->string('title_line1')->default('Lentes que cuidan');
            $table->string('title_line2')->default('tus ojos de las');
            $table->string('title_line3')->default('pantallas');
            $table->string('title_highlight_word')->default('pantallas');
            $table->text('subtitle')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('btn_primary_text')->default('Ver lentes');
            $table->string('btn_primary_url')->default('/lentes');
            $table->string('btn_secondary_text')->default('¿Qué es la luz azul?');
            $table->string('btn_secondary_url')->default('/que-es-la-luz-azul');
            $table->json('trust_items')->nullable();
            $table->string('stat1_number')->default('2x1');
            $table->string('stat1_label')->default('en todos los lentes');
            $table->string('stat2_number')->default('6');
            $table->string('stat2_label')->default('modelos disponibles');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_settings');
    }
};
