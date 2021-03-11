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

			<a href="{{route('attendance_register')}}" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-list-alt"></i> Take Attendance</h1>
				</div>
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-3">

		@include('includes.form_errors')

		<div class="py-2">

			{!! Form::open(['method'=>'POST', 'action'=>'TeachersAttendanceController@store', 'class' => 'mb-5']) !!}

			<div class="form-group">
				<label for="date">Select Date <span style="color: red">*</span></label>
				<input value="{{ Carbon::now()->toDateString() }}" name="date" type="date" class="form-control bg-dark text-white">
			</div>

			<table id="studentsData" class="table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="text-center">Staff ID</th>
						<th class="text-center">Staff Name</th>
						<th class="text-center">Attendance</th>
					</tr>
				</thead>
				@if($users)
					@foreach($users as $user)
				<tbody>
					<tr>
						<td class="text-center"><input type="hidden" value="{{$user->teacher_id}}" name="teacher_id[]">{{$user->teacher_id}}</td>
						<td class="text-center"><input value="{{$user->first_name}} {{$user->last_name}}" type="hidden" name="name[]">{{$user->first_name}} {{$user->last_name}}</td>
						<td>
							<!-- SELECT -->
							<div class="form-group">
								<select class="browser-default custom-select" name="attendance[]">
									<option>Present</option>
									<option>Absent</option>
									<option>Leave</option>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
					@endforeach
					@endif
			</table>

			{!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn peach-gradient float-right mb-4']) !!}
			{!! Form::close() !!}
		</div>

	</div>

@stop
