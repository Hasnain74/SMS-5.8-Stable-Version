@extends('layouts.dashboard')


@section('content')

	<!--Main Page-->
	<div class="header-backgroud">
	<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
		<div class="ml-2">
			@include('includes.school_profile')

		</div>
	</nav>

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="{{route('admin.fee.index')}}" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-file-invoice"></i> Create Fee Invoice</h1>
				</div>
			</div>

		</div>
	</header>
	</div>

	<div class="mx-4 py-4">
		@if ($message = Session::get('delete_fee'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@include('includes.form_errors')
		{!! Form::open(['method'=>'POST', 'action'=>'FeeController@store']) !!}
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::select('class_id', ['all_classes'=>'All Class'] + $classes, null,
					['class'=>'browser-default custom-select mt-4', 'id'=>'class_id_fee_create']) !!}
				</div>
				<div class="md-form mb-0">
					<select name="fee_setup" class="browser-default custom-select" id="student_feeSetup_fee_create">

						<option value="">Fee Setup *</span></option>
			
						@foreach ($fee_setup as $key => $value)
			
							<option value="{{ $value }}">{{ $value }}</option>
			
						@endforeach
			
					</select>
					{{-- {{Form::select('fee_setup', $fee_setup, null,
					['class' => 'browser-default custom-select', 'placeholder' => 'Select Fee Method'
					, 'id'=>'student_feeSetup_fee_create'])}} --}}
				</div>
				<div class="md-form mb-0">
					{{Form::select('instalment_month', $instalment_months, null,
					['class' => 'browser-default custom-select', 'placeholder' => 'Select Instalment Month'])}}
				</div>
				<div class="form-group">
					{{-- {!! Form::label('student_id', 'Student ID', ['class'=>'control-label mt-2', 'name'=>'student_id']) !!} --}}
					{!! Form::select('student_id', ['all_students'=>'All Students IDs'], null,
					['class'=>'browser-default custom-select mt-4', 'id'=>'student_id_fee_create']) !!}
				</div>
				<div class="form-group">
					{{-- {!! Form::label('student_name', 'Student Name', ['class'=>'control-label', 'name'=>'student_name']) !!} --}}
					{!! Form::select('student_name', ['all_students'=>'All Students Names'], null,
					['class'=>'browser-default custom-select mt-2', 'id'=>'student_name_fee_create']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Prospectus</label>
					{!! Form::number('prospectus', '0', ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Admin & Management Fee</label>
					{!! Form::number('admin_and_management_fee', '0', ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Books</label>
					{!! Form::number('books', '0', ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Security Fee</label>
					{!! Form::number('security_fee', '0', ['class'=>'form-control']) !!}
				</div>
			</div>
				<div class="col-md-6">
					
					<div class="md-form md-outline">
						<label class="control-label">Uniform</label>
						{!! Form::number('uniform', '0', ['class'=>'form-control']) !!}
					</div>
					<div class="md-form md-outline">
						<label class="control-label">Fine Panalties</label>
						{!! Form::number('fine_panalties', '0', ['class'=>'form-control']) !!}
					</div>
					<div class="md-form md-outline">
						<label class="control-label">Printing & Stationary</label>
						{!! Form::number('printing_and_stationary', '0', ['class'=>'form-control']) !!}
					</div>
					<div class="md-form md-outline">
						<label class="control-label">Promotion Fee</label>
						{!! Form::number('promotion_fee', '0', ['class'=>'form-control']) !!}
					</div>
				<div class="md-form md-outline">
					<label class="control-label">Other Fee Type </label>
					{!! Form::text('other_fee_type', null, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Other Fee Amount</label>
					{!! Form::number('other_amount', '0', ['class'=>'form-control']) !!}
				</div>
				<div class="md-form md-outline">
					<label class="control-label">Month  <span style="color: red">*</span></label>
					{!! Form::text('month', $now, ['class'=>'form-control']) !!}
				</div>
				<div class="md-form">
					{{Form::date('issue_date', null, ['class' => 'form-control mt-4'])}}
					<label class="mt-3">Issue Date</label>
				</div>
				<div class="md-form">
					{{Form::date('due_date', null, ['class' => 'form-control'])}}
					<label class="mt-3">Due Date</label>
				</div>
				</div>
			</div>

			{!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient']) !!}


		{!! Form::close() !!}

	</div>


@stop
