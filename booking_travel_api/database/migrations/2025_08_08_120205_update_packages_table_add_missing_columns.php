<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePackagesTableAddMissingColumns extends Migration
{
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('packages', 'duration')) {
                $table->string('duration')->nullable();
            }
            if (!Schema::hasColumn('packages', 'rating')) {
                $table->decimal('rating', 3, 1)->nullable();
            }
            if (!Schema::hasColumn('packages', 'bookings')) {
                $table->integer('bookings')->default(0);
            }
            if (!Schema::hasColumn('packages', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('packages', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('packages', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('packages', 'price')) {
                $table->string('price')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'location', 'duration', 'rating', 'bookings', 'status', 'image', 'category', 'price'
            ]);
        });
    }
}
