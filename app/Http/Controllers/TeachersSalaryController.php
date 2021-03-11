<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\TeachersSalaryRequest;
use App\ManageSalaryInvoice;
use App\Sms;
use App\Teacher;
use App\TeachersAttendance;
use App\TeachersSalary;
use App\User;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Psy\Util\Json;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeachersSalaryExport;

class TeachersSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher_id = Teacher::all()->pluck('teacher_id', 'id');
        $invoices = TeachersSalary::all()->sortByDesc('updated_at');
        return view('admin.teachers.salary_list', compact('teacher_id', 'invoices'));
    }

    // public function manage_salary_invoice() {
    //     $setup = ManageSalaryInvoice::all();
    //     return view('admin.teachers.manage_salary_invoice', compact('setup'));
    // }

    // public function delete_setup($id) {
    //     $setup = ManageSalaryInvoice::find($id);
    //     $setup->delete();
    //     return redirect()->back();
    // }

    // public function store_invoice_setup(Request $request) {
    //     ManageSalaryInvoice::create([
    //        'per_day_amount' => $request->per_day_amount
    //     ]);
    //     return redirect()->back();
    // }

    public function getTeacherName($id) {
        $teachers = DB::table("teachers")->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->where("id", '=', $id)->pluck("name","id");
        return json_encode($teachers);
    }

    public function getTeacherAttendance(Request $request) {
        $teacher = Teacher::find($request->teacher_id);
        $date = Carbon::parse($request->date);
        $dateDay = $date->format('d');

        if($dateDay <= 15) {
            $month =  $date->subMonth()->month;
            $year = $date->subMonth()->year;
            $attendance = TeachersAttendance::where([['teacher_id', '=', $teacher->teacher_id],
            ['attendance', '=', 'Absent'], [DB::raw('MONTH(date)'), '=', $month],
            [DB::raw('YEAR(date)'), '=', $year]])->get();
        }
        
        if($dateDay > 15) {
            $month =  $date->format('m');
            $year = (int) $date->format('Y');
            $attendance = TeachersAttendance::where([['teacher_id', '=', $teacher->teacher_id],
            ['attendance', '=', 'Absent'], [DB::raw('MONTH(date)'), '=', $month],
            [DB::raw('YEAR(date)'), '=', $year]])->get();
        }
       
        $attendance_count = (object) count($attendance);
        return json_encode($attendance_count);
    }

    public function teacherPhNo($id) {
        $teacher = Teacher::find($id);
        $teacherPhNo = Teacher::where('phone_no' , '=', $teacher->phone_no)->pluck('phone_no');
        return json_encode($teacherPhNo);
    }

    // public function calculateCash($id) {
    //     $teacher = Teacher::find($id);
    //     $month = Carbon::now()->subMonth()->month;
    //     $attendance = TeachersAttendance::where([['teacher_id', '=', $teacher->teacher_id],
    //         ['attendance', '=', 'Absent'], [DB::raw('MONTH(date)'), '=', $month]])->get();
    //     $attendance_count = count($attendance);
    //     $amount = DB::table('manage_salary_invoices')->pluck('per_day_amount');
    //     if (isset($amount[0])) {
    //         $total_amount = (object) ($amount[0] * $attendance_count);
    //     } else {
    //         $total_amount = (object) 0;
    //     }
    //     return json_encode($total_amount);
    // }

    public function getTeacherSalary($id) {
        $teachers = DB::table("teachers")->where("id", '=', $id)->pluck("salary","id");
        return json_encode($teachers);
    }

    public function TeachersSalaryAjax($id) {
        $teachers_id = TeachersSalary::where('teacher_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($teachers_id);
    }

    public function deleteInvoices(Request $request) {

        if(!empty($request->checkBoxArray))
        {
            TeachersSalary::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_teacher_invoices','The salary invoice has been deleted successfully !');
        } else {
            return redirect()->back()->with('delete_teacher_invoices','The salary invoice has been deleted successfully !');
        }

    }

    public function deleteInv(Request $request) {
        if(!empty($request->checkBoxArray)) {
            TeachersSalary::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_teacher_invoices','The salary invoice has been deleted successfully !');
        } else {
            return redirect()->back()->with('delete_teacher_invoices','The salary invoice has been deleted successfully !');
        }

    }

    public function downloadPDF($id) {
        $invoices = [ TeachersSalary::findOrFail($id) ];
        return view('admin.teachers.salary_pdf', compact('invoices'));
        // $pdf = PDF::loadView('admin.teachers.salary_pdf', compact( 'invoices'));
        // return $pdf->download('Salary_Invoice.pdf');
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
    public function store(TeachersSalaryRequest $request)
    {
        // do{
        //     $randomNo = rand(0, 1000);
        //     $rand =  "-".$randomNo;
        // }
        // while(!empty(TeachersSalary ::where('invoice_no',$rand)->first()));
        $statement = DB::select("SHOW TABLE STATUS LIKE 'teachers_salaries'");
        $rand = $statement[0]->Auto_increment;

        $input = $request->all();
        $user = Teacher::find( $input['teacher_id']);
        $input['teacher_id'] = $user->teacher_id;
        $input['teacher_name'] = $user->first_name.' '.$user->last_name;
        $input['paid_amount'] = $request->paid_amount;
        $input['payable_amount'] = $request->payable_amount;
        $input['absent_days'] = $request->absent_days;
        $input['cash_out'] = $request->cash_out;
        $input['date'] = $request->date;
        $input['month_year'] = date('F, Y', strtotime($request->date));
        $input['year'] = date('Y', strtotime($request->date));
        $input['invoice_created_by'] = ucfirst(Auth::user()->username);
        $input['invoice_no'] = '#'.strtolower($user->teacher_id).'-'.$rand;
        TeachersSalary::create($input);

        $APIKey = '2bbbd0e93e2a249b20a19534c940d9ddec08ba48';

        $receiver = $request->teacherPhNo;
        $sender = 'Hasnain khan';
        $textmessage = "Dear " .  $user->first_name.' '.$user->last_name .
            ' Your salary has been paid at date ' . $request->date;

        $url = "http://api.smilesn.com/sendsms?hash=" . $APIKey . "&receivenum=" . $receiver . "&sendernum=" . urlencode($sender) . "&textmessage=" . urlencode($textmessage);
        $ch = curl_init();
        $timeout = 30;;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $response = curl_exec($ch);
        curl_close($ch);
        Sms::create(['message' => $textmessage,
            'sent_by' => ucfirst(Auth::user()->username),
            'student_name' => $user->first_name.' '.$user->last_name,
            'guardian_phone_no' => $request->teacherPhNo,
            'student_id' => $user->teacher_id, 'class' => 'Null']);

        return redirect()->back()->with('create_teacher_salary','The salary invoice has been created successfully !');
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
        $attendance = TeachersSalary::find($id);
        $attendance->update([
            'date' => $request->date,
            'year' => date('Y', strtotime($request->date)),
        ]);
        return redirect()->back()->with('update_teacher_salary','The salary invoice has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoices = TeachersSalary::findOrFail($id);
        $invoices->delete();
        return redirect()->back()->with('delete_teacher_salary','The salary invoice has been deleted successfully !');
    }

    public function export() {
        return (new TeachersSalaryExport)->download('staff_finance.xlsx');
    }
}
