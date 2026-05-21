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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'discount_2x1')) {
                $table->decimal('discount_2x1', 10, 2)->default(0)->after('discount_amount');
            }
            if (! Schema::hasColumn('orders', 'discount_coupon')) {
                $table->decimal('discount_coupon', 10, 2)->default(0)->after('discount_2x1');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'discount_2x1')) {
                $table->dropColumn('discount_2x1');
            }
            if (Schema::hasColumn('orders', 'discount_coupon')) {
                $table->dropColumn('discount_coupon');
            }
        });
    }
};
