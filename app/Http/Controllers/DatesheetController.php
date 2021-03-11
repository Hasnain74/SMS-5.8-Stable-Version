<?php

namespace App\Http\Controllers;

use App\Account;
use App\Datesheet;
use App\Http\Requests\DatesheetRequest;
use App\Student;
use App\StudentsClass;
use App\User;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DatesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $datesheet = Datesheet::orderBy('id', 'asc')->get();
        return view('admin.timetable.datesheet.index', compact('classes', 'datesheet'));
    }

    public function myDatesheetAjax($id) {
        $datesheet = Datesheet::where('class_id', $id)->get();
        return json_encode($datesheet);
    }

    public function deleteDs (Request $request) {
        if(!empty($request->checkBoxArray)) {
            Datesheet::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_datesheet','The datesheet has been deleted successfully !');
        }
        return redirect()->back()->with('delete_datesheet','The datesheet has been deleted successfully !');
    }

    public function printDateSheet($id) {
        $datesheet = Datesheet::find($id);
        $records = DB::table('datesheets')->where('class_id', '=', $datesheet->class_id)->get();
        // return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->
        // loadView('admin.timetable.datesheet.datesheet_print', compact('records'))->stream();
        return view('admin.timetable.datesheet.datesheet_print', compact('records'));
    }

    public function printAllDateSheet($id) {
        $records = Datesheet::all();
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->
        loadView('admin.timetable.datesheet.datesheet_print', compact('records'))->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.timetable.datesheet.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()) {
            $rules = array(
                'monday.*'  => 'required',
                'tuesday.*'  => 'required',
                'wednesday.*'  => 'required',
                'thursday.*'  => 'required',
                'friday.*'  => 'required',
                'saturday.*'  => 'required',
                'class_id' => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json([
                    'error'  => $error->errors()->all()
                ]);
            }

            $monday = $request->monday;
            $tuesday = $request->tuesday;
            $wednesday = $request->wednesday;
            $thursday = $request->thursday;
            $friday = $request->friday;
            $saturday = $request->saturday;
            for($count = 0; $count < count($monday); $count++)
            {
                $data = array(
                    'monday' => $monday[$count],
                    'tuesday'  => $tuesday[$count],
                    'wednesday'  => $wednesday[$count],
                    'thursday'  => $thursday[$count],
                    'friday'  => $friday[$count],
                    'saturday'  => $saturday[$count],
                    'class_name' => StudentsClass::find( $request->class_id )->class_name,
                    'class_id' => $request->class_id
                );
                $insert_data[] = $data;
            }

            Datesheet::insert($insert_data);
            return response()->json([
                'success'  => 'Data Added successfully.'
            ]);
        }
        return redirect()->back();
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
        $datesheet = Datesheet::findOrFail($id);
        $datesheet->update($request->all());
        return redirect()->back()->with('update_datesheet','The datesheet has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function get_student_datesheet_api($id) {
    //     $user = User::find($id);
    //     $student = Student::where('student_id', '=', $user->user_id)->get();
    //     $datesheet = Datesheet::where('class_id', '=', $student[0]->students_class_id)->get();
    //     return \App\Http\Resources\Timetable::collection($datesheet);
    // }
}
