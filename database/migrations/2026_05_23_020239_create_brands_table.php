<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si ya existe con solo id+timestamps (del stub vacío), drop y recrear
        if (Schema::hasTable('brands') && ! Schema::hasColumn('brands', 'slug')) {
            Schema::drop('brands');
        }

        if (! Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo_path')->nullable();        // logo cuadrado u horizontal
                $table->string('banner_path')->nullable();      // banner wide opcional
                $table->string('short_description', 255)->nullable();
                $table->text('long_description')->nullable();
                $table->string('website_url')->nullable();
                $table->string('country_origin')->nullable();
                $table->boolean('is_featured')->default(true);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->string('meta_title')->nullable();
                $table->string('meta_description', 500)->nullable();
                $table->timestamps();

                $table->index(['is_active', 'is_featured', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
