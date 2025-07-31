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
        Schema::create('adventures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for many-to-many relationship between adventures and hotels (if needed)
        Schema::create('adventure_hotel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adventure_id')->constrained('adventures')->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adventure_hotel');
        Schema::dropIfExists('adventures');
    }
};
