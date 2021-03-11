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
                        <h1><i class="fas fa-award"></i> Staff Roles</h1>
                    </div>
                </div>

                <div class="d-flex text-white text-center">
                    @permission('view.teachers|create.teachers|delete.teachers|edit.teachers')
                    <a href="{{route('admin.teachers.index')}}">
                        <div class="active-item hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                            <i class="fas fa-chalkboard-teacher fa-3x d-block pointer"></i>
                            <span>Manage Staff</span>
                        </div>
                    </a>
                    @endpermission
                    @permission('edit.teachers_account|delete.teachers_account')
                    <a href="{{route('teacher_accounts')}}">
                        <div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
                            <i class="fas fa-user-circle fa-3x d-block pointer"></i>
                            <span>Staff Accounts</span>
                        </div>
                    </a>
                    @endpermission
                    <a href="{{route('teacher_roles')}}">
                        <div class="active-item port-item p-3 d-none d-md-block">
                            <i class="fas fa-award fa-3x d-block pointer"></i>
                            <span>Staff Roles</span>
                        </div>
                    </a>
                </div>

            </div>
        </header>
    </div>


    <div class="mx-4 py-4">

        @if ($message = Session::get('delete_teacher_role'))
            <div class="alert peach-gradient alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        <div class="filterable">

            <table id="table" class="table table-striped table-bordered table-responsive-lg">
                <thead>
                <tr class="filters">
                    <th class="align-middle text-center">Staff Name</th>
                    <th class="align-middle text-center">Staff ID</th>
                    <th class="align-middle text-center">Email</th>
                    <th class="text-center">Roles Assigned</th>
                    <th class="align-middle text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($roles)
                    @foreach($roles as $role)
                        <tr>
                            <td class="text-center align-middle">{{$role->username}}</td>
                            <td class="text-center align-middle">{{$role->user_id}}</td>
                            <td class="text-center align-middle">{{$role->email}}</td>
                            <td class="text-center align-middle">{{$role->name}}</td>
                            <td class="text-center">
                                @permission('delete.teachers_roles')
                                <a data-toggle="modal" data-target="#deleteRoleModal{{$role->id}}">
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


    @foreach($roles as $role)
        <div class="modal fade" id="deleteRoleModal{{$role->id}}">
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
                                {!! Form::model($role, ['method'=>'DELETE', 'action'=>['TeacherController@delete_role',  $role->id]]) !!}
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
