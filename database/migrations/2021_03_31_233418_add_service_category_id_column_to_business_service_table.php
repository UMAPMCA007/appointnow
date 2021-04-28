<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceCategoryIdColumnToBusinessServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_services', function (Blueprint $table) {
            $table->unsignedBigInteger("service_category_id")->nullable();
            $table->foreign("service_category_id")->references("id")->on("business_service_categories");
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
            $table->dropColumn("service_category_id");
        });
    }
}
