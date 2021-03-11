<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model {

    // use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'student_id',
        'student_primary_id',
        'student_name',
        'class_id',
        'class_name',
        'subject',
        'teacher_name',
        'report_cat_id',
        'report_categories_id',
        'report_categories_name',
        'total_marks',
        'obtained_marks',
        'percentage',
        'position',
        'grade',
        'status',
        'created_by',
        'date',
        'year'
    ];

    public function studentsClass() {
        return $this->belongsTo('App\StudentsClass', 'class_id');
    }

    public function student() {
        return $this->belongsTo('App\Student');
    }

}
