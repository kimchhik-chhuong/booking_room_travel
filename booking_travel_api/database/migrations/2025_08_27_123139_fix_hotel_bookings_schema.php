<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing table if it exists
        Schema::dropIfExists('hotel_bookings');

        // Recreate the table with the correct structure
        Schema::create('hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('hotel_metadata_id');
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('num_guests');
            $table->integer('num_rooms');
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_hotel_price', 10, 2);
            $table->string('status');
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->string('nationality')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('hotel_metadata_id')->references('hotel_id')->on('hotel_metadata')->onDelete('cascade');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};