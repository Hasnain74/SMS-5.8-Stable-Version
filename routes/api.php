<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//getting student attendance
Route::get('get_student_attendance/{id}', 'AttendanceController@get_student_attendance_api');

//getting student reports
Route::get('get_student_reports/{id}', 'ReportController@get_student_reports_api');

//getting student fee
Route::get('get_student_fee/{id}', 'FeeController@get_student_fee_api');

//getting student sms
Route::get('get_student_sms/{id}', 'SmsController@get_student_sms_api');

//getting student timetable
Route::get('get_student_timetable/{id}', 'TimetableController@get_student_timetable_api');

//getting student timetable
Route::get('get_daywise_timetable', 'TimetableController@get_daywise_timetable');

//getting student datesheet
Route::get('get_student_datesheet/{id}', 'DatesheetController@get_student_datesheet_api');

//login
Route::post('login', 'Api\AuthController@login');

//For getting classes
Route::get('/get_classes', 'StudentsClassController@get_classes_api');

//For getting students
Route::get('/get_students/{id}', 'StudentsController@get_students');

//For submitting attendance
Route::post('/submit_attendance', 'AttendanceController@submit_attendance');

//For getting student data
Route::get('/get_student_data/{id}', 'StudentsController@get_student_data');
