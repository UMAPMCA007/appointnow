<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColumnToBusinessServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_services', function (Blueprint $table) {
            $table->decimal('price', $precision = 8, $scale = 2)->default(0.00);
            $table->string('currency', 3)->nullable();
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
            $table->dropColumn(["price", "currency"]);
        });
    }
}
