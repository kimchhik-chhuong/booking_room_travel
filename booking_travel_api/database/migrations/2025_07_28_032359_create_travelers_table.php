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
Schema::create('travelers', function (Blueprint $table) {
    $table->id('traveler_id');
    $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
    $table->string('first_name');
    $table->string('last_name');
    $table->date('date_of_birth')->nullable();
    $table->string('gender')->nullable();
    $table->string('email')->nullable();
    $table->string('phone_number')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};
