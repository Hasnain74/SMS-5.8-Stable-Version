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

					<h1><i class="fas fa-address-card"></i> Add New Student</h1>
				</div>
			</div>

		</div>
	</header>
</div>


	<div class="ml-4 mr-4">

		@include('includes.form_errors')

		{!! Form::open(['method'=>'POST', 'action'=>'StudentsController@store', 'files'=>true, 'class' => 'mb-5']) !!}

		<h3 class="mt-3">Student Personal Detail</h3>
		<div class="row">
			<div class="col-md-6">
				<div class="md-form md-outline">
					<label class="control-label">First Name <span style="color: red">*</span></label>
					{!! Form::text('first_name', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Last Name <span style="color: red">*</span></label>
					{!! Form::text('last_name', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form">
					{{Form::date('DOB', null, ['class' => 'form-control'])}}
					<label class="mt-3">Date Of Birth <span style="color: red">*</span></label>
				</div>
				<div class="md-form">
					{{Form::date('admission_date', null, ['class' => 'form-control'])}}
					<label class="mt-3">Admission Date <span style="color: red">*</span></label>
				</div>
			</div>

			<div class="col-md-6">
				<div class="md-form">
					{!! Form::select('students_class_id', [''=>'Choose Class'] + $classes, null,  ['class'=>'browser-default custom-select', 'id'=>'class_id']) !!}
				</div>
				<div class="md-form">
					{{Form::select('gender',['male' => 'Male', 'female' => 'Female'], null,['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender'])}}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('blood_group', 'Blood Group', ['class'=>'control-label']) !!}
					{!! Form::text('blood_group', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('religion', 'Religion', ['class'=>'control-label']) !!}
					{!! Form::text('religion', null,  ['class'=>'form-control']) !!}
				</div>
				<div class="form-group mt-5">
					{!! Form::label('photo_id', 'Upload Photo : ', ['class'=>'control-label']) !!}
					<span style="color: red"></span>
					{!! Form::file('photo_id', null,  ['class'=>'custom-form-control']) !!}
				</div>
			</div>

			<div class="col-lg-6">
				<h3 class="mt-3">Student Contact</h3>
				<div class="md-form md-outline">
					<label class="control-label">Student Address <span style="color: red">*</span></label>
					{!! Form::textarea('student_address', null, ['class'=>'form-control', 'rows'=>2]) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Phone Number </label>
					{!! Form::text('student_phone_no', null, ['class'=>'form-control']) !!}
				</div>
			</div>

			<div class="col-lg-6">
				<h3 class="mt-3">Student Fee</h3>
				<div class="md-form">
					{{Form::select('fee_setup',['monthly' => 'Monthly', 'instalment' => 'Instalment'], null,
					['class' => 'browser-default custom-select', 'placeholder' => 'Select Fee Method'])}}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('discount_percent', 'Discount Percent', ['class'=>'control-label']) !!}
					{!! Form::number('discount_percent', null, ['class'=>'form-control discount']) !!}
				</div>
				<div class="form-group">
					<input type="text" name="default_fee" id="default_fee" hidden class="default_fee"/>
				</div>
				<div class="md-form md-outline">
					{!! Form::label('total_fee', 'Total Fee', ['class'=>'control-label']) !!}
					{!! Form::text('total_fee', null, ['class'=>'form-control final_fee', 'readonly', 'id'=>'final_fee']) !!}
				</div>
				<div class="md-form md-outline">
					{!! Form::label('transport_fee', 'Transport Fee', ['class'=>'control-label']) !!}
					{!! Form::text('transport_fee', '0', ['class'=>'form-control']) !!}
				</div>
			</div>

			<div class="col-md-6">
				<h3 class="mt-3">Guardian Detail</h3>
				<div class="md-form md-outline">
					<label class="control-label">Guardian Name <span style="color: red">*</span></label>
					{!! Form::text('guardian_name', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					{{Form::select('guardian_gender',['male' => 'Male', 'female' => 'Female'], null,['class' => 'browser-default custom-select', 'placeholder' => 'Select Gender'])}}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Relation <span style="color: red">*</span></label>
					{!! Form::text('guardian_relation', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Guardian Occupation <span style="color: red">*</span></label>
					{!! Form::text('guardian_occupation', null, ['class'=>'form-control']) !!}
				</div>
			</div>
			<div class="col-md-6 mt-5">
				<div class="md-form md-outline">
					<label class="control-label">Phone Number <span style="color: red">*</span></label>
					{!! Form::text('guardian_phone_no', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">NIC number <span style="color: red">*</span></label>
					{!! Form::text('NIC_no', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Guardian Address <span style="color: red">*</span></label>
					{!! Form::textarea('guardian_address', null, ['class'=>'form-control', 'rows'=>2]) !!}
				</div>
			</div>

			<div class="col-md-12">
				<h3 class="mt-3">Student Account</h3>
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
					{!! Form::button(' Save', ['type'=>'submit',
					'class'=>'fas fa-folder-open btn btn-block peach-gradient']) !!}
				</div>
			</div>

		</div>


		{!! Form::close() !!}

	</div>



@stop