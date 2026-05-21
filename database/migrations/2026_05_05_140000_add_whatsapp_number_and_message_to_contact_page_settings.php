<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_page_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_page_settings', 'whatsapp_number')) {
                $table->string('whatsapp_number', 32)->nullable()->after('whatsapp');
            }
            if (! Schema::hasColumn('contact_page_settings', 'whatsapp_message')) {
                $table->text('whatsapp_message')->nullable()->after('whatsapp_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_page_settings', function (Blueprint $table) {
            if (Schema::hasColumn('contact_page_settings', 'whatsapp_number')) {
                $table->dropColumn('whatsapp_number');
            }
            if (Schema::hasColumn('contact_page_settings', 'whatsapp_message')) {
                $table->dropColumn('whatsapp_message');
            }
        });
    }
};
