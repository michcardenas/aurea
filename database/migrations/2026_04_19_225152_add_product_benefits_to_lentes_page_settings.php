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
        Schema::table('lentes_page_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('lentes_page_settings', 'product_benefits')) {
                $table->json('product_benefits')->nullable()->after('catalog_subtitle');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lentes_page_settings', function (Blueprint $table) {
            if (Schema::hasColumn('lentes_page_settings', 'product_benefits')) {
                $table->dropColumn('product_benefits');
            }
        });
    }
};
