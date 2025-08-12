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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_metadata_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('max_occupancy')->default(2);
            $table->integer('available_rooms')->default(0);
            $table->json('amenities')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->foreign('hotel_metadata_id')->references('hotel_id')->on('hotel_metadata')->onDelete('cascade');
        });

        // Remove price column from hotel_metadata table
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');

        // Add price column back to hotel_metadata
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
    }
};
