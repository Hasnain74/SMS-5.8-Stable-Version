<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no');
            $table->string('class_id');
            $table->string('class_name');
            $table->string('student_id');
            $table->string('student_name');
            $table->string('other_amount')->nullable();
            $table->string('other_fee_type')->nullable();
            $table->string('total_amount');
            $table->string('paid_amount')->default('0');
            $table->string('paid_date')->default('Not paid yet');
            $table->string('arrears');
            $table->string('total_payable_fee');
            $table->string('previous_month_fee');
            $table->string('month');
            $table->string('issue_date');
            $table->string('due_date');
            $table->string('year')->default('y-m-d');;
            $table->string('month_year');
            $table->string('concession');
            $table->string('transport_fee')->nullable();
            $table->string('invoice_created_by')->nullable();
            $table->string('prospectus')->nullable();
            $table->string('admin_and_management_fee')->nullable();
            $table->string('books')->nullable();
            $table->string('security_fee')->nullable();
            $table->string('uniform')->nullable();
            $table->string('fine_panalties')->nullable();
            $table->string('printing_and_stationary')->nullable();
            $table->string('promotion_fee')->nullable();
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
        Schema::dropIfExists('fees');
    }
}
