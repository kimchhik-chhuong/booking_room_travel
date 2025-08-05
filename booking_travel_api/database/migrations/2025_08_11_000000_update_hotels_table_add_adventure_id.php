<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHotelsTableAddAdventureId extends Migration
{
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('adventure_id')->nullable()->after('province_id');
            $table->foreign('adventure_id')->references('id')->on('adventures')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            // First drop foreign key constraint explicitly
            $table->dropForeign(['adventure_id']);

            // Then drop the column
            $table->dropColumn('adventure_id');
        });
    }
}
