<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 30);
            $table->string('last_name', 30);
            $table->string('teacher_id');
            $table->string('date_of_birth');
            $table->string('join_date');
            $table->string('gender');
            $table->string('teacher_qualification');
            $table->string('teacher_subject')->nullable();
            $table->string('exp_detail');
            $table->string('nic_no');
            $table->string('photo_id')->nullable();
            $table->string('phone_no');
            $table->string('emergency_no')->nullable();
            $table->string('full_address');
            $table->string('salary');
            $table->softDeletes();
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
        Schema::dropIfExists('teachers');
    }
}
