<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequiredColumnToBusinessServiceSignupFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_service_signup_form_fields', function (Blueprint $table) {
            $table->enum('is_required', ["yes", "no"])->default("yes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_service_signup_form_fields', function (Blueprint $table) {
            $table->dropColumn("is_required");
        });
    }
}
