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
        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Check if the table exists
            if (!Schema::hasTable('hotel_bookings')) {
                return;
            }

            // Check if the column exists
            if (!Schema::hasColumn('hotel_bookings', 'hotel_metadata_id')) {
                Schema::table('hotel_bookings', function (Blueprint $table) {
                    $table->unsignedBigInteger('hotel_metadata_id')->nullable()->after('booking_id');
                });
            }

            // Safely drop the foreign key if it exists
            if (Schema::hasColumn('hotel_bookings', 'hotel_id')) {
                Schema::table('hotel_bookings', function (Blueprint $table) {
                    // Try to drop the foreign key if it exists
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $foreignKeys = collect($sm->listTableForeignKeys('hotel_bookings'))
                        ->filter(fn($fk) => in_array('hotel_id', $fk->getLocalColumns()));

                    foreach ($foreignKeys as $foreignKey) {
                        $table->dropForeign([$foreignKey->getLocalColumns()[0]]);
                    }

                    // Drop the column
                    $table->dropColumn('hotel_id');
                });
            }

            // Add the new foreign key constraint
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->foreign('hotel_metadata_id')
                      ->references('id')
                      ->on('hotel_metadata')
                      ->onDelete('cascade')
                      ->change();
            });

        } catch (\Exception $e) {
            // Log the error but don't stop execution
            \Log::error('Migration error: ' . $e->getMessage());
        } finally {
            // Always re-enable foreign key checks
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
