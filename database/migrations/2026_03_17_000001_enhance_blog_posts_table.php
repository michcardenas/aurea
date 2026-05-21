<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('featured_image_alt')->nullable()->after('image');
            $table->string('focus_keyword')->nullable()->after('meta_description');
            $table->string('canonical_url')->nullable()->after('focus_keyword');
            $table->string('og_title')->nullable()->after('canonical_url');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->unsignedInteger('reading_time')->default(1)->after('og_image');
            $table->string('status')->default('draft')->after('reading_time');
            $table->string('author_name')->default('nuvion glass')->after('status');
            $table->string('schema_type')->default('BlogPosting')->after('author_name');
        });

        // Migrate existing posts: if they have published_at, set status to published
        DB::table('blog_posts')->whereNotNull('published_at')->update(['status' => 'published']);
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image_alt',
                'focus_keyword',
                'canonical_url',
                'og_title',
                'og_description',
                'og_image',
                'reading_time',
                'status',
                'author_name',
                'schema_type',
            ]);
        });
    }
};
