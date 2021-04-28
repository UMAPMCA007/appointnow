<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessServiceSignupFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_service_signup_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId("signup_form_id")
                  ->references('id')
                  ->on("business_service_signup_forms");
            $table->string('description', 255);
            $table->string('type', 50);
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
        Schema::dropIfExists('business_service_signup_form_fields');
    }
}
