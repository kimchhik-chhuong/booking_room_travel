<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('travelers', function (Blueprint $table) {
            if (!Schema::hasColumn('travelers', 'booking_id')) {
                $table->foreignId('booking_id')
                      ->after('id')
                      ->constrained('bookings')
                      ->cascadeOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('travelers', function (Blueprint $table) {
            if (Schema::hasColumn('travelers', 'booking_id')) {
                $table->dropForeign(['booking_id']);
                $table->dropColumn('booking_id');
            }
        });
    }
};
