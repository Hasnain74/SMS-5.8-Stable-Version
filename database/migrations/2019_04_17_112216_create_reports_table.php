<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id');
            $table->integer('student_primary_id')->unsigned()->index();
            $table->foreign('student_primary_id')->references('id')->on('students')->onDelete('cascade');
            $table->string('student_name');
            $table->integer('class_id');
            $table->string('class_name');
            $table->string('subject');
            $table->string('teacher_name');
            $table->string('report_categories_id');
            $table->string('report_categories_name');
            $table->string('total_marks');
            $table->string('obtained_marks');
            $table->string('percentage');
            $table->string('position');
            $table->string('grade');
            $table->string('date');
            $table->string('created_by');
            $table->softDeletes();
            $table->string('status')->default('unpost');
            $table->string('year');
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
        Schema::dropIfExists('reports');
    }
}
