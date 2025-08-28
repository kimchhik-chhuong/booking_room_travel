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
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Check if hotel_metadata table exists
            if (!Schema::hasTable('hotel_metadata')) {
                throw new \Exception('hotel_metadata table does not exist. Please run the hotel_metadata migration first.');
            }

            // Check if hotel_bookings table exists
            if (!Schema::hasTable('hotel_bookings')) {
                throw new \Exception('hotel_bookings table does not exist. Please run the hotel_bookings migration first.');
            }

            // Get all columns in the hotel_bookings table
            $columns = collect(DB::select('SHOW COLUMNS FROM hotel_bookings'))
                ->pluck('Field')
                ->toArray();

            // Add hotel_metadata_id if it doesn't exist
            if (!in_array('hotel_metadata_id', $columns)) {
                DB::statement('ALTER TABLE hotel_bookings ADD COLUMN hotel_metadata_id BIGINT UNSIGNED NULL AFTER booking_id');
                Log::info('Added hotel_metadata_id column to hotel_bookings table');
            }

            // Drop hotel_id column if it exists
            if (in_array('hotel_id', $columns)) {
                // First, drop any foreign key constraints on hotel_id
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'hotel_bookings' 
                    AND COLUMN_NAME = 'hotel_id' 
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");

                foreach ($foreignKeys as $fk) {
                    DB::statement("ALTER TABLE hotel_bookings DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                    Log::info("Dropped foreign key constraint: {$fk->CONSTRAINT_NAME}");
                }

                // Then drop the column
                DB::statement('ALTER TABLE hotel_bookings DROP COLUMN hotel_id');
                Log::info('Dropped hotel_id column from hotel_bookings table');
            }

            // Add foreign key constraint if it doesn't exist
            $hasFk = DB::selectOne("
                SELECT COUNT(*) as count 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_NAME = 'hotel_bookings' 
                AND COLUMN_NAME = 'hotel_metadata_id' 
                AND REFERENCED_TABLE_NAME = 'hotel_metadata'
            ");

            if ($hasFk->count == 0) {
                DB::statement('ALTER TABLE hotel_bookings 
                    ADD CONSTRAINT fk_hotel_bookings_metadata
                    FOREIGN KEY (hotel_metadata_id) 
                    REFERENCES hotel_metadata(id) 
                    ON DELETE CASCADE');
                
                Log::info('Added foreign key constraint from hotel_bookings.hotel_metadata_id to hotel_metadata.id');
            }

            // Add any missing guest columns if they don't exist
            $missingColumns = array_diff([
                'guest_name', 'guest_email', 'guest_phone', 'nationality', 'special_requests'
            ], $columns);

            if (!empty($missingColumns)) {
                Schema::table('hotel_bookings', function (Blueprint $table) use ($missingColumns) {
                    if (in_array('guest_name', $missingColumns)) {
                        $table->string('guest_name')->nullable()->after('status');
                    }
                    if (in_array('guest_email', $missingColumns)) {
                        $table->string('guest_email')->nullable()->after('guest_name');
                    }
                    if (in_array('guest_phone', $missingColumns)) {
                        $table->string('guest_phone')->nullable()->after('guest_email');
                    }
                    if (in_array('nationality', $missingColumns)) {
                        $table->string('nationality')->nullable()->after('guest_phone');
                    }
                    if (in_array('special_requests', $missingColumns)) {
                        $table->text('special_requests')->nullable()->after('nationality');
                    }
                });
                
                Log::info('Added missing guest columns to hotel_bookings table', ['added_columns' => $missingColumns]);
            }

            // Make sure room_type_id exists and has proper foreign key
            if (!in_array('room_type_id', $columns)) {
                DB::statement('ALTER TABLE hotel_bookings ADD COLUMN room_type_id BIGINT UNSIGNED NULL AFTER hotel_metadata_id');
                Log::info('Added room_type_id column to hotel_bookings table');
                
                // Add foreign key to room_types table if it exists
                if (Schema::hasTable('room_types')) {
                    DB::statement('ALTER TABLE hotel_bookings 
                        ADD CONSTRAINT fk_hotel_bookings_room_type
                        FOREIGN KEY (room_type_id) 
                        REFERENCES room_types(id) 
                        ON DELETE SET NULL');
                    
                    Log::info('Added foreign key from hotel_bookings.room_type_id to room_types.id');
                }
            }

            // Drop the old room_type column if it exists and room_type_id is present
            if (in_array('room_type', $columns) && in_array('room_type_id', $columns)) {
                DB::statement('ALTER TABLE hotel_bookings DROP COLUMN room_type');
                Log::info('Dropped old room_type column from hotel_bookings table');
            }

            Log::info('Successfully fixed hotel_bookings table structure');

        } catch (\Exception $e) {
            Log::error('Failed to fix hotel_bookings table structure: ' . $e->getMessage());
            throw $e;
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
        // We don't want to automatically reverse these changes as they're fixing data consistency
    }
};
