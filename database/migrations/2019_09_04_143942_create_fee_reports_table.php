<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no');
            $table->string('class_id');
            $table->string('class_name');
            $table->string('student_id');
            $table->string('student_name');
            $table->string('paid_amount');
            $table->string('paid_date');
            $table->string('month');
            $table->string('month_year');
            $table->string('year');
            $table->string('issue_date')->default('-');
            $table->string('due_date')->default('-');
            $table->string('invoice_created_by');
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
        Schema::dropIfExists('fee_reports');
    }
}
