<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $columnsToAdd = [
                'guest_name' => 'string',
                'guest_email' => 'string',
                'guest_phone' => 'string',
                'nationality' => 'string',
                'special_requests' => 'text'
            ];

            foreach ($columnsToAdd as $column => $type) {
                if (!Schema::hasColumn('hotel_bookings', $column)) {
                    if ($type === 'text') {
                        $table->text($column)->nullable();
                    } else {
                        $table->string($column)->nullable();
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $columns = [
                'guest_name',
                'guest_email',
                'guest_phone',
                'nationality',
                'special_requests'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('hotel_bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};