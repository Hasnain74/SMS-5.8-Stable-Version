@extends('layouts.dashboard')

@section('content')

<div class="header-backgroud">
		<nav class="navbar navbar-expand-md navbar-light">
			<div class="ml-2">
				@include('includes.school_profile')
			</div>
		</nav>

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

	{!! Form::model($student, ['method'=>'PATCH', 'action'=>['StudentsController@update', $student->id], 
	'files'=>true, 'class' => 'mb-5']) !!}

	<h3 class="mt-3">Student Personal Detail</h3>
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
			{{-- <div class="md-form md-outline">
				{!! Form::label('student_id', 'Student ID', ['class'=>'control-label']) !!}
				{!! Form::text('student_id', null, ['class'=>'form-control']) !!}
			</div> --}}
			<div class="md-form">
				{{Form::date('DOB', old('DOB', $student->DOB), ['class' => 'form-control'])}}
				{!! Form::label('DOB', 'Date Of Birth', ['class'=>'mt-3']) !!}
			</div>
			<div class="md-form">
				{{Form::date('admission_date', old('admission_date', $student->admission_date), ['class' => 'form-control'])}}
				{!! Form::label('admission_date', 'Admission Date', ['class'=>'mt-3']) !!}
			</div>
		</div>

		<div class="col-md-6">

			<div class="md-form">
				{!! Form::select('students_class_id', [''=>'Choose Class'] + $classes, null,
				['class'=>'browser-default custom-select', 'id'=>'class_id_edit_form']) !!}
			</div>
			<div class="md-form">
				{{Form::select('gender',['male' => 'Male', 'female' => 'Female'], old('gender', $student->gender),
				['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender...'])}}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('blood_group', 'Blood Group', ['class'=>'control-label']) !!}
				{!! Form::text('blood_group', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('religion', 'Religion', ['class'=>'control-label']) !!}
				{!! Form::text('religion', null,  ['class'=>'form-control']) !!}
			</div>
			<div class="md-form">
				{{Form::select('status',['Active' => 'Active', 'Inactive' => 'Inactive'], old('status', $student->status),
				['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender...'])}}
			</div>
			<div class="form-group mt-5">
				{!! Form::label('photo_id', 'Upload Photo : ', ['class'=>'control-label']) !!}
				{!! Form::file('photo_id', null,  ['class'=>'custom-form-control']) !!}
			</div>
		</div>

		<div class="col-lg-6">
			<h3 class="mt-3">Student Contact</h3>
			<div class="md-form  md-outline">
				{!! Form::label('student_address', 'Full Address', ['class'=>'control-label']) !!}
				{!! Form::text('student_address', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('student_phone_no', 'Phone Number', ['class'=>'control-label']) !!}
				{!! Form::text('student_phone_no', null, ['class'=>'form-control']) !!}
			</div>
		</div>

		<div class="col-lg-6">
			<h3 class="mt-3">Student Fee</h3>
			<div class="md-form">
				{{Form::select('fee_setup',['monthly' => 'Monthly', 'instalment' => 'Instalment'], null,
                ['class' => 'browser-default custom-select', 'placeholder' => 'Select Fee Method'])}}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('discount_percent', 'Discount Percent', ['class'=>'control-label']) !!}
				{!! Form::number('discount_percent', null, ['class'=>'form-control discount']) !!}
			</div>
			<div class="form-group">
				<input type="text" name="default_fee" id="default_fee" hidden class="default_fee"/>
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('total_fee', 'Total Fee', ['class'=>'control-label']) !!}
				{!! Form::text('total_fee', null, ['class'=>'form-control final_fee', 'readonly',
				'data-total-fee' => $student->total_fee,'id'=>'final_fee']) !!}
			</div>
			<div class="md-form md-outline">
				{!! Form::label('transport_fee', 'Transport Fee', ['class'=>'control-label']) !!}
				{!! Form::text('transport_fee', null, ['class'=>'form-control']) !!}
			</div>
		</div>

		<div class="col-md-6">
			<h3 class="mt-3">Guardian Detail</h3>
			<div class="md-form  md-outline">
				{!! Form::label('guardian_name', 'Name', ['class'=>'control-label']) !!}
				{!! Form::text('guardian_name', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form">
				{{Form::select('guardian_gender',['male' => 'Male', 'female' => 'Female'], old('guardian_gender', $student->guardian_gender),
				['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender...'])}}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('guardian_relation', 'Relation', ['class'=>'control-label']) !!}
				{!! Form::text('guardian_relation', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('guardian_occupation', 'Occupation', ['class'=>'control-label']) !!}
				{!! Form::text('guardian_occupation', null, ['class'=>'form-control']) !!}
			</div>
		</div>
		<div class="col-md-6 mt-5">
			<div class="md-form  md-outline">
				{!! Form::label('guardian_phone_no', 'Phone Number', ['class'=>'control-label']) !!}
				{!! Form::text('guardian_phone_no', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('NIC_no', 'NIC Number', ['class'=>'control-label']) !!}
				{!! Form::text('NIC_no', null, ['class'=>'form-control']) !!}
			</div>
			<div class="md-form  md-outline">
				{!! Form::label('guardian_address', 'Full Address', ['class'=>'control-label']) !!}
				{!! Form::textarea('guardian_address', null, ['class'=>'form-control', 'rows'=>2]) !!}
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