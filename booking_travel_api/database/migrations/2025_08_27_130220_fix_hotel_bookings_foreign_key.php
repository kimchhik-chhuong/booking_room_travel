<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            // First, drop any existing foreign key constraints to avoid errors
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $foreignKeys = collect($sm->listTableForeignKeys('hotel_bookings'))
                ->filter(function($fk) {
                    return in_array('hotel_metadata_id', $fk->getLocalColumns()) || 
                           in_array('hotel_id', $fk->getLocalColumns());
                });

            foreach ($foreignKeys as $foreignKey) {
                $table->dropForeign([$foreignKey->getLocalColumns()[0]]);
            }

            // Make sure the column exists and is the correct type
            if (!Schema::hasColumn('hotel_bookings', 'hotel_metadata_id')) {
                $table->unsignedBigInteger('hotel_metadata_id')->after('booking_id');
            }

            // Add the correct foreign key constraint
            $table->foreign('hotel_metadata_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration
    }
};
