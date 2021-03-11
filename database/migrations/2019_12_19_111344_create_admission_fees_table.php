<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmissionFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('class_id');
            $table->string('class_name');
            $table->string('invoice_no');
            // $table->string('student_id_no');
            $table->string('student_id');
            // $table->string('student_name_no');
            $table->string('student_name');
            $table->string('admission_fee');
            $table->string('paid_amount');
            $table->string('arrears');
            $table->string('paid_date');
            $table->string('created_by');
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
        Schema::dropIfExists('admission_fees');
    }
}
