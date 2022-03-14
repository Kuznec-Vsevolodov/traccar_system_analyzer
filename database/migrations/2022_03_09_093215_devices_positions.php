<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DevicesPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id');
            $table->integer('lesson_id');
            $table->float('speed', 15, 12)->default(0);
            $table->float('longitude', 15, 12)->default(0);
            $table->float('latitude', 15, 12)->default(0);
            $table->float('distance', 8, 2)->default(0);
            $table->integer('course')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices_positions');
    }
}
