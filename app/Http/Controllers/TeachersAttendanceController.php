<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\TeachersAttendanceRequest;
use App\StudentsClass;
use App\Teacher;
use App\TeachersAttendance;
use App\User;
use Illuminate\Http\Request;


class TeachersAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Teacher::all();
        $attendance = TeachersAttendance::all();
        return view('admin.teachers.attendance.take_attendance', compact('users', 'attendance'));
    }

    public function teacher_attendance_register() {
        $teacher_id = Teacher::pluck('teacher_id', 'id');
        $attendance = TeachersAttendance::all();
        $attendances = array('Present', 'Absent', 'Leave');
        return view('admin.teachers.attendance.teacher_attendance_register',
            compact( 'attendance', 'attendances', 'teacher_id'));
    }

    public function TeachersAtdAjax($id) {
        $students_id = TeachersAttendance::where('teacher_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($students_id);
    }

    public function deleteAttendance(Request $request) {
        if(!empty($request->checkBoxArray)) {
            TeachersAttendance::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_teacher_attendance','The attendance has been deleted successfully !');
        } else {
            return redirect()->back()->with('delete_teacher_attendance','The attendance has been deleted successfully !');
        }
    }

    public function deleteStudents(Request $request) {
        if(!empty($request->checkBoxArray)) {
            TeachersAttendance::whereIn('id', $request->checkBoxArray)->delete();
            return response()->json();
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeachersAttendanceRequest $request)
    {
        $input = $request->all();
        $date = $input['date'];

        foreach($input['teacher_id'] as $i => $teacher_id) {
            TeachersAttendance::create([
                'date' => $date,
                'teacher_id' => $teacher_id,
                'teacher_name' => $input['name'][$i],
                'attendance' => $input['attendance'][$i]
            ]);
        }

        return redirect('/teachers/teacher_attendance_register')
            ->with('create_teacher_attendance','The attendance has been added successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = TeachersAttendance::find($id);
        $attendance->update(['attendance' => $request->attendance[0]]);
        return redirect()->back()
            ->with('update_teacher_attendance','The attendance has been updated successfully !');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atd = TeachersAttendance::findOrFail($id);
        $atd->delete();
        return redirect()->back()->with('delete_teacher_attendance','The attendance has been deleted successfully !');
    }
}
