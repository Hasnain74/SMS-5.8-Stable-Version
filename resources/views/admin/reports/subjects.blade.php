@extends('layouts.dashboard')

@section('content')


    @include('includes.report_nav')

    <!--HEADER-->
    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">

            <a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <div class="row">
                <div class="col-md-12 text-center">

                    <h1><i class="fas fa-book"></i> Subject Marks</h1>
                </div>
            </div>

            <div class="d-flex text-white text-center">
                @permission('pdf.reports|view.reports|create.reports|delete.reports|edit.reports|print.reports|post.reports')
                <a href="{{route('admin.reports.index')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-list-alt fa-3x d-block pointer"></i>
                        <span>Reports List</span>
                    </div>
                </a>
                @endpermission
                @permission('delete.report_categories|edit.report_categories')
                <a href="{{route('admin.reports.rep_cats.index')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
                        <i class="fas fa-list-alt fa-3x d-block pointer"></i>
                        <span>Report Categories</span>
                    </div>
                </a>
                @endpermission
                @permission('create_subject.reports|delete_subject.reports|edit_subject.reports|
                add_marks.reports')
                <a href="{{route('subject_marks')}}">
                    <div class="active-item port-item p-3 d-none d-md-block">
                        <i class="fas fa-book fa-3x d-block pointer"></i>
                        <span>Subject Marks</span>
                    </div>
                </a>
                @endpermission
            </div>

        </div>
    </header>
    </div>


    <div class="mx-4 py-3">

        <div class="filterable">

            <div>
                <div class="mb-2">
                    @permission('create.reports')
                    <a data-toggle="modal" data-target="#addNewSubject">
                        <button class="btn purple-gradient"><span class="fas fa-plus-square"></span> Add Subject</button>
                    </a>
                    @endpermission
                </div>
            </div>

            <div class="form-group">
                <select name="class_id_subjects" class="browser-default custom-select bg-dark text-white" >
                    <option value="">Select Class</option>
                    @foreach ($classes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <table id="subjects" class="export_table table table-striped table-bordered table-responsive-lg">
                <thead>
                <tr class="filters">
                    <th class="text-center">Subject Name</th>
                    <th class="text-center">Subject Teacher</th>
                    <th class="text-center">Report Type</th>
                    <th class="text-center">Subject Marks</th>
                    <th class="text-center">Class</th>
                    <th class="align-middle text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($subjects)
                    @foreach($subjects as $subject)
                        <tr>
                            <td class="text-center align-middle">{{$subject->subject_name}}</td>
                            <td class="text-center align-middle">{{$subject->subject_teacher}}</td>
                            <td class="text-center align-middle">{{$subject->report_type_name}}</td>
                            <td class="text-center align-middle">{{$subject->subject_marks}}</td>
                            <td class="text-center align-middle">{{$subject->class_name}}</td>
                            <td class="text-center">
                                @permission('edit_subject.reports')
                                <a data-toggle="modal" data-target="#edit{{$subject->id}}">
                                    <button type="button" title="Edit" class="btn btn-primary mx-2"><span class="fas fa-pencil-alt"></span>
                                    </button>
                                </a>
                                @endpermission
                                @permission('add_marks.reports')
                                <a href="{{route('add_marks', $subject->id)}}">
                                    <button type="button" title="Add Marks" class="btn purple-gradient mx-2"><span class="fas fa-clipboard-list"></span>
                                    </button>
                                </a>
                                @endpermission
                                @permission('delete_subject.reports')
                                <a data-toggle="modal" data-target="#deleteSubject{{$subject->id}}">
                                    <button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
                                </a>
                                @endpermission
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
    </div>

    @foreach($subjects as $subject)
        <div class="modal fade" id="deleteSubject{{$subject->id}}">
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
                                {!! Form::model($subject, ['method'=>'DELETE', 'action'=>['ReportController@delete_subject',  $subject->id]]) !!}
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


    <div class="modal fade" id="addNewSubject">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Subject View</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="container">
                {!! Form::open(['method'=>'POST', 'action'=>'ReportController@store_subjects', 'class' => 'mb-3']) !!}
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::select('class_id', $classes, null,
                                ['class'=>'browser-default custom-select mt-2', 'placeholder'=>'Select Class']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('report_type_id', $rep_cat, null,  
                                ['class'=>'browser-default custom-select', 'placeholder'=>'Select Report Type']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Subject Name <span style="color: red">*</span></label>
                                {!! Form::text('subject_name', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Subject Marks <span style="color: red">*</span></label>
                                {!! Form::text('subject_marks', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('subject_teacher', $teachers, null,
                                ['class'=>'browser-default custom-select', 'placeholder'=>'Select Teacher']) !!}
                            </div>
                            {!! Form::button(' Add Subject', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>

    @foreach($subjects as $subject)
    <div class="modal fade" id="edit{{$subject->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Edit Subject</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="container">
                    {!! Form::model($subject, ['method'=>'PATCH', 'action'=>['ReportController@edit_subjects', $subject->id], 'class' => 'mb-3']) !!}
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::select('class_id', $classes, null,  ['class'=>'mt-2 browser-default custom-select'
                                , 'placeholder'=>'Select Class']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::select('report_type_id', $rep_cat, null,  
                                ['class'=>'browser-default custom-select', 'placeholder'=>'Select Report Type']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Subject Name <span style="color: red">*</span></label>
                                {!! Form::text('subject_name', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Subject Marks <span style="color: red">*</span></label>
                                {!! Form::text('subject_marks', null, ['class'=>'form-control']) !!}
                            </div>
                            <select class="form-control mb-4" name="subject_teacher">
                                <option>Select Teacher</option>                           
                                @foreach ($teachers as $teacher)
                                  <option value="{{ $teacher }}" {{ ( $teacher == $subject->subject_teacher) ? 'selected' : '' }}> {{ $teacher }} </option>
                                @endforeach    
                            </select>
                            {!! Form::button(' Edit', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endforeach


@stop





