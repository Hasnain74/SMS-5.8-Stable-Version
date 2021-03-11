<?php

namespace App\Http\Controllers;

use App\DmcSetup;
use App\StudentsAttendance;
use App\Http\Requests\SubjectsStoreRequest;
use App\Report;
use App\ReportCategories;
use App\Student;
use App\StudentsClass;
use App\Subjects;
use App\User;
use App\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ReportRequest;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportsExport;

class ReportController extends Controller
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
        $reports = Report::all();
        $report = Report::orderBy('id', 'desc')->get();
        $rep_cat = ReportCategories::pluck('name', 'id')->all();
        $student_id = Student::where('status', '=', 'Active')->pluck('student_id', 'id');
        $dmc_setup = DmcSetup::select('report_type', 'id')->get()->toArray();
        $years_array = [];
        $years = Report::pluck('date', 'id')->all();
        foreach($years as $year) {
            $timestamp = strtotime($year);
            $y = date('Y', $timestamp);
            $years_array[] = $y;
        }
        $unique_array = array_unique($years_array);
        return view('admin.reports.index', compact('classes', 'reports', 'rep_cat', 'student_id', 'report', 'dmc_setup',
    'unique_array'));
    }

    public function subject_marks() {
        $user = Auth::user();
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        if($user->status == 'admin') {
            $subjects = Subjects::all();
        } else {
            $subjects = Subjects::where('subject_teacher', '=' ,$user->user_id)->orderBy('id', 'desc')->get();
        }
        $rep_cat = ReportCategories::pluck('name', 'id')->all();
        $teachers = DB::table("teachers")->pluck("teacher_id","id");
        return view('admin.reports.subjects', compact('classes', 'subjects', 'rep_cat', 'teachers'));
    }

    public function store_subjects(SubjectsStoreRequest $request) {
        $subjects = new Subjects;
        $subjects->subject_name = ucfirst($request->subject_name);
        $subjects->subject_marks = $request->subject_marks;
        $subjects->class_id = $request->class_id;
        $subjects->class_name =  StudentsClass::find( $request->class_id )->class_name;
        $subjects->report_type_id = $request->report_type_id;
        $subjects->report_type_name = ReportCategories::find($request->report_type_id)->name;
        $subjects->subject_teacher = Teacher::find($request->subject_teacher)->teacher_id;
        $subjects->save();
        return redirect()->back();
    }

    public function edit_subjects(Request $request, $id) {
        $subject = Subjects::find($id);
        $subject->update([
            $subject->subject_name = $request->subject_name,
            $subject->subject_marks = $request->subject_marks,
            $subject->class_id = $request->class_id,
            $subject->class_name =  StudentsClass::find( $request->class_id )->class_name,
            $subject->subject_teacher = $request->subject_teacher,
            $subject->report_type_name = ReportCategories::find($request->report_type_id)->name,
            $subject->report_type_id = $request->report_type_id,
        ]);
        return redirect()->back();
    }

    public function subjects_ajax($id) {
        $user = Auth::user();
        if($user->status == 'admin') {
            $subjects = Subjects::where('class_id',$id)->orderBy('id', 'desc')->get();
        } else {
            $subjects = Subjects::where([['class_id', '=' ,$id], ['subject_teacher', '=' ,$user->user_id]])->orderBy('id', 'desc')->get();
        }
        return json_encode($subjects);      
    }

    public function add_marks(Request $request) {
        $subjects = Subjects::find(array_key_first($request->all()));
        $result = [];
            $students = Student::where([['students_class_id', '=', $subjects->class_id], ['status', '=', 'Active']])->get();
            foreach ($students as $student) {
                $result[] = ['subject_name' => $subjects->subject_name, 'total_marks' => $subjects->subject_marks,
                    'report_type' => $request->rep_cat_name, 'class'=>$subjects->class_name, 'student_id' => $student->student_id,
                    'student_name' => $student->first_name.' '.$student->last_name, 'class_id' => $subjects->class_id,
                    'rep_cat_id' => $subjects->report_type_id, 'id' => $student->id,
                    'rep_cat_name' => $subjects->report_type_name];
            }
        $rep_cat = ReportCategories::pluck('name', 'id')->all();
        $teacher = $subjects->subject_teacher;
        return view('admin.reports.add_marks', compact('result', 'rep_cat', 'teacher'));
    }

    public function delete_subject($id) {
        $subject = Subjects::find($id);
        $subject->delete();
        return redirect()->back();
    }

    public function myReportAjax($id) {
        $reports = Report::where('class_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($reports);
    }

    public function myRptAjax($id) {
        $students_id = Report::where('student_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($students_id);
    }

    public function printReport($id) {
        $reports = [ Report::findOrFail($id) ];
        $classes = [StudentsClass::pluck('class_name', 'id')->all()];
        return view('admin.reports.print', compact('classes', 'reports'));
    }

    public function print_dmc(Request $request, $id) {
        $request_years = $request->year;
        foreach($request_years as $request_year) {
            if($request_year == null) {
                return redirect()->back()->with('delete_reports','Kindly enter the year !');
            break;
            }
        }
        
            $dmc_cats = $request->dmc;
            $years = $request->year;
            $cat_year;
            $index= 0;

            foreach($dmc_cats as $_dmc_cat) {
                $cat_year[$_dmc_cat]=  $years[$index];                
                $index++;
            }           

            $report = Report::find($id);
            $total_subjects_marks = 0;

            foreach ($cat_year as $key => $value) {
                          
    
                $subjects = Report::where([['class_id', '=', $request->class_id],
                        ['report_categories_name', '=', $key],
                        ['year', '=', $value],
                        ['student_id', '=', $request->student_id]])->pluck('subject')->toArray();
            
                    $students = Student::where('student_id', '=', $request->student_id)->get();
            }

            if($subjects == null) {
                return redirect()->back()->with('delete_reports','Record Missing For The Selected Categories !');
            }

            $rows = [];
            $vertical_percent = [];
            $grade_arr = [];
            $total_subjects_marks_vert = 0;

            $total_arr = [];
            $total_marks = 0;
            $dmc_total_cats = [];
            $total_subjects_arr = [];
            $marks = [];
            $index=0;

            foreach ($cat_year as $key => $value) {
                $attendance = StudentsAttendance::where([['student_id', '=', $report->student_id],
                ['year', '=', $value], ['attendance', '=', 'Absent'], ['attendance_type', '=', $key]])->get();
                $total = 0;
                $reports = Report::where([['student_id', '=', $request->student_id],
                ['class_id', '=', $request->class_id],['year', '=', $value],
                ['report_categories_name', '=', $key]])
                        ->get()->groupBy(['report_categories_name', 'year'])->toArray();

                        


                if (isset($reports[$key][$value])) { 
                    foreach ($reports[$key][$value] as $_report) {
                        $marks[$_report['subject']][$key] = intval($_report['obtained_marks']);
                        $mark = intval($_report['obtained_marks']);
                        $total += $marks[$_report['subject']][$key];
                        $total_arr[$key] = $total;
                    }

                    $total_marks += $mark;
                    $dmc_total_cats[$index] = (object)['dmc_cat' => $key];
                    $index++;
                }

            }


        $_dmc_total_cats = [];
        foreach($dmc_total_cats as $cats) {
            $_dmc_total_cats[$index] = $cats->dmc_cat;
            $index++;
        }

        

        $_dmc_total_cats = array_unique($_dmc_total_cats);

        $_dmc_total_cats_array = [];
        $j = 0;
        foreach($_dmc_total_cats as $Key => $value)
        {
            $j = $Key;
        break;

        }

        for($i=0; $i < count($_dmc_total_cats); $i++) {
       
            
            $_dmc_total_cats_array += array($i => $_dmc_total_cats[$j]);
            $j++;
        }
        $k=0;
        $forlop = count($_dmc_total_cats);

        $grand_total = [];
        for($i=0; $i < count($_dmc_total_cats); $i++)
            {
                $grand_total[$i]=0;
            }
            $total_subject =[];
           
            

            $i = 0;
            $no_cat_type = [];
                foreach ($cat_year as $key => $value) {
                    
                    $count_report_cat = Report::where([['student_id', '=', $request->student_id],
                    ['report_categories_name', '=', $key], ['year', '=', $value]])->pluck('subject')->toArray();
                    
                    $no_cat_type += array($key => [$count_report_cat]);
                    $i++;
                      
                }

                foreach($no_cat_type as $key => $value){
                
                        foreach($value[0] as $subject){
                            $_subject_mark = Subjects::select('subject_marks')->where([['subject_name', '=', $subject],
                            ['class_id', '=', $request->class_id],['report_type_name', '=', $key]])->first()->subject_marks; 
                            
                            $grand_total[$k] = $grand_total[$k] + $_subject_mark;
                            $total_subjects_arr[$index] = (object) ['subject'=> $subject, 'subject_mark' => $_subject_mark];
                            $index++;
                            
                        }
                        $k++;                       
                    
                }

        
           $k = 0;


            foreach ($total_arr as $total_mark) {
                $percentage = ($total_mark / $grand_total[$k]) *100;
                $vertical_percent[$index] = $percentage;
                $index++;
                if ($percentage >= 0 && $percentage <= 49) {
                    $grade = 'D';
                } elseif ($percentage >= 50 && $percentage <= 59) {
                    $grade = 'C';
                } elseif ($percentage >= 60 && $percentage <= 69) {
                    $grade = 'B';
                } elseif ($percentage >= 70 && $percentage <= 79) {
                    $grade = 'A';
                } elseif ($percentage >= 80) {
                    $grade = 'A+';
                }
                $grade_arr[$index] = $grade;
                $index++;
                $k++;
            }           

            $k = 0;
            foreach ($students as $student) {
                $rows[] = (object)['student_name'=>$student->first_name. ' ' .$student->last_name, 'father_name'=>$student->guardian_name,
                    'grade'=>$grade_arr, 'marks' => $marks, 'student_id' => $student->student_id,'class' => $student->students_class_name,
                    'total_subject_marks'=>$grand_total, 'student_address'=>$student->student_address,
                    'total_marks' => $total_arr, 'verical_percent' => $vertical_percent];
                    $k++;
            }

            return \view('admin.reports.print_dmc', compact('rows', 'dmc_cats'
            , '_dmc_total_cats', 'vertical_percent', 'students', 'total_subjects_arr',
            'attendance'));
    }



    public function print_empty_award_list($id) {
        $report = Report::find($id);
        $year = $report->created_at->year;

        $subjects = array_unique(Report::where([['class_id', '=', $report->class_id],
                ['report_categories_name', '=', $report->report_categories_name],
                [DB::raw('YEAR(created_at)'), '=', $year]])->pluck('subject')->toArray());

            $students_ids = array_unique(Report::where([['class_id', '=', $report->class_id],
                ['report_categories_name', '=', $report->report_categories_name],
                [DB::raw('YEAR(created_at)'), '=', $year]])->pluck('student_primary_id')->toArray());

            $students = Student::whereIn('id', $students_ids)->get();
            $total_students = Student::where('students_class_id', '=', $report->class_id)->get();
        

        $rows = [];
        $vert_total_marks = [];
        $vertical_percent = [];
        $all_total_remarks = [];
        $total_class_percent = 0;
        $total_subjects_marks_vert = 0;
        $passed = 0;
        $failed = 0;

        foreach ($students as $student) {
            $reports = $student->reportsByType($report->class_id, $report->report_categories_name, $year);
            
            $total_marks = 0;
            $total_subjects_marks = 0;
            $total_subjects_marks_arr = [];
            $marks = [];
            $index=0;

            foreach ($subjects as $subject) {
                $mark = intval($reports[$subject]->obtained_marks);
                $total_marks += $mark;
                $marks[$index] = $mark;
                $vert_total_marks[$index] = 0;
                $_subject_mark = intval(Subjects::where([['subject_name', $subject], ['class_id', $report->class_id]])->first()->subject_marks);
                $total_subjects_marks += $_subject_mark;
                if(!isset($vertical_percent[$subject]) )
                    $vertical_percent[$subject] = 0;
                $vertical_percent[$subject] += ($mark / $_subject_mark) *100;
                $index++;
                $total_subjects_marks_arr[$index] = (object)['subject'=> $subject, 'subject_mark' => $_subject_mark];
                $index++;
            }

            $total_subjects_marks_vert += $total_subjects_marks;

            $class_teacher = $report->studentsClass->class_teacher;

            $all_total_remarks[] = $total_marks;
            $rows[] = (object)['student_name'=>$student->first_name. ' ' .$student->last_name, 'father_name'=>$student->guardian_name,
                'marks' => $marks, 'total_marks'=>$total_marks, 'student_id' => $student->student_id,
                'total_subject_marks'=>$total_subjects_marks];
        }

        $overall_class_percent = $total_class_percent/count($total_students);

        $obtained_marks = array_unique($all_total_remarks);
        sort($obtained_marks);
        $positions = array_reverse($obtained_marks);
        return view('admin.reports.print_empty_award_list', compact( 'subjects', 'positions', 'rows',
            'total_subjects_marks_arr', 'vertical_percent', 'students', 'report', 'class_teacher', 'total_students',
            'overall_class_percent', 'passed', 'failed'));
    }

    public function print_subject_for_whole_class($id) {
        $report = Report::find($id);
        $reports = Report::where([['report_categories_id', '=', $report->report_categories_id], ['class_id', '=', $report->class_id],
        ['subject', '=', $report->subject]])->whereYear('date', '=', $report->year)->get();


        $total_class_percent = 0;
        $passed = 0;
        $failed = 0;

        foreach($reports as $report) {
            $percentage = ($report->obtained_marks / $report->total_marks) *100;
            $total_class_percent += $percentage;

            if ($percentage >= 0 && $percentage <= 49) {
                $grade = 'D';
            } elseif ($percentage >= 50 && $percentage <= 59) {
                $grade = 'C';
            } elseif ($percentage >= 60 && $percentage <= 69) {
                $grade = 'B';
            } elseif ($percentage >= 70 && $percentage <= 79) {
                $grade = 'A';
            } elseif ($percentage >= 80) {
                $grade = 'A+';
            }

            $overall_class_percent = $total_class_percent/count($reports);

            $rows[] = (object)['student_name'=>$report->student_name,
                 'student_id' => $report->student_id,
                'marks'=>$report->obtained_marks, 'grade'=>$grade, 'percentage'=>$percentage];
        }

        return view('admin.reports.print_whole_class_subject', compact('rows', 'report', 'failed', 'passed',
    'overall_class_percent'));
    }

    public function print_award_list_report($id)
    {
        $report = Report::find($id);
        $year = $report->created_at->year;

        $subjects = array_unique(Report::where([['class_id', '=', $report->class_id],
                ['report_categories_name', '=', $report->report_categories_name],
                [DB::raw('YEAR(created_at)'), '=', $year]])->pluck('subject')->toArray());

            $students_ids = array_unique(Report::where([['class_id', '=', $report->class_id],
                ['report_categories_name', '=', $report->report_categories_name],
                [DB::raw('YEAR(created_at)'), '=', $year]])->pluck('student_primary_id')->toArray());

            $students = Student::whereIn('id', $students_ids)->get();
            $total_students = Student::where('students_class_id', '=', $report->class_id)->get();
        

        $rows = [];
        $vert_total_marks = [];
        $vertical_percent = [];
        $all_total_remarks = [];
        $total_class_percent = 0;
        $total_subjects_marks_vert = 0;
        $passed = 0;
        $failed = 0;

        foreach ($students as $student) {
            $reports = $student->reportsByType($report->class_id, $report->report_categories_name, $year);
            

            $total_marks = 0;
            $total_subjects_marks = 0;
            $total_subjects_marks_arr = [];
            $marks = [];
            $index=0;

            foreach ($subjects as $subject) {
                $mark = intval($reports[$subject]->obtained_marks);
                $total_marks += $mark;
                $marks[$index] = $mark;
                $vert_total_marks[$index] = 0;
                $_subject_mark = intval(Subjects::where([['subject_name', $subject], ['class_id', $report->class_id]])->first()->subject_marks);
                $total_subjects_marks += $_subject_mark;
                if(!isset($vertical_percent[$subject]) )
                    $vertical_percent[$subject] = 0;
                    $vertical_percent[$subject] += ($mark / $_subject_mark) *100;
                    $index++;
                    $total_subjects_marks_arr[$index] = (object)['subject'=> $subject, 'subject_mark' => $_subject_mark];
                    $index++;
            }

            $percentage = ($total_marks / $total_subjects_marks) *100;
            $total_class_percent += $percentage;

            if ($percentage >= 0 && $percentage <= 49) {
                $grade = 'D';
            } elseif ($percentage >= 50 && $percentage <= 59) {
                $grade = 'C';
            } elseif ($percentage >= 60 && $percentage <= 69) {
                $grade = 'B';
            } elseif ($percentage >= 70 && $percentage <= 79) {
                $grade = 'A';
            } elseif ($percentage >= 80) {
                $grade = 'A+';
            }

            $total_subjects_marks_vert += $total_subjects_marks;

            $class_teacher = $report->studentsClass->class_teacher;

            $all_total_remarks[] = $total_marks;
            $rows[] = (object)['student_name'=>$student->first_name. ' ' .$student->last_name, 'father_name'=>$student->guardian_name,
                'percent'=>$percentage, 'grade'=>$grade, 'marks' => $marks, 'total_marks'=>$total_marks, 'student_id' => $student->student_id,
                'total_subject_marks'=>$total_subjects_marks];
        }

        $overall_class_percent = $total_class_percent/count($total_students);

        $obtained_marks = array_unique($all_total_remarks);
        sort($obtained_marks);
        $positions = array_reverse($obtained_marks);
        return view('admin.reports.print_award_list_report', compact( 'subjects', 'positions', 'rows',
            'total_subjects_marks_arr', 'vertical_percent', 'students', 'report', 'class_teacher', 'total_students',
            'overall_class_percent', 'passed', 'failed'));

    }


    public function downloadReportPDF($id) {
        $reports = [ Report::findOrFail($id) ];
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $pdf = PDF::loadView('admin.reports.pdf', compact('classes', 'reports'));
        return $pdf->download('Student_report.pdf');
    }

    public function reportsActions(Request $request) {
        if (!empty($request->checkBoxArray)) {
            if ($request->options == 'post') {
                $reports = Report::findOrFail($request->checkBoxArray);
                foreach ($reports as $report) {
                    $report->update([
                        'status' => 'post'
                    ]);
                }
                return redirect()->back()->with('post_report', 'The report has been posted successfully !');
            } elseif ($request->options == 'unpost') {
                $reports = Report::findOrFail($request->checkBoxArray);
                foreach ($reports as $report) {
                    $report->update([
                        'status' => 'unpost'
                    ]);
                }
                return redirect()->back()->with('unpost_report', 'The report has been unposted successfully !');
            } else {
                return redirect()->back();
            }

        } else {
            return redirect()->back();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $rep_cat = ReportCategories::pluck('name', 'id')->all();
        return view('admin.reports.create', compact('classes', 'rep_cat'));
    }

    public function getStudentId($id) {
        $user = Auth::user();
        if($user->status == 'admin') {
            $student_id = Student::where('status', '=', 'Active')->pluck('student_id', 'id');
            $classes = StudentsClass::pluck('class_name', 'id');
        } elseif($user->status == 'teacher') {
            $class = StudentsClass::where('class_teacher', '=', $user->username)->get();
            if(count($class) == 0) {
                return redirect()->back();
            }
            $student_id = Student::where([['status', '=', 'Active'], ['students_class_id', '=', $class[0]->id]])->pluck('student_id', 'id');
        }
        return json_encode($students);
    }

    public function getStudentName($id) {
        $user = Auth::user();
        if($user->status == 'admin') {
            $students = DB::table("students")->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->where([['status', '=', 'Active'], ['students_class_id', '=', $class[0]->id], 
            ['id', '=', $id]])->pluck("name","id");
            $classes = StudentsClass::pluck('class_name', 'id');
        } elseif($user->status == 'teacher') {
            $class = StudentsClass::where('class_teacher', '=', $user->username)->get();
            if(count($class) == 0) {
                return redirect()->back();
            }
            $students = DB::table("students")->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->where([['status', '=', 'Active'], ['students_class_id', '=', $class[0]->id], 
            ['id', '=', $id]])->pluck("name","id");
        }
       
        return json_encode($students);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request) {
        $input = $request->all();
        $date = $input['date'];
        $_date = Carbon::createFromFormat('Y-m-d', $date)->year;
        
        $teacher_name = $request->teacher_name;

        foreach($input['student_id'] as $i => $student_id) {

            if (is_numeric($input['obt_marks'][$i])) {
                $percentage = (($input['obt_marks'][$i]/$input['total_marks'][$i]) * 100);
            } else {
                $percentage = 0;
            }

            if ($percentage >= 0 && $percentage <= 49) {
                $grade = 'D';
            } elseif ($percentage >= 50 && $percentage <= 59) {
                $grade = 'C';
            } elseif ($percentage >= 60 && $percentage <= 69) {
                $grade = 'B';
            } elseif ($percentage >= 70 && $percentage <= 79) {
                $grade = 'A';
            } elseif ($percentage >= 80) {
                $grade = 'A+';
            }

            $unique_marks = array_unique($input['obt_marks']);
            sort($unique_marks);
            $marks = array_reverse($unique_marks);

            Report::create([
                'percentage' => $percentage,
                'position' => array_search($input['obt_marks'][$i], $marks)+1,
                'grade' => $grade,
                'date' => $date,
                'student_id' => $student_id,
                'student_primary_id' => $input['id'][$i],
                'student_name' => $input['student_name'][$i],
                'subject' => $input['subject_name'][$i],
                'total_marks' => $input['total_marks'][$i],
                'obtained_marks' => $input['obt_marks'][$i],
                'teacher_name' => $teacher_name,
                'report_categories_id' => $input['rep_cat_id'][$i],
                'report_categories_name' => $input['rep_cat_name'][$i],
                'class_id' => $input['class_id'][$i],
                'class_name' => $input['class'][$i],
                'created_by' => ucfirst(Auth::user()->username),
                'year' => $_date
            ]);
        }

        return redirect()->back()->with('add_reports','The report has been added successfully !');

    }

    //DELETING REPORT
    public function deleteReport(Request $request) {
        if(!empty($request->checkBoxArray)) {
            foreach ($request->checkBoxArray as $id) {
                $report = Report::where('id', $id)->first();
                $report->delete();
            }
        }
        return redirect()->back()->with('delete_reports','The report has been deleted successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $report = Report::findOrFail($id);
        $input = $request->all();

        $input['report_categories_name'] = ReportCategories::find( $input['report_categories_id'] )->name;

        if (is_numeric($input['obtained_marks'])) {
            $percentage = (($input['obtained_marks']/$input['total_marks']) * 100);
        } else {
            $percentage = 0;
        }

        $input['percentage'] = $percentage;


        if ($percentage >= 0 && $percentage <= 49) {
            $grade = 'D';
        } elseif ($percentage >= 50 && $percentage <= 59) {
            $grade = 'C';
        } elseif ($percentage >= 60 && $percentage <= 69) {
            $grade = 'B';
        } elseif ($percentage >= 70 && $percentage <= 79) {
            $grade = 'A';
        } elseif ($percentage >= 80) {
            $grade = 'A+';
        }

        $input['grade'] = $grade;

        $all_reports = Report::where([['class_id', '=', $report->class_id], ['report_categories_id', '=', $report->report_categories_id],['subject', '=', $report->subject]])->get();
        $obtained_marks = Report::where([['class_id', '=', $report->class_id], ['report_categories_id', '=', $report->report_categories_id],['subject', '=', $report->subject]])->where('id', '!=', $id)->pluck('obtained_marks')->toArray();

        array_push($obtained_marks, $input['obtained_marks']);

        $json = json_encode($obtained_marks);
        $obtained_marks = json_decode($json, true);

        $unique_marks = array_unique( $obtained_marks );
        sort($unique_marks);


        $obtained_marks = array_reverse($unique_marks);

        foreach($all_reports as $rep) {
            if($rep->id == $id){
                $position = array_search($input['obtained_marks'], $obtained_marks)+1;
                $input['position'] = $position;
                $rep->update($input);
            } else {
                $position = array_search($rep['obtained_marks'], $obtained_marks)+1;
                $rep->position = $position;
                $rep->save();
            }
        }

        return redirect()->back()->with('update_report','The report has been updated successfully !');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $report = Report::where('id', $id)->first();
        $report->forceDelete();
        return redirect()->back()->with('delete_report','The report has been deleted successfully !');
    }


    public function get_student_reports_api($id) {
        $student = User::find($id);
        $reports = Report::where([['student_id', '=', $student->user_id], ['status', '=', 'post']])->orderBy('id', 'desc')->get();
        return \App\Http\Resources\Report::collection($reports);
    }


    public function export() {
        return (new ReportsExport)->download('students_reports.xlsx');
    }


}
