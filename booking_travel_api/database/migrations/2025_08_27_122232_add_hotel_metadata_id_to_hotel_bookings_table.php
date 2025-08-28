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
            $table->unsignedBigInteger('hotel_metadata_id')->after('booking_id');
            
            $table->foreign('hotel_metadata_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_metadata_id']);
            $table->dropColumn('hotel_metadata_id');
        });
    }
};
