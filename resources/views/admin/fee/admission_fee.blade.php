@extends('layouts.dashboard')

@section('content')


    <div class="header-backgroud">
        <nav class="navbar navbar-expand-md navbar-light" id="main-nav">
            <div class="ml-2">
                @include('includes.school_profile')
            </div>
        </nav>

        <!--HEADER-->
        <header id="main-header" class="py-2 text-white">
            <div class="ml-4">

                <a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1><i class="fas fa-file-invoice"></i> Fee Invoices List</h1>
                    </div>
                </div>

                <div class="d-flex text-white text-center">
                    <a href="{{route('admin.fee.index')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300   ">
                            <i class="fas fa-file-invoice fa-3x d-block pointer"></i>
                            <span>Monthly Fee</span>
                        </div>
                    </a>

                    <a href="{{route('instalment_fee')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300   ">
                            <i class="fas fa-file-invoice fa-3x d-block pointer"></i>
                            <span>Instalment Setup</span>
                        </div>
                    </a>
                    <a href="{{route('admission_fee')}}">
                        <div class="active-item port-item p-3 d-none d-md-block">
                            <i class="fas fa-file-invoice fa-3x d-block pointer"></i>
                            <span>Admission Fee</span>
                        </div>
                    </a>
                </div>

            </div>
        </header>
    </div>



    <div class="mx-4 py-2">

        <div>
            <div class="mb-2">
                <a data-toggle="modal" data-target="#admission_fee">
                    <button class="btn purple-gradient"><span class="fas fa-plus-square"></span> Create Fee Setup</button>
                </a>
            </div>
        </div>

        <div class="filterable mt-3">

            <table id="studentsData_fee_index" class="export_table table table-striped table-bordered table-responsive-lg">
                <thead>
                <tr class="filters">
                    <th class="text-center">Invoice No</th>
                    <th class="text-alignment text-center">Student ID</th>
                    <th class="text-alignment text-center">Student Name</th>
                    <th class="text-alignment text-center">Class</th>
                    <th class="text-alignment text-center">Admission Fee</th>
                    <th class="text-alignment text-center">Paid Date</th>
                    <th width="15%" class="align-middle text-center">Actions</th>
                </tr>
                </thead>

                <tbody>
                @if($admission_fee)
                    @foreach($admission_fee as $fee)
                        <tr>
                            <td class="text-center align-middle">{{$fee->invoice_no}}</td>
                            <td class="text-center align-middle">{{$fee->student_id}}</td>
                            <td class="text-center align-middle">{{$fee->student_name}}</td>
                            <td class="text-center align-middle">{{$fee->class_name}}</td>
                            <td class="text-center align-middle">
                                Admission Fee : {{$fee->admission_fee}} <br>
                                Paid Amount : {{$fee->paid_amount}} <br>
                                Remaining : {{$fee->arrears}}
                            </td>
                            <td class="text-center align-middle">{{$fee->paid_date}}</td>
                            <td class="text-center d-flex">
                                <a data-toggle="modal" data-target="#editFee{{$fee->id}}">
                                    <button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
                                </a>
                                <a data-toggle="modal" data-target="#deleteAdmissionFee">
                                    <button title="View" class="btn btn-danger"><span class="fas fa-trash"></span></button>
                                </a>
                                <a target="_blank" href="{{route('print_admission_fee', $fee->id)}}">
                                    <button type="button" title="View" class="btn dusty-grass-gradient">
                                        <span class="fas fa-print"></span>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>

            </table>

            

        </div>
    </div>


    <div class="modal fade" id="admission_fee">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Add Detail</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="container">
                {!! Form::open(['method'=>'POST', 'action'=>'FeeController@store_admission_fee', 'class' => 'mb-3']) !!}
                <!--CLASS DETAIL-->
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::select('class_id', ['all_classes'=>'Select Class'] + $classes, null,
                                ['class'=>'browser-default custom-select mt-4', 'id'=>'class_id_admfee_create']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('student_id', 'Student ID', ['class'=>'control-label', 'name'=>'student_id']) !!}
                                {!! Form::select('student_id', ['all_students'=>'All Students'], null,
                                ['class'=>'browser-default custom-select', 'id'=>'student_id_adm_create']) !!}
                            </div>
                            {{-- <div class="md-form">
                                <select name="student_id" class="browser-default custom-select" id="student_id_adm_create">
            
                                    <option value="">Student ID</span></option>
                        
                                    @foreach ($student_ids as $key => $value)
                        
                                        <option value="{{ $value }}">{{ $value }}</option>
                        
                                    @endforeach
                        
                                </select>
                            </div> --}}
                            <div class="form-group">
                                {!! Form::label('student_name', 'Student Name', ['class'=>'control-label', 'name'=>'student_name']) !!}
                                {!! Form::text('student_name', null, ['class'=>'form-control', 'id'=>'student_name_adm_create']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Admission Fee <span style="color: red">*</span></label>
                                {!! Form::text('admission_fee', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Paid Amount <span style="color: red">*</span></label>
                                {!! Form::text('paid_amount', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="md-form md-outline">
                                {!! Form::date('paid_date', null, ['class'=>'form-control']) !!}
                                <label class="control-label mt-2">Paid Date <span style="color: red">*</span></label>
                            </div>
                            {!! Form::button(' Save', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>


    @foreach($admission_fee as $fee)
        <div class="modal fade" id="editFee{{$fee->id}}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header header-backgroud text-white">
                        <h5 class="modal-title">Edit Admission Fee</h5>
                        <button class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="container">
                    {!! Form::model($fee, ['method'=>'POST', 'action'=>['FeeController@update_admission_fee', $fee->id], 'class' => 'mb-3']) !!}
                    <!--CLASS DETAIL-->
                        <div class="row mt-2">
                            <div class="col-md-12">
                                {{-- <div class="form-group">
                                    {!! Form::select('class_id', ['all_classes'=>'Select Class'] + $classes, null,
                                    ['class'=>'browser-default custom-select mt-4', 'id'=>'class_id_adm_edit']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('student_id', 'Student ID', ['class'=>'control-label', 'name'=>'student_id']) !!}
                                    {!! Form::select('student_id', $student_ids, null,
                                    ['class'=>'browser-default custom-select', 'id'=>'student_id_adm_edit']) !!}
                                </div> --}}
                                 {{-- <div class="md-form">
                                    <select id="student_id_adm_edit" class="browser-default custom-select" name="student_id">
                                        <option value="all_students">Student ID</option>
                                        @foreach($student_ids as $student_id)
                                            <option value="{{$student_id}}">{{$student_id}}</option>
                                        @endforeach
                                    </select>
                            </div> --}}
                                {{-- <div class="form-group">
                                    {!! Form::label('student_name', 'Student Name', ['class'=>'control-label', 'name'=>'student_name']) !!}
                                    {!! Form::text('student_name', null, ['class'=>'form-control', 'id'=>'student_name_adm_edit']) !!}
                                </div> --}}
                                <div class="md-form md-outline">
                                    <label class="control-label">Admission Fee <span style="color: red">*</span></label>
                                    {!! Form::text('admission_fee', null, ['class'=>'form-control']) !!}
                                </div>
                                <div class="md-form md-outline">
                                    <label class="control-label">Paid Amount <span style="color: red">*</span></label>
                                    {!! Form::text('paid_amount', null, ['class'=>'form-control']) !!}
                                </div>
                                <div class="md-form md-outline">
                                    {!! Form::date('paid_date', null, ['class'=>'form-control']) !!}
                                    <label class="control-label mt-2">Paid Date <span style="color: red">*</span></label>
                                </div>
                                {!! Form::button(' Update', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    @endforeach


    @foreach($admission_fee as $fee)
        <div class="modal fade" id="deleteAdmissionFee">
            <div class="modal-dialog modal-md">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Delete Confirmation</h5>
                        <button class="close" data-dismiss="modal">
                            <span class="text-white">&times;</span>
                        </button>
                    </div>
                    <div class="container">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <p class="text-center">Do you really want to delete ?</p>
                                {!! Form::model($fee, ['method'=>'POST', 'action'=>['FeeController@delete_admission_fee',  $fee->id]]) !!}
                                {!! Form::button(' Delete', ['type'=>'submit', 'class'=>'btn btn-danger mb-3 float-right']) !!}
                                {!! Form::close() !!}
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

@stop
