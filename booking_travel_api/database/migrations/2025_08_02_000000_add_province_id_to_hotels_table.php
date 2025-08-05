<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvinceIdToHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'province_id')) {
                $table->unsignedBigInteger('province_id')->nullable()->after('id');
                $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'province_id')) {
                // Drop foreign key if exists
                $table->dropForeign(['province_id']);
                $table->dropColumn('province_id');
            }
        });
    }
}
