<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_transfer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->default('');
            $table->timestamps();
        });

        // Seed defaults
        $defaults = [
            'bank_name' => '',
            'account_holder' => '',
            'clabe' => '',
            'account_number' => '',
            'reference_instructions' => 'Usa tu número de pedido como referencia',
            'additional_notes' => '',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('bank_transfer_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transfer_settings');
    }
};
