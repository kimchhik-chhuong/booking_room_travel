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
Schema::create('hotel_bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
    $table->foreignId('hotel_id')->constrained('hotel_metadata', 'hotel_id')->onDelete('cascade');
    $table->date('check_in_date');
    $table->date('check_out_date');
    $table->string('room_type');
    $table->integer('num_rooms');
    $table->integer('num_guests');
    $table->decimal('price_per_night', 10, 2);
    $table->decimal('total_hotel_price', 10, 2);
    $table->string('status');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
