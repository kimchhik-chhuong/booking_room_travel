<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('adventure_id');
            $table->string('name');
            $table->string('image');
            $table->decimal('price', 8, 2);
            $table->integer('day');
            $table->text('description');
            $table->timestamps();

            $table->foreign('adventure_id')->references('id')->on('adventures')->onDelete('cascade');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotels');
    }
}
