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
Schema::create('restaurant_metadata', function (Blueprint $table) {
    $table->id('restaurant_id');
    $table->string('name');
    $table->text('address')->nullable();
    $table->string('cuisine_type')->nullable();
    $table->text('description')->nullable();
    $table->string('image_url')->nullable();
    $table->float('latitude')->nullable();
    $table->float('longitude')->nullable();
    $table->string('contact_phone')->nullable();
    $table->string('website_url')->nullable();
    $table->string('opening_hours')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_metadata');
    }
};
