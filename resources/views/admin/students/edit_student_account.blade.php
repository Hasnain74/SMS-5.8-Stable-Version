@extends('layouts.dashboard')

@section('content')

    @include('includes.students_nav')

    <!--HEADER-->
    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">
            <a href="{{route('admin.students.index')}}" class="btn aqua-gradient float-right mr-4">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <div class="row">
                <div class="col-md-12 text-center">

                    <h1><i class="fas fa-pencil-alt"></i> Edit Student</h1>
                </div>
            </div>

        </div>
    </header>
    </div>


    <div class="mx-4">

        @include('includes.form_errors')

        {!! Form::model($user, ['method'=>'PATCH', 'action'=>['StudentsController@update_account', $user->id], 'files'=>true, 'class' => 'mb-5']) !!}

        <div class="row">

            <div class="col-md-12">
                <h3 class="mt-3">Student Account</h3>
                <div class="form-group">
                    {!! Form::hidden('photo_id', null,  ['class'=>'custom-form-control']) !!}
                </div>
                <div class="md-form md-outline">
                    <label class="control-label">Email <span style="color: red">*</span></label>
                    {!! Form::email('email', null, ['class'=>'form-control']) !!}
                </div>
                <div class="md-form md-outline">
                    <label class="control-label">Password <span style="color: red">*</span></label>
                    {!! Form::password('password', ['class'=>'form-control']) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient']) !!}
                </div>
            </div>

        </div>


        {!! Form::close() !!}

    </div>


@stop
