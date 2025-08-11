<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('duration');
            $table->decimal('price', 10, 2);
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('bookings')->default(0);
            $table->enum('status', ['Active', 'Draft'])->default('Active');
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
