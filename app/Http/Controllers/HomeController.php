<?php

namespace App\Http\Controllers;

use App\Account;
use App\ExpenseReport;
use App\Fee;
use App\FeeReport;
use App\Student;
use App\Teacher;
use App\TeachersSalary;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Khill\Lavacharts\Lavacharts;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $students = Student::where('status', '=', 'active')->count();
        $teachers = Teacher::count();
        $users = User::where('email', '!=', 'hasnain@ecloud.school')->count();
        $maleStudents = Student::where([['gender', '=', 'male'], ['status', '=', 'active']])->count();
        $femaleStudents = Student::where([['gender', '=', 'female'], ['status', '=', 'active']])->count();
        $maleUsers = Teacher::where('gender', '=', 'male')->count();
        $femaleUsers = Teacher::where('gender', '=', 'female')->count();

        $fee = FeeReport::all();
        $fees = $fee->groupBy(function ($result) {

                return date('F, Y', strtotime($result->paid_date));

        });

        $salary = TeachersSalary::all();
        $salaries = $salary->groupBy(function ($result) {
            return date('F, Y', strtotime($result->date));
        });

        $expense = ExpenseReport::all();
        $expenses = $expense->groupBy(function ($result) {
           return  date('F, Y', strtotime($result->date));
        });

        $salaries = response()->json($salaries)->getData();
        $fees = response()->json($fees)->getData();
        $expenses = response()->json($expenses)->getData();

        return view('admin.index',
            compact('fees',  'expenses', 'salaries', 'students', 'teachers',
                'users', 'maleStudents', 'femaleStudents', 'maleUsers', 'femaleUsers'));
    }
}
