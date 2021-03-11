@extends('layouts.dashboard')

@section('content')

    @include('includes.timetable_nav')


    <!--HEADER-->
    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">

            <a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <div class="row">
                <div class="col-md-12 text-center">
                    <h1><i class="fas fa-clock"></i> Timetables</h1>
                </div>
            </div>

            <div class="d-flex text-white text-center">
                @permission('view.timetable|create.timetable|delete.timetable|edit.timetable|pdf.timetable|
                view.datesheet|create.datesheet|delete.datesheet|edit.datesheet|pdf.datesheet')
                <a href="{{route('admin.timetable.index')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                        <i class="fas fa-clock fa-3x d-block pointer"></i>
                        <span>Class Wise Timetable</span>
                    </div>
                </a>
                <a href="{{route('day_wise_timetable')}}">
                    <div class="active-item port-item p-3 d-none d-md-block">
                        <i class="fas fa-clock fa-3x d-block pointer"></i>
                        <span>Day Wise Timetable</span>
                    </div>
                </a>
                <a href="{{route('admin.timetable.datesheet.index')}}">
                    <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                        <i class="fas fa-calendar-alt fa-3x d-block pointer"></i>
                        <span>Datesheet</span>
                    </div>
                </a>
                @endpermission
            </div>

        </div>
    </header>
    </div>


    <div class="mx-4 py-4">


        @if ($message = Session::get('update_timetable'))
            <div class="alert dusty-grass-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('delete_timetables'))
            <div class="alert peach-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

            <div class="col-md-12">
            @permission('create.timetable')
            <div class="row">
                <a href="{{route('create2')}}" class="btn purple-gradient float-left mb-3">
                    <i class="fas fa-plus-square"></i> Add New Timetable
                </a>
            </div>
            @endpermission
            </div>

            <table id="table" class="export_table text-center table table-bordered table-hover table-sortable table-responsive-lg mt-3">
                <thead>
                <tr>
                    <th class="text-center">Period</th>
                    <th class="text-center">Timing</th>
                    <th class="text-center">Days</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($timetable as $tb)
                    <tr>
                        <td class="align-middle">{{$tb->period}}</td>
                        <td class="align-middle">{{$tb->period_timing}}</td>
                        <td class="align-middle">{{$tb->day}}</td>
                        <td class="align-middle">
                            <a data-toggle="modal" data-target="#editTimetableModal{{$tb->id}}">
                                <button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
                            </a>
                            <a target="_blank" href="{{route('print_day_wise_timetable', $tb->id)}}">
                                <button type="button" title="View" class="btn dusty-grass-gradient">
                                    <span class="fas fa-print"></span>
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#deleteModal{{$tb->id}}">
                                <button type="button" title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            

    </div>

    @foreach($timetable as $tb)
        <div class="modal fade" id="deleteModal{{$tb->id}}">
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
                                {!! Form::model($tb, ['method'=>'DELETE', 'action'=>['TimetableController@delete2',  $tb->id]]) !!}
                                {!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteTb btn btn-danger mb-3 float-right']) !!}
                                {!! Form::close() !!}
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach


    @foreach($timetable as $tb)
        <!--EDIT CLASS MODAL-->
        <div class="modal fade" id="editTimetableModal{{$tb->id}}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header header-backgroud text-white">
                        <h5 class="modal-title">Edit Timetable</h5>
                        <button class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="container">

                    {!! Form::model($tb, ['method'=>'PATCH', 'action'=>['TimetableController@update2',  $tb->id]]) !!}
                    <!--CLASS DETAIL-->
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('period', 'Period', ['class'=>'control-label']) !!}
                                    {!! Form::text('period', null, ['class'=>'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('period_timing', 'Period Timing', ['class'=>'control-label']) !!}
                                    {!! Form::text('period_timing', null, ['class'=>'form-control']) !!}
                                </div>
                                {!! Form::button(' Save Changes', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    @endforeach


@stop
