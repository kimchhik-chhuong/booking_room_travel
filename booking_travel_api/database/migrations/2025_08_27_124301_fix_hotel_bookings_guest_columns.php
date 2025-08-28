<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, ensure the table exists
        if (!Schema::hasTable('hotel_bookings')) {
            return;
        }

        // Get existing columns
        $existingColumns = collect(DB::select('SHOW COLUMNS FROM hotel_bookings'))
            ->pluck('Field')
            ->toArray();

        // Columns we want to add if they don't exist
        $columnsToAdd = [
            'guest_name' => 'string',
            'guest_email' => 'string',
            'guest_phone' => 'string',
            'nationality' => 'string',
            'special_requests' => 'text'
        ];

        foreach ($columnsToAdd as $column => $type) {
            if (!in_array($column, $existingColumns)) {
                Schema::table('hotel_bookings', function (Blueprint $table) use ($column, $type) {
                    if ($type === 'text') {
                        $table->text($column)->nullable();
                    } else {
                        $table->string($column)->nullable();
                    }
                });
            }
        }
    }
    
    public function down()
    {
        // No need to implement down() as we're only adding missing columns
    }
};