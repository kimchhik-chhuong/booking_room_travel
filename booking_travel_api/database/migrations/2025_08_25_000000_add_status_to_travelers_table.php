<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('travelers', 'status')) {
            Schema::table('travelers', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            });
        } else {
            // If column exists, make sure it has the correct definition
            Schema::table('travelers', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive'])->default('active')->change();
            });
        }
    }

    public function down()
    {
        // Only drop the column if it was added by this migration
        if (Schema::hasColumn('travelers', 'status')) {
            Schema::table('travelers', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
