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
