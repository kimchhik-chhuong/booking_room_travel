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
        if (!Schema::hasColumn('room_types', 'is_available')) {
            Schema::table('room_types', function (Blueprint $table) {
                $table->boolean('is_available')->default(true)->after('available_rooms');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('room_types', 'is_available')) {
            Schema::table('room_types', function (Blueprint $table) {
                $table->dropColumn('is_available');
            });
        }
    }
};
