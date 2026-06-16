<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('home_page_settings', 'star_product_id')) {
                $table->foreignId('star_product_id')
                    ->nullable()
                    ->after('promo_title')
                    ->constrained('products')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            if (Schema::hasColumn('home_page_settings', 'star_product_id')) {
                $table->dropForeign(['star_product_id']);
                $table->dropColumn('star_product_id');
            }
        });
    }
};
