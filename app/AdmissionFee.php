<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdmissionFee extends Model
{
    protected $fillable = [
      'class_id',
      'class_name',
      'invoice_no',
      'student_id',
      // 'student_id_no',
      'student_name',
      // 'student_name_no',
      'admission_fee',
      'paid_amount',
      'arrears',
      'paid_date',
      'created_by',
    ];
}
