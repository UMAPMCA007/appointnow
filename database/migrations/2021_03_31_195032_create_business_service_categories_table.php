<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessServiceCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId("business_id")->references("id")->on("businesses");
            $table->string('name', 255);
            $table->enum("status", ['active', 'disabled'])->default("active");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_service_categories');
    }
}
