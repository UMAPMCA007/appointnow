<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignupFormIdColumnToBusinessServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_services', function (Blueprint $table) {
            $table->unsignedBigInteger("signup_form_id")->nullable();
            $table->foreign("signup_form_id")->references("id")->on("business_service_signup_forms");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_services', function (Blueprint $table) {
            $table->dropColumn('signup_form_id');
        });
    }
}
