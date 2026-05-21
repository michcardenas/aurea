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
        Schema::create('blog_page_settings', function (Blueprint $table) {
            $table->id();

            // Hero del blog
            $table->string('hero_label')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_line2')->nullable();
            $table->string('hero_title_accent')->nullable();
            $table->string('hero_subtitle')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_page_settings');
    }
};
