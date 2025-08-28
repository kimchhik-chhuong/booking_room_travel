<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop old room_type if it exists
        if (Schema::hasColumn('hotel_bookings', 'room_type')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->dropColumn('room_type');
            });
        }

        // Add room_type_id only if it does not already exist
        if (!Schema::hasColumn('hotel_bookings', 'room_type_id')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->foreignId('room_type_id')
                      ->after('hotel_id')
                      ->constrained('room_types')
                      ->cascadeOnDelete();
            });
        }
    }

    public function down()
    {
        // Remove room_type_id if it exists
        if (Schema::hasColumn('hotel_bookings', 'room_type_id')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->dropForeign(['room_type_id']);
                $table->dropColumn('room_type_id');
            });
        }

        // Restore room_type column if it was dropped
        if (!Schema::hasColumn('hotel_bookings', 'room_type')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->string('room_type')->nullable();
            });
        }
    }
};
