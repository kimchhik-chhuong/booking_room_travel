<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // This migration is no longer needed as the foreign key is now handled by 2025_12_31_000000_add_room_types_foreign_key
        // Keeping it as an empty migration to maintain migration history
    }

    public function down()
    {
        // No need to drop the foreign key in the down method
        // as the table will be dropped by the create_room_types_table migration
    }
};
