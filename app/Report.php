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
    
     public static function datatableColumns() {
        return [
            ['data' => 'checkbox', 'name' => 'checkbox', 'searchable'=> false, 'orderable'=> false, 'title' => '<input type="checkbox" id="dataTablesCheckbox">'],
            ['data' => 'id', 'name' => 'id', 'title' => 'Id', 'className'=> 'text-center', 'visible' => false],
            ['data' => 'student_id', 'name' => 'student_id', 'title' => 'Student ID', 'className'=> 'text-center'],
            ['data' => 'student_name', 'name' => 'student_name', 'title' => 'Student Name', 'className'=> 'text-center'],
            ['data' => 'report_categories_name', 'name' => 'report_categories_name', 'title' => 'Report Category', 'className'=> 'text-center'],
            ['data' => 'subject', 'name' => 'subject', 'title' => 'Subject', 'className'=> 'text-center'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created Date', 'className'=> 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Actions', 'sortable' => false, 'className'=> 'text-center'],
        ];
    }

    public function studentsClass() {
        return $this->belongsTo('App\StudentsClass', 'class_id');
    }

    public function student() {
        return $this->belongsTo('App\Student');
    }

}
