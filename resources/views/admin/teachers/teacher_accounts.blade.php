@extends('layouts.dashboard')


@section('content')


    @include('includes.teacher_nav')

        <!--HEADER-->
        <header id="main-header" class="py-2 text-white">
            <div class="ml-4">

                <a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <h1><i class="fas fa-user-circle"></i> Staff Accounts</h1>
                    </div>
                </div>

                <div class="d-flex text-white text-center">
                    @permission('view.teachers|create.teachers|delete.teachers|edit.teachers')
                    <a href="{{route('admin.teachers.index')}}">
                        <div class="active-item hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300 ">
                            <i class="fas fa-chalkboard-teacher fa-3x d-block pointer"></i>
                            <span>Manage Staff</span>
                        </div>
                    </a>
                    @endpermission
                    <a href="{{route('teacher_accounts')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block">
                            <i class="fas fa-user-circle fa-3x d-block pointer"></i>
                            <span>Staff Accounts</span>
                        </div>
                    </a>
                    @permission('delete.teachers_roles')
                    <a href="{{route('teacher_roles')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                            <i class="fas fa-award fa-3x d-block pointer"></i>
                            <span>Staff Roles</span>
                        </div>
                    </a>
                    @endpermission
                </div>

            </div>
        </header>
    </div>


    <div class="mx-4 py-4">

        @if ($message = Session::get('delete_teacher_account'))
            <div class="alert peach-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('update_teacher_account'))
            <div class="alert dusty-grass-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="filterable">

            <table id="table" class="export_table table table-striped table-bordered table-responsive-lg">
                <thead>
                <tr class="filters">
                    <th class="align-middle text-center">Photo</th>
                    <th class="align-middle text-center">Staff Name</th>
                    <th class="align-middle text-center">Staff ID</th>
                    <th class="text-center">Email</th>
                    <th class="align-middle text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($accounts)
                    @foreach($accounts as $account)
                        @if($account->status == 'teacher' || $account->status == 'admin')
                        <tr>
                            <td class="text-center align-middle">
                                <img style="border-radius: 50%;" height="100" src="{{$account->photo ? $account->photo->file :  asset('img/avatar.png')}}" alt="">
                            </td>
                            <td class="text-center align-middle">{{$account->username}}</td>
                            <td class="text-center align-middle">{{$account->user_id}}</td>
                            <td class="text-center align-middle">{{$account->email}}</td>
                            <td class="text-center align-middle">
                                @permission('edit.teachers_account')
                                <a href="{{route('edit_account', $account->id)}}">
                                    <button title="Edit" class="btn btn-primary mx-2"><span class="fas fa-pencil-alt"></span>
                                    </button>
                                </a>
                                @endpermission
                                @permission('delete.teachers_account')
                                <a data-toggle="modal" data-target="#deleteRoleModal{{$account->id}}">
                                    <button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
                                </a>
                                @endpermission
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
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
                                {!! Form::model($account, ['method'=>'DELETE', 'action'=>['TeacherController@delete_teacher_account',  $account->id]]) !!}
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
