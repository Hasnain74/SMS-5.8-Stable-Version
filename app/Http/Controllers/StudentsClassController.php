<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\StudentsClassRequest;
use Illuminate\Http\Request;
use App\Student;
use App\StudentsAttendance;
use App\StudentsClass;
use Illuminate\Support\Facades\DB;
use App\Teacher;

class StudentsClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher_name = DB::table("teachers")
                 ->whereNotNull('teacher_subject')
                 ->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
                 ->pluck("name", "id");       

        $classes = StudentsClass::all()->sortByDesc('updated_at');
        $maleStudents = Student::where([['gender', '=', 'male'], ['status', '=', 'Active']])->get();
        $Mstudents = $maleStudents->groupBy(function ($result) {
            return $result->students_class_name;
        });

        $femaleStudents = Student::where([['gender', '=', 'female'], ['status', '=', 'Active']])->get();
        $Fstudents = $femaleStudents->groupBy(function ($result) {
            return $result->students_class_name;
        });

        $result = [];

        foreach ($classes as $cls) {
            $male_students = 0;
            $female_students = 0;

            foreach ($Mstudents as $students) {
                if ($students[0]->students_class_name == $cls->class_name) {
                    $male_students = $male_students + count($students);
                }
            }

            foreach ($Fstudents as $students) {
                if ($students[0]->students_class_name == $cls->class_name) {
                    $female_students = $female_students + count($students);
                }
            }

            $result[$cls->class_name] =
                ['id'=>$cls->id,'class_name'=>$cls->class_name, 'class_fee' => $cls->class_fee, 'class_teacher' => $cls->class_teacher,
                    'male' => $male_students, 'female' => $female_students];

        }

        // dd($result);


        return view('admin.classes.index', compact('result', 'teacher_name'));
    }
    
    public function print_class(Request $request) {
        $class = StudentsClass::find(key($request->all()));
        $students = Student::where([['students_class_id', '=', $class->id], ['status', '=', 'Active']])->get();
        $no_of_students = $students->count();
        return view('admin.classes.print_classes', compact('students', 'class', 'no_of_students'));
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
    public function store(StudentsClassRequest $request)
    {
        $db_classes = StudentsClass::where('class_name', '=', $request->class_name)->get();
        if (count($db_classes) > 0) {
            return redirect()->back()->with('create_class','The class is already created !');
        } else {
            $class = $request->all();
            StudentsClass::create([
                'class_name' => $class['class_name'],
                'class_fee' => $class['class_fee'],
                'class_teacher' => $class['class_teacher'],
            ]);
            // $class = $request->all();
            // StudentsClass::create($class);
            return redirect()->back()->with('create_class','The class has been created successfully !');
        }
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
//        $class = StudentsClass::all($id);
//        return view('admin.classes.index', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentsClassRequest $request, $id)
    {
        $update_student = new StudentsClass();
        $update_student->update_students_class($id, $request->class_name);

        $update_student_attendances = new StudentsClass();
        $update_student_attendances->update_students_attendance($id, $request->class_name);

        $update_timetable = new Studentsclass();
        $update_timetable->update_timetable($id, $request->class_name);

        $update_datesheet = new StudentsClass();
        $update_datesheet->update_datesheet($id, $request->class_name);

        $update_fees = new StudentsClass();
        $update_fees->update_fees($id, $request->class_name);

        $update_reports = new StudentsClass();
        $update_reports->update_reports($id, $request->class_name);

        $update_subjects = new StudentsClass();
        $update_subjects->update_subjects($id, $request->class_name);

        $class = StudentsClass::find($id);
        $class->update($request->all());
        return redirect('/admin/classes')->with('update_class','The class has been updated successfully !');
    }

    public function get_classes_api() {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return \App\Http\Resources\Report::collection($classes);
    }
}
