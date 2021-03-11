<?php

namespace App\Http\Controllers;

use App\DayWiseTimetable;
use App\Student;
use App\StudentsClass;
use App\Timetable;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $timetable = Timetable::orderBy('id', 'asc')->get();
        return view('admin.timetable.index', compact('classes', 'timetable'));
    }

    public function day_wise_timetable(){
        $timetable = DB::table('day_wise_timetables')->get();
        return view('admin.timetable.day_wise_timetable', compact('timetable'));
    }

    public function create2() {
        return view('admin.timetable.create2');
    }

    public function store_day_wise_timetable(Request $request) {
        if($request->ajax()) {
            $rules = array(
                'period.*'  => 'required',
                'period_timing.*'  => 'required',
                'day' => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json([
                    'error'  => $error->errors()->all()
                ]);
            }

            $period = $request->period;
            $period_timing = $request->period_timing;
            for($count = 0; $count < count($period); $count++)
            {
                $data = array(
                    'period' => $period[$count],
                    'period_timing'  => $period_timing[$count],
                    'day' => $request->day
                );
                $insert_data[] = $data;
            }

            DayWiseTimetable::insert($insert_data);
            return response()->json([
                'success'  => 'Data Added successfully.'
            ]);
        }
        return redirect()->back();
    }

    public function update2(Request $request, $id) {
        $input = $request->all();
        $timetable = DayWiseTimetable::find($id);
        $timetable->update($input);
        return redirect()->back();
    }

    public function delete2($id) {
        $tb = DayWiseTimetable::find($id);
        $tb->delete();
        return redirect()->back();
    }

    public function print_day_wise_timetable($id) {
        $timetable = DayWiseTimetable::find($id);
        $record = DB::table('day_wise_timetables')->where('day', '=', $timetable->day)->get();
        $records = $record->groupBy(function ($result) {
            return $result->day;
        })->toArray();
        return view('admin.timetable.print_day_wise_timetable', compact('records'));
    }

    public function myTimetableAjax($id) {
        $timetable = Timetable::where('class_id', $id)->get();
        return json_encode($timetable);
    }

    public function deleteTb (Request $request) {
        if(!empty($request->checkBoxArray)) {
            Timetable::whereIn('id', $request->checkBoxArray)->delete();
            return redirect()->back()->with('delete_timetables','The timetable has been deleted successfully !');
        }
        return redirect()->back()->with('delete_timetables','The timetables has been deleted successfully !');
    }

    public function printTimetable($id) {
        $timetable = [Timetable::find($id)];
        $records = DB::table('timetables')->where('class_id', '=', $timetable[0]->class_id)->get();
        return view('admin.timetable.timetable_print', compact('records'));
    }

    public function printAllTimetable($id) {
        $records = Timetable::all();
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->
        loadView('admin.timetable.timetable_print', compact('records'))->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        return view('admin.timetable.create', compact('classes'));
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

            $period = $request->period;
            $monday = $request->monday;
            $tuesday = $request->tuesday;
            $wednesday = $request->wednesday;
            $thursday = $request->thursday;
            $friday = $request->friday;
            $saturday = $request->saturday;
            for($count = 0; $count < count($period); $count++)
            {
                $data = array(
                    'period' => $period[$count],
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

            Timetable::insert($insert_data);
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
        $timetable = Timetable::findOrFail($id);
        $timetable->update($request->all());
        return redirect()->back()->with('update_timetable','The timetables has been updated successfully !');
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

    public function get_student_timetable_api($id) {
        $user = User::find($id);
        $student = Student::where('student_id', '=', $user->user_id)->get();
        $timetable = Timetable::where('class_id', '=', $student[0]->students_class_id)->get();
        return \App\Http\Resources\Timetable::collection($timetable);
    }

    public function get_daywise_timetable_api() {
        $timetable = DayWiseTimetable::all();
        return \App\Http\Resources\DaywiseTimetable::collection($timetable);
    }
}
