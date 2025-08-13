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
    Schema::table('packages', function (Blueprint $table) {
        if (!Schema::hasColumn('packages', 'location')) {
            $table->string('location')->nullable();
        }
        if (!Schema::hasColumn('packages', 'duration')) {
            $table->string('duration')->nullable();
        }
        // Repeat for other new fields
    });
}


public function down(): void
{
    Schema::table('packages', function (Blueprint $table) {
        $table->dropColumn([
            'location', 'duration', 'price', 'rating',
            'bookings', 'status', 'image', 'category'
        ]);
    });
}

};
