<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessServiceStaffMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_service_staff_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId("business_service_id")->references('id')->on('business_services')->onDelete("cascade");
            $table->foreignId("business_staff_member_id")->references('id')->on("business_staff_members")->onDelete("cascade");
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
        Schema::dropIfExists('business_service_staff_members');
    }
}
