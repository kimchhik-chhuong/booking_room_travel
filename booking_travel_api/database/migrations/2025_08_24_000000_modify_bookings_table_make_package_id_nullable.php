<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // First, we need to drop the foreign key constraint
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
        });

        // Then modify the column to be nullable
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->change();
        });

        // Recreate the foreign key constraint with onDelete('set null')
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('package_id')
                  ->references('id')
                  ->on('packages')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // First, drop the foreign key constraint
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
        });

        // Set any null values to a default value (e.g., 1 or another valid package ID)
        // Note: You'll need to ensure the package ID exists in the packages table
        \DB::table('bookings')->whereNull('package_id')->update(['package_id' => 1]);

        // Change the column back to not nullable
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable(false)->change();
        });

        // Recreate the original foreign key constraint
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('package_id')
                  ->references('id')
                  ->on('packages')
                  ->onDelete('cascade');
        });
    }
};
