<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('home_page_settings', 'comparison_label')) {
                $table->string('comparison_label')->default('Comparativo')->after('cta_trust_items');
            }
            if (! Schema::hasColumn('home_page_settings', 'comparison_title')) {
                $table->string('comparison_title')->default('Con vs. sin protección')->after('comparison_label');
            }
            if (! Schema::hasColumn('home_page_settings', 'comparison_subtitle')) {
                $table->text('comparison_subtitle')->nullable()->after('comparison_title');
            }
            if (! Schema::hasColumn('home_page_settings', 'comparison_without_label')) {
                $table->string('comparison_without_label')->default('Sin protección')->after('comparison_subtitle');
            }
            if (! Schema::hasColumn('home_page_settings', 'comparison_without_items')) {
                $table->json('comparison_without_items')->nullable()->after('comparison_without_label');
            }
            if (! Schema::hasColumn('home_page_settings', 'comparison_with_label')) {
                $table->string('comparison_with_label')->default('Con nuvion glass')->after('comparison_without_items');
            }
            if (! Schema::hasColumn('home_page_settings', 'comparison_with_items')) {
                $table->json('comparison_with_items')->nullable()->after('comparison_with_label');
            }
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            foreach ([
                'comparison_label',
                'comparison_title',
                'comparison_subtitle',
                'comparison_without_label',
                'comparison_without_items',
                'comparison_with_label',
                'comparison_with_items',
            ] as $col) {
                if (Schema::hasColumn('home_page_settings', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
