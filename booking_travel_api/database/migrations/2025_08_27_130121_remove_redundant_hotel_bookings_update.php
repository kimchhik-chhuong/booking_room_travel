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
        // Remove the problematic migration record
        DB::table('migrations')
            ->where('migration', '2025_08_27_104200_update_hotel_bookings_table')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration, no need to implement down
    }
};
