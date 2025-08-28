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
            // Check if hotel_id column exists and hotel_metadata_id doesn't exist before renaming
            if (Schema::hasColumn('hotel_bookings', 'hotel_id') && !Schema::hasColumn('hotel_bookings', 'hotel_metadata_id')) {
                // Drop existing foreign key constraint
                $table->dropForeign(['hotel_id']);
                
                // Rename hotel_id to hotel_metadata_id
                $table->renameColumn('hotel_id', 'hotel_metadata_id');
                
                // Add the new foreign key constraint
                $table->foreign('hotel_metadata_id')
                      ->references('hotel_id')
                      ->on('hotel_metadata')
                      ->onDelete('cascade');
            }
                  
            // Add missing columns
            if (!Schema::hasColumn('hotel_bookings', 'room_type_id')) {
                $table->unsignedBigInteger('room_type_id')->after('hotel_metadata_id');
            }
            
            if (!Schema::hasColumn('hotel_bookings', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('hotel_bookings', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_name');
            }
            
            if (!Schema::hasColumn('hotel_bookings', 'guest_phone')) {
                $table->string('guest_phone')->nullable()->after('guest_email');
            }
            
            if (!Schema::hasColumn('hotel_bookings', 'nationality')) {
                $table->string('nationality')->nullable()->after('guest_phone');
            }
            
            if (!Schema::hasColumn('hotel_bookings', 'special_requests')) {
                $table->text('special_requests')->nullable()->after('nationality');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['hotel_metadata_id']);
            
            // Rename back to hotel_id
            $table->renameColumn('hotel_metadata_id', 'hotel_id');
            
            // Re-add the original foreign key constraint
            $table->foreign('hotel_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
                  
            // Remove added columns
            $table->dropColumn([
                'room_type_id',
                'guest_name',
                'guest_email',
                'guest_phone',
                'nationality',
                'special_requests'
            ]);
        });
    }
};
