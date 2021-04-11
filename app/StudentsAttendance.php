<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentsAttendance extends Model
{

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'class_id',
        'class_name',
        'student_id',
        'first_name',
        'last_name',
        'attendance',
        'date',
        'attendance_type',
        'attendance_type_id',
        'year'
    ];


    /**
     * Columns of StudentsAttendance that we need in the datatable
     * For more options take a look to https://yajrabox.com/docs/laravel-datatables/master/html-builder-column
     *
     * @return array
     */
    public static function datatableColumns() {
        return [
            ['data' => 'checkbox', 'name' => 'checkbox', 'searchable'=> false, 'orderable'=> false, 'title' => '<input type="checkbox" id="dataTablesCheckbox">'],
            ['data' => 'id', 'name' => 'id', 'title' => 'Id', 'className'=> 'text-center', 'visible' => false],
            ['data' => 'fullName', 'name' => 'fullName', 'title' => 'Name', 'className'=> 'text-center'],
            ['data' => 'class_name', 'name' => 'class_name', 'title' => 'Class Name', 'className'=> 'text-center'],
            ['data' => 'attendance', 'name' => 'attendance', 'title' => 'Attendance', 'className'=> 'text-center'],
            ['data' => 'date', 'name' => 'date', 'title' => 'Date' ,'className'=> 'text-center'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Actions', 'sortable' => false, 'className'=> 'text-center'],
        ];
    }

    public function studentsClass() {
        return $this->belongsTo('App\StudentsClass');
    }

    public function getDateForHumansAttribute() {
        return $this->created_at->diffForHumans();
    }


//    public function toArray()
//    {
//        $data = parent::toArray();
//
//        $data['diffForHumans'] = $this->diffForHumans;
//
//        return $data;
//    }

}
