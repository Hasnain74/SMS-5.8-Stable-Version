@extends('layouts.dashboard')


@section('content')


    @include('includes.students_nav')

        <!--HEADER-->
        <header id="main-header" class="py-2 text-white">
            <div class="ml-4">

                <a href="/" class="btn aqua-gradient float-right mr-4">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1><i class="fas fa-user-circle"></i> Student Accounts</h1>
                    </div>
                </div>

                <div class="d-flex text-white text-center">
                    <a href="{{route('admin.students.index')}}">
                        <div class="active-item hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                            <i class="fas fa-user-graduate fa-3x d-block pointer"></i>
                            <span>Manage Students</span>
                        </div>
                    </a>
                    <a href="{{route('student_accounts')}}">
                        <div class="active-item port-item p-3 d-none d-md-block">
                            <i class="fas fa-user-circle fa-3x d-block pointer"></i>
                            <span>Student Accounts</span>
                        </div>
                    </a>
                    <a href="{{route('student_promotions')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                            <i class="fas fa-arrow-circle-right fa-3x d-block pointer"></i>
                            <span>Students Promotion</span>
                        </div>
                    </a>
                </div>

            </div>
        </header>
    </div>


    <div class="mx-4 py-4">

        @if ($message = Session::get('delete_student_account'))
            <div class="alert peach-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

            @if ($message = Session::get('update_student_account'))
                <div class="alert dusty-grass-gradient alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            <table id="table" class="export_table table table-striped table-bordered">
                <thead>
                    <tr class="filters">
                        <th class="align-middle text-center">Photo</th>
                        <th class="align-middle text-center">Student Name</th>
                        <th class="align-middle text-center">Student ID</th>
                        <th class="align-middle text-center">Class</th>
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{ dd($accounts) }} --}}
                @if($accounts)
                    @foreach($accounts as $account)
                        <tr>
                            <td class="text-center align-middle">
                                <img style="border-radius: 50%;" height="100" src="{{$account->photo ? $account->photo->file :  asset('img/avatar.png')}}" alt="">
                            </td>
                            <td class="text-center align-middle">{{$account->username}}</td>
                            <td class="text-center align-middle">{{$account->user_id}}</td>
                            <td class="text-center align-middle">{{$account->students_class_name}}</td>
                            <td class="text-center align-middle">{{$account->email}}</td>
                            <td class="text-center align-middle">
                                <a href="{{route('edit_student_account', $account->id)}}">
                                    <button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
                                </a>
                                <a data-toggle="modal" data-target="#deleteRoleModal{{$account->id}}">
                                    <button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            

        </div>


    @foreach($accounts as $account)
        <div class="modal fade" id="deleteRoleModal{{$account->id}}">
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
                                {!! Form::model($account, ['method'=>'DELETE', 'action'=>['TeacherController@delete_account',  $account->id]]) !!}
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
