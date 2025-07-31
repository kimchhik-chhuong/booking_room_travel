<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDestinationIdToHotelMetadataTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->unsignedBigInteger('destination_id')->nullable()->after('hotel_id');

            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->dropForeign(['destination_id']);
            $table->dropColumn('destination_id');
        });
    }
}
