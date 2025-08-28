<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove the problematic migration record from the migrations table
        DB::table('migrations')
            ->where('migration', '2025_08_27_123643_add_guest_columns_to_hotel_bookings')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed safely
    }
};
