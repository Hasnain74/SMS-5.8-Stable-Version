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

				<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
					<i class="fas fa-arrow-left i-resp"></i> Back
				</a>

				<div class="row">
					<div class="col-md-12 text-center">
						<h1><i class="fas fa-address-book"></i> Attendance Register</h1>
					</div>
				</div>

			</div>
		</header>
	</div>

	<div class="mx-4 py-4">

		@if ($message = Session::get('delete_student_attendance'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('update_student_attendance'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif


		{!! Form::open(['method'=>'GET', 'action'=>'AttendanceController@print_attendance_report', 'target'=>'blank']) !!}

		<div class="d-flex">
			<div class="col-md-6">
				<div class="md-form">
					{{Form::date('date', null, ['class' => 'form-control'])}}
				</div>
			</div>

			<div class="col-md-6">
				{!! Form::button(' Print Daily Report', ['type'=>'submit',
                'class'=>'fas fa-save btn btn-block dusty-grass-gradient text-dark mt-4']) !!}
			</div>
		</div>

		{!! Form::close() !!}

		<div class="filterable">

			<div class="form-group float-right mx-2">
				<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
					<option value="">Delete</option>
				</select>
				<a data-toggle="modal" data-target="#deleteModal">
					<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
				</a>
			</div>

			@permission('create.attendance')
			<div class="mb-2">
				<a href="{{route('admin.students.attendance.index')}}">
					<button type="button" class="btn purple-gradient px-4"><span class="fas fa-plus-square"></span> Add New Attendance</button>
				</a>
			</div>
			@endpermission

			@permission('view.attendance')
			<div class="my-3">
				<select name="class_id_attendance_register" class="browser-default custom-select bg-dark text-white" >
					<option value="">Select Class</option>
					@foreach ($classes as $key => $value)
						<option value="{{ $key }}">{{ $value }}</option>
					@endforeach
				</select>
			</div>

			<div class="my-3">
				<select name="student_id_attendance_register" class="browser-default custom-select bg-dark text-white" >
					<option value="">Select Student</option>
					@foreach ($student_id as $key => $value)
						<option value="{{ $value }}">{{ $value }}</option>
					@endforeach
				</select>
			</div>
			@endpermission

			<div class="py-2">
				{!! $datatable->table() !!}
			</div>
		</div>

	</div>

	@permission('delete.attendance')
		<div class="modal fade" id="deleteModal">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header bg-danger text-white">
						<h5 class="modal-title">Delete Confirmation</h5>
						<button class="close" data-dismiss="modal">
							<span class="text-white">&times;</span>
						</button>
					</div>
					<div class="container">
						<div class="modal-body">
							<div class="col-md-12">
								<p class="text-center">Do you really want to delete ?</p>
								<form method="DELETE" action="/students/attendance/destroy_all">
									@csrf
									<button type="button" class="btn btn-danger mb-3 float-right deleteAttendance"> Delete</button>
								</form>
								<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	@endpermission


	@permission('delete.attendance')
	@foreach($attendances as $atd)
		<div class="modal fade" id="deleteAttendanceModal{{$atd->id}}">
			<div class="modal-dialog modal-md">
				<div class="modal-content">

					<div class="modal-header bg-danger text-white">
						<h5 class="modal-title">Delete Confirmation</h5>
						<button class="close" data-dismiss="modal">
							<span class="text-white">&times;</span>
						</button>
					</div>
					<div class="container">
						<div class="modal-body">
							<div class="col-md-12">
								<p class="text-center">Do you really want to delete ?</p>
								{!! Form::model($atd, ['method'=>'DELETE', 'action'=>['AttendanceController@destroy',  $atd->id]]) !!}
								{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'btn btn-danger mb-3 float-right']) !!}
								{!! Form::close() !!}
								<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	@endforeach
	@endpermission
@stop

@section('script')
	{!! $datatable->scripts() !!}
	<script>
		$(function ()
		{
		    //Pass here the fields that you want to filter with and the related selectors
            DataTableFilter.init("{{ route('student_attendance_register') }}" ,{
				student_id: $('select[name="student_id_attendance_register"]'),
				class_id: $('select[name="class_id_attendance_register"]'),
            });
        })
	</script>
@stop