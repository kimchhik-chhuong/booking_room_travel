<?php

use Illuminate\Database\Migrations\Migration;
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
            // Check if the database connection is working
            DB::connection()->getPdo();
            
            // List all tables in the database
            $tables = DB::select('SHOW TABLES');
            echo "\n=== Database Tables ===\n";
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                echo "- $tableName\n";
                
                // Show columns for each table
                $columns = DB::select("SHOW COLUMNS FROM `$tableName`");
                foreach ($columns as $column) {
                    echo "  - {$column->Field} ({$column->Type})\n";
                }
                
                // Show foreign keys
                $foreignKeys = DB::select("
                    SELECT 
                        COLUMN_NAME, 
                        CONSTRAINT_NAME, 
                        REFERENCED_TABLE_NAME, 
                        REFERENCED_COLUMN_NAME
                    FROM 
                        information_schema.KEY_COLUMN_USAGE 
                    WHERE 
                        TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = '$tableName'
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($foreignKeys)) {
                    echo "  Foreign Keys:\n";
                    foreach ($foreignKeys as $fk) {
                        echo "  - {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}({$fk->REFERENCED_COLUMN_NAME}) [{$fk->CONSTRAINT_NAME}]\n";
                    }
                }
                
                echo "\n";
            }
            
            // Check for hotel_metadata table and its structure
            if (in_array('hotel_metadata', array_map(fn($t) => array_values((array)$t)[0], $tables))) {
                echo "\n=== Hotel Metadata Table Structure ===\n";
                $columns = DB::select('SHOW COLUMNS FROM hotel_metadata');
                foreach ($columns as $column) {
                    echo "- {$column->Field} ({$column->Type})\n";
                }
            }
            
            // Check for hotel_bookings table and its structure
            if (in_array('hotel_bookings', array_map(fn($t) => array_values((array)$t)[0], $tables))) {
                echo "\n=== Hotel Bookings Table Structure ===\n";
                $columns = DB::select('SHOW COLUMNS FROM hotel_bookings');
                foreach ($columns as $column) {
                    echo "- {$column->Field} ({$column->Type})\n";
                }
                
                // Show foreign keys for hotel_bookings
                $foreignKeys = DB::select("
                    SELECT 
                        COLUMN_NAME, 
                        CONSTRAINT_NAME, 
                        REFERENCED_TABLE_NAME, 
                        REFERENCED_COLUMN_NAME
                    FROM 
                        information_schema.KEY_COLUMN_USAGE 
                    WHERE 
                        TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = 'hotel_bookings'
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                
                ");
                
                if (!empty($foreignKeys)) {
                    echo "\nForeign Keys:\n";
                    foreach ($foreignKeys as $fk) {
                        echo "- {$fk->COLUMN_NAME} -> {$fk->REFERENCED_TABLE_NAME}({$fk->REFERENCED_COLUMN_NAME}) [{$fk->CONSTRAINT_NAME}]\n";
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Database check failed: ' . $e->getMessage());
            echo "\nError: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a read-only migration
    }
};
