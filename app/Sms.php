<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sms extends Model
{

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
      'class',
      'student_id',
      'student_name',
      'guardian_phone_no',
      'message',
      'created_at',
      'sent_by',
    ];
}
