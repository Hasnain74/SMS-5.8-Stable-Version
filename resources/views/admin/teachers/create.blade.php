@extends('layouts.dashboard')


@section('content')


@include('includes.teacher_nav')

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="{{route('admin.teachers.index')}}" class="btn aqua-gradient float-right mr-4">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-plus-square"></i> Add New Staff</h1>
				</div>
			</div>

		</div>
	</header>
</div>


<div class="ml-4 mr-4">

	@include('includes.form_errors')

	{!! Form::open(['method'=>'POST', 'action'=>'TeacherController@store', 'files'=>true, 'class' => 'mb-5']) !!}

	<h3 class="mt-3">Staff Personal Detail</h3>
	<div class="row">
		<div class="col-md-6 ">
			<div class="md-form md-outline">
				<label class="control-label">First Name <span style="color: red">*</span></label>
				{!! Form::text('first_name', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form md-outline">
				<label class="control-label">Last Name <span style="color: red">*</span></label>
				{!! Form::text('last_name', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form">
				{{Form::date('date_of_birth', null, ['class' => 'form-control'])}}
				<label class="mt-3">Date Of Birth <span style="color: red">*</span></label>
			</div>

			<div class="md-form">
				{{Form::date('join_date', null, ['class' => 'form-control'])}}
				<label class="mt-3">Join Date <span style="color: red">*</span></label>
			</div>
            <div class="form-group mt-5">
                {!! Form::label('photo_id', 'Upload Photo : ', ['class'=>'control-label']) !!}
                {!! Form::file('photo_id', null,  ['class'=>'custom-form-control']) !!}
            </div>
		</div>

		<div class="col-md-6">
			<div class="form-group mt-4">
				{{Form::select('gender',['male' => 'Male', 'female' => 'Female'], null,['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender'])}}
			</div>
			<div class="md-form md-outline">
				<label class="control-label">Qualification <span style="color: red">*</span></label>
				{!! Form::text('teacher_qualification', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form md-outline">
				{!! Form::label('teacher_subject', 'Teacher Subject', ['class'=>'control-label']) !!}
				{!! Form::text('teacher_subject', null,  ['class'=>'form-control']) !!}
			</div>
			<div class="md-form md-outline">
				<label class="control-label">NIC Number <span style="color: red">*</span></label>
				{!! Form::text('nic_no', null,  ['class'=>'form-control']) !!}
			</div>
            <div class="md-form md-outline">
				<label class="control-label">Experience Detail <span style="color: red">*</span></label>
                {!! Form::textarea('exp_detail', null,  ['class'=>'form-control', 'rows'=>2]) !!}
            </div>
		</div>

		<div class="col-lg-6">
			<h3 class="mt-3">Staff Contact</h3>
			<div class="md-form md-outline">
				<label class="control-label">Phone Number <span style="color: red">*</span></label>
				{!! Form::text('phone_no', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form md-outline">
				{!! Form::label('emergency_no', 'Emergency Number', ['class'=>'control-label']) !!}
				{!! Form::text('emergency_no', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form md-outline">
				<label class="control-label">Address <span style="color: red">*</span></label>
				{!! Form::textarea('full_address', null, ['class'=>'form-control', 'rows'=>2]) !!}
			</div>
		</div>

		<div class="col-md-6">
			<h3 class="mt-3">Staff Salary</h3>
			<div class="md-form md-outline">
				<label class="control-label">Define Salary <span style="color: red">*</span></label>
				{!! Form::text('salary', null, ['class'=>'form-control']) !!}
			</div>
			<h3 class="mt-3">Roles</h3>
			<div class="form-group">
				<select name="roles" class="browser-default custom-select">
					<option value="" disabled selected>Select Role</option>
					@foreach($roles as $id => $name)
						<option value="{{$id}}">{{$name}}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<h3 class="mt-3">Staff Account</h3>
			<div class="form-group mt-4">
				{{Form::select('status',['teacher' => 'Teacher', 'admin' => 'Privileged'], null,['class' => 'browser-default custom-select'])}}
			</div>
			<div class="md-form md-outline">
				<label class="control-label">Email <span style="color: red">*</span></label>
				{!! Form::email('email', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form md-outline">
				<label class="control-label">Password (Minimum 6 characters) <span style="color: red">*</span></label>
				{!! Form::password('password', ['class'=>'form-control']) !!}
			</div>
		</div>

		<div class="col-md-6p">

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
