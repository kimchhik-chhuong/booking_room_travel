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
        // First, drop any existing foreign key constraints that might cause issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Check if the column exists and has the right type
        if (!Schema::hasColumn('hotel_bookings', 'hotel_metadata_id')) {
            Schema::table('hotel_bookings', function (Blueprint $table) {
                $table->unsignedBigInteger('hotel_metadata_id')->after('booking_id');
            });
        }

        // Add the foreign key constraint
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // Drop any existing foreign key constraints on this column
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $tableName = $table->getTable();
            $foreignKeys = collect($sm->listTableForeignKeys($tableName))
                ->filter(function($fk) {
                    return in_array('hotel_metadata_id', $fk->getLocalColumns()) || 
                           in_array('hotel_id', $fk->getLocalColumns());
                });

            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign([$foreignKey->getLocalColumns()[0]]);
            }

            // Add the correct foreign key constraint
            $table->foreign('hotel_metadata_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
    }
};
