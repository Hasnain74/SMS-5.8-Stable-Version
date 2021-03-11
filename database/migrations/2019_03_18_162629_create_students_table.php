<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('DOB');
            $table->string('admission_date');
            $table->integer('students_class_id')->index()->unsigned()->nullable();
            $table->string('students_class_name');
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->string('religion');
            $table->string('photo_id')->nullable();
            $table->string('student_address');
            $table->string('student_phone_no')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_gender');
            $table->string('guardian_relation');
            $table->string('guardian_occupation');
            $table->string('guardian_phone_no');
            $table->string('NIC_no');
            $table->string('fee_setup');
            $table->string('guardian_address');
            $table->string('discount_percent');
            $table->softDeletes();
            $table->string('total_fee');
            $table->string('transport_fee');
            $table->string('status');
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
        Schema::dropIfExists('students');
    }
}
