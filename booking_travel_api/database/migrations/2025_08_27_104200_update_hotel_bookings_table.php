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
        // Drop the foreign key constraint first
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
        });

        // Rename the column
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->renameColumn('hotel_id', 'hotel_metadata_id');
        });

        // Add the new foreign key constraint
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreign('hotel_metadata_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_metadata_id']);
        });

        // Rename the column back
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->renameColumn('hotel_metadata_id', 'hotel_id');
        });

        // Add back the original foreign key constraint
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreign('hotel_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });
    }
};
