<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Generaliza las variantes de producto para cualquier tipo de producto de
     * belleza (color, tamaño, aroma, acabado, estilo, material, cantidad, otro).
     *
     * Antes el sistema asumía solo color + graduation (heredado del esqueleto
     * de lentes). Ahora cada variante declara su option_type y el UI/storefront
     * se adapta.
     */
    public function up(): void
    {
        // Si la columna ya existe (creada manualmente para evitar redownload), saltar.
        if (Schema::hasColumn('product_variants', 'option_type')) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->enum('option_type', [
                'color',
                'size',
                'scent',
                'finish',
                'style',
                'material',
                'quantity',
                'other',
            ])->default('other')->after('product_id')->index();
        });

        // Backfill: variantes existentes con color_hex → option_type=color
        DB::table('product_variants')
            ->whereNotNull('color_hex')
            ->update(['option_type' => 'color']);

        // Backfill: variantes con graduation → option_type=other (legacy lentes)
        DB::table('product_variants')
            ->whereNotNull('graduation_type')
            ->update(['option_type' => 'other']);
    }

    public function down(): void
    {
        if (! Schema::hasColumn('product_variants', 'option_type')) {
            return;
        }
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['option_type']);
            $table->dropColumn('option_type');
        });
    }
};
