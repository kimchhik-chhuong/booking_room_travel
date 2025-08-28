<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Check if table exists
            if (!Schema::hasTable('hotel_bookings')) {
                throw new \Exception('hotel_bookings table does not exist');
            }

            // Get all columns in the table
            $columns = collect(DB::select('SHOW COLUMNS FROM hotel_bookings'))
                ->pluck('Field')
                ->toArray();

            // Add hotel_metadata_id if it doesn't exist
            if (!in_array('hotel_metadata_id', $columns)) {
                DB::statement('ALTER TABLE hotel_bookings ADD COLUMN hotel_metadata_id BIGINT UNSIGNED NULL AFTER booking_id');
            }

            // Drop hotel_id foreign key if it exists
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'hotel_bookings' 
                AND COLUMN_NAME = 'hotel_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE hotel_bookings DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }

            // Drop hotel_id column if it exists
            if (in_array('hotel_id', $columns)) {
                DB::statement('ALTER TABLE hotel_bookings DROP COLUMN hotel_id');
            }

            // Add foreign key constraint
            DB::statement('ALTER TABLE hotel_bookings 
                ADD CONSTRAINT fk_hotel_metadata_id 
                FOREIGN KEY (hotel_metadata_id) 
                REFERENCES hotel_metadata(id) 
                ON DELETE CASCADE');

        } catch (\Exception $e) {
            Log::error('Migration error: ' . $e->getMessage());
            throw $e;
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
    }
};
