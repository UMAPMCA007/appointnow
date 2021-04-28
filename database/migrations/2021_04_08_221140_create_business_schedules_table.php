<?php

use App\Enums\DayType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId("business_id")->references('id')->on("businesses");
            $table->string("timezone")->default("UTC");
            $table->enum('is_default', ['yes', 'no'])->default("no");
            $table->timestamps();

            $table->unique(['name', 'business_id']);
        });

        Schema::create('business_schedule_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_schedule_id')->references('id')->on("business_schedules");
            $table->enum('day', DayType::getItems());
            $table->enum("status", ['active', 'disabled'])->default("active");
            $table->text('hours')->nullable();
            $table->timestamps();

            $table->unique(['day', 'business_schedule_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_schedules');
        Schema::dropIfExists("business_schedule_days");
        Schema::dropIfExists("business_schedule_day_hours");
    }
}
