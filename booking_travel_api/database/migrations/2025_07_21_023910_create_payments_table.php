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
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
    $table->decimal('amount', 10, 2);
    $table->string('currency', 10);
    $table->decimal('total_amount', 10, 2);
    $table->string('payment_method');
    $table->string('transaction_id')->unique();
    $table->date('payment_date');
    $table->string('status');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
