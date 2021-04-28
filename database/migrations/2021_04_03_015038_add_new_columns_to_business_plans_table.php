<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToBusinessPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_plans', function (Blueprint $table) {
            $table->smallInteger("staff_member_limit")->default(1);
            $table->smallInteger("location_limit")->default(1);
            $table->smallInteger("categories_limit")->default(1);
            $table->smallInteger("services_limit")->default(1);
            $table->smallInteger("signup_form_limit")->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_plans', function (Blueprint $table) {
            $columns = ["staff_member_limit", "location_limit", "categories_limit", "services_limit", "signup_form_limit"];
            $table->dropColumn($columns);
        });
    }
}
