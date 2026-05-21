<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_carrier', 100)->nullable()->after('shipping_address');
            $table->string('tracking_number', 100)->nullable()->after('shipping_carrier');
            $table->string('tracking_url', 500)->nullable()->after('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_carrier', 'tracking_number', 'tracking_url']);
        });
    }
};
