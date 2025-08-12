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
        Schema::create('hotel_metadata', function (Blueprint $table) {
            $table->id('hotel_id');
            $table->string('name');
            $table->text('address')->nullable();
            $table->float('star_rating')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('website_url')->nullable();
            $table->string('map')->nullable();
            $table->date('days')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('adventure_id');
            $table->foreign('adventure_id')->references('id')->on('adventures')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_metadata');
    }
};
