<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId("staff_id")->references('id')->on("business_staff_members");
            $table->string("timezone")->default("UTC");
            $table->enum('is_default', ['yes', 'no'])->default("no");
            $table->timestamps();

            $table->unique(['name','staff_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_schedules');
    }
}
