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
        Schema::table('hotel_metadata', function (Blueprint $table) {
            // Add location coordinates for map functionality
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            
            // Add multiple images support (JSON array of image URLs)
            $table->json('images')->nullable()->after('image_url');
            
            // Add amenities as JSON array
            $table->json('amenities')->nullable()->after('description');
            
            // Add email field
            $table->string('email')->nullable()->after('contact_phone');
            
            // Add check-in/check-out times
            $table->time('check_in_time')->default('14:00')->after('email');
            $table->time('check_out_time')->default('12:00')->after('check_in_time');
            
            // Add province relationship
            $table->unsignedBigInteger('province_id')->nullable()->after('adventure_id');
            
            // Add status field
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active')->after('province_id');
            
            // Add foreign key for province
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_metadata', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropColumn([
                'latitude',
                'longitude', 
                'images',
                'amenities',
                'email',
                'check_in_time',
                'check_out_time',
                'province_id',
                'status'
            ]);
        });
    }
};
