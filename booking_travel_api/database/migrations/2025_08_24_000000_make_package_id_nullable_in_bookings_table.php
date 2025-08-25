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
        // For MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE bookings MODIFY package_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE bookings DROP FOREIGN KEY bookings_package_id_foreign');
            DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL');
        }
        // For SQLite (for testing)
        elseif (DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support modifying columns, so we need to recreate the table
            // This is a simplified version - you might need to adjust based on your schema
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['package_id']);
                $table->foreignId('package_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // For MySQL
        if (DB::getDriverName() === 'mysql') {
            // First, update any null values to a default value (e.g., 1)
            DB::table('bookings')->whereNull('package_id')->update(['package_id' => 1]);
            
            // Then make the column not nullable
            DB::statement('ALTER TABLE bookings MODIFY package_id BIGINT UNSIGNED NOT NULL');
            
            // Recreate the original foreign key constraint
            DB::statement('ALTER TABLE bookings DROP FOREIGN KEY bookings_package_id_foreign');
            DB::statement('ALTER TABLE bookings ADD CONSTRAINT bookings_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE');
        }
        // For SQLite
        elseif (DB::getDriverName() === 'sqlite') {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['package_id']);
                $table->foreignId('package_id')->nullable(false)->change();
            });
        }
    }
};
