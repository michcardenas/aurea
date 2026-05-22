<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade el campo cost_price para almacenar "PV Distribuidor"
     * (lo que el negocio paga por unidad — su costo de compra).
     *
     *   price          = precio al que se vende en la web (lo que paga el cliente)
     *   compare_price  = precio sugerido / tachado / PVP físico
     *   cost_price     = costo de compra (PV Distribuidor) — solo visible en admin
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('compare_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });
    }
};
