<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\SmsRequest;
use App\Sms;
use App\Student;
use App\StudentsClass;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nexmo\Laravel\Facade\Nexmo;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inbox = Sms::orderBy('id', 'desc')->get();
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.sms.index', compact('inbox', 'classes'));
    }

    public function mySmsAjax($id)
    {
        $sms = Sms::where('class', $id)->orderBy('id', 'desc')->get();
        return json_encode($sms);
    }

    public function getStudentId($id)
    {
        $students = DB::table("students")->where([["students_class_id", $id], ['deleted_at', '=', null]])->pluck("student_id", "id");
        return json_encode($students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.sms.create', compact('classes'));
    }

    public function getStudentNumber($id) {
        $students = DB::table("students")
            ->where("id", $id)->pluck("guardian_phone_no","id");
        return json_encode($students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SmsRequest $request)
    {
        $APIKey = '2bbbd0e93e2a249b20a19534c940d9ddec08ba48';

        if ($request->student_id == 'all_students') {
            $class = StudentsClass::findOrFail($request->class);
            foreach($class->students as $student){
                $receiver = $student['guardian_phone_no'];
                $sender = 'Hasnain khan';
                $textmessage = "Dear ".$student->guardian_name.", ".$request->message;

                $url = "http://api.smilesn.com/sendsms?hash=".$APIKey. "&receivenum=" .$receiver. "&sendernum=" .urlencode($sender)."&textmessage=" .urlencode($textmessage);
                $ch = curl_init();
                $timeout = 30;
                curl_setopt ($ch,CURLOPT_URL, $url) ;
                curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
                $response = curl_exec($ch);
                curl_close($ch) ;
            }
        } else {
            $input = $request->all();
            $student = Student::where('guardian_phone_no', '=', $input['guardian_phone_no'])->get();
            $receiver = $student[0]->guardian_phone_no;
            $sender = 'Hasnain khan';
            $textmessage = "Dear ".$student[0]->guardian_name.", ".$request->message;

            $url = "http://api.smilesn.com/sendsms?hash=".$APIKey. "&receivenum=" .$receiver. "&sendernum=" .urlencode($sender)."&textmessage=" .urlencode($textmessage);

            $ch = curl_init();
            $timeout = 30;
            curl_setopt ($ch,CURLOPT_URL, $url) ;
            curl_setopt ($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT, $timeout) ;
            $response = curl_exec($ch);
            curl_close($ch) ;
        }

        if ($request->student_id == 'all_students') {
            $class = StudentsClass::findOrFail($request->class);
            foreach ($class->students as $student) {
                Sms::create(['message' => $request->message,
                    'sent_by' => ucfirst(Auth::user()->username),
                    'student_name' => $student->first_name.' '.$student->last_name,
                    'guardian_phone_no' => $student->guardian_phone_no,
                    'student_id' => $student->student_id, 'class'=>$request->class]);
            }
        } else {
            $input = $request->all();
            $students = Student::where('guardian_phone_no', '=', $input['guardian_phone_no'])->get();
            $input['student_id'] = $students[0]->student_id;
            $input['student_name'] = $students[0]->first_name.' '.$students[0]->last_name;
            $input['message'] = $request->message;
            $input['guardian_phone_no'] = $request->guardian_phone_no;
            $input['class'] = $request->class;
            $input['sent_by'] = ucfirst(Auth::user()->username);
            Sms::create($input);
        }

        return redirect('admin/sms')->with('send_sms','Your message has been sent successfully !');

    }

    public function deleteSms(Request $request) {
        if(!empty($request->checkBoxArray)) {
            Sms::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('send_smses','Your message has been deleted successfully !');
        }
        return redirect()->back()->with('delete_smses','Your message has been deleted successfully !');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sms = Sms::findOrFail($id);
        $sms->delete();
        return redirect()->back()->with('deleted_sms','Your message has been deleted successfully !');
    }

    public function get_student_sms_api($id) {
        $student = User::find($id);
        $sms = Sms::where('student_id', '=', $student->user_id)->orderBy('id', 'desc')->get();
        return \App\Http\Resources\Sms::collection($sms);

    }
}
