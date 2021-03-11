<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id')->index()->unsigned()->nullable();
            $table->string('class_name');
            $table->string('student_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('attendance');
            $table->string('date');
            $table->string('attendance_type_id');
            $table->string('attendance_type');
            $table->string('year');
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
        Schema::dropIfExists('students_attendances');
    }
}
