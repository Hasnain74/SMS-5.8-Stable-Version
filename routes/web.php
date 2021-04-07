<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware'=>'prevent-back-history'], function() {

//    Auth::routes(['register' => false]);

    Auth::routes();
    Route::get('/', 'HomeController@index');
    Route::get('/logout', 'Auth\LoginController@logout');
    Route::get('/student_profile', 'StudentProfileController@studentData');

    //ROUTES FOR STUDENTS===========================================================================
    Route::get('/students/export', 'StudentsController@export');
    Route::get('students/edit_student/{id}', ['as' => 'edit_student', 'uses' => 'StudentsController@edit']);
    Route::delete('/students/delete/std', 'StudentsController@deleteStudents');
    Route::get('students/student_accounts', ['as' => 'student_accounts', 'uses' => 'StudentsController@student_accounts']);
    Route::patch('students/update_account/{id}', ['as' => 'update_account', 'uses' => 'StudentsController@update_account']);
    Route::get('students/edit_student_account/{id}', ['as' => 'edit_student_account', 'uses' => 'StudentsController@edit_student_account']);
    Route::get('students/students_promotions/', ['as' => 'student_promotions', 'uses' => 'StudentsController@student_promotions']);

    Route::resource('admin/students', 'StudentsController', ['names' => [

        'index' => 'admin.students.index',
        'create' => 'admin.students.create'
    ]]);

    Route::get('students/student_attendance_register', ['as' => 'student_attendance_register', 'uses' => 'AttendanceController@student_attendance_registers']);
    Route::get('students/edit_class_register', ['as' => 'edit_class_register', 'uses' => 'AttendanceController@edit']);
    Route::get('students/getStudents', ['as' => 'getStudents', 'uses' => 'AttendanceController@getStudents']);
    Route::get('/ajaxFee/{id}', ['as' => 'students.ajaxFee', 'uses' => 'StudentsController@getStudentFee']);
    Route::get('myform/ajax/{id}', array('as' => 'myform.ajax', 'uses' => 'AttendanceController@mytableAjax'));
    Route::get('students/student_promotions_ajax/{id}', array('as' => 'students.student_promotions_ajax', 'uses' => 'StudentsController@student_promotions_ajax'));
    Route::post('students/students_promotions', 'StudentsController@promote_students');
    Route::get('attendance/ajax/{id}', array('as' => 'attendance.ajax', 'uses' => 'AttendanceController@myAttendanceAjax'));
    Route::get('atd/ajax/{id}', array('as' => 'atd.ajax', 'uses' => 'AttendanceController@myAtdAjax'));
    Route::post('students/attendance', 'AttendanceController@store');
    Route::post('attendance/delete', 'AttendanceController@deleteAttendance');
    Route::delete('students/delete', 'StudentsController@deleteStudents');
    Route::get('/downloadPdf/{id}', 'StudentsController@downloadPDF');
    Route::delete('students/student_accounts/{id}', 'TeacherController@delete_account');
    Route::get('print_attendance_report', 'AttendanceController@print_attendance_report');

    Route::resource('/students/attendance', 'AttendanceController', ['names' => [

        'index' => 'admin.students.attendance.index',

    ]]);


//ROUTES FOR TEACHERS===========================================================================

    Route::get('/teachers/export', 'TeacherController@export');
    Route::get('/teachers_finance/export', 'TeachersSalaryController@export');

    Route::get('teachers/edit/{id}', ['as' => 'edit', 'uses' => 'TeacherController@edit']);
    Route::get('teachers/edit_account/{id}', ['as' => 'edit_account', 'uses' => 'TeacherController@edit_account']);
    Route::patch('teachers/update_account/{id}', ['as' => 'update_account', 'uses' => 'TeacherController@update_account']);
    Route::patch('teachers/update_teacher_role/{id}', ['as' => 'update_teacher_role', 'uses' => 'TeacherController@update_teacher_role']);
    Route::get('teachers/teacher_accounts', ['as' => 'teacher_accounts', 'uses' => 'TeacherController@teacher_accounts']);
    Route::get('teachers/teacher_roles', ['as' => 'teacher_roles', 'uses' => 'TeacherController@teacher_roles']);
    Route::delete('/teachers/delete/attendance', 'TeachersAttendanceController@deleteAttendance');
    Route::get('teachers/salary_list', ['as' => 'index', 'uses' => 'TeachersSalaryController@index']);
    Route::post('teachers/salary_list', 'TeachersSalaryController@store');
    Route::delete('teachers/salary_list/{id}', 'TeachersSalaryController@destroy');
    Route::get('teachers/ajaxName/{id}', array('as' => 'teachers.ajaxName', 'uses' => 'TeachersSalaryController@getTeacherName'));
    Route::get('teachers/ajaxSalary/{id}', array('as' => 'teachers.ajaxSalary', 'uses' => 'TeachersSalaryController@getTeacherSalary'));
    Route::delete('/teachers/delete/invoices', 'TeachersSalaryController@deleteInvoices');
    Route::patch('teachers/salary_list/{id}', 'TeachersSalaryController@update');
    Route::delete('teachers/delete', 'TeacherController@deleteTeachers');
    Route::delete('teachers/attendance/delete', 'TeachersAttendanceController@deleteAttendance');
    Route::delete('teachers/delete_teacher_account/{id}', 'TeacherController@delete_teacher_account');
    Route::delete('inv/delete', 'TeachersSalaryController@deleteInv');
    Route::delete('teachers/teacher_roles/{id}', 'TeacherController@delete_role');
    Route::get('/downloadUserPdf/{id}', 'TeacherController@downloadPDF');
    Route::get('/downloadSalaryPdf/{id}', 'TeachersSalaryController@downloadPDF');
    Route::get('teacher_attendance/ajax/{id}', array('as' => 'teacher_attendance.ajax', 'uses' => 'TeachersAttendanceController@TeachersAtdAjax'));
    Route::get('teacher_salary/ajaxSalaryList/{id}', array('as' => 'teacher_salary.ajaxSalaryList', 'uses' => 'TeachersSalaryController@TeachersSalaryAjax'));

    Route::resource('admin/teachers', 'TeacherController', ['names' => [
        'index' => 'admin.teachers.index',
        'create' => 'admin.teachers.create'
    ]]);

    // Route::get('/manage_salary_invoice', array('as' => 'manage_salary_invoice', 'uses' => 'TeachersSalaryController@manage_salary_invoice'));
    Route::post('/store_invoice_setup', 'TeachersSalaryController@store_invoice_setup');
    // Route::delete('manage_salary_invoice/delete/{id}', 'TeachersSalaryController@delete_setup');
    Route::get('/ajaxTeachersAttendance/', array('as' => 'ajaxTeachersAttendance', 'uses' => 'TeachersSalaryController@getTeacherAttendance'));
    // Route::get('calculateCash/{id}', array('as' => 'calculateCash', 'uses' => 'TeachersSalaryController@calculateCash'));
    Route::get('teacherPhNo/{id}', array('as' => 'teacherPhNo', 'uses' => 'TeachersSalaryController@teacherPhNo'));


    Route::get('teachers/teacher_attendance_register', ['as' => 'attendance_register', 'uses' => 'TeachersAttendanceController@teacher_attendance_register']);

    Route::resource('/teachers/attendance', 'TeachersAttendanceController', ['names' => [

        'index' => 'admin.teachers.attendance.take_attendance',

    ]]);


//ROUTES FOR CLASSES===========================================================================

    Route::resource('admin/classes', 'StudentsClassController', ['names' => [

        'index' => 'admin.classes.index',

    ]]);

    Route::get('/print_classes', array('as' => 'print_classes', 'uses' => 'StudentsClassController@print_class'));

//ROUTES FOR TIMETABLE===========================================================================

    Route::get('timetable/ajax/{id}', array('as' => 'timetable.ajax', 'uses' => 'TimetableController@myTimetableAjax'));
    Route::delete('timetable/delete', 'TimetableController@deleteTb');
    Route::get('timetable/print/{id}', 'TimetableController@printTimetable');
    Route::get('timetable/printAll/{id}', 'TimetableController@printAllTimetable');
    Route::get('timetable/day_wise_timetable', array('as' => 'day_wise_timetable', 'uses' => 'TimetableController@day_wise_timetable'));
    Route::get('timetable/create2', array('as' => 'create2', 'uses' => 'TimetableController@create2'));
    Route::post('timetable/store_day_wise_timetable', 'TimetableController@store_day_wise_timetable')->name('timetable.store_day_wise_timetable');
    Route::patch('timetable/update2/{id}', 'TimetableController@update2');
    Route::delete('timetable/delete2/{id}', 'TimetableController@delete2');
    Route::get('timetable/print_day_wise_timetable/{id}', array('as' => 'print_day_wise_timetable', 'uses' => 'TimetableController@print_day_wise_timetable'));
    Route::post('timetable/store', 'TimetableController@store')->name('timetable.store');

    Route::resource('admin/timetable', 'TimetableController', ['names' => [

        'index' => 'admin.timetable.index',
        'create' => 'admin.timetable.create'

    ]]);


//ROUTES FOR DATESHEET===========================================================================

    Route::get('/datesheet/ajax/{id}', array('as' => 'datesheet.ajax', 'uses' => 'DatesheetController@myDatesheetAjax'));
    Route::delete('datesheet/delete', 'DatesheetController@deleteDs');
    Route::get('datesheet/print/{id}', 'DatesheetController@printDateSheet');
    Route::get('datesheet/printAll/{id}', 'DatesheetController@printAllDateSheet');
    Route::post('datesheet/store', 'DatesheetController@store')->name('datesheet.store');

    Route::resource('timetable/datesheet', 'DatesheetController', ['names' => [

        'index' => 'admin.timetable.datesheet.index',
        'create' => 'admin.timetable.datesheet.create',

    ]]);

//ROUTES FOR Reports CAT===========================================================================
    Route::post('/dmc_setup/{id}', 'ReportCategoriesController@dmc_setup');
    Route::delete('/dmc_setup/remove_cat/{id}', 'ReportCategoriesController@remove_cat');

    Route::resource('reports/rep_cats', 'ReportCategoriesController', ['names' => [

        'index' => 'admin.reports.rep_cats.index',

    ]]);

//ROUTES FOR Reports===========================================================================

    Route::get('report/export', 'ReportController@export');

    Route::get('reports/ajaxID/{id}', array('as' => 'reports.ajaxID', 'uses' => 'ReportController@getStudentId'));
    Route::get('reports/ajaxName/{id}', array('as' => 'reports.ajaxName', 'uses' => 'ReportController@getStudentName'));
    Route::get('reports/ajax/{id}', array('as' => 'reports.ajax', 'uses' => 'ReportController@myReportAjax'));
    Route::get('rpt/ajax/{id}', array('as' => 'rpt.ajax', 'uses' => 'ReportController@myRptAjax'));
    Route::get('/downloadReportPDF/{id}', 'ReportController@downloadReportPDF');
    Route::get('/printReport/{id}', 'ReportController@printReport');
    Route::get('/print_award_list_report/{id}', 'ReportController@print_award_list_report');
    Route::post('/print_dmc/{id}', 'ReportController@print_dmc');
    Route::get('/print_empty_award_list/{id}', 'ReportController@print_empty_award_list');
    Route::get('/print_subject_for_whole_class/{id}', 'ReportController@print_subject_for_whole_class');
    Route::get('/reportsActions', 'ReportController@reportsActions');
    Route::get('reports/subject_marks', array('as' => 'subject_marks', 'uses' => 'ReportController@subject_marks'));
    Route::post('/store_subjects', 'ReportController@store_subjects');
    Route::patch('/edit_subjects/{id}', 'ReportController@edit_subjects');
    Route::get('subjects/ajax/{id}', array('as' => 'subjects.ajax', 'uses' => 'ReportController@subjects_ajax'));
    Route::get('/add_marks', array('as' => 'add_marks', 'uses' => 'ReportController@add_marks'));
    Route::post('/store', 'ReportController@store');
    Route::delete('/delete_subject/{id}', 'ReportController@delete_subject');

    Route::delete('reports/delete', 'ReportController@deleteReport');
    

    Route::resource('admin/reports', 'ReportController', ['names' => [

        'index' => 'admin.reports.index',
        'create' => 'admin.reports.create',

    ]]);


//ROUTES FOR FEE===========================================================================


    Route::get('fee/ajax/{id}', array('as' => 'fee.ajax', 'uses' => 'FeeController@myFeeAjax'));
    Route::get('fee/status/{id}', array('as' => 'feeStatus.ajax', 'uses' => 'FeeController@feeStatusAjax'));
    Route::get('/downloadPDF/{id}', 'FeeController@downloadPDF');

    Route::get('/printInvoice/{id}', 'FeeController@printInvoice');
    
    Route::post('/storeFeeSetup}', 'FeeController@storeFeeSetup');
    Route::post('/delete_fee_setup/{id}', 'FeeController@delete_fee_setup');
    Route::post('/update_fee_setup/{id}', 'FeeController@update_fee_setup');
    Route::post('/paid/{id}', array('as' => 'paid', 'uses' => 'FeeController@paid'));
    Route::get('/invoicesActions', 'FeeController@invoicesActions');
    Route::post('invoice/delete', 'FeeController@delete_invoices');
    Route::get('fee/throughId/{id}', array('as' => 'throughId.ajax', 'uses' => 'FeeController@getFeeWithId'));
    Route::get('fee/instalment_fee', array('as' => 'instalment_fee', 'uses' => 'FeeController@instalment_fee'));
    Route::get('fee/admission_fee', array('as' => 'admission_fee', 'uses' => 'FeeController@admission_fee'));
    Route::post('/store_admission_fee', 'FeeController@store_admission_fee');
    Route::post('/update_admission_fee/{id}', 'FeeController@update_admission_fee');
    Route::post('/delete_admission_fee/{id}', 'FeeController@delete_admission_fee');
    Route::get('/print_admission_fee', array('as' => 'print_admission_fee', 'uses' => 'FeeController@print_admission_fee'));
    

    Route::get('invoice/ajaxID/', array('as' => 'invoice.ajaxID', 'uses' => 'FeeController@getStudentId'));
    Route::get('invoice/ajaxName/{id}', array('as' => 'invoice.ajaxName', 'uses' => 'FeeController@getStudentName'));

    Route::get('invoice/ajaxAdmFeeID/{id}', array('as' => 'invoice.ajaxID', 'uses' => 'FeeController@getStudentIdForAdm'));
    Route::get('invoice/ajaxAdmFeeName/', array('as' => 'invoice.ajaxName', 'uses' => 'FeeController@getStudentNameForAdm'));

    // Route::get('invoice/ajaxAdmEditID/{id}', array('as' => 'invoice.ajaxID', 'uses' => 'FeeController@getStudentIdeForAdmEdit'));
    // Route::post('invoice/ajaxAdmEditName/', array('as' => 'invoice.ajaxID', 'uses' => 'FeeController@getStudentNameForAdmEdit'));

    Route::resource('admin/fee', 'FeeController', ['names' => [

        'index' => 'admin.fee.index',
        'create' => 'admin.fee.create',

    ]]);


//ROUTES FOR ROLES===========================================================================

    Route::resource('admin/mng_roles', 'RolesController', ['names' => [

        'index' => 'admin.mng_roles.index',
        'create' => 'admin.mng_roles.create',
        'edit' => 'admin.mng_roles.edit'

    ]]);


//ROUTES FOR EXPENSE===========================================================================

    Route::get('expense/expense_report', array('as' => 'expense_report', 'uses' => 'ExpenseController@expense_report'));
    Route::get('expense/salary_report', array('as' => 'salary_report', 'uses' => 'ExpenseController@salary_report'));
    Route::get('expense/monthly_summary', array('as' => 'monthly_summary', 'uses' => 'ExpenseController@monthly_summary'));
    Route::get('expense/yearly_summary', array('as' => 'yearly_summary', 'uses' => 'ExpenseController@yearly_summary'));
    Route::get('expense/total_summary', array('as' => 'total_summary', 'uses' => 'ExpenseController@total_summary'));
    Route::delete('expense/deleteReport', 'ExpenseController@deleteReport');
    Route::get('/downloadExpensePdf/{id}', 'ExpenseController@downloadPDF');
    Route::get('/downloadReportPdf/{id}', 'ExpenseController@downloadReportPDF');
    Route::get('/downloadFeePdf/{id}', 'ExpenseController@downloadFeePDF');
    Route::get('/pdf_total_summary', 'ExpenseController@pdf_total_summary');
    Route::get('/print_daily_report', 'ExpenseController@print_daily_report');
    Route::get('/print_monthly_expense', array('as' => 'print', 'uses' => 'ExpenseController@print_monthly_expense'));
    Route::get('/print_multiple_months', array('as' => 'print_multiple_months', 'uses' => 'ExpenseController@print_multiple_months'));
    Route::get('/print_yearly_expense', array('as' => 'print_yearly_expense', 'uses' => 'ExpenseController@print_yearly_expense'));
    Route::get('/print_multiple_years', array('as' => 'print_multiple_years', 'uses' => 'ExpenseController@print_multiple_years'));

    Route::resource('admin/expense', 'ExpenseController', ['names' => [

        'index' => 'admin.expense.index',

    ]]);


//ROUTES FOR SMS===========================================================================

    Route::delete('sms/delete', 'SmsController@deleteSms');
    Route::get('sms/ajaxNumber/{id}', array('as' => 'sms.ajaxNumber', 'uses' => 'SmsController@getStudentNumber'));
    Route::get('sms/ajax/{id}', array('as' => 'sms.ajax', 'uses' => 'SmsController@mySmsAjax'));

    Route::get('sms/students/{id}', array('as' => 'sms.ajax', 'uses' => 'SmsController@getStudentId'));

    Route::resource('admin/sms', 'SmsController', ['names' => [

        'index' => 'admin.sms.index',
        'create' => 'admin.sms.create'

    ]]);


    Route::get('/students/attendance/edit/{id}', ['as' => 'students.attendance.edit', 'uses' => 'AttendanceController@edit']);
    Route::post('/students/attendance/update/{id}', ['as' => 'students.attendance.update', 'uses' => 'AttendanceController@update']);
    Route::get('/students/attendance/delete/{id}', ['as' => 'students.attendance.delete', 'uses' => 'AttendanceController@delete']);
    Route::post('/students/attendance/destroy/{id}', ['as' => 'students.attendance.destroy', 'uses' => 'AttendanceController@destroy']);
    Route::get('/students/attendance/print/{id}', ['as' => 'students.attendance.print', 'uses' => 'AttendanceController@print']);
});