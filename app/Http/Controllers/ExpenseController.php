<?php

namespace App\Http\Controllers;

use App\Account;
use App\Expense;
use App\ExpenseReport;
use App\Fee;
use App\FeeReport;
use App\Http\Requests\ExpenseRequest;
use App\Teacher;
use App\TeachersSalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fee = FeeReport::all();
        $fees = $fee->groupBy(function ($result) {
            return $result->month_year;
        });
        return view('admin.expense.index', compact('fees'));
    }

    public function print_daily_report(Request $request) {
        $fees = FeeReport::where('paid_date', $request->date)->get();
        $salaries = TeachersSalary::where('date', $request->date)->get();
        $other_expenses = ExpenseReport::where('date',$request->date)->get();

        $total = 0;
        $result = [];
        $report = [];

        foreach($fees as $fee) {
            $total += (int) $fee->paid_amount;
            $report[] =  [
                'paid_amount' => $fee->paid_amount,
                'type' => 'Paid Fee'
            ];
        }

        foreach($salaries as $salary) {
            $total += (int) $salary->paid_amount;
            $report[] =  [
                'paid_amount' => $salary->paid_amount,
                'type' => 'Paid Salary'
            ];
        }

        foreach($other_expenses as $other_expense) {
            $total += (int) $other_expense->total_amount;
            $report[] =  [
                'paid_amount' => $other_expense->total_amount,
                'type' => $other_expense->note
            ];
        }

        $result[] = ['total_amount' => $total,
                      'report' => $report,
                    'date' => $request->date];

        return view('admin.expense.print_daily_report', compact('result'));
    }

    public function deleteFeeRecord($id) {
        $input = FeeReport::where('id', '=', $id)->get();
        $record = DB::table('fee_reports')->where('date', '=', $input[0]->date);
        $record->delete();
        return redirect()->back();
    }

    public function expense_report() {
        $reports = ExpenseReport::all()->sortByDesc('updated_at');;
        return view('admin.expense.expense_report', compact('reports'));
    }

    public function salary_report() {
        $teacherSalaries = TeachersSalary::all()->sortByDesc('updated_at');
        $salaries = $teacherSalaries->groupBy(function ($result, $key) {
            return date('F, Y', strtotime($result->date));
        });

        return view('admin.expense.salary_report', compact('salaries'));
    }

    public function monthly_summary() {

        $fee = DB::table('fee_reports')->select('paid_amount', 'month_year')->get();
        $fees = $fee->groupBy(function ($result) {
            return $result->month_year;
        });


        $salary = DB::table('teachers_salaries')->select('paid_amount', 'month_year')->get();
        $salaries = $salary->groupBy(function ($result) {
            return $result->month_year;
        });

        $expense = DB::table('expense_reports')->select('total_amount', 'month_year')->get();
        $expenses = $expense->groupBy(function ($result) {
            return $result->month_year;
        });

        $years = range(date("Y"), 2017, -1 );

        $months = [
            'December',
            'November',
            'October',
            'September',
            'August',
            'July',
            'June',
            'May',
            'April',
            'March',
            'February',
            'January'
        ];

        $result = [];
        foreach ($years as $year){
            foreach ($months as $month) {
                $fee = 0;
                $salary = 0;
                $exp = 0;

                if( isset($fees["$month, $year"]) ){
                    foreach ($fees["$month, $year"] as $fee_entry){
                        $fee = $fee + intval($fee_entry->paid_amount);
                    }
                }
                if( isset($salaries["$month, $year"]) ){
                    foreach ($salaries["$month, $year"] as $salary_entry){
                        $salary = $salary + intval($salary_entry->paid_amount);
                    }
                }

                if( isset($expenses["$month, $year"]) ){
                    foreach ($expenses["$month, $year"] as $expense_entry){
                        $exp = $exp + intval($expense_entry->total_amount);
                    }
                }

                if( $fee != 0 || $salary != 0 || $exp != 0)
                {
                    $result["$month, $year"] = ['month'=> $month.', '.$year ,'fee'=>$fee, 'salary'=>$salary, 'exp'=>$exp];
                }
            }
        }

        return view('admin.expense.monthly_summary', compact('salaries', 'fees', 'result'));

    }

    public function print_monthly_expense(Request $request) {
        $input = $request->all();
        $fee = DB::table('fee_reports')
            ->select('paid_amount', 'month_year')
            ->where('month_year', '=', str_replace('_', ' ', array_key_first($input)))
            ->get();
        $fees = $fee->groupBy(function ($result) {
            return $result->month_year;
        });

        $salary = DB::table('teachers_salaries')->where('month_year', '=', str_replace('_', ' ', array_key_first($input)))
            ->select('paid_amount', 'month_year')->get();
        $salaries = $salary->groupBy(function ($result) {
            return $result->month_year;
        });

        $expense = DB::table('expense_reports')->where('month_year', '=', str_replace('_', ' ', array_key_first($input)))
            ->select('total_amount', 'month_year')->get();
        $expenses = $expense->groupBy(function ($result) {
            return $result->month_year;
        });

        $years = range(date("Y"), 2018, -1 );

        $months = [
            'December',
            'November',
            'October',
            'September',
            'August',
            'July',
            'June',
            'May',
            'April',
            'March',
            'February',
            'January'
        ];

        $result = [];
        foreach ($years as $year){
            foreach ($months as $month) {
                $fee = 0;
                $salary = 0;
                $exp = 0;

                if( isset($fees["$month, $year"]) ){
                    foreach ($fees["$month, $year"] as $fee_entry){
                        $fee = $fee + intval($fee_entry->paid_amount);
                    }
                }
                if( isset($salaries["$month, $year"]) ){
                    foreach ($salaries["$month, $year"] as $salary_entry){
                        $salary = $salary + intval($salary_entry->paid_amount);
                    }
                }

                if( isset($expenses["$month, $year"]) ){
                    foreach ($expenses["$month, $year"] as $expense_entry){
                        $exp = $exp + intval($expense_entry->total_amount);
                    }
                }

                if( $fee != 0 || $salary != 0 || $exp != 0)
                {
                    $result["$month, $year"] = ['month'=> $month.', '.$year ,'fee'=>$fee, 'salary'=>$salary, 'exp'=>$exp];
                }
            }
        }

        return view('admin.expense.pdf_monthly_summary', compact('result'));
    }

    public function print_multiple_months(Request $request) {
        if ($request->options == 'print') {
            if (!empty($request->checkBoxArray)) {
                $result = [];
                foreach ($request->checkBoxArray as $month) {
                    $fee = DB::table('fee_reports')->select('paid_amount', 'month_year')
                    ->where('month_year', '=', $month)->get();
                    $fees = $fee->groupBy(function ($result) {
                        return $result->month_year;
                    });

                    $salary = DB::table('teachers_salaries')->select('paid_amount', 'month_year')
                        ->where('month_year', '=', $month)->get();
                    $salaries = $salary->groupBy(function ($result) {
                        return $result->month_year;
                    });

                    $expense = DB::table('expense_reports')->select('total_amount', 'month_year')
                        ->where('month_year', '=', $month)->get();
                    $expenses = $expense->groupBy(function ($result) {
                        return $result->month_year;
                    });

                    $feeAmount = 0;
                    $salaryAmount = 0;
                    $expAmount = 0;

                    if( isset($fees["$month"]) ){
                        foreach ($fees["$month"] as $fee_entry){
                            $feeAmount = $feeAmount + intval($fee_entry->paid_amount);
                        }
                    }
                    if( isset($salaries["$month"]) ){
                        foreach ($salaries["$month"] as $salary_entry){
                            $salaryAmount = $salaryAmount + intval($salary_entry->paid_amount);
                        }
                    }

                    if( isset($expenses["$month"]) ){
                        foreach ($expenses["$month"] as $expense_entry){
                            $expAmount = $expAmount + intval($expense_entry->total_amount);
                        }
                    }

                    if( $feeAmount != 0 || $salaryAmount != 0 || $expAmount != 0)
                    {
                        $result["$month"] = ['month'=> $month ,'fee'=>$feeAmount, 'salary'=>$salaryAmount, 'exp'=>$expAmount];
                    }

                }
            }
        }

        return view('admin.expense.pdf_multiple_months', compact('result'));

    }

    public function yearly_summary() {

        $fee = DB::table('fee_reports')->select('paid_amount', 'year')->get();
        $fees = $fee->groupBy(function ($result) {
            return $result->year;
        });

        $salary = DB::table('teachers_salaries')->select('paid_amount', 'year')->get();
        $salaries = $salary->groupBy(function ($result) {
            return $result->year;
        });

        $expense = DB::table('expense_reports')->select('total_amount', 'year')->get();
        $expenses = $expense->groupBy(function ($result) {
            return $result->year;
        });


        $years = range(date("Y"), 2017, -1 );
        $result = [];

        foreach ($years as $year) {
            $feeAmount = 0;
            $salaryAmount = 0;
            $expAmount = 0;

            if( isset($fees[$year] )) {
                foreach ($fees[$year] as $yea) {
                    $feeAmount = $feeAmount + intval($yea->paid_amount);
                }
            }

            if( isset($salaries[$year] )) {
                foreach ($salaries[$year] as $yea) {
                    $salaryAmount = $salaryAmount + intval($yea->paid_amount);
                }
            }

            if( isset($expenses[$year] )) {
                foreach ($expenses[$year] as $yea) {
                    $expAmount = $expAmount + intval($yea->total_amount);
                }
            }

            if ($feeAmount != 0 || $salaryAmount != 0 || $expAmount != 0) {
                $result[$year] = ['fee' => $feeAmount, 'salary' => $salaryAmount, 'exp' => $expAmount];
            }

        }

        return view('admin.expense.yearly_summary', compact('result'));
    }

    public function print_yearly_expense(Request $request) {
        $input = $request->all();
        $fee = DB::table('fee_reports')
            ->select('paid_amount', 'year')
            ->where('year', '=', array_key_first($input))
            ->get();
        $fees = $fee->groupBy(function ($result) {
            return $result->year;
        });

        $salary = DB::table('teachers_salaries')
            ->where('year', '=', array_key_first($input))
            ->select('paid_amount', 'year')->get();
        $salaries = $salary->groupBy(function ($result) {
            return $result->year;
        });

        $expense = DB::table('expense_reports')
            ->where('year', '=', array_key_first($input))
            ->select('total_amount', 'year')->get();
        $expenses = $expense->groupBy(function ($result) {
            return $result->year;
        });

        $years = range(date("Y"), 2017, -1 );

        $result = [];

        foreach ($years as $year) {
            $feeAmount = 0;
            $salaryAmount = 0;
            $expAmount = 0;

            if( isset($fees[$year] )) {
                foreach ($fees[$year] as $f) {
                    $feeAmount = $feeAmount + intval($f->paid_amount);
                }
            }

            if( isset($salaries[$year] )) {
                foreach ($salaries[$year] as $f) {
                    $salaryAmount = $salaryAmount + intval($f->paid_amount);
                }
            }

            if( isset($expenses[$year])) {
                foreach ($expenses[$year] as $f) {
                    $expAmount = $expAmount + intval($f->total_amount);
                }
            }

            if ($feeAmount != 0 || $salaryAmount != 0 || $expAmount != 0) {
                $result[$year] = ['year' => $year, 'fee' => $feeAmount, 'salary' => $salaryAmount, 'exp' => $expAmount];
            }
        }

        return view('admin.expense.pdf_yearly_summary', compact('result'));

    }

    public function print_multiple_years(Request $request) {
        if ($request->options == 'print') {
            if (!empty($request->checkBoxArray)) {
                $result = [];
                foreach ($request->checkBoxArray as $year) {
                    $fee = DB::table('fee_reports')->select('paid_amount', 'year')
                        ->where('year', '=', $year)->get();
                    $fees = $fee->groupBy(function ($result) {
                        return $result->year;
                    });

                    $salary = DB::table('teachers_salaries')->select('paid_amount', 'year')
                        ->where('year', '=', $year)->get();
                    $salaries = $salary->groupBy(function ($result) {
                        return $result->year;
                    });

                    $expense = DB::table('expense_reports')->select('total_amount', 'year')
                        ->where('year', '=', $year)->get();
                    $expenses = $expense->groupBy(function ($result) {
                        return $result->year;
                    });

                    $feeAmount = 0;
                    $salaryAmount = 0;
                    $expAmount = 0;

                    if( isset($fees["$year"]) ){
                        foreach ($fees["$year"] as $fee_entry){
                            $feeAmount = $feeAmount + intval($fee_entry->paid_amount);
                        }
                    }
                    if( isset($salaries["$year"]) ){
                        foreach ($salaries["$year"] as $salary_entry){
                            $salaryAmount = $salaryAmount + intval($salary_entry->paid_amount);
                        }
                    }

                    if( isset($expenses["$year"]) ){
                        foreach ($expenses["$year"] as $expense_entry){
                            $expAmount = $expAmount + intval($expense_entry->total_amount);
                        }
                    }

                    if( $feeAmount != 0 || $salaryAmount != 0 || $expAmount != 0)
                    {
                        $result["$year"] = ['year'=> $year ,'fee'=>$feeAmount, 'salary'=>$salaryAmount, 'exp'=>$expAmount];
                    }

                }
            }
        }

        return view('admin.expense.pdf_multiple_years', compact('result'));
    }

    public function total_summary() {
        $fees = DB::table('fee_reports')->select('paid_amount')->get();
        $salaries = DB::table('teachers_salaries')->select('paid_amount')->get();
        $expenses = DB::table('expense_reports')->select('total_amount')->get();


        $feeAmount = 0;
        $salaryAmount = 0;
        $expenseAmount = 0;
        $result = [];

        foreach ($fees as $fee) {
            foreach ($fee as $f) {
                $feeAmount = $feeAmount + intval($f);
            }
        }

        foreach ($expenses as $expense) {
            foreach ($expense as $e) {
                $expenseAmount = $expenseAmount + intval($e);
            }
        }

        foreach ($salaries as $salary) {
            foreach ($salary as $s) {
                $salaryAmount = $salaryAmount + intval($s);
            }
        }

        if ($feeAmount != 0 || $expenseAmount != 0 || $salaryAmount != 0) {
            $result[] = ['fee' => $feeAmount, 'salary' => $salaryAmount, 'exp' => $expenseAmount];
        }

        return view('admin.expense.total_summary', compact('result'));
    }

    public function pdf_total_summary() {
        $fees = DB::table('fee_reports')->select('paid_amount')->get();
        $salaries = DB::table('teachers_salaries')->select('paid_amount')->get();
        $expenses = DB::table('expense_reports')->select('total_amount')->get();


        $feeAmount = 0;
        $salaryAmount = 0;
        $expenseAmount = 0;
        $result = [];

        foreach ($fees as $fee) {
            foreach ($fee as $f) {
                $feeAmount = $feeAmount + intval($f);
            }
        }

        foreach ($expenses as $expense) {
            foreach ($expense as $e) {
                $expenseAmount = $expenseAmount + intval($e);
            }
        }

        foreach ($salaries as $salary) {
            foreach ($salary as $s) {
                $salaryAmount = $salaryAmount + intval($s);
            }
        }

        if ($feeAmount != 0 || $expenseAmount != 0 || $salaryAmount != 0) {
            $result[] = ['fee' => $feeAmount, 'salary' => $salaryAmount, 'exp' => $expenseAmount];
        }

        return view('admin.expense.pdf_total_summary', compact('result'));
    }

    public function deleteReport(Request $request) {
        if(!empty($request->checkBoxArray)) {
            ExpenseReport::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_expense_invoice','The invoice has been deleted successfully !');
        }
        return redirect()->back()->with('delete_expense_invoice','The invoice has been deleted successfully !');
    }

    public function downloadReportPDF($id) {
        $reports = [ ExpenseReport::findOrFail($id) ];
        return view('admin.expense.ReportPdf', compact('reports'));
    }

    public function downloadFeePDF($id) {
        $fees = FeeReport::find($id);
        $fee = Carbon::parse($fees->paid_date)->format('m');
        $report = DB::table('fee_reports')->whereMonth('paid_date', '=', $fee)->get();
        $reports = $report->groupBy(function ($result) {
            return date('F, Y', strtotime($result->paid_date));
        });
        return view('admin.expense.feePDF', compact('reports', 'report'));
    }

    public function downloadPDF($id) {
        $salaries = TeachersSalary::find($id);
        $month = date('F, Y', strtotime($salaries->date));
        $report = DB::table('teachers_salaries')->where('month_year', '=', $month)->get();
        $reports = $report->groupBy(function ($result, $key) {
            return date('F, Y', strtotime($result->date));
        });
        return view('admin.expense.expensePdf', compact('reports', 'report'));
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
    public function store(ExpenseRequest $request)
    {
        $input = $request->all();
        $input['date'] = $request->date;
        $input['total_amount'] = $request->total_amount;
        $input['note'] = $request->note;
        $input['created_by'] = ucfirst(Auth::user()->username);
        $input['month_year'] = date('F, Y', strtotime($request->date));
        $input['year'] = date('Y', strtotime($request->date));
        ExpenseReport::create($input);
        return redirect()->back()->with('create_expense_invoice','The invoice has been created successfully !');
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
        $reports = ExpenseReport::all();
        return view('admin.expense.expense_report', compact('reports'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, $id)
    {
        $report = ExpenseReport::findOrFail($id);
        $input = $request->all();
        $input['date'] = $request->date;
        $input['total_amount'] = $request->total_amount;
        $input['note'] = $request->note;
        $input['month_year'] = date('F, Y', strtotime($request->date));
        $input['year'] = date('Y', strtotime($request->date));
        $report->update($input);
        return redirect()->back()->with('update_expense_invoice','The invoice has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
