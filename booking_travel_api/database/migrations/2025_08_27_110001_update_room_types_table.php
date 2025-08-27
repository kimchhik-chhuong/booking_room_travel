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
        // First, drop the foreign key constraint
        Schema::table('room_types', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['hotel_metadata_id']);
            
            // Rename the column
            $table->renameColumn('hotel_metadata_id', 'hotel_id');
        });

        // Add the new foreign key constraint
        Schema::table('room_types', function (Blueprint $table) {
            $table->foreign('hotel_id')
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
        // Drop the foreign key constraint
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            
            // Rename the column back
            $table->renameColumn('hotel_id', 'hotel_metadata_id');
        });

        // Add back the original foreign key constraint
        Schema::table('room_types', function (Blueprint $table) {
            $table->foreign('hotel_metadata_id')
                  ->references('hotel_id')
                  ->on('hotel_metadata')
                  ->onDelete('cascade');
        });
    }
};
