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
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->string('benefits_label')->default('Beneficios')->after('promo_background');
            $table->string('benefits_title')->default('¿Por qué elegir nuvion?')->after('benefits_label');
            $table->string('benefits_subtitle')->default('Tecnología que cuida tu visión. Diseño que querrás usar todo el día.')->after('benefits_title');
            $table->json('benefits_cards')->nullable()->after('benefits_subtitle');
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->dropColumn(['benefits_label', 'benefits_title', 'benefits_subtitle', 'benefits_cards']);
        });
    }
};
