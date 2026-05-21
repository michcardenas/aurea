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
        Schema::table('quiz_page_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('quiz_page_settings', 'questions')) {
                $table->json('questions')->nullable()->after('default_product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_page_settings', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_page_settings', 'questions')) {
                $table->dropColumn('questions');
            }
        });
    }
};
