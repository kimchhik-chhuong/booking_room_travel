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
Schema::create('notifications', function (Blueprint $table) {
    $table->id('notification_id');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
    $table->string('type');
    $table->text('message');
    $table->timestamp('sent_at')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
