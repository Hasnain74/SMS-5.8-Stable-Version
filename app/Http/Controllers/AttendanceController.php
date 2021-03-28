<?php

namespace App\Http\Controllers;

use App\Sms;
use App\Student;
use App\StudentsAttendance;
use App\StudentsClass;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DmcSetup;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;


class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $classes = StudentsClass::where('class_teacher', '=', $user->username)->pluck('class_name', 'id');
        if($user->status == 'admin') {
            $classes = StudentsClass::pluck('class_name', 'id');
        } else {
            $classes = StudentsClass::where('class_teacher', '=', $user->username)->pluck('class_name', 'id');
        }
        $attendance_type = DmcSetup::pluck('report_type', 'id');
        return view('admin.students.attendance.index', compact( 'classes', 'attendance_type'));
    }

    public function mytableAjax($id)
    {
        $students = Student::where([['students_class_id', '=' ,$id], ['status', '=', 'Active']])->get();
        return json_encode($students);
    }

    public function myAttendanceAjax($id) {
        $students_register = StudentsAttendance::where('class_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($students_register);
    }

    public function myAtdAjax($id) {
        $students_id = StudentsAttendance::where('student_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($students_id);
    }

    /**
     * @param Builder $builder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function student_attendance_registers(Builder $builder)
    {
        $query = StudentsAttendance::orderBy('id', 'desc');

        //Ajax request made by {!! $datatable->scripts() !!} when the page is ready
        if (request()->ajax())
        {
            return DataTables::eloquent($query)
                ->editColumn('fullName',function ($data){
                    return $data->first_name .' '. $data->last_name;
                })
                ->filter(function ($query) {
                    //Here you receive the filters key/value and add them to the query
                    if (request()->has('class_id')) {
                        $query->where('class_id', request('class_id'));
                    }
                    if (request()->has('student_id')) {
                        $query->where('student_id', request('student_id'));
                    }
                }, true)
                ->toJson();
        }

        $attendances = $query->get();
        $user = Auth::user();

        if($user->status == 'admin')
        {
            $student_id = Student::where('status', '=', 'Active')->pluck('student_id', 'id');
            $classes = StudentsClass::pluck('class_name', 'id');
        } elseif($user->status == 'teacher')
        {
            $class = StudentsClass::where('class_teacher', '=', $user->username)->get();

            if(count($class) == 0) {
                return redirect()->back();
            }

            $student_id = Student::where([['status', '=', 'Active'], ['students_class_id', '=', $class[0]->id]])->pluck('student_id', 'id');
            $classes = StudentsClass::where('class_teacher', '=', $user->username)->pluck('class_name', 'id');
        }

        $_attendances = array('Present', 'Absent', 'Leave');

        //Datatable builder
        $datatable = $builder->columns(StudentsAttendance::datatableColumns());

        return view('admin.students.attendance.student_attendance_registers',
            compact('datatable', 'attendances', 'classes', '_attendances', 'student_id'));
    }

    public function deleteAttendance(Request $request) {
        if(!empty($request->checkBoxArray)) {
            StudentsAttendance::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_student_attendance','The attendance has been deleted successfully !');
        } else {
            return redirect()->back()->with('delete_student_attendance','The attendance has been deleted successfully !');
        }
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
    public function store(Request $request)
    {
        $APIKey = '2bbbd0e93e2a249b20a19534c940d9ddec08ba48';
        $info = \Illuminate\Support\Facades\Input::all();
        // dd($request->all());
        $class_id = $info['class_id'];
        $date = $info['date'];
        $attendance_type_id = $info['attendance_type'];
        // dd($info);
        $attendance_type_name = DmcSetup::find( $attendance_type_id )->report_type;
        parse_str($info['data'], $input);

        foreach($input['student_id'] as $i => $student_id) {
            StudentsAttendance::create([
                'class_id' => $class_id,
                'class_name' => StudentsClass::find( $class_id )->class_name,
                'date' => $date,
                'student_id' => $student_id,
                'first_name' => $input['first_name'][$i],
                'last_name' => $input['last_name'][$i],
                'attendance' => $input['attendance'][$i],
                'year' => date('Y', strtotime($date)),
                'attendance_type' => $attendance_type_name,
                'attendance_type_id' => $attendance_type_id,
            ]);
        }

        $input = $request->all();
        parse_str(urldecode($input['data']), $arr);
        foreach ($arr['attendance'] as $j => $attendance) {
            if ($attendance == "Absent") {
                $student = DB::table('students')->where('student_id', '=', $arr['student_id'][$j])->get();
                $receiver = $student[0]->guardian_phone_no;
                $sender = 'Hasnain khan';
                $textmessage = "Dear " . $student[0]->guardian_name.' Your child '. $student[0]->first_name.' '.$student[0]->last_name
                    .' is Absent today in the class';

                $url = "http://api.smilesn.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
                $ch = curl_init();
                $timeout = 30;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $response = curl_exec($ch);
                curl_close($ch);

                Sms::create(['message' => $textmessage,
                    'sent_by' => ucfirst(Auth::user()->username),
                    'student_name' => $student[0]->first_name.' '.$student[0]->last_name,
                    'guardian_phone_no' => $student[0]->guardian_phone_no,
                    'student_id' => $student[0]->student_id, 'class'=>$student[0]->students_class_id]);
            }
        }

        return url(config('student_promotion_url.connections.student_attendance_url'));

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
    public function edit()
    {

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
        $APIKey = '2bbbd0e93e2a249b20a19534c940d9ddec08ba48';
        $attendance = StudentsAttendance::find($id);
        $input = $request->attendance;
        if ($attendance->attendance == 'Absent') {
            foreach ($input as $atd) {
                if ($atd == 'Present') {
                    $student = DB::table('students')->where('student_id', '=', $attendance->student_id)->get();
                    $receiver = $student[0]->guardian_phone_no;
                    $sender = 'Hasnain khan';
                    $textmessage = "Dear " . $student[0]->guardian_name . ' We are sorry your child ' . $student[0]->first_name . ' ' . $student[0]->last_name
                        . ' was marked as absent for some reason but now he is present !';

                    $url = "http://api.smilesn.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
                    $ch = curl_init();
                    $timeout = 30;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    Sms::create(['message' => $textmessage,
                        'sent_by' => ucfirst(Auth::user()->username),
                        'student_name' => $student[0]->first_name . ' ' . $student[0]->last_name,
                        'guardian_phone_no' => $student[0]->guardian_phone_no,
                        'student_id' => $student[0]->student_id, 'class' => $student[0]->students_class_id]);
                }
            }
        }

        if ($attendance->attendance == 'Present' || $attendance->attendance == 'Leave') {
            $student = DB::table('students')->where('student_id', '=', $attendance->student_id)->get();
            $receiver = $student[0]->guardian_phone_no;
            $sender = 'Hasnain khan';
            $textmessage = "Dear " . $student[0]->guardian_name.' Your child '. $student[0]->first_name.' '.$student[0]->last_name
                .' is Absent today in the class';

            $url = "http://api.smilesn.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $response = curl_exec($ch);
            curl_close($ch);

            Sms::create(['message' => $textmessage,
                'sent_by' => ucfirst(Auth::user()->username),
                'student_name' => $student[0]->first_name . ' ' . $student[0]->last_name,
                'guardian_phone_no' => $student[0]->guardian_phone_no,
                'student_id' => $student[0]->student_id, 'class' => $student[0]->students_class_id]);
        }
        $attendance->update(['attendance' => $request->attendance[0]]);
        return redirect()->back()->with('update_student_attendance','The attendance has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atd = StudentsAttendance::findOrFail($id);
        $atd->delete();
        return redirect('/students/student_attendance_register')->with('delete_student_attendance','The attendance has been deleted successfully !');
    }

    public function get_student_attendance_api($id) {
        $user = User::find($id);
        $attendances = StudentsAttendance::where('student_id', '=', $user->user_id)->orderBy('id', 'desc')->get();
        return \App\Http\Resources\StudentsAttendance::collection($attendances);
    }

    public function print_attendance_report(Request $request) {
        $date = $request->date;
        $attendances = StudentsAttendance::where([['date', $date], ['attendance', 'Absent'] ])->get();
        $result = [];

        foreach($attendances as $attendance) {
            $result[] = ['student_id' => $attendance->student_id, 'first_name' => $attendance->first_name,
                'last_name' => $attendance->last_name, 'class' => $attendance->class_name];
        }

        return view('admin.students.attendance.attendace_report_print', compact('result', 'attendances', 'date'));
    }
}