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
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-calendar-alt"></i> Staff Attendance</h1>
				</div>
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-3">

		@if ($message = Session::get('create_teacher_attendance'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('update_teacher_attendance'))
				<div class="alert dusty-grass-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('delete_teacher_attendance'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		<div class="filterable">

			<div class="float-right mx-2">
				@permission('delete.teachers_attendance')
				<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
					<option value="">Delete</option>
				</select>
				<a data-toggle="modal" data-target="#deleteModal">
					<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
				</a>
				@endpermission
			</div>


			<div class="mb-2">
				@permission('create.teachers_attendance')
				<a href="{{route('admin.teachers.attendance.take_attendance')}}">
				<button type="button" class="btn purple-gradient"><span class="fas fa-plus-square"></span> Take Attendance</button>
				</a>
				@endpermission
			</div>


			<div class="my-3">
				@permission('view.teachers_attendance')
				<select name="teacher_id_teachers_attendance" class="browser-default custom-select bg-dark text-white" >
					<option value="">Select Staff ID</option>
					@foreach ($teacher_id as $key => $value)
						<option value="{{ $value }}">{{ $value }}</option>
					@endforeach
				</select>
				@endpermission
			</div>


			<table id="studentsData_teachers_attendance" class="export_table table table-striped table-bordered mt-5 table-responsive-lg ">
				<thead>
				<tr class="filters">
					{{-- <th class="align-middle text-center"></th> --}}
					<th class="text-center">Staff ID</th>
					<th class="text-center">Staff Name</th>
					<th class="text-center">Date</th>
					<th class="text-center">Attendance</th>
					{{-- <th class="text-center">Actions</th> --}}
				</tr>
				</thead>
				<tbody>
					@if($attendance)
					@foreach($attendance as $atd)
					<tr>
					{{-- <td class="text-center"></td> --}}
					<td class="text-center">{{ $atd->teacher_id }}</td>
					<td class="text-center">{{ $atd->teacher_name }}</td>
					<td class="text-center">{{ $atd->date }}</td>
					<td class="text-center">{{ $atd->attendance }}</td>
					{{-- <td class="text-center">Actions</td> --}}
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			
		</div>
	</div>

@permission('edit.teachers_attendance')
@foreach($attendance as $atd)
	<!--EDIT CLASS MODAL-->
	<div class="modal fade" id="editAtdModal{{$atd->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Edit Attendance</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="container">

				{!! Form::model($atd, ['method'=>'PATCH', 'action'=>['TeachersAttendanceController@update',  $atd->id]]) !!}
					<div class="row mt-2">
						<div class="col-md-12">
							<h3 class="display-4 text-center">Edit Attendance</h3>
							<div class="form-group">
								<label for="attendance">Attendance</label>
								<select class="browser-default custom-select" name="attendance[]">
									@foreach($attendances as $_attendance)
										<option value="{{$_attendance}}" {{$_attendance==$atd->attendance?'selected':''}}>{{$_attendance}}</option>
									@endforeach
								</select>
							</div>
							{!! Form::button(' Save Changes', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}
						</div>
					</div>
					{!! Form::close() !!}

				</div>

			</div>
		</div>
	</div>
@endforeach
@endpermission


@permission('delete.teachers_attendance')
@foreach($attendance as $atd)
	<div class="modal fade" id="deleteAtdModal{{$atd->id}}">
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
							{!! Form::model($atd, ['method'=>'DELETE', 'action'=>['TeachersAttendanceController@destroy',  $atd->id]]) !!}
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

@foreach($attendance as $atd)
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
							{!! Form::model($atd, ['method'=>'DELETE', 'action'=>['TeachersAttendanceController@deleteAttendance',  $atd->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteAtd btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach

@stop
