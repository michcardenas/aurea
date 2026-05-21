<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Backfill: orders that already moved past 'pending'/'cancelled' must have
     * payment_status='paid'. Fixes historical inconsistency where the status
     * dropdown only updated `status` and left payment_status as 'pending'.
     */
    public function up(): void
    {
        DB::table('orders')
            ->whereIn('status', ['confirmed', 'shipped', 'delivered'])
            ->where('payment_status', '!=', 'paid')
            ->update(['payment_status' => 'paid']);
    }

    public function down(): void
    {
        // No revertimos: no sabemos cuáles fueron tocados por este backfill
        // vs cuáles ya estaban en 'paid'.
    }
};
