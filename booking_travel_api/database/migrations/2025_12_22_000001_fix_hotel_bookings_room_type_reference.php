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
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Drop the old room_type string column
            $table->dropColumn('room_type');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Add proper foreign key reference to room_types table
            $table->unsignedBigInteger('room_type_id')->after('check_out_date');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
            $table->dropColumn('room_type_id');
        });

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->string('room_type')->after('check_out_date');
        });
    }
};
