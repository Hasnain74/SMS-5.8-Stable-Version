<?php

namespace App\Http\Controllers;

use App\Datesheet;
use App\Fee;
use App\Report;
use App\Sms;
use App\Student;
use App\StudentsAttendance;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{

    public function studentData() {

        $user = Auth::user();
        $student = Student::where('student_id','=', $user->user_id)->get();
        $attendance = StudentsAttendance::where('student_id', '=', $user->user_id)->orderBy('id', 'desc')->get();
        $reports = Report::where([['student_id', '=', $user->user_id], ['status', '=', 'post']])->orderBy('id', 'desc')->get();
        $fees = Fee::where('student_id', '=', $user->user_id)->orderBy('id', 'desc')->get();
        $msgs = Sms::where('student_id', '=', $user->user_id)->orderBy('id', 'desc')->get();
        $timetable = \App\DayWiseTimetable::all();
        $datesheet = Datesheet::where('class_id','=', $student[0]->students_class_id)->get();
        return view('student.index', compact('user', 'student', 'attendance', 'reports', 'fees', 'msgs', 'timetable', 'datesheet'));
    }

}
