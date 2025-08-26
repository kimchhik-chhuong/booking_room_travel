<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, drop the foreign key if it exists (MySQL specific)
        DB::statement('ALTER TABLE room_types DROP FOREIGN KEY IF EXISTS room_types_hotel_metadata_id_foreign');
        
        // Add the foreign key
        DB::statement('
            ALTER TABLE room_types 
            ADD CONSTRAINT room_types_hotel_metadata_id_foreign 
            FOREIGN KEY (hotel_metadata_id) 
            REFERENCES hotel_metadata(hotel_id) 
            ON DELETE CASCADE;
        ');
    }

    public function down()
    {
        DB::statement('ALTER TABLE room_types DROP FOREIGN KEY IF EXISTS room_types_hotel_metadata_id_foreign');
    }
};