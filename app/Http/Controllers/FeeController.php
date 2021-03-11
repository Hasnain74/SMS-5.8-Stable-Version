<?php

namespace App\Http\Controllers;

use App\AdmissionFee;
use App\Fee;
use App\FeeReport;
use App\FeeSetup;
use App\Http\Requests\FeeRequest;
use App\Http\Requests\FeeUpdateRequest;
use App\RemainingFee;
use App\Sms;
use App\Student;
use App\StudentsClass;
use App\User;
use App\Donations;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FeeExport;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Fee::all()->sortByDesc('updated_at');
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $status = array('Paid', 'Unpaid', 'Arrears');
        $student_id = Student::where('status', 'Active')->pluck('student_id', 'id');
        return view('admin.fee.index', compact('classes', 'invoices', 'status', 'student_id'));
    }

    public function admission_fee() {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $student_ids = Student::pluck('student_id', 'id')->all();
        // dd($student_ids);
        $student_names = DB::table("students")->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->pluck("name", "id")->toArray();
        $admission_fee = AdmissionFee::all();
        return view('admin.fee.admission_fee', compact('classes', 'admission_fee', 'student_ids', 'student_names'));
    }

    public function store_admission_fee(Request $request) {
        // dd($request->student_id);
        // // dd($request->all());
        $student_id = Student::where('student_id', '=', $request->student_id)->pluck('student_id');
        do {
            $randomNo = rand(0, 10000);
            $rand = "-" . $randomNo;
        } while (!empty(AdmissionFee::where('invoice_no', $rand)->first()));
        AdmissionFee::create([
            'class_id' => $request->class_id,
            'class_name' => StudentsClass::find($request->class_id)->class_name,
            'invoice_no' => '#' . $student_id[0] . $rand,
            'student_id' => $student_id[0],
            'student_name' => $request->student_name,
            'admission_fee' => $request->admission_fee,
            'paid_amount' => $request->paid_amount,
            'paid_date' => $request->paid_date,
            'arrears' => $request->admission_fee - $request->paid_amount,
            'created_by' => ucfirst(Auth::user()->username),
        ]);
        FeeReport::create([
            'invoice_no' => '#' . $student_id[0] . $rand,
            'class_id' => $request->class_id,
            'class_name' => StudentsClass::find($request->class_id)->class_name,
            'student_id' => $student_id[0],
            'student_name' => $request->student_name,
            'issue_date' => '-',
            'due_date' => '-',
            'paid_date' => $request->paid_date,
            'month_year' => date('F, Y', strtotime($request->paid_date)),
            'year' => date('Y', strtotime($request->paid_date)),
            'paid_amount' => $request->paid_amount,
            'month' => date('F, Y', strtotime($request->paid_date)),
            'invoice_created_by' => ucfirst(Auth::user()->username),
        ]);
        return redirect()->back();
    }

    public function update_admission_fee(Request $request, $id) {
        $input = $request->all();
        $fee = AdmissionFee::find($id);
        $fee->update([
            'admission_fee' => $input['admission_fee'],
            'paid_amount' => $input['paid_amount'],
            'arrears' => $input['admission_fee'] - $input['paid_amount'],
            'paid_date' => $input['paid_date'],
        ]);
        $fee_report = FeeReport::where('invoice_no', '=', $fee->invoice_no);
        $fee_report->update([
            'issue_date' => '-',
            'due_date' => '-',
            'paid_date' => $request->paid_date,
            'month_year' => date('F, Y', strtotime($request->paid_date)),
            'year' => date('Y', strtotime($request->paid_date)),
            'paid_amount' => $request->paid_amount,
            'month' => date('F, Y', strtotime($request->paid_date)),
            'invoice_created_by' => ucfirst(Auth::user()->username),
        ]);
        return redirect()->back();
    }

    public function delete_admission_fee($id) {
        $fee = AdmissionFee::find($id);
        $fee->delete();
        return redirect()->back();
    }

    public function print_admission_fee(Request $request) {
        $fee = AdmissionFee::find(array_key_first($request->all()));
        return view('admin.fee.print_admission_fee', compact('fee'));
    }

    public function instalment_fee() {
        $fee_setup = FeeSetup::all();
        return view('admin.fee.instalment_fee', compact('fee_setup'));
    }

    public function storeFeeSetup(Request $request) {
        $input = $request->all();
        FeeSetup::create([
            'month' => date('F, Y', strtotime($input['month'])),
            'fee_amount' => $input['fee_amount']
        ]);
        return redirect()->back();
    }

    public function delete_fee_setup($id) {
        $setup = FeeSetup::find($id);
        $setup->delete();
        return redirect()->back();
    }

    public function update_fee_setup(Request $request, $id) {
        $input = $request->all();
        $setup = FeeSetup::find($id);
        $setup->update($input);
        return redirect()->back();
    }

    public function paid($id) {
        $invoice = Fee::find($id);
        $now = now()->format('d-m-Y');
        $invoice->update([
            'paid_date' => $now,
            'paid_amount' => $invoice->arrears + $invoice->paid_amount,
            'arrears' => '0',
            'month_year' => date('F, Y', strtotime($now)),
            'year' => date('Y', strtotime($now)),
            'invoice_created_by' => ucfirst(Auth::user()->username),
        ]);
        return redirect()->back();
    }

    public function getFeeWithId($id)
    { 
        $students_id = Fee::where('student_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($students_id);
    }

    public function myFeeAjax($id)
    {
        $invoices = Fee::where('class_id', $id)->orderBy('id', 'desc')->get();
        return json_encode($invoices);
    }

    public function feeStatusAjax($id)
    {
        if ($id == 'paid') {
            $invoices = Fee::where('paid_amount', '>', '0')->orderBy('id', 'desc')->get();
        } elseif ($id == 'arrears') {
            $invoices = Fee::where('paid_amount', '=', '0')->orderBy('id', 'desc')->get();
        }
        return json_encode($invoices);
    }

    public function downloadPDF($id)
    {
        $invoice = [Fee::findOrFail($id)];
        $arrears = Fee::where([['arrears', '>', '0'], ['student_id', '=', $invoice[0]->student_id]])->get();
        $arrear = $arrears->groupBy(function ($result, $key) {
            return date('F, Y', strtotime($result->month));
        })->toArray();
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $records = [DB::table('fee_reports')->where('invoice_no', '=', $invoice[0]->invoice_no)->get()];
        $pdf = PDF::loadView('admin.fee.pdf', compact('classes', 'invoice', 'arrear', 'records'));
        return $pdf->download('Fee_Invoice.pdf');
    }

    public function printInvoice($id)
    {
        $invoice = [Fee::findOrFail($id)];
        $arrears = Fee::where([['arrears', '>', '0'], ['student_id', '=', $invoice[0]->student_id]])->get();
        $arrear = $arrears->groupBy(function ($result, $key) {
            return date('F, Y', strtotime($result->month));
        })->toArray();
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $records = [DB::table('fee_reports')->where('invoice_no', '=', $invoice[0]->invoice_no)->get()];
        return view('admin.fee.print', compact('invoice', 'classes', 'arrear', 'records'));
    }

    public function invoicesActions(Request $request)
    {

        if ($request->options == 'print') {
            if (!empty($request->checkBoxArray)) {
                $fees = Fee::findOrFail($request->checkBoxArray);

                foreach ($fees as $fee)
                {
                    $fee_reports_info = [];
                    $fee_reports = FeeReport::where('invoice_no', $fee->invoice_no)->get();

                    foreach ($fee_reports as $fee_report) {
                        $fee_reports_info[] = [
                            'paid_amount' => $fee_report->paid_amount,
                            'paid_date' => $fee_report->paid_date,
                        ];
                    }

                    $result[] = [
                        'invoice_no' => $fee->invoice_no,
                        'paid_amount' => $fee->paid_amount,
                        'month'=>$fee->month,
                        'transport_fee' => $fee->transport_fee,
                        'student_id'=>$fee->student_id,
                        'student_name'=>$fee->student_name,
                        'class_name'=>$fee->class_name,
                        'created_at'=>date('h:i:s a m/d/Y', strtotime($fee->created_at)),
                        'invoice_created_by' => $fee->invoice_created_by,
                        'pre_paid_date'=>$fee->paid_date,
                        'previous_month_fee'=>$fee->previous_month_fee,
                        'other_fee_type'=>$fee->other_fee_type,
                        'other_amount'=>$fee->other_amount,
                        'current_month_fee'=>$fee->total_amount,
                        'payable_fee'=>$fee->total_payable_fee,
                        'concession'=>$fee->concession,
                        'arrears'=>$fee->arrears,
                        'paid_date'=>$fee->paid_date,
                        'fee_reports' => $fee_reports,
                        'prospectus' => $fee->prospectus,
                        'admin_and_management_fee' => $fee->admin_and_management_fee,
                        'books' => $fee->books,
                        'security_fee' => $fee->security_fee,
                        'uniform' => $fee->uniform,
                        'fine_panalties' => $fee->fine_panalties,
                        'printing_and_stationary' => $fee->printing_and_stationary,
                        'promotion_fee' => $fee->promotion_fee,
                        'percentage' => $fee->percentage,
                        'guardian_name' => $fee->guardian_name
                    ];
                }

                return view('admin.fee.print_multiple_invoices', compact('result'));
            }

        } elseif ($request->options == 'pdf') {

            if (!empty($request->checkBoxArray)) {
                $fees = Fee::findOrFail($request->checkBoxArray);

                foreach ($fees as $fee)
                {
                    $fee_reports_info = [];
                    $fee_reports = FeeReport::where('invoice_no', $fee->invoice_no)->get();

                    foreach ($fee_reports as $fee_report) {
                        $fee_reports_info[] = [
                            'paid_amount' => $fee_report->paid_amount,
                            'paid_date' => $fee_report->paid_date,
                        ];
                    }

                    $result[] = [
                        'invoice_no' => $fee->invoice_no,
                        'paid_amount' => $fee->paid_amount,
                        'month'=>$fee->month,
                        'transport_fee' => $fee->transport_fee,
                        'student_id'=>$fee->student_id,
                        'student_name'=>$fee->student_name,
                        'class_name'=>$fee->class_name,
                        'created_at'=>date('h:i:s a m/d/Y', strtotime($fee->created_at)),
                        'pre_paid_date'=>$fee->paid_date,
                        'previous_month_fee'=>$fee->previous_month_fee,
                        'other_fee_type'=>$fee->other_fee_type,
                        'other_amount'=>$fee->other_amount,
                        'current_month_fee'=>$fee->total_amount,
                        'payable_fee'=>$fee->total_payable_fee,
                        'concession'=>$fee->concession,
                        'arrears'=>$fee->arrears,
                        'paid_date'=>$fee->paid_date,
                        'fee_reports' => $fee_reports,
                        'prospectus' => $fee->prospectus,
                        'admin_and_management_fee' => $fee->admin_and_management_fee,
                        'books' => $fee->books,
                        'security_fee' => $fee->security_fee,
                        'uniform' => $fee->uniform,
                        'fine_panalties' => $fee->fine_panalties,
                        'printing_and_stationary' => $fee->printing_and_stationary,
                        'promotion_fee' => $fee->promotion_fee,
                    ];
                }

                $pdf = PDF::loadView('admin.fee.multiple_pdf', compact('result'));
                return $pdf->download('Fee_Invoice.pdf');
            }

        } elseif ($request->options == 'sms') {
            $APIKey = '2bbbd0e93e2a249b20a19534c940d9ddec08ba48';
            if (!empty($request->checkBoxArray)) {
                $invoices = Fee::find($request->checkBoxArray);
                foreach ($invoices as $invoice) {
                    $student = DB::table('students')->where('student_id', '=', $invoice['student_id'])->get();
                    $receiver = $student[0]->guardian_phone_no;
                    $sender = 'Hasnain khan';
                    $textmessage = "Dear " . $student[0]->guardian_name .
                        ' last date of your child\'s (' . $student[0]->first_name . ' ' . $student[0]->last_name
                        . ') fee is over _آپ کے بچے کی فیس کی آخری تاریخ ختم ہوگئی ہے. برائے مہربانی فیس ادا کریں.';

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
                        'student_name' => $student[0]->first_name . ' ' . $student[0]->last_name,
                        'guardian_phone_no' => $student[0]->guardian_phone_no,
                        'student_id' => $student[0]->student_id, 'class' => $student[0]->students_class_id]);
                }
                return redirect()->back();
            }
        } else if($request->date != null) {
            $fees = Fee::where('paid_date', $request->date)->get();
            $total = 0;
            $result = [];
            $fee_report = [];

            foreach($fees as $fee) {
                $total += (int) $fee->paid_amount;
                $fee_report[] =  [
                    'paid_amount' => $fee->paid_amount,
                    'student_name' => $fee->student_name,
                    'student_id' => $fee->student_id,
                    'guardian_name' => $fee->guardian_name,
                    'class_name' => $fee->class_name,
                ];
            }

            $result[] = ['total_amount' => $total,
                          'fee_report' => $fee_report,
                        'date' => $request->date];

            return view('admin.fee.print_fee_report', compact('result'));

        } else {
            return redirect()->back();
        }

    }

    public function getStudentIdeForAdmEdit($id)
    {
        $students = Student::where([['students_class_id', '=' ,$id], ['status', '=', 'Active']])->pluck('student_id')->all();
        return json_encode($students);
    }

    public function getStudentNameForAdmEdit(Request $request) {
        $students = DB::table("students")
        ->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
        ->where([["student_id", '=' ,$request->name], ['status', '=', 'Active']])
        ->pluck("name", "id");
        return json_encode($students);
    }

    public function getStudentIdForAdm($id)
    {
        $students = Student::where([['students_class_id', '=' ,$id], ['status', '=', 'Active']])->pluck('student_id', 'id')->all();
        // dd($students);
        return json_encode($students);
    }

    public function getStudentNameForAdm(Request $request)
    {
        $students = DB::table("students")
                 ->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
                 ->where([["student_id", '=' ,$request->name], ['status', '=', 'Active']])
                 ->pluck("name", "id");
        return json_encode($students);
    }


    public function getStudentId(Request $request)
    {
        if ($request->id == 'all_classes') {
            $students = Student::where('fee_setup', $request->id1)->pluck('student_id', 'id')->all();
        } else  {
            $students = Student::where(['students_class_id' => $request->id, 'fee_setup' => $request->id1])
        ->pluck('student_id', 'id')->all();
        }

        return json_encode($students);
    }

    public function getStudentName($id)
    {
        $students = DB::table("students")->select("id", DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->where("id", $id)->pluck("name", "id");
        return json_encode($students);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $now = Carbon::now()->addMonth()->format('F, Y');
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $instalment_months = FeeSetup::pluck('month', 'id')->all();
        $fee_setup = array('Monthly', 'Instalment');
        return view('admin.fee.create', compact('classes', 'now', 'instalment_months', 'fee_setup'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeeRequest $request) {

        $statement = DB::select("SHOW TABLE STATUS LIKE 'fees'");
        $rand = $statement[0]->Auto_increment;

        if($request->fee_setup == 0) {
            $fee_setup = 'monthly';
        } else if($request->fee_setup == 1) {
            $fee_setup = 'instalment';        
        }

        if ($fee_setup == 'instalment') {
            $request->validate([
                'instalment_month' => 'required',
            ]);
            if ($request->issue_date && $request->due_date) {

                if ($request->class_id == 'all_classes') {
                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);

                        $students = Student::where('fee_setup', '=', 'instalment')->get();
                        $fee_setup = FeeSetup::find($request->instalment_month);
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee =(int)  "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee +=(int)  $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }


                                Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                    'month' => $fee_setup->month,
                                    'month_year' => $fee_setup->month,
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' => (int) $fee_setup->fee_amount,
                                    'class_id' => $student->students_class_id, 'arrears' => (int) $student->transport_fee + 
                                    (int)   $fee_setup->fee_amount + (int) $request->other_amount + $previous_month_fee + (int) $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + $request->security_fee +(int)  $request->uniform + (int) $request->printing_and_stationary +
                                    (int)  $request->fine_panalties + (int) $request->promotion_fee,
                                    'total_payable_fee' => (int) $fee_setup->fee_amount +(int)  $request->other_amount + 
                                    $previous_month_fee + (int) $student->transport_fee + (int) $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int)  $request->books + (int) $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'previous_month_fee' =>(int)  $previous_month_fee,
                                    'other_amount' => (int) $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id) . '-' .$rand,
                                    'concession' => '0',
                                    'transport_fee' =>(int)  $student->transport_fee,
                                    'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                    'books' =>(int)  $request->books, 'security_fee' => (int) $request->security_fee,
                                    'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                    'printing_and_stationary' => (int) $request->printing_and_stationary,
                                    'promotion_fee' =>(int)  $request->promotion_fee,
                                    'percentage' => $student->discount_percent,
                                    'guardian_name' => $student->guardian_name,
                                ]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    } else {
                        $students = Student::where('fee_setup', '=', 'instalment')->get();
                        $fee_setup = FeeSetup::find($request->instalment_month);
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee =(int)  "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee = (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }

                                Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                    'month' => $fee_setup->month,
                                    'month_year' => $fee_setup->month,
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' => $fee_setup->fee_amount,
                                    'total_payable_fee' =>(int)  $student->transport_fee +(int)  $fee_setup->fee_amount +(int)  $request->other_amount + 
                                    $previous_month_fee + (int) $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int) $request->books +(int)  $request->security_fee +(int)  $request->uniform + (int) $request->printing_and_stationary +
                                    (int)$request->fine_panalties + $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'concession' => '0',
                                    'transport_fee' => $student->transport_fee,
                                    'class_id' => $student->students_class_id, 'arrears' => (int)$student->transport_fee +
                                    (int) $fee_setup->fee_amount + $previous_month_fee +(int) $request->prospectus + (int)$request->admin_and_management_fee +
                                     (int)  $request->books +(int) $request->security_fee + (int)$request->uniform + (int)$request->printing_and_stationary +
                                     (int) $request->fine_panalties + (int)$request->promotion_fee, 
                                     'other_amount' =>(int) '0', 'other_fee_type' => '-',
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id) . '-' .$rand,
                                    'prospectus' =>(int) $request->prospectus, 'admin_and_management_fee' =>(int) $request->admin_and_management_fee,
                                    'books' => (int)$request->books, 'security_fee' => (int)$request->security_fee,
                                    'uniform' => (int)$request->uniform, 'fine_panalties'=> (int)$request->fine_panalties,
                                    'printing_and_stationary' =>(int) $request->printing_and_stationary,
                                    'promotion_fee' =>(int) $request->promotion_fee,
                                    'percentage' => $student->discount_percent,
                                    'guardian_name' => $student->guardian_name
                                ]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    }
                } else {

                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);
                        $fee_setup = FeeSetup::find($request->instalment_month);
                        if ($request->student_id == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int) "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee =(int) $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee =(int) "0";
                                    }

                                    Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                        'month' => $fee_setup->month,
                                        'month_year' => $fee_setup->month,
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' =>(int) $fee_setup->fee_amount,
                                        'class_id' => $request->class_id, 'arrears' =>(int) $student->transport_fee + 
                                        (int)$fee_setup->fee_amount +(int) $request->other_amount + $previous_month_fee + (int)$request->prospectus + (int)$request->admin_and_management_fee +
                                        (int)$request->books + (int)$request->security_fee +(int) $request->uniform + (int)$request->printing_and_stationary +
                                        (int)$request->fine_panalties + (int)$request->promotion_fee,
                                        'total_payable_fee' =>(int) $student->transport_fee +(int) $fee_setup->fee_amount + (int)$request->other_amount + 
                                        $previous_month_fee  +(int) $request->prospectus +(int) $request->admin_and_management_fee +
                                        (int) $request->books + (int)$request->security_fee +(int) $request->uniform + (int)$request->printing_and_stationary +
                                        (int)$request->fine_panalties +(int) $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'other_amount' =>(int) $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'concession' => '0',
                                        'transport_fee' =>(int) $student->transport_fee,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' =>(int) $request->prospectus, 'admin_and_management_fee' =>(int) $request->admin_and_management_fee,
                                        'books' =>(int) $request->books, 'security_fee' =>(int) $request->security_fee,
                                        'uniform' =>(int) $request->uniform, 'fine_panalties'=> (int)$request->fine_panalties,
                                        'printing_and_stationary' =>(int) $request->printing_and_stationary,
                                        'promotion_fee' =>(int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                    ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $students = Student::where('fee_setup', '=', 'instalment')->get();
                            $fee_setup = FeeSetup::find($request->instalment_month);
                            $input = $request->all();
                            if ($students->count() != 0) {
                                foreach ($students as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int) "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee += (int)$i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee = (int)"0";
                                    }

                                    $students = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'instalment']])->first();
                                    Fee::create([
                                        'student_id' => $students->student_id,
                                        'student_name' => $students->first_name . ' ' . $students->last_name,
                                        'other_amount' =>(int) $request->other_amount,
                                        'other_fee_type' => $request->other_fee_type,
                                        'total_amount' =>(int) $fee_setup->fee_amount,
                                        'total_payable_fee' => (int)$students->transport_fee +(int) $fee_setup->fee_amount +(int) $request->other_amount + 
                                        $previous_month_fee  +(int) $request->prospectus + (int)$request->admin_and_management_fee +
                                        (int) $request->books +(int) $request->security_fee +(int) $request->uniform +(int) $request->printing_and_stationary +
                                        (int) $request->fine_panalties + (int)$request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'class_id' => $students->students_class_id,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'month_year' => $fee_setup->month,
                                        'month' => $fee_setup->month,
                                        'issue_date' => $request->issue_date,
                                        'due_date' => $request->due_date,
                                        'concession' => '0',
                                        'transport_fee' =>(int) $students->transport_fee,
                                        'arrears' => (int)$students->transport_fee +(int) $fee_setup->amount + 
                                        (int) $request->other_amount + $previous_month_fee +(int) $request->prospectus + (int)$request->admin_and_management_fee +
                                        (int) $request->books +(int) $request->security_fee +(int) $request->uniform + (int)$request->printing_and_stationary +
                                        (int)$request->fine_panalties +(int) $request->promotion_fee,
                                        'invoice_no' => '#' . strtolower($students->student_id)  . '-' . $rand,
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'prospectus' =>(int) $request->prospectus, 'admin_and_management_fee' => (int)$request->admin_and_management_fee,
                                        'books' =>(int) $request->books, 'security_fee' =>(int) $request->security_fee,
                                        'uniform' =>(int) $request->uniform, 'fine_panalties'=> (int)$request->fine_panalties,
                                        'printing_and_stationary' =>(int) $request->printing_and_stationary,
                                        'promotion_fee' =>(int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                    ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        }
                    } else {
                        if ($request->student_id == 'all_students' && $request->student_name == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            $fee_setup = FeeSetup::find($request->instalment_month);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee = (int)"0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee =(int) $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee =(int) "0";
                                    }

                                    Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                        'month' => $fee_setup->month,
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' => (int)$fee_setup->fee_amount,
                                        'total_payable_fee' =>(int) $student->transport_fee +(int) $fee_setup->fee_amount + (int)$request->other_amount + 
                                        $previous_month_fee +(int) $request->prospectus + (int)$request->admin_and_management_fee +
                                        (int) $request->books + (int)$request->security_fee +(int) $request->uniform +(int) $request->printing_and_stationary +
                                        (int) $request->fine_panalties + (int)$request->promotion_fee,
                                        'previous_month_fee' =>  $previous_month_fee,
                                        'class_id' => $request->class_id, 'arrears' =>(int)  $student->transport_fee + 
                                        (int)  $fee_setup->fee_amount + $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                        (int)  $request->books + $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                        (int)  $request->fine_panalties + (int) $request->promotion_fee, 
                                        'other_amount' => '0', 'other_fee_type' => '-',
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'concession' => '0',
                                        'transport_fee' =>(int)  $student->transport_fee,
                                        'month_year' => $fee_setup->month,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' =>(int)  $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                        ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $input = $request->all();
                            $students = Student::where('fee_setup', '=', 'instalment')->get();
                            $fee_setup = FeeSetup::find($request->instalment_month);
                            if ($students->count() != 0) {
                                foreach ($students as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int)  "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee += (int) $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee =(int)  "0";
                                    }

                                    $students = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'instalment']])->first();
                                    Fee::create([
                                        'student_id' => $students->student_id,
                                        'student_name' => $students->first_name . ' ' . $students->last_name,
                                        'other_amount' => (int) '0',
                                        'other_fee_type' => '-',
                                        'total_amount' =>(int)  $fee_setup->fee_amount,
                                        'total_payable_fee' => (int) $students->transport_fee +(int)  $fee_setup->fee_amount +(int)  $request->other_amount + 
                                        $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                        (int)   $request->books +(int)  $request->security_fee +(int)  $request->uniform + (int) $request->printing_and_stationary +
                                        (int)   $request->fine_panalties + (int) $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'class_id' => $students->students_class_id,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'month_year' => $fee_setup->month,
                                        'month' => $fee_setup->month,
                                        'issue_date' => $request->issue_date,
                                        'due_date' => $request->due_date,
                                        'concession' => '0',
                                        'transport_fee' => (int) $students->transport_fee,
                                        'arrears' =>(int)  $students->transport_fee +(int)  $fee_setup->amount + 
                                        $previous_month_fee + (int) $request->prospectus +(int)  $request->admin_and_management_fee +
                                        (int)  $request->books + (int) $request->security_fee + (int) $request->uniform +(int)  $request->printing_and_stationary +
                                        (int) $request->fine_panalties +(int)  $request->promotion_fee,
                                        'invoice_no' => '#' . strtolower($students->student_id)  . '-' . $rand,
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'prospectus' => (int) $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                    ]);

                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        }
                    }

                }

            } else {
                if ($request->class_id == 'all_classes') {
                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);
                        $students = Student::where('fee_setup', '=', 'instalment')->get();
                        $fee_setup = FeeSetup::find($request->instalment_month);
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee =(int)  "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee =(int)  $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }

                                Fee::create(['month' => $fee_setup->month,
                                    'issue_date' => '-', 'due_date' => '-',
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' => (int) $fee_setup->fee_amount,
                                    'class_id' => $student->students_class_id, 'arrears' => (int) $student->transport_fee + 
                                    (int)  $fee_setup->fee_amount +(int)  $request->other_amount + $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + (int) $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'other_amount' =>(int)  $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                    'total_payable_fee' =>(int)  $student->transport_fee +(int)  $fee_setup->fee_amount + (int) $request->other_amount + 
                                    $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + $request->security_fee +(int)  $request->uniform + (int) $request->printing_and_stationary +
                                    (int)  $request->fine_panalties + (int) $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'concession' => '0',
                                    'transport_fee' =>(int)  $student->transport_fee,
                                    'month_year' => $fee_setup->month,
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                    'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' => (int) $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' =>(int)  $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                    ]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    } else {
                        $students = Student::where('fee_setup', '=', 'instalment')->get();
                        $fee_setup = FeeSetup::find($request->instalment_month);
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee = (int) "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee = (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }

                                Fee::create(['month' => $fee_setup->month,
                                    'issue_date' => '-', 'due_date' => '-',
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' =>(int)  $fee_setup->fee_amount,
                                    'class_id' => $student->students_class_id, 'arrears' =>(int)  $student->transport_fee +(int)  $fee_setup->fee_amount + $previous_month_fee + (int) $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int)  $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee, 
                                    'other_amount' => (int) '0', 'other_fee_type' => '-',
                                    'total_payable_fee' =>(int)  $student->transport_fee +(int)  $fee_setup->fee_amount + $previous_month_fee
                                    +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + $request->security_fee +(int)  $request->uniform + (int) $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'concession' => '0',
                                    'transport_fee' =>(int)  $student->transport_fee,
                                    'month_year' => $fee_setup->month,
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                    'prospectus' => (int) $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                    ]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }

                    }
                } else {

                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);
                        if ($request->student_id == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            $fee_setup = FeeSetup::find($request->instalment_month);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int)  "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee =(int)  $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee =(int)  "0";
                                    }

                                    Fee::create(['month' => $fee_setup->month,
                                        'issue_date' => '-', 'due_date' => '-',
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' =>(int)  $fee_setup->fee_amount,
                                        'class_id' => $class->students->students_class_id, 'arrears' => 
                                        (int)  $student->transport_fee +(int)  $fee_setup->fee_amount +(int)  $request->other_amount +
                                         $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                         (int)   $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                         (int)   $request->fine_panalties +(int)  $request->promotion_fee,
                                        'other_amount' =>(int)  $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                        'total_payable_fee' =>(int)  $student->transport_fee+(int)  $fee_setup->fee_amount + 
                                        (int)  $request->other_amount + $previous_month_fee + (int) $request->prospectus +(int)  $request->admin_and_management_fee +
                                        (int)  $request->books +(int)  $request->security_fee + (int) $request->uniform +(int)  $request->printing_and_stationary +
                                        (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'concession' => '0',
                                        'transport_fee' =>(int)  $student->transport_fee,
                                        'month_year' => $fee_setup->month,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' => (int) $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                        ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $input = $request->all();
                            $std = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'monthly']])->first();
                            if ($std != null) {
                                $fee = Fee::where([['student_id', '=', $std->student_id], ['arrears', '>', '0']])->get();
                                $fee_setup = FeeSetup::find($request->instalment_month);
                                if ($fee->count() > 0 && $fee[0]->student_id == $std->student_id) {
                                    $previous_month_fee = (int) "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee += (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }

                                Fee::create([
                                    'student_id' => $std->student_id,
                                    'student_name' => $std->first_name . ' ' . $std->last_name,
                                    'other_amount' => (int) $request->other_amount,
                                    'other_fee_type' => $request->other_fee_type,
                                    'total_amount' => (int) $fee_setup->fee_amount,
                                    'total_payable_fee' =>(int)  $std->transport_fee +(int)  $fee_setup->amount + 
                                    (int)  $request->other_amount + $previous_month_fee + (int) $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int)  $request->books + $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'class_id' => $std->students_class_id,
                                    'class_name' => StudentsClass::find($request->class_id)->class_name,
                                    'month_year' => $fee_setup->month,
                                    'month' => $fee_setup->month,
                                    'issue_date' => '-',
                                    'due_date' => '-',
                                    'concession' => '0',
                                    'transport_fee' =>(int)  $std->transport_fee,
                                    'arrears' => (int) $std->transport_fee +(int)  $fee_setup->amount + 
                                    (int)  $request->other_amount + $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'invoice_no' => '#' . strtolower($std->student_id)  . '-' . $rand,
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' => (int) $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' => (int) $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $std->guardian_name
                                ]);
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        }
                    } else {
                        if ($request->student_id == 'all_students' && $request->student_name == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            $fee_setup = FeeSetup::find($request->instalment_month);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int)  "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee =(int)  $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee =(int)  "0";
                                    }

                                    Fee::create(['month' => $fee_setup->month,
                                        'issue_date' => '-', 'due_date' => '-',
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' => $fee_setup->fee_amount,
                                        'class_id' => $class->students[0]->student_id, 'arrears' => 
                                        (int)  $student->transport_fee +(int)  $fee_setup->fee_amount + $previous_month_fee + (int) $request->prospectus + (int) $request->admin_and_management_fee +
                                        (int) $request->books +(int)  $request->security_fee + (int) $request->uniform + (int) $request->printing_and_stationary +
                                        (int) $request->fine_panalties +(int)  $request->promotion_fee, 
                                        'other_amount' =>(int)  '0', 'other_fee_type' => '-',
                                        'total_payable_fee' =>(int)  $student->transport_fee + (int) $fee_setup->fee_amount + 
                                         $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                        (int)  $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->printing_and_stationary +
                                        (int)  $request->fine_panalties + (int) $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'concession' => '0',
                                        'transport_fee' =>(int)  $student->transport_fee,
                                        'month_year' => $fee_setup->month,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' =>(int)  $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                        ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $input = $request->all();
                            $std = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'instalment']])->first();
                            if ($std != null) {
                                $fee = Fee::where([['student_id', '=', $std->student_id], ['arrears', '>', '0']])->get();
                                $fee_setup = FeeSetup::find($request->instalment_month);
                                if ($fee->count() > 0) {
                                    $previous_month_fee =(int)  "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee = (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }

                                Fee::create([
                                    'student_id' => $std->student_id,
                                    'student_name' => $std->first_name . ' ' . $std->last_name,
                                    'other_amount' => '0',
                                    'other_fee_type' => '-',
                                    'total_amount' =>(int)  $fee_setup->fee_amount,
                                    'total_payable_fee' =>(int)  $std->transport_fee + (int) $fee_setup->amount + 
                                    (int)  $request->other_amount + $previous_month_fee + (int) $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + (int) $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties + (int) $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'class_id' => $std->students_class_id,
                                    'class_name' => StudentsClass::find($request->class_id)->class_name,
                                    'month_year' => $fee_setup->month,
                                    'month' => $fee_setup->month,
                                    'issue_date' => '-',
                                    'due_date' => '-',
                                    'concession' => '0',
                                    'transport_fee' =>(int)  $std->transport_fee,
                                    'arrears' =>(int)  $std->transport_fee +(int)  $fee_setup->amount + $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int)  $request->books + (int) $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'invoice_no' => '#' . strtolower($std->student_id)  . '-' . $rand,
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $std->discount_percent,
                                        'guardian_name' => $std->guardian_name
                                ]);
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }

                        }
                    }

                }
            }
        } else if ($fee_setup == 'monthly') {
            if ($request->issue_date && $request->due_date) {
                if ($request->class_id == 'all_classes') {
                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);

                        $students = Student::where('fee_setup', '=', 'monthly')->get();
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee = (int) "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee +=(int)  $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }


                                Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                    'month' => $request->month,
                                    'month_year' => $request->month,
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' =>(int)  $student->total_fee,
                                    'class_id' => $student->students_class_id, 'arrears' => 
                                    (int)  $student->transport_fee + (int) $student->total_fee +(int)  $request->other_amount + 
                                    (int)  $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int) $request->books + $request->security_fee + $request->uniform +(int)  $request->printing_and_stationary +
                                    (int) $request->fine_panalties +(int)  $request->promotion_fee,
                                    'total_payable_fee' =>(int)  $student->transport_fee +(int)  $student->total_fee + 
                                    (int)  $request->other_amount + $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int)  $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'other_amount' =>(int)  $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                    'concession' => '0',
                                    'transport_fee' => $student->transport_fee,
                                    'prospectus' => (int) $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' => (int) $request->security_fee,
                                        'uniform' => (int) $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' =>(int)  $request->printing_and_stationary,
                                        'promotion_fee' =>(int)  $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                ]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    } else {
                        $students = Student::where('fee_setup', '=', 'monthly')->get();
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee =(int)  "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee =(int)  $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee = (int) "0";
                                }

                                Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                    'month' => $request->month,
                                    'month_year' => $request->month,
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' => $student->total_fee,
                                    'total_payable_fee' =>(int)  $student->transport_fee + (int) $student->total_fee + 
                                    (int)  $request->other_amount + $previous_month_fee +(int)  $request->prospectus +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                    (int) $request->fine_panalties + (int) $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'concession' => '0',
                                    'transport_fee' => (int) $student->transport_fee,
                                    'class_id' => $student->students_class_id, 'arrears' => 
                                    (int) $student->transport_fee + (int) $student->total_fee + $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int) $request->books +(int)  $request->security_fee +(int)  $request->uniform + (int) $request->printing_and_stationary +
                                    (int)  $request->fine_panalties +(int)  $request->promotion_fee, 
                                    'other_amount' => (int) '0', 'other_fee_type' => '-',
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                    'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' => (int) $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' => (int) $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                ]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    }
                } else {

                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);
                        if ($request->student_id == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int)  "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee =(int)  $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee = (int) "0";
                                    }

                                    Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                        'month' => $request->month,
                                        'month_year' => $request->month,
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' =>(int)  $student->total_fee,
                                        'class_id' => $request->class_id, 'arrears' =>(int)  $student->transport_fee +
                                        (int)  $student->total_fee +(int)  $request->other_amount + $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                        (int)  $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->printing_and_stationary +
                                         (int)  $request->fine_panalties + (int) $request->promotion_fee,
                                        'total_payable_fee' =>(int)  $student->transport_fee + 
                                        (int) $student->total_fee +(int)  $request->other_amount + $previous_month_fee + (int) $request->prospectus + (int) $request->admin_and_management_fee +
                                        (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->printing_and_stationary +
                                        (int) $request->fine_panalties + (int) $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'other_amount' =>(int)  $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'concession' => '0',
                                        'transport_fee' => (int) $student->transport_fee,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' =>(int)  $request->books, 'security_fee' => (int) $request->security_fee,
                                        'uniform' =>(int)  $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                        'printing_and_stationary' => (int) $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                        ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $students = Student::where('fee_setup', '=', 'monthly')->get();
                            if ($students->count() != 0) {
                                foreach ($students as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int)  "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee +=(int)  $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee = (int) "0";
                                    }

                                    $input = $request->all();
                                    $students = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'monthly']])->first();
                                    $input['student_id'] = $students->student_id;
                                    $input['student_name'] = $students->first_name . ' ' . $students->last_name;
                                    $input['other_amount'] =(int)  $request->other_amount;
                                    $input['other_fee_type'] = $request->other_fee_type;
                                    $input['total_payable_fee'] =(int)  $students->transport_fee +(int)  $students->total_fee + 
                                    (int)  $request->other_amount + $previous_month_fee + (int) $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                    (int) $request->printing_and_stationary +(int)  $request->promotion_fee;
                                    $input['previous_month_fee'] = $previous_month_fee;
                                    $input['total_amount'] =(int)  $students->total_fee;
                                    $input['class_id'] = $students->students_class_id;
                                    $input['class_name'] = StudentsClass::find($request->class_id)->class_name;
                                    $input['issue_date'] = $request->issue_date;
                                    $input['arrears'] = (int) $students->transport_fee +(int)  $students->total_fee + (int) $request->other_amount + 
                                    $previous_month_fee +(int)  $request->prospectus + (int) $request->admin_and_management_fee +
                                    (int)  $request->books +(int)  $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                    (int)  $request->printing_and_stationary +(int)  $request->promotion_fee;
                                    $input['month_year'] = $request->month;
                                    $input['concession'] = '0';
                                    $input['transport_fee'] =(int)  $students->transport_fee;
                                    $input['month'] = $request->month;
                                    $input['month_year'] = $request->month;
                                    $input['due_date'] = $request->due_date;
                                    $input['invoice_no'] = '#' . strtolower($students->student_id)  . '-' . $rand;
                                    $input['invoice_created_by'] = ucfirst(Auth::user()->username);
                                    $input['prospectus'] =(int)  $request->prospectus;
                                    $input['admin_and_management_fee'] = (int) $request->admin_and_management_fee;
                                    $input['books'] = (int) $request->books;
                                    $input['security_fee'] = (int) $request->security_fee;
                                    $input['uniform'] = (int) $request->uniform;
                                    $input['fine_panalties'] = (int) $request->fine_panalties;
                                    $input['printing_and_stationary'] = (int) $request->printing_and_stationary;
                                    $input['promotion_fee'] = (int) $request->promotion_fee;
                                    $input['percentage'] = $students->percent_discount;
                                    $input['guardian_name'] = $students->guardian_name;
                                    Fee::create($input);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        }
                    } else {
                        if ($request->student_id == 'all_students' && $request->student_name == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee = (int) "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee =(int)  $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee =(int)  "0";
                                    }

                                    Fee::create(['issue_date' => $request->issue_date, 'due_date' => $request->due_date,
                                        'month' => $request->month,
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' => $student->total_fee,
                                        'total_payable_fee' =>(int)  $student->transport_fee + (int) $student->total_fee + 
                                        (int)  $request->other_amount + $previous_month_fee +(int)  $request->admin_and_management_fee +
                                        (int) $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->fine_panalties +
                                        (int) $request->printing_and_stationary + (int) $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'class_id' => $request->class_id, 'arrears' => (int) $student->transport_fee + (int) $student->total_fee + 
                                        $previous_month_fee +(int)  $request->admin_and_management_fee +
                                        (int) $request->books + $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                        (int) $request->printing_and_stationary + (int) $request->promotion_fee, 
                                        'other_amount' =>(int)  '0', 'other_fee_type' => '-',
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'concession' => '0',
                                        'transport_fee' => $student->transport_fee,
                                        'month_year' => $request->month,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                        'books' => (int) $request->books, 'security_fee' =>(int)  $request->security_fee,
                                        'uniform' => (int) $request->uniform, 'fine_panalties'=> (int) $request->fine_panalties,
                                        'printing_and_stationary' => (int) $request->printing_and_stationary,
                                        'promotion_fee' => (int) $request->promotion_fee,
                                        'percentage' => $student->discount_percent,
                                        'guardian_name' => $student->guardian_name
                                        ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $input = $request->all();
                            // $students = Student::where('fee_setup', '=', 'monthly')->get();
                            $student = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'monthly']])->first();
                            if ($student->count() != 0) {
                                // foreach ($students as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee =(int)  "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee += (int) $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee = (int) "0";
                                    }
                                    $input['student_id'] = $student->student_id;
                                    $input['student_name'] = $student->first_name . ' ' . $student->last_name;
                                    $input['other_amount'] =(int)  '0';
                                    $input['other_fee_type'] = '-';
                                    $input['total_amount'] = (int) $student->total_fee;
                                    $input['class_id'] = $student->students_class_id;
                                    $input['class_name'] = StudentsClass::find($request->class_id)->class_name;
                                    $input['issue_date'] = $request->issue_date;
                                    $input['arrears'] = (int) $student->transport_fee +(int)  $student->total_fee + $previous_month_fee +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + (int) $request->security_fee +(int)  $request->uniform + (int) $request->fine_panalties +
                                    (int) $request->printing_and_stationary + (int) $request->promotion_fee;
                                    $input['due_date'] = $request->due_date;
                                    $input['concession'] = '0';
                                    $input['transport_fee'] = $student->transport_fee;
                                    $input['total_payable_fee'] = (int) $student->transport_fee +(int)  $student->total_fee + (int) $request->other_amount + 
                                    $previous_month_fee + (int) $request->admin_and_management_fee +
                                    (int) $request->books + (int) $request->security_fee +(int)  $request->uniform + (int) $request->fine_panalties +
                                    (int) $request->printing_and_stationary +(int)  $request->promotion_fee;
                                    $input['previous_month_fee'] = $previous_month_fee;
                                    $input['month'] = $request->month;
                                    $input['month_year'] = $request->month;
                                    $input['invoice_no'] = '#' . strtolower($student->student_id)  . '-' . $rand;
                                    $input['invoice_created_by'] = ucfirst(Auth::user()->username);
                                    $input['prospectus'] =(int)  $request->prospectus;
                                    $input['admin_and_management_fee'] = (int) $request->admin_and_management_fee;
                                    $input['books'] = (int) $request->books;
                                    $input['security_fee'] = (int) $request->security_fee;
                                    $input['uniform'] = (int) $request->uniform;
                                    $input['fine_panalties'] =(int)  $request->fine_panalties;
                                    $input['printing_and_stationary'] =(int)  $request->printing_and_stationary;
                                    $input['promotion_fee'] = (int) $request->promotion_fee;
                                    $input['percentage'] = $student->percent_discount;
                                    $input['guardian_name'] = $student->guardian_name;
                                    Fee::create($input);
                                // }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        }
                    }

                }

            } else {
                if ($request->class_id == 'all_classes') {
                    // dd('works');
                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);
                        $students = Student::where('fee_setup', '=', 'monthly')->get();
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee =(int)  "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee = (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee = (int) "0";
                                }

                                Fee::create(['month' => $request->month,
                                    'issue_date' => '-', 'due_date' => '-',
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' => (int) $student->total_fee,
                                    'class_id' => $student->students_class_id, 'arrears' => (int) $student->transport_fee + 
                                    (int) $student->total_fee + (int) $request->other_amount + $previous_month_fee + (int) $request->admin_and_management_fee +
                                    (int) $request->books +(int)  $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                    (int) $request->printing_and_stationary + (int) $request->promotion_fee,
                                    'other_amount' => (int) $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                    'total_payable_fee' =>(int)  $student->transport_fee + (int) $student->total_fee + (int) $request->other_amount + 
                                    $previous_month_fee + (int) $request->admin_and_management_fee +
                                    (int)  $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->fine_panalties +
                                    (int) $request->printing_and_stationary + (int) $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'concession' => '0',
                                    'transport_fee' => (int) $student->transport_fee,
                                    'month_year' => $request->month,
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                    'prospectus' =>(int)  $request->prospectus, 'admin_and_management_fee' =>(int)  $request->admin_and_management_fee,
                                    'books' => (int) $request->books, 'security_fee' =>(int)  $request->security_fee,
                                    'uniform' => (int) $request->uniform, 'fine_panalties'=> (int) $request->fine_panalties,
                                    'printing_and_stationary' => (int) $request->printing_and_stationary,
                                    'promotion_fee' => (int) $request->promotion_fee,
                                    'percentage' => $student->discount_percent,
                                    'guardian_name' => $student->guardian_name
                                    ]);
                            }
                        }else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    } else {
                        $students = Student::where('fee_setup', '=', 'monthly')->get();
                        
                        if ($students->count() != 0) {
                            foreach ($students as $student) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee = "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee =(int)  $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee = (int) "0";
                                }

                                Fee::create(['month' => $request->month,
                                    'issue_date' => '-', 'due_date' => '-',
                                    'invoice_created_by' => ucfirst(Auth::user()->username),
                                    'student_name' => $student->first_name . ' ' . $student->last_name,
                                    'student_id' => $student->student_id, 'total_amount' => (int) $student->total_fee,
                                    'class_id' => $student->students_class_id, 'arrears' =>(int)  $student->transport_fee +(int)  $student->total_fee +
                                     $previous_month_fee + (int) $request->admin_and_management_fee +
                                     (int) $request->books + (int) $request->security_fee +(int)  $request->uniform + (int) $request->fine_panalties +
                                     (int)  $request->printing_and_stationary + (int) $request->promotion_fee, 
                                     'other_amount' => '0', 'other_fee_type' => '-',
                                    'total_payable_fee' => (int) $student->transport_fee + (int) $student->total_fee + $previous_month_fee +(int)  $request->admin_and_management_fee +
                                    (int)  $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                    (int)  $request->printing_and_stationary +(int)  $request->promotion_fee,
                                    'previous_month_fee' => $previous_month_fee,
                                    'concession' => '0',
                                    'transport_fee' => (int) $student->transport_fee,
                                    'month_year' => $request->month,
                                    'class_name' => StudentsClass::find($student->students_class_id)->class_name,
                                    'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                    'prospectus' => (int) $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                    'books' => (int) $request->books, 'security_fee' => (int) $request->security_fee,
                                    'uniform' => (int) $request->uniform, 'fine_panalties'=>(int)  $request->fine_panalties,
                                    'printing_and_stationary' => (int) $request->printing_and_stationary,
                                    'promotion_fee' => (int) $request->promotion_fee,
                                    'percentage' => $student->discount_percent,
                                    'guardian_name' => $student->guardian_name]);
                            }
                        } else {
                            return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                        }
                    }
                } else {

                    if ($request->other_amount) {
                        $request->validate([
                            'other_fee_type' => 'required',
                        ]);
                        if ($request->student_id == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee = "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee = (int) $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee = (int) "0";
                                    }

                                    Fee::create(['month' => $request->month,
                                        'issue_date' => '-', 'due_date' => '-',
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' => $student->total_fee,
                                        'class_id' => $class->students->students_class_id, 'arrears' =>(int)  $student->transport_fee + 
                                        (int)  $student->total_fee + (int) $request->other_amount + $previous_month_fee + (int) $request->admin_and_management_fee +
                                        (int)  $request->books +(int)  $request->security_fee +(int)  $request->uniform +(int)  $request->fine_panalties +
                                        (int) $request->printing_and_stationary + (int) $request->promotion_fee,
                                        'other_amount' => (int) $request->other_amount, 'other_fee_type' => $request->other_fee_type,
                                        'total_payable_fee' => (int) $student->transport_fee + (int) $student->total_fee + 
                                        (int) $request->other_amount + $previous_month_fee + (int) $request->admin_and_management_fee +
                                        (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                        (int) $request->printing_and_stationary +(int)  $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'concession' => '0',
                                        'transport_fee' => (int) $student->transport_fee,
                                        'month_year' => $request->month,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' => (int) $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                    'books' => (int) $request->books, 'security_fee' => (int) $request->security_fee,
                                    'uniform' => (int) $request->uniform, 'fine_panalties'=> (int) $request->fine_panalties,
                                    'printing_and_stationary' => (int) $request->printing_and_stationary,
                                    'promotion_fee' => (int) $request->promotion_fee,
                                    'percentage' => $student->discount_percent,
                                    'guardian_name' => $student->guardian_name
                                    ]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }
                        } else {
                            $input = $request->all();
                            $student = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'monthly']])->first();
                            if ($student != null) {
                                $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                    $previous_month_fee = "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee += (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee =(int)  "0";
                                }


                                $input['student_id'] = $student->student_id;
                                $input['student_name'] = $student->first_name . ' ' . $student->last_name;
                                $input['other_amount'] = (int) $request->other_amount;
                                $input['other_fee_type'] = $request->other_fee_type;
                                $input['total_amount'] = (int) $student->total_fee;
                                $input['class_id'] = $student->students_class_id;
                                $input['class_name'] = StudentsClass::find($request->class_id)->class_name;
                                $input['month'] = $request->month;
                                $input['month_year'] = $request->month;
                                $input['total_payable_fee'] = (int) $student->transport_fee + 
                                (int) $student->total_fee + (int) $request->other_amount + $previous_month_fee + (int) $request->admin_and_management_fee +
                                (int) $request->books + (int) $request->security_fee + (int) $request->uniform +(int)  $request->fine_panalties +
                                (int) $request->printing_and_stationary + (int) $request->promotion_fee;
                                $input['previous_month_fee'] = $previous_month_fee;
                                $input['issue_date'] = '-';
                                $input['concession'] = '0';
                                $input['transport_fee'] = (int) $student->transport_fee;
                                $input['due_date'] = '-';
                                $input['arrears'] =(int)  $student->transport_fee + (int) $student->total_fee + 
                                (int) $request->other_amount + $previous_month_fee + (int) $request->admin_and_management_fee +
                                (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                (int) $request->printing_and_stationary + (int) $request->promotion_fee;
                                $input['invoice_no'] = '#' . strtolower($student->student_id)  . '-' . $rand;
                                $input['invoice_created_by'] = ucfirst(Auth::user()->username);
                                $input['prospectus'] = (int) $request->prospectus;
                                    $input['admin_and_management_fee'] = (int) $request->admin_and_management_fee;
                                    $input['books'] = (int) $request->books;
                                    $input['security_fee'] = (int) $request->security_fee;
                                    $input['uniform'] = (int) $request->uniform;
                                    $input['fine_panalties'] = (int) $request->fine_panalties;
                                    $input['printing_and_stationary'] = (int) $request->printing_and_stationary;
                                    $input['promotion_fee'] =(int)  $request->promotion_fee;
                                    $input['percentage'] = $students->percent_discount;
                                    $input['guardian_name'] = $student->guardian_name;

                                Fee::create($input);
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }

                        }
                    } else {
                        if ($request->student_id == 'all_students' && $request->student_name == 'all_students') {
                            $class = StudentsClass::findOrFail($request->class_id);
                            if ($class->findStudents($request->class_id, $request->fee_setup)->count() != 0) {
                                foreach ($class->findStudents($request->class_id, $request->fee_setup) as $student) {
                                    $fee = Fee::where([['student_id', '=', $student->student_id], ['arrears', '>', '0']])->get();
                                    if ($fee->count() > 0 && $fee[0]->student_id == $student->student_id) {
                                        $previous_month_fee = "0";
                                        foreach ($fee as $i) {
                                            $previous_month_fee = (int) $i->arrears;
                                        }
                                    } else {
                                        $previous_month_fee = (int) "0";
                                    }

                                    Fee::create(['month' => $request->month,
                                        'issue_date' => '-', 'due_date' => '-',
                                        'invoice_created_by' => ucfirst(Auth::user()->username),
                                        'student_name' => $student->first_name . ' ' . $student->last_name,
                                        'student_id' => $student->student_id, 'total_amount' => $student->total_fee,
                                        'class_id' => $class->students[0]->student_id, 
                                        'arrears' => (int) $student->transport_fee + 
                                        (int) $student->total_fee + $previous_month_fee + (int) $request->admin_and_management_fee +
                                        (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                        (int) $request->printing_and_stationary + (int) $request->promotion_fee, 
                                        'other_amount' => '0', 'other_fee_type' => '-',
                                        'total_payable_fee' => (int) $student->transport_fee + (int) $student->total_fee + $previous_month_fee
                                        + (int) $request->admin_and_management_fee +
                                        (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                        (int) $request->printing_and_stationary + (int) $request->promotion_fee,
                                        'previous_month_fee' => $previous_month_fee,
                                        'concession' => '0',
                                        'transport_fee' => (int) $student->transport_fee,
                                        'month_year' => $request->month,
                                        'class_name' => StudentsClass::find($request->class_id)->class_name,
                                        'invoice_no' => '#' . strtolower($student->student_id)  . '-' . $rand,
                                        'prospectus' => (int) $request->prospectus, 'admin_and_management_fee' => (int) $request->admin_and_management_fee,
                                    'books' => (int) $request->books, 'security_fee' => (int) $request->security_fee,
                                    'uniform' => (int) $request->uniform, 'fine_panalties'=> (int) $request->fine_panalties,
                                    'printing_and_stationary' => (int) $request->printing_and_stationary,
                                    'promotion_fee' => (int) $request->promotion_fee,
                                    'guardian_name' => $student->guardian_name,
'percentage' => $student->discount_percent]);
                                }
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }

                        } else {
                            $input = $request->all();
                            $std = Student::where([['id', '=', $input['student_id']], ['fee_setup', '=', 'monthly']])->first();
                            if ($std != null) {
                                $fee = Fee::where([['student_id', '=', $std->student_id], ['arrears', '>', '0']])->get();
                                if ($fee->count() > 0) {
                                    $previous_month_fee = "0";
                                    foreach ($fee as $i) {
                                        $previous_month_fee = (int) $i->arrears;
                                    }
                                } else {
                                    $previous_month_fee = (int) "0";
                                }

                                $input['student_id'] = $std->student_id;
                                $input['student_name'] = $std->first_name . ' ' . $std->last_name;
                                $input['other_amount'] = (int) '0';
                                $input['other_fee_type'] = '-';
                                $input['total_amount'] = (int) $std->total_fee;
                                $input['total_payable_fee'] = (int) $std->transport_fee + (int) $std->total_fee + (int) $request->other_amount +
                                 $previous_month_fee + (int) $request->admin_and_management_fee +
                                 (int)  $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                 (int) $request->printing_and_stationary + (int) $request->promotion_fee;
                                $input['previous_month_fee'] = $previous_month_fee;
                                $input['class_id'] = $std->students_class_id;
                                $input['class_name'] = StudentsClass::find($request->class_id)->class_name;
                                $input['month'] = $request->month;
                                $input['month_year'] = $request->month;
                                $input['issue_date'] = '-';
                                $input['due_date'] = '-';
                                $input['concession'] = '0';
                                $input['transport_fee'] = (int) $std->transport_fee;
                                $input['arrears'] = (int) $std->transport_fee + (int) $std->total_fee + $previous_month_fee + (int) $request->admin_and_management_fee +
                                (int) $request->books + (int) $request->security_fee + (int) $request->uniform + (int) $request->fine_panalties +
                                (int) $request->printing_and_stationary + (int) $request->promotion_fee;
                                $input['invoice_no'] = '#' . strtolower($std->student_id)  . '-' . $rand;
                                $input['invoice_created_by'] = ucfirst(Auth::user()->username);
                                $input['prospectus'] = (int) $request->prospectus;
                                    $input['admin_and_management_fee'] = (int) $request->admin_and_management_fee;
                                    $input['books'] = (int) $request->books;
                                    $input['security_fee'] = (int) $request->security_fee;
                                    $input['uniform'] = (int) $request->uniform;
                                    $input['fine_panalties'] = (int) $request->fine_panalties;
                                    $input['printing_and_stationary'] = (int) $request->printing_and_stationary;
                                    $input['promotion_fee'] = (int) $request->promotion_fee;
                                    $input['percentage'] = $std->percent_discount;
                                    $input['guardian_name'] = $std->guardian_name;
                                Fee::create($input);
                            } else {
                                return redirect('/admin/fee/create')->with('delete_fee','You don\'t have any student for this Fee Method !');
                            }

                        }
                    }

                }
            }
        } else {
            $request->validate([
                'fee_setup' => 'required',
            ]);
        }


        return redirect('/admin/fee')->with('create_fee','The invoice has been created successfully !');

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
    public function update(FeeUpdateRequest $request, $id){
        $APIKey = '2bbbd0e93e2a249b20a19534c940d9ddec08ba48';
        $invoice = Fee::find($id);

        $student = Student::where('student_id', '=', $invoice['student_id'])->get()->first();
        if ($student == null) {
            return redirect()->back()->with('warning', 'The student record does not exit !');
        } else {

            if ($invoice->arrears == 0) {
                $invoice->update([
                    'paid_date' => $request->paid_date,
                    'month' => $invoice->month,
                    'month_year' => date('F, Y', strtotime($request->paid_date)),
                    'year' => date('Y', strtotime($request->paid_date)),
                ]);
            } else {

                if ($request->paid_amount <= $invoice->arrears) {
                    $invoice->update([
                        'invoice_no' => $invoice->invoice_no,
                        'class_id' => $invoice->class_id,
                        'class_name' => $invoice->class_name,
                        'student_id' => $invoice->student_id,
                        'student_name' => $invoice->student_name,
                        'issue_date' => $invoice->issue_date,
                        'due_date' => $invoice->due_date,
                        'paid_date' => $request->paid_date,
                        'month_year' => date('F, Y', strtotime($request->paid_date)),
                        'year' => date('Y', strtotime($request->paid_date)),
                        'paid_amount' => $invoice->paid_amount + $request->paid_amount,
                        'arrears' => $invoice->arrears - $request->paid_amount,
                        'concession' => $request->concession + $request->concession,
                        'previous_month_fee' => $invoice->previous_month_fee,
                        'total_payable_fee' => $invoice->total_payable_fee,
                        'total_amount' => $invoice->total_amount,
                        'other_amount' => $invoice->other_amount,
                        'month' => $invoice->month,
                        'other_fee_type' => $invoice->other_fee_type,
                        'invoice_created_by' => ucfirst(Auth::user()->username),
                    ]);
                } else {
                    $invoice->update([
                        'invoice_no' => $invoice->invoice_no,
                        'class_id' => $invoice->class_id,
                        'class_name' => $invoice->class_name,
                        'student_id' => $invoice->student_id,
                        'student_name' => $invoice->student_name,
                        'issue_date' => $invoice->issue_date,
                        'due_date' => $invoice->due_date,
                        'paid_date' => $request->paid_date,
                        'month_year' => date('F, Y', strtotime($request->paid_date)),
                        'year' => date('Y', strtotime($request->paid_date)),
                        'paid_amount' => $invoice->total_payable_fee,
                        'arrears' => '0',
                        'concession' => $request->concession + $request->concession,
                        'previous_month_fee' => $invoice->previous_month_fee,
                        'total_payable_fee' => $invoice->total_payable_fee,
                        'total_amount' => $invoice->total_amount,
                        'other_amount' => $invoice->other_amount,
                        'month' => $invoice->month,
                        'other_fee_type' => $invoice->other_fee_type,
                        'invoice_created_by' => ucfirst(Auth::user()->username),
                    ]);
                }

                FeeReport::create([
                    'invoice_no' => $invoice->invoice_no,
                    'class_id' => $invoice->class_id,
                    'class_name' => $invoice->class_name,
                    'student_id' => $invoice->student_id,
                    'student_name' => $invoice->student_name,
                    'issue_date' => $invoice->issue_date,
                    'due_date' => $invoice->due_date,
                    'paid_date' => $request->paid_date,
                    'month_year' => date('F, Y', strtotime($request->paid_date)),
                    'year' => date('Y', strtotime($request->paid_date)),
                    'paid_amount' => $request->paid_amount,
                    'month' => $invoice->month,
                    'invoice_created_by' => ucfirst(Auth::user()->username),
                ]);
            }


            $receiver = $student->guardian_phone_no;
            $sender = 'Hasnain khan';
            $textmessage = "Dear " . $student->guardian_name . ' Your child ' . $student->first_name . ' ' . $student->last_name
                . ' has paid RS ' . $request->paid_amount . ' in total of RS ' . $invoice['total_payable_fee'] . ' .Now the remaining balance is '
                . $request->arrears . ' RS';

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
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'guardian_phone_no' => $student->guardian_phone_no,
                'student_id' => $student->student_id, 'class' => $student->students_class_id]);
        }

        return redirect()->back()->with('update_fee','The invoice has been updated successfully !');
    }

    public function delete_invoices(Request $request) {
        if (!empty($request->checkBoxArray)) {
            Fee::whereIn('id', $request->checkBoxArray)->delete();
        }
        return redirect()->back()->with('delete_fee','The invoice has been deleted successfully !');
    }


    public function get_student_fee_api($id) {
        $student = User::find($id);
        $fee = Fee::where('student_id', '=', $student->user_id)->orderBy('id', 'desc')->get();
        return \App\Http\Resources\Fee::collection($fee);
    }

}
