<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_metadata_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('max_occupancy');
            $table->integer('available_rooms');
            $table->json('amenities')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Add index
            $table->index('hotel_metadata_id');
        });

        // Add foreign key in a separate statement to ensure the table exists first
        DB::statement('
            ALTER TABLE room_types 
            ADD CONSTRAINT room_types_hotel_metadata_id_foreign 
            FOREIGN KEY (hotel_metadata_id) 
            REFERENCES hotel_metadata(hotel_id) 
            ON DELETE CASCADE;
        ');
    }

    public function down()
    {
        Schema::dropIfExists('room_types');
    }
};