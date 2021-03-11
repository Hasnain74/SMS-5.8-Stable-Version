<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentsClass extends Model
{
    protected $fillable = [
        'class_name',
        'class_fee',
        'class_teacher'
    ];

    public function students() {
        return $this->hasMany('App\Student');
    }

    public function reports() {
        return $this->hasMany('App\Reports');
    }

    public function users() {
        return $this->belongsTo('App\User');
    }

    public function timetables() {
        return $this->belongsTo('App\Timetable');
    }

    public function findStudents($class_id, $fee_setup) {
        return $this->hasMany('App\Student')->where([['students_class_id', '=', $class_id], ['fee_setup', '=', $fee_setup]])->get();
    }

    public function update_students_class($class_id, $class_name) {
        $students = Student::where('students_class_id', '=', $class_id)->get();
        foreach($students as $student) {
            $student['students_class_name'] = $class_name;
            $student->update();
        }
    }

    public function update_students_attendance($class_id, $class_name) {
        $students = StudentsAttendance::where('class_id', '=', $class_id)->get();
        foreach($students as $student) {
            $student['class_name'] = $class_name;
            $student->update();
        }
    }

    public function update_timetable($class_id, $class_name) {
        $timetables = Timetable::where('class_id', '=', $class_id)->get();
        foreach($timetables as $timetable) {
            $timetable['class_name'] = $class_name;
            $timetable->update();
        }
    }

    public function update_datesheet($class_id, $class_name) {
        $datesheets = Datesheet::where('class_id', '=', $class_id)->get();
        foreach($datesheets as $datesheet) {
            $datesheet['class_name'] = $class_name;
            $datesheet->update();
        }
    }

    public function update_fees($class_id, $class_name) {
        $fees = Fee::where('class_id', '=', $class_id)->get();
        foreach($fees as $fee) {
            $fee['class_name'] = $class_name;
            $fee->update();
        }
    }

    public function update_reports($class_id, $class_name) {
        $reports = Report::where('class_id', '=', $class_id)->get();
        foreach($reports as $report) {
            $report['class_name'] = $class_name;
            $report->update();
        }
    }

    public function update_subjects($class_id, $class_name) {
        $subjects = Subjects::where('class_id', '=', $class_id)->get();
        foreach($subjects as $subject) {
            $subject['class_name'] = $class_name;
            $subject->update();
        }
    }

}
