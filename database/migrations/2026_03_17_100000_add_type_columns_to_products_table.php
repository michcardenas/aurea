<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        // Make category_id nullable without doctrine/dbal
        DB::statement('ALTER TABLE products MODIFY category_id BIGINT UNSIGNED NULL');

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();

            $table->string('internal_code')->nullable()->after('id');
            $table->string('type')->nullable()->after('slug');
            $table->boolean('badge_2x1')->default(true)->after('is_featured');
            $table->integer('sort_order')->default(0)->after('badge_2x1');

            $table->index('type');
            $table->index('internal_code');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['internal_code']);
            $table->dropColumn(['internal_code', 'type', 'badge_2x1', 'sort_order']);

            $table->dropForeign(['category_id']);
        });

        DB::statement('ALTER TABLE products MODIFY category_id BIGINT UNSIGNED NOT NULL');

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });
    }
};
