<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeachersSalary extends Model
{

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
      'invoice_no',
      'teacher_id',
      'teacher_name',
      'payable_amount',
      'paid_amount',
      'absent_days',
      'cash_out',
      'month_year',
      'date',
        'year',
      'invoice_created_by'
    ];

    public function user() {
        return $this->belongsToMany('App\User');
    }
}
