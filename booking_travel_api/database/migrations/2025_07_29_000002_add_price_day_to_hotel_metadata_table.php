<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceDayToHotelMetadataTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('star_rating');
            $table->integer('day')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->dropColumn(['price', 'day']);
        });
    }
}
