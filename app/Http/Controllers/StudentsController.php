<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentsEditRequest;
use App\Http\Requests\StudentsRequest;
use App\Photo;
use App\Report;
use App\Student;
use App\StudentsClass;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentExport;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all()->sortByDesc('updated_at');
        $students_status = Student::all()->pluck('id', 'status')->all();
        return view('admin.students.index', compact('students', 'students_status'));
    }

    public function promote_students(Request $request) {
        $info = \Illuminate\Support\Facades\Input::all();
        $input = (array) $info['data'];
        $to_class_id = $info['to_class_id'];
        foreach($input as $student_id) {
            $to_class_fee = StudentsClass::find($to_class_id)->class_fee;
            $student_fee_percent = Student::find(1)->discount_percent;
            $discount_percent = (int) $student_fee_percent/100;
            $total_fee = $to_class_fee - ($to_class_fee * $discount_percent);
            DB::table('students')->where('student_id', '=', $student_id)->update([
                'students_class_id' => $to_class_id,
                'students_class_name' => StudentsClass::find($to_class_id)->class_name,
                'total_fee' => $total_fee,
            ]);
        }
        return url( config('student_promotion_url.connections.student_promotion_url'));
        
    }

    public function student_promotions() {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.students.students_promotions', compact('classes'));
    }

    public function student_promotions_ajax($id) {
        $students = Student::where([['students_class_id', '=' ,$id], ['status', '=', 'Active']])->get();
        return json_encode($students);
    }

    public function student_accounts() {
        $accounts = User::
            join('students', 'users.user_id', '=', 'students.student_id')
            ->select('users.username', 'users.photo_id', 'users.user_id', 'users.email', 'students.students_class_name', 'users.id')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.students.student_accounts', compact('accounts'));
    }

    public function edit_student_account($id) {
        $user = User::find($id);
        return view('admin.students.edit_student_account', compact('user'));
    }

    public function update_account(Request $request, $id) {
        $user = User::find($id);
        if (trim($request->password) == '') {
            $input = $request->except('password');
            $user->update($input);
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);
            $user->update($input);
        }

        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file'=>$name]);
            $input['photo_id'] = $photo->id;
        }

        return redirect('/students/student_accounts')->with('update_student_account','The account has been updated successfully !');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.students.create', compact('classes'));
    }

    public function getStudentFee($id) {
        $studentFee = DB::table("students_classes")->where("id", $id)->pluck("class_fee","id");
        return json_encode($studentFee);
    }

    public function deleteStudents(Request $request) {
        if(!empty($request->checkBoxArray)) {
            foreach ($request->checkBoxArray as $id) {
                $students = DB::table('students')->where('id', '=', $id)->get();
                DB::table('users')->where('user_id', '=', $students[0]->student_id)->delete();
                $student = Student::findOrFail($id);
                $student->delete();
            }
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function downloadPDF($id) {
        $students = [Student::findOrFail($id)];
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->
        loadView('admin.students.pdf', compact( 'students'))->stream();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentsRequest $request)
    {
        // do{
        //     $randomNo = rand(0, 10000);
        //     $rand =  "-".$randomNo;
        // }
        // while(!empty(Student::where('student_id',$rand)->first()));

        $input = $request->all();

        $validation = Validator::make($request->all(), [
            'email' => 'required|unique:users,email',
            'password' => 'string|min:6',
        ]);

        if (!$validation->fails()) {

            if ($file = $request->file('photo_id')) {
                $name = time() . $file->getClientOriginalName();
                $file->move('images', $name);
                $photo = Photo::create(['file' => $name]);
                $input['photo_id'] = $photo->id;
            }

            $input['password'] = bcrypt($request->password);

            // if ($input['fee_setup'] == 'monthly') {
                $request->validate([
                    'discount_percent' => 'required',
                ]);
            //     if($request['photo_id'] == null) {
            //         Student::create([
            //             'first_name' => $request->first_name,
            //             'last_name' => $request->last_name,
            //             'student_id' => strtolower($request->first_name).'-'.strtolower($request->last_name.$rand),
            //             'DOB' => $request->DOB,
            //             'admission_date' => $request->admission_date,
            //             'students_class_id' => $request->students_class_id,
            //             'students_class_name' => StudentsClass::find($request->students_class_id)->class_name,
            //             'gender' => $request->gender,
            //             'blood_group' => $request->blood_group,
            //             'religion' => $request->religion,
            //             'student_address' => $request->student_address,
            //             'student_phone_no' => $request->student_phone_no,
            //             'discount_percent' => $request->discount_percent,
            //             'total_fee' => $request->total_fee,
            //             'transport_fee' => $request->transport_fee,
            //             'fee_setup' => $request->fee_setup,
            //             'guardian_name' => $request->guardian_name,
            //             'guardian_gender' => $request->guardian_gender,
            //             'guardian_relation' => $request->guardian_relation,
            //             'guardian_occupation' => $request->guardian_occupation,
            //             'guardian_phone_no' => $request->guardian_phone_no,
            //             'NIC_no' => $request->NIC_no,
            //             'guardian_address' => $request->guardian_address,
            //         ]);
            //     } else {
            //         Student::create([
            //             'first_name' => $request->first_name,
            //             'last_name' => $request->last_name,
            //             'student_id' => strtolower($request->first_name).'-'.strtolower($request->last_name.$rand),
            //             'DOB' => $request->DOB,
            //             'admission_date' => $request->admission_date,
            //             'students_class_id' => $request->students_class_id,
            //             'students_class_name' => StudentsClass::find($request->students_class_id)->class_name,
            //             'gender' => $request->gender,
            //             'blood_group' => $request->blood_group,
            //             'religion' => $request->religion,
            //             'photo_id' => $input['photo_id'],
            //             'student_address' => $request->student_address,
            //             'student_phone_no' => $request->student_phone_no,
            //             'discount_percent' => $request->discount_percent,
            //             'total_fee' => $request->total_fee,
            //             'transport_fee' => $request->transport_fee,
            //             'fee_setup' => $request->fee_setup,
            //             'guardian_name' => $request->guardian_name,
            //             'guardian_gender' => $request->guardian_gender,
            //             'guardian_relation' => $request->guardian_relation,
            //             'guardian_occupation' => $request->guardian_occupation,
            //             'guardian_phone_no' => $request->guardian_phone_no,
            //             'NIC_no' => $request->NIC_no,
            //             'guardian_address' => $request->guardian_address,
            //         ]);
            //     }
               
            // } else if ($input['fee_setup'] == 'instalment') {
            //     Student::create([
            //         'first_name' => $request->first_name,
            //         'last_name' => $request->last_name,
            //         'student_id' => strtolower($request->first_name).'-'.strtolower($request->last_name.$rand),
            //         'DOB' => $request->DOB,
            //         'admission_date' => $request->admission_date,
            //         'students_class_id' => $request->students_class_id,
            //         'students_class_name' => StudentsClass::find($request->students_class_id)->class_name,
            //         'gender' => $request->gender,
            //         'blood_group' => $request->blood_group,
            //         'religion' => $request->religion,
            //         'photo_id' => $input['photo_id'],
            //         'student_address' => $request->student_address,
            //         'student_phone_no' => $request->student_phone_no,
            //         'discount_percent' => 'null',
            //         'total_fee' => $request->total_fee,
            //         'transport_fee' => $request->transport_fee,
            //         'fee_setup' => $request->fee_setup,
            //         'guardian_name' => $request->guardian_name,
            //         'guardian_gender' => $request->guardian_gender,
            //         'guardian_relation' => $request->guardian_relation,
            //         'guardian_occupation' => $request->guardian_occupation,
            //         'guardian_phone_no' => $request->guardian_phone_no,
            //         'NIC_no' => $request->NIC_no,
            //         'guardian_address' => $request->guardian_address,
            //     ]);
            // }

            // dd($request->all());

            $statement = DB::select("SHOW TABLE STATUS LIKE 'students'");
            $Id = $statement[0]->Auto_increment;

            if($request->photo_id == null) {
                Student::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'student_id'
                     => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                     .'-'. date('Y', strtotime($request->admission_date)).'-'.$Id,
                    'DOB' => $request->DOB,
                    'admission_date' => $request->admission_date,
                    'students_class_id' => $request->students_class_id,
                    'students_class_name' => StudentsClass::find($request->students_class_id)->class_name,
                    'gender' => $request->gender,
                    'blood_group' => $request->blood_group,
                    'religion' => $request->religion,
                    'student_address' => $request->student_address,
                    'student_phone_no' => $request->student_phone_no,
                    'discount_percent' => $request->discount_percent,
                    'total_fee' => $request->total_fee,
                    'transport_fee' => $request->transport_fee,
                    'fee_setup' => $request->fee_setup,
                    'guardian_name' => $request->guardian_name,
                    'guardian_gender' => $request->guardian_gender,
                    'guardian_relation' => $request->guardian_relation,
                    'guardian_occupation' => $request->guardian_occupation,
                    'guardian_phone_no' => $request->guardian_phone_no,
                    'NIC_no' => $request->NIC_no,
                    'guardian_address' => $request->guardian_address,
                    'status' => 'Active'
                ]);

            } else {
                $statement = DB::select("SHOW TABLE STATUS LIKE 'students'");
                $Id = $statement[0]->Auto_increment;

                Student::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'student_id'
                     => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                     .'-'. date('Y', strtotime($request->admission_date)).'-'.$Id,
                    'DOB' => $request->DOB,
                    'admission_date' => $request->admission_date,
                    'students_class_id' => $request->students_class_id,
                    'students_class_name' => StudentsClass::find($request->students_class_id)->class_name,
                    'gender' => $request->gender,
                    'blood_group' => $request->blood_group,
                    'religion' => $request->religion,
                    'photo_id' => $input['photo_id'],
                    'student_address' => $request->student_address,
                    'student_phone_no' => $request->student_phone_no,
                    'discount_percent' => $request->discount_percent,
                    'total_fee' => $request->total_fee,
                    'transport_fee' => $request->transport_fee,
                    'fee_setup' => $request->fee_setup,
                    'guardian_name' => $request->guardian_name,
                    'guardian_gender' => $request->guardian_gender,
                    'guardian_relation' => $request->guardian_relation,
                    'guardian_occupation' => $request->guardian_occupation,
                    'guardian_phone_no' => $request->guardian_phone_no,
                    'NIC_no' => $request->NIC_no,
                    'guardian_address' => $request->guardian_address,
                    'status' => 'Active'
                    
                ]);
            }

        }

        $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'string|min:6',
        ]);

        if($request->photo_id == null) {
            if ($request->email && $request->password) {
                User::create([
                    'username' =>  $request->first_name.' '.$request->last_name,
                    'user_id'  => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                    .'-'. date('Y', strtotime($request->admission_date)).'-'.$Id,
                    'status' =>  'student',
                    'email' =>  $request->email,
                    'password' =>   bcrypt($request->password),
                    'api_token' => Str::random(80),
                ]);
            }
        } else {
            User::create([
                'username' =>  $request->first_name.' '.$request->last_name,
                'user_id'  => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                .'-'. date('Y', strtotime($request->admission_date)).'-'.$Id,
                'status' =>  'student',
                'photo_id' =>  $input['photo_id'],
                'email' =>  $request->email,
                'password' =>   bcrypt($request->password),
                'api_token' => Str::random(80),
            ]);
        }

        return redirect('/admin/students')->with('create_student','The student has been created successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $students = Student::all();
        return view('admin.students.index', compact('students', 'class'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.students.edit_student', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentsEditRequest $request, $id)
    {
        $student = Student::findOrFail($id);
       
        $input = $request->all();


        $input['students_class_name'] = StudentsClass::find($input['students_class_id'])->class_name;

        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file'=>$name]);
            $input['photo_id'] = $photo->id;
            $user = User::where('user_id', '=', $student->student_id)->get();
            foreach($user as $u) {
                $u->update([
                    'photo_id' => $photo->id
                ]);
            }
        }

        $input['fee_setup'] = $request->fee_setup;
        $input['transport_fee'] = $request->transport_fee;
        if($request->status == null) {
            $input['status'] = StudentsClass::find($id)->status;
        } else {
            $input['status'] = $request->status;
        }


        $student->update($input);
        return redirect('/admin/students/')->with('update_student','The student has been updated successfully !');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        if($student->photo_id == null) {
            $user = User::where('user_id', '=', $student->student_id);
            $user->delete();
            $student->delete();
        } else  {
            $user = User::where('user_id', '=', $student->student_id);
            $user->delete();
            unlink(public_path() . $student->photo->file);
            $student->delete();
        }
       
        return redirect()->back()->with('delete_student','The student has been deleted successfully !');
    }

    public function get_students($id) {
        $students = Student::where('students_class_id', '=', $id)->get();
        return \App\Http\Resources\Student::collection($students);
    }

    public function get_student_data($id) {
        $user = User::find($id);
        $students = Student::where('student_id', '=', $user->user_id)->get();
        return \App\Http\Resources\Student::collection($students);
    }

    public function export() {
        return (new StudentExport)->download('students.xlsx');
    }
}
