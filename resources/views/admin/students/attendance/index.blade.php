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

			<a href="{{route('student_attendance_register')}}" class="btn aqua-gradient float-right mr-5 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">

					<h1><i class="fas fa-check-square"></i> Take Attendance</h1>
				</div>
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-4">

		<div class="col-sm-12">

			<div class="form-group">
				<label for="date">Select Date <span style="color: red">*</span></label>
				<input value="{{ Carbon::now()->toDateString() }}" name="date" type="date" class="form-control bg-dark text-white">
			</div>

		<select name="class_id_create_attendance" class="browser-default custom-select bg-dark text-white" >
			<option value="">Select Class</option>
			@foreach ($classes as $key => $value)
				<option value="{{ $key }}">{{ $value }}</option>
			@endforeach
		</select>

		<select name="attendance_type" class="browser-default custom-select bg-dark text-white mt-3" >
			{{-- <option value="">Select Attendance Type</option> --}}
			@foreach ($attendance_type as $key => $value)
				<option value="{{ $key }}">{{ $value }}</option>
			@endforeach
		</select>

	</div>



		<div class="col-sm-12">

		<table id="create_attendance_table" class="table table-striped table-bordered table-list-search mt-3 table-responsive-lg">
			<thead>
			<tr>
				<th class="text-center">Student ID</th>
				<th class="text-center">Student Name</th>
				<th class="text-center">Attendance</th>
			</tr>
			</thead>
		</table>
		<a id="save-btn" class="fas fa-folder-open btn peach-gradient float-right mb-4 mr-2">
			Save
		</a>
	

		</div>

	</div>



@stop

