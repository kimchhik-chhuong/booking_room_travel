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
        Schema::table('adventures', function (Blueprint $table) {
            if (Schema::hasColumn('adventures', 'image')) {
                $table->renameColumn('image', 'image_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adventures', function (Blueprint $table) {
            if (Schema::hasColumn('adventures', 'image_url')) {
                $table->renameColumn('image_url', 'image');
            }
        });
    }
};
