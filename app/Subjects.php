<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    protected $fillable = [
      'subject_name',
      'subject_marks',
      'class_id',
      'class_name',
      'report_type_id',
      'report_type_name',
      'subject_teacher'
    ];
}
