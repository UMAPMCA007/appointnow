<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessStaffMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_staff_members', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->string("email", 255);
            $table->string('password');
            $table->enum("status", ['active', 'disabled'])->default("active");
            $table->string('code', 10)->unique();
            $table->string("timezone")->default("UTC");
            $table->unsignedBigInteger("business_id");
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();

            $table->foreign("business_id")->references("id")->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_staff_members');
    }
}
