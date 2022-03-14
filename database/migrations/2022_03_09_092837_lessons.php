<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Lessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->integer('device_id');
            $table->timestamp('lesson_start');
            $table->timestamp('lesson_end');
            $table->integer('lesson_driver');
            $table->float('max_speed', 15, 12)->default(0);
            $table->integer('harsh_braking_count')->default(0);
            $table->integer('rapid_acceleration_count')->default(0);
            $table->integer('wide_turn_count')->default(0);
            $table->integer('lesson_student');
            $table->float('grade', 8, 2)->default(0);
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
        Schema::dropIfExists('lessons');
    }
}
