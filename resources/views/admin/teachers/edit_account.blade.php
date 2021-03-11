@extends('layouts.dashboard')

@section('content')


    @include('includes.teacher_nav')

    <header id="main-header" class="py-2 text-white">
        <div class="ml-4">

            <a href="{{route('teacher_accounts')}}" class="btn aqua-gradient float-right mr-4">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <div class="row">
                <div class="col-md-12 text-center">
                    <h1><i class="fas fa-pencil-alt"></i> Edit Staff Account</h1>
                </div>
            </div>

        </div>
    </header>
    </div>


    <div class="mx-4 my-4">

        @include('includes.form_errors')

        {!! Form::model($user, ['method'=>'PATCH', 'action'=>['TeacherController@update_account', $user->id], 'files'=>true, 'class' => 'mb-5']) !!}

        <h3 class="mt-3">Staff Role</h3>
        <div class="form-group">
            <div class="form-group">
                <select name="roles" class="browser-default custom-select">
                    <option disabled>Select Roles</option>
                    @foreach($roles as $id => $name)
                        <option value="{{$id}}" {{in_array($id, $userRoles)?'selected':''}}>{{$name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="md-form md-outline">
                    {{Form::select('status',['teacher' => 'Teacher', 'admin' => 'Privileged '], old('status', $user->status),
                    ['class' => 'browser-default custom-select', 'placeholder' => 'Select Status'])}}
                </div>
                <div class="md-form md-outline">
                    <label class="control-label">Email</label>
                    {!! Form::text('email', null, ['class'=>'form-control']) !!}
                </div>
                <div class="md-form md-outline">
                    <label class="control-label">Password</label>
                    {!! Form::password('password', ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        {!! Form::button(' Edit Account', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}

        {!! Form::close() !!}

    </div>

@stop
