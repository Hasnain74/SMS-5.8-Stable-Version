@extends('layouts.dashboard')

@section('content')

@include('includes.salary_list_nav')

        <!--HEADER-->
        <header id="main-header" class="py-2 text-white">
            <div class="ml-4">

                <a href="/" class="btn aqua-gradient float-right mr-4">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <div class="row">
                    <div class="col-md-12 text-center text-white">
                        <h1><i class="fas fa-calendar-check"></i> Manage Invoice</h1>
                    </div>
                </div>

                <div class="d-flex text-white text-center">
                    <a href="{{route('index')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                            <i class="fas fa-money-bill-alt fa-3x d-block pointer"></i>
                            <span>Teachers Salary</span>
                        </div>
                    </a>
                    <a href="{{route('manage_salary_invoice')}}">
                        <div class="active-item  port-item p-3 d-none d-md-block">
                            <i class="fas fa-calendar-check fa-3x d-block pointer"></i>
                            <span>Manage Invoice</span>
                        </div>
                    </a>
                </div>

            </div>
        </header>
    </div>


    <div class="mx-4 py-4">

        @if ($message = Session::get('create_teacher_salary'))
            <div class="alert dusty-grass-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('update_teacher_salary'))
            <div class="alert dusty-grass-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('delete_teacher_salary'))
            <div class="alert peach-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('delete_teacher_invoices'))
            <div class="alert peach-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @include('includes.form_errors')

        <div class="filterable">

            <div>
                <div class="mb-2">
                    @permission('create.teachers_salary')
                    <a data-toggle="modal" data-target="#defineAmount">
                        <button type="button" class="btn purple-gradient"><span class="fas fa-plus-square"></span> Make Setup</button>
                    </a>
                    @endpermission
                </div>
            </div>

            <table id="table_teachers_salary" class="table table-striped table-bordered table-responsive-lg">
                <thead>
                <tr class="filters">
                    <th class="text-center">Amount Per Absent Day</th>
                    <th class="align-middle text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($setup)
                    @foreach($setup as $entry)
                        <tr>
                            <td class="text-center align-middle">{{$entry->per_day_amount}}</td>
                            <td class="text-center align-middle">
                                @permission('delete.teachers_salary')
                                <a data-toggle="modal" data-target="#delete{{$entry->id}}">
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

    @foreach($setup as $entry)
        <div class="modal fade" id="delete{{$entry->id}}">
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
                                {!! Form::model($entry, ['method'=>'DELETE', 'action'=>['TeachersSalaryController@delete_setup',  $entry->id]]) !!}
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


    <!--CREATE INVOICE VIEW MODAL-->
    <div class="modal fade" id="defineAmount">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Create New Salary Invoice</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="col-md-12 py-2 ">

                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::open(['method'=>'POST', 'action'=>'TeachersSalaryController@store_invoice_setup']) !!}
                            <div class="md-form md-outline">
                                {!! Form::label('per_day_amount', 'Define Amount', ['class'=>'control-label']) !!}
                                {!! Form::text('per_day_amount', null,  ['class'=>'form-control']) !!}
                            </div>
                            {!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient mb-2']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>




@stop
