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
    public function up()
    {
        // First, drop the foreign key constraint using a direct SQL statement
        DB::statement('ALTER TABLE bookings DROP FOREIGN KEY IF EXISTS bookings_package_id_foreign');
        
        // Then modify the column to be nullable
        DB::statement('ALTER TABLE bookings MODIFY package_id BIGINT UNSIGNED NULL');

        // Recreate the foreign key constraint with onDelete('set null')
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_package_id_foreign 
            FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop the foreign key constraint
        DB::statement('ALTER TABLE bookings DROP FOREIGN KEY IF EXISTS bookings_package_id_foreign');
        
        // Set any null values to a default value (e.g., 1 or another valid package ID)
        DB::table('bookings')->whereNull('package_id')->update(['package_id' => 1]);
        
        // Make the column not nullable
        DB::statement('ALTER TABLE bookings MODIFY package_id BIGINT UNSIGNED NOT NULL');
        
        // Recreate the original foreign key constraint
        DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_package_id_foreign 
            FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE');
    }
};
