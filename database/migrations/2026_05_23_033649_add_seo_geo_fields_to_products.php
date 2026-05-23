<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Campos SEO + GEO (Generative Engine Optimization) para que los LLMs
     * (ChatGPT, Perplexity, Google AI Overviews, Bing Copilot) puedan citar
     * tus productos con datos estructurados ricos.
     *
     * Distribución general:
     *   SEO clásico → og_image, focus_keyword, noindex
     *   Contenido enriquecido (AI-friendly) → key_features, how_to_use,
     *     ingredients, suitable_for
     *   Datos técnicos (Schema.org Product) → gtin, mpn, weight, country
     *   Trust badges → is_cruelty_free, is_vegan
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // SEO clásico extendido
            if (! Schema::hasColumn('products', 'og_image_path'))
                $table->string('og_image_path')->nullable()->after('meta_description');
            if (! Schema::hasColumn('products', 'focus_keyword'))
                $table->string('focus_keyword', 120)->nullable()->after('og_image_path');
            if (! Schema::hasColumn('products', 'noindex'))
                $table->boolean('noindex')->default(false)->after('focus_keyword');

            // Contenido enriquecido (legible para humanos y para IA)
            if (! Schema::hasColumn('products', 'key_features'))
                $table->json('key_features')->nullable()->after('noindex');
            if (! Schema::hasColumn('products', 'how_to_use'))
                $table->text('how_to_use')->nullable()->after('key_features');
            if (! Schema::hasColumn('products', 'ingredients'))
                $table->text('ingredients')->nullable()->after('how_to_use');
            if (! Schema::hasColumn('products', 'suitable_for'))
                $table->string('suitable_for', 500)->nullable()->after('ingredients');

            // Datos técnicos (Schema.org Product)
            if (! Schema::hasColumn('products', 'gtin'))
                $table->string('gtin', 14)->nullable()->after('suitable_for');
            if (! Schema::hasColumn('products', 'mpn'))
                $table->string('mpn', 70)->nullable()->after('gtin');
            if (! Schema::hasColumn('products', 'weight_value'))
                $table->decimal('weight_value', 8, 2)->nullable()->after('mpn');
            if (! Schema::hasColumn('products', 'weight_unit'))
                $table->string('weight_unit', 10)->nullable()->after('weight_value');
            if (! Schema::hasColumn('products', 'country_origin'))
                $table->string('country_origin', 100)->nullable()->after('weight_unit');

            // Trust badges (sirven en card, página de producto y schema)
            if (! Schema::hasColumn('products', 'is_cruelty_free'))
                $table->boolean('is_cruelty_free')->default(false)->after('country_origin');
            if (! Schema::hasColumn('products', 'is_vegan'))
                $table->boolean('is_vegan')->default(false)->after('is_cruelty_free');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            foreach ([
                'og_image_path', 'focus_keyword', 'noindex',
                'key_features', 'how_to_use', 'ingredients', 'suitable_for',
                'gtin', 'mpn', 'weight_value', 'weight_unit', 'country_origin',
                'is_cruelty_free', 'is_vegan',
            ] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
