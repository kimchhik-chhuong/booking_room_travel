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
        // Add the new room_type_id column
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('room_type_id')->nullable()->after('hotel_id');
        });

        // Set room_type_id based on room_type (you may need to adjust this based on your data)
        // This is a placeholder - you'll need to implement the actual data migration logic
        // For example:
        // DB::statement("UPDATE hotel_bookings 
        //     JOIN room_types ON hotel_bookings.room_type = room_types.name 
        //     AND room_types.hotel_id = hotel_bookings.hotel_id 
        //     SET hotel_bookings.room_type_id = room_types.id");

        // Add foreign key constraint after data is migrated
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->foreign('room_type_id')
                  ->references('id')
                  ->on('room_types')
                  ->onDelete('set null');
        });

        // Drop the old room_type column after data is migrated
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn('room_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the room_type column
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->string('room_type')->after('hotel_id')->nullable();
        });

        // Set room_type based on room_type_id (you may need to adjust this based on your data)
        // This is a placeholder - you'll need to implement the actual data migration logic
        // For example:
        // DB::statement("UPDATE hotel_bookings 
        //     JOIN room_types ON hotel_bookings.room_type_id = room_types.id 
        //     SET hotel_bookings.room_type = room_types.name");

        // Drop the foreign key constraint
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
        });

        // Drop the room_type_id column
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn('room_type_id');
        });
    }
};
