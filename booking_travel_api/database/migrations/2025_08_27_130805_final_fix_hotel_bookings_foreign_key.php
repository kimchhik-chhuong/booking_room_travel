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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Add the hotel_metadata_id column if it doesn't exist
        if (!Schema::hasColumn('hotel_bookings', 'hotel_metadata_id')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('hotel_metadata_id')->nullable()->after('booking_id');
            });
        }

        // Drop any existing foreign key constraints
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropColumn('hotel_id');
        });

        // Add the new foreign key constraint
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreign('hotel_metadata_id')
                  ->references('id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
    }
};
