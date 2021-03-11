<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
      'invoice_no',
      'class_id',
      'class_name',
      'student_id',
      'student_name',
      'total_amount',
      'other_amount',
      'previous_month_fee',
      'total_payable_fee',
      'other_fee_type',
      'paid_amount',
      'paid_date',
      'arrears',
      'concession',
      'issue_date',
      'due_date',
      'month',
      'month_year',
      'year',
      'invoice_created_by',
      'transport_fee',
      'invoice_created_by',
      'prospectus',
      'admin_and_management_fee',
      'books',
      'security_fee',
      'uniform',
      'fine_panalties',
      'printing_and_stationary',
      'promotion_fee',
      'percentage',
      'guardian_name'
    ];

    public function students() {
        return $this->hasMany('App\Student');
    }

    public function studentsClass() {
        return $this->belongsTo('App\StudentsClass', 'class_id');
    }


    public function reports()
    {
        return $this->hasMany(FeeReport::class, 'invoice_no');
    }

}
