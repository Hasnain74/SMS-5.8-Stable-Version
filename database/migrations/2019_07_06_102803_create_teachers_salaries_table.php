<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers_salaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no');
            $table->string('teacher_id');
            $table->string('teacher_name');
            $table->string('payable_amount');
            $table->string('paid_amount');
            $table->string('absent_days');
            $table->string('cash_out');
            $table->string('date');
            $table->string('year');
            $table->string('month_year');
            $table->string('invoice_created_by');
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
        Schema::dropIfExists('teachers_salaries');
    }
}
