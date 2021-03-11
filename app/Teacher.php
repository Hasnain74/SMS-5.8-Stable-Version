<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{

    // use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'first_name',
        'last_name',
        'teacher_id',
        'date_of_birth',
        'join_date',
        'gender',
        'teacher_qualification',
        'teacher_subject',
        'exp_detail',
        'nic_no',
        'photo_id',
        'phone_no',
        'emergency_no',
        'full_address',
        'salary',
        'status',
    ];

    public function photo() {
        return $this->belongsTo('App\Photo');
    }

    public function teachersSalary() {
        return $this->hasMany('App\TeachersSalary');
    }
}
