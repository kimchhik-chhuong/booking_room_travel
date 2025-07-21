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
Schema::create('reviews', function (Blueprint $table) {
    $table->id('review_id');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('entity_type');
    $table->unsignedBigInteger('entity_id');
    $table->integer('rating');
    $table->text('comment')->nullable();
    $table->date('review_date');
    $table->timestamps();

    $table->index(['entity_type', 'entity_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
