<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only proceed if both tables exist
        if (Schema::hasTable('room_types') && Schema::hasTable('hotel_metadata')) {
            // Check if the foreign key already exists
            $constraintExists = DB::selectOne(
                "SELECT COUNT(*) as count FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'room_types' 
                AND CONSTRAINT_NAME = 'fk_room_types_hotel_metadata' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
            );

            if ($constraintExists->count == 0) {
                // Use raw SQL to add the foreign key
                DB::statement('ALTER TABLE room_types 
                    ADD CONSTRAINT fk_room_types_hotel_metadata
                    FOREIGN KEY (hotel_metadata_id) 
                    REFERENCES hotel_metadata(hotel_id) 
                    ON DELETE CASCADE
                    ON UPDATE CASCADE');
            }
        }
    }

    public function down()
    {
        // Drop the foreign key constraint if it exists
        if (Schema::hasTable('room_types')) {
            DB::statement('ALTER TABLE room_types DROP FOREIGN KEY IF EXISTS fk_room_types_hotel_metadata');
        }
    }
};
