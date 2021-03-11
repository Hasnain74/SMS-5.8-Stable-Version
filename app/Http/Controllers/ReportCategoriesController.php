<?php

namespace App\Http\Controllers;

use App\Account;
use App\DmcSetup;
use App\Http\Requests\ReportCategoriesRequest;
use App\Report;
use App\ReportCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rep_cats = ReportCategories::all();
        return view('admin.reports.rep_cats.index', compact('rep_cats'));
    }

    public function dmc_setup($id) {
        $cat = ReportCategories::find($id);
        $cats = DmcSetup::where('report_type', '=', $cat->name)->first();
            if ($cats == null) {
                DmcSetup::create([
                    'id' => $cat->id,
                    'report_type' => $cat->name
                ]);
                return redirect()->back()->with('add_report_type','The report category has been added successfully to DMC setup!');
            }

            if (DmcSetup::where('report_type', '=', $cat->name)->exists()) {
                return redirect()->back()->with('exist_cat','The report category is exist in DMC setup!');
            }
    }

    public function remove_cat($id) {
        $cat = ReportCategories::find($id);
        DmcSetup::where('report_type', '=', $cat->name)->delete();
        return redirect()->back()->with('cat_removed','The report category has been removed from DMC setup!');
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
    public function store(ReportCategoriesRequest $request)
    {
        $input = $request->all();
        ReportCategories::create($input);
        return redirect()->back()->with('create_report_cat','The report category has been created successfully !');
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
        $input = ReportCategories::findOrFail($id);
        $input->update($request->all());
        $dmc_setup = DmcSetup::findOrFail($id);
        if($dmc_setup != null) {
            DmcSetup::findOrFail($id)->update(array('report_type' => $request->name));
        }
        return redirect()->back()->with('update_report_cat','The report category has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = ReportCategories::findOrFail($id);
        DmcSetup::where('report_type', '=', $cat->name)->delete();
        $cat->delete();
        return redirect()->back()->with('delete_report_cat','The report category has been deleted successfully !');
    } 
}
