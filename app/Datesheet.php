<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datesheet extends Model
{
    protected $fillable = [
        'class_id',
        'class_name',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday'
    ];

    public function studentsClass() {
        return $this->belongsToMany('App\StudentsClass');
    }
}
