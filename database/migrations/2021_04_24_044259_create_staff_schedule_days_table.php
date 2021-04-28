<?php

use App\Enums\DayType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffScheduleDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_schedule_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_schedule_id')->references('id')->on("staff_schedules");
            $table->enum('day',DayType::getItems());
            $table->enum("status", ['active', 'disabled'])->default("active");
            $table->text('hours')->nullable();
            $table->timestamps();

            $table->unique(['day', 'staff_schedule_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_schedule_days');
    }
}
