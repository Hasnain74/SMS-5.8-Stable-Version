@extends('layouts.dashboard')

@section('content')


@include('includes.teacher_nav')

	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="{{route('admin.teachers.index')}}" class="btn aqua-gradient float-right mr-4">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-pencil-alt"></i> Edit Staff</h1>
				</div>
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 my-4">

		@include('includes.form_errors')

		{!! Form::model($user, ['method'=>'PATCH', 'action'=>['TeacherController@update', $user->id], 'files'=>true, 'class' => 'mb-5']) !!}

		<h3 class="mt-3">Staff Personal Detail</h3>
		<div class="row">
			<div class="col-md-6 ">
				<div class="md-form md-outline">
					{!! Form::label('first_name', 'First Name', ['class'=>'control-label']) !!}
					{!! Form::text('first_name', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('last_name', 'Last name', ['class'=>'control-label']) !!}
					{!! Form::text('last_name', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form">
					{{Form::date('date_of_birth', old('date_of_birth', $user->date_of_birth), ['class' => 'form-control'])}}
					{!! Form::label('date_of_birth', 'Date Of Birth', ['class'=>'mt-3']) !!}
				</div>
				<div class="md-form">
					{{Form::date('join_date', old('join_date', $user->join_date), ['class' => 'form-control'])}}
					{!! Form::label('join_date', 'Join Date', ['class'=>'mt-3']) !!}
				</div>
				<div class="form-group mt-5">
					{!! Form::label('photo_id', 'Upload Photo : ', ['class'=>'control-label']) !!}
					{!! Form::file('photo_id', null,  ['class'=>'custom-form-control']) !!}
				</div>
			</div>

			<div class="col-md-6">
				<div class="form-group mt-4">
					{{Form::select('gender',['male' => 'Male', 'female' => 'Female'], old('gender', $user->gender),
				['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender'])}}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('teacher_qualification', 'Qualification', ['class'=>'control-label']) !!}
					{!! Form::text('teacher_qualification', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('teacher_subject', 'Subject', ['class'=>'control-label']) !!}
					{!! Form::text('teacher_subject', null,  ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('nic_no', 'NIC number', ['class'=>'control-label']) !!}
					{!! Form::text('nic_no', null,  ['class'=>'form-control']) !!}
				</div>
				<div class="form-group">
					{!! Form::select('students_class_id', $classes, null, ['class'=>'browser-default custom-select', 'id'=>'class_id']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('exp_detail', 'Experience Detail', ['class'=>'control-label']) !!}
					{!! Form::textarea('exp_detail', null,  ['class'=>'form-control', 'rows'=>2]) !!}
				</div>
			</div>

			<div class="col-lg-6">
				<h3 class="mt-3">Staff Contact</h3>
				<div class="md-form md-outline">
					{!! Form::label('phone_no', 'Phone Number', ['class'=>'control-label']) !!}
					{!! Form::text('phone_no', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('emergency_no', 'Emergency Number', ['class'=>'control-label']) !!}
					{!! Form::text('emergency_no', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('full_address', 'Full Address', ['class'=>'control-label']) !!}
					{!! Form::textarea('full_address', null, ['class'=>'form-control', 'rows'=>2]) !!}
				</div>
			</div>

			<div class="col-md-6">
				<h3 class="mt-3">Staff Salary</h3>
				<div class="md-form md-outline">
					{!! Form::label('salary', 'Define Salary', ['class'=>'control-label']) !!}
					{!! Form::text('salary', null, ['class'=>'form-control']) !!}
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
