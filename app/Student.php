<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Student extends Model
{

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'student_id',
        'first_name',
        'last_name',
        'DOB',
        'admission_date',
        'students_class_id',
        'students_class_name',
        'gender',
        'blood_group',
        'religion',
        'photo_id',
        'student_address',
        'student_phone_no',
        'guardian_name',
        'guardian_gender',
        'guardian_relation',
        'guardian_occupation',
        'guardian_phone_no',
        'NIC_no',
        'fee_setup',
        'guardian_address',
        'discount_percent',
        'total_fee',
        'transport_fee',
        'status'
    ];

    protected $hidden = [
        'password'
    ];


    public function photo() {
        return $this->belongsTo('App\Photo');
    }

    public function studentsClass() {
        return $this->belongsTo('App\StudentsClass');
    }

    public function studentsAttendance() {
        return $this->hasMany('App\StudentsAttendance');
    }

    public function reports() {
        return $this->hasMany('App\Report', 'student_primary_id');
    }

    public function FinalReports() {
        return $this->hasMany('App\FinalReport', 'student_primary_id');
    }

    public function reportsByType($class_id, $report_categories_name, $year) {
        return $this->hasMany('App\Report', 'student_primary_id')->where([['class_id', '=', $class_id],
            ['report_categories_name', '=', $report_categories_name], [DB::raw('YEAR(created_at)'), '=', $year]])->get()->keyBy('subject');
    }

}
