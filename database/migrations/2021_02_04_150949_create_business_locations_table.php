<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_locations', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->unsignedBigInteger("business_id");
            $table->string('address', 500);
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('code', 10);
            $table->enum('status', ['active', 'disabled'])->default("active");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("business_id")->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_locations');
    }
}
