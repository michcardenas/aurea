<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color')->nullable()->after('product_id');
            $table->string('graduation')->nullable()->after('color');
            $table->string('graduation_type')->nullable()->after('graduation');
            $table->boolean('is_active')->default(true)->after('stock');

            $table->index(['product_id', 'color', 'graduation_type']);
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'color', 'graduation_type']);
            $table->dropColumn(['color', 'graduation', 'graduation_type', 'is_active']);
        });
    }
};
