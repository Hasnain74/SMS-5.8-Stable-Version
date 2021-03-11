<?php

namespace App\Http\Controllers\Auth;

use App\ExpenseReport;
use App\FeeReport;
use App\Http\Controllers\Controller;
use App\TeachersSalary;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/test2';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated() {
        if (Auth::check()) {
            if (Auth::user()->isStudent()) {
                return redirect('/student_profile');
            } elseif (Auth::user()->isAdmin()) {
                return redirect('/');
            } else {
                return redirect()->back();
            }
        }

        return redirect()->back();

    }

}
