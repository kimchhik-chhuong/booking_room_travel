<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Check if the column already exists before adding it
            if (!Schema::hasColumn('hotel_bookings', 'hotel_metadata_id')) {
                // Add the new column
                $table->unsignedBigInteger('hotel_metadata_id')->after('booking_id');
                
                // Add foreign key constraint
                $table->foreign('hotel_metadata_id')
                      ->references('hotel_id')
                      ->on('hotel_metadata')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['hotel_metadata_id']);
            
            // Then drop the column
            $table->dropColumn('hotel_metadata_id');
        });
    }
};
