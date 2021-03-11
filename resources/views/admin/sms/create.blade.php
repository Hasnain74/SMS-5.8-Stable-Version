@extends('layouts.dashboard')

@section('content')


	<div class="header-backgroud">
		<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
			<div class="ml-2">
				@include('includes.school_profile')

			</div>
		</nav>

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="{{route('admin.sms.index')}}" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">

					<h1><i class="fas fa-share-square   "></i> Send Message</h1>
				</div>
			</div>

		</div>
	</header>
	</div>

	<div class="mx-4 py-4">

		@include('includes.form_errors')

		<div class="col-md-12">

			{!! Form::open(['method'=>'POST', 'action'=>'SmsController@store']) !!}

				<div class="form-group">
					{!! Form::select('class', [''=>'Choose Class'] + $classes, null,
					 ['class'=>'browser-default custom-select', 'id'=>'class_id_sms_create']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('student_id', 'Student ID', ['class'=>'control-label', 'name'=>'student_id']) !!}
					{!! Form::select('student_id', ['all_students'=>'All Students'], null,
					['class'=>'browser-default custom-select', 'id'=>'student_id_sms_create']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('student_name', 'Student Name', ['class'=>'control-label', 'name'=>'student_name']) !!}
					{!! Form::text('student_name', null, ['class'=>'browser-default custom-select', 'id'=>'student_name_sms_create']) !!}
				</div>
				<div class="form-group">
					{!! Form::label('guardian_phone_no', 'Guardian Phone Number', ['class'=>'control-label', 'name'=>'phone_no']) !!}
					{!! Form::text('guardian_phone_no', null,['class'=>'browser-default custom-select', 'id'=>'phone_no_sms_create']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Your Message <span style="color: red">*</span></label>
					{!! Form::textarea('message', null, ['class'=>'form-control', 'name'=>'message', 'rows'=>3]) !!}
				</div>

				<div class="form-group">
					{!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient']) !!}
				</div>

			{!! Form::close() !!}

		</div>
	</div>



@stop
