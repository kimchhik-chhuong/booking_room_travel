<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('image_url')->nullable()->after('image');
            // Note: timestamps() already exist from original migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropColumn(['description', 'image_url']);
            // Note: We don't drop timestamps as they belong to original migration
        });
    }
};
