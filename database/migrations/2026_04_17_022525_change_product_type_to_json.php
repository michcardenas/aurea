<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convert existing string values to JSON arrays
        // e.g. "miopia" -> ["miopia"]
        $products = DB::table('products')->whereNotNull('type')->get();

        foreach ($products as $product) {
            $currentType = $product->type;

            // Skip if already JSON
            if (str_starts_with($currentType, '[')) {
                continue;
            }

            DB::table('products')
                ->where('id', $product->id)
                ->update(['type' => json_encode([$currentType])]);
        }

        // Change column type to JSON
        Schema::table('products', function (Blueprint $table) {
            $table->json('type')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Convert JSON arrays back to strings (take first value)
        $products = DB::table('products')->whereNotNull('type')->get();

        foreach ($products as $product) {
            $types = json_decode($product->type, true);
            $firstType = is_array($types) ? ($types[0] ?? null) : $product->type;

            DB::table('products')
                ->where('id', $product->id)
                ->update(['type' => $firstType]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
        });
    }
};
