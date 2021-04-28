<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffScheduleDayHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_schedule_day_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_schedule_day_id')->references('id')->on("staff_schedule_days");
            $table->json('start_time');
            $table->json('end_time');
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
        Schema::dropIfExists('staff_schedule_day_hours');
    }
}
