<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, check if the problematic migration is in migrations table
        $migration = DB::table('migrations')
            ->where('migration', '2025_08_24_000001_add_guest_columns_to_hotel_bookings')
            ->first();

        // If the migration is not marked as run, mark it as run
        if (!$migration) {
            DB::table('migrations')->insert([
                'migration' => '2025_08_24_000001_add_guest_columns_to_hotel_bookings',
                'batch' => 1
            ]);
        }

        // Now safely add any missing columns
        $columnsToAdd = [
            'guest_name' => 'string',
            'guest_email' => 'string',
            'guest_phone' => 'string',
            'nationality' => 'string',
            'special_requests' => 'text'
        ];

        foreach ($columnsToAdd as $column => $type) {
            if (!Schema::hasColumn('hotel_bookings', $column)) {
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
        // No need to implement down() as we're just fixing the migration state
    }
};