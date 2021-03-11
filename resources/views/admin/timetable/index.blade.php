@extends('layouts.dashboard')

@section('content')

@include('includes.timetable_nav')


	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-clock"></i> Timetables</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				@permission('view.timetable|create.timetable|delete.timetable|edit.timetable|pdf.timetable|
				view.datesheet|create.datesheet|delete.datesheet|edit.datesheet|pdf.datesheet')
				<a href="{{route('admin.timetable.index')}}">
					<div class="active-item port-item p-3 d-none d-md-block">
						<i class="fas fa-clock fa-3x d-block pointer"></i>
						<span>Class Wise Timetable</span>
					</div>
				</a>
				<a href="{{route('day_wise_timetable')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-clock fa-3x d-block pointer"></i>
						<span>Day Wise Timetable</span>
					</div>
				</a>
				<a href="{{route('admin.timetable.datesheet.index')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-calendar-alt fa-3x d-block pointer"></i>
						<span>Datesheet</span>
					</div>
				</a>
				@endpermission
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-4">


		@if ($message = Session::get('update_timetable'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('delete_timetables'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		
		@permission('view.timetable')
		<div class="form-group">
			<select name="class_id_timetable" class="browser-default custom-select bg-dark text-white" >
				<option value="">Select Class</option>
				@foreach ($classes as $key => $value)
					<option value="{{ $key }}">{{ $value }}</option>
				@endforeach
			</select>
		</div>
		@endpermission

		@permission('create.timetable')
			<div class="form-group">
				<a href="{{route('admin.timetable.create')}}" class="btn purple-gradient float-left">
					<i class="fas fa-plus-square"></i> Add New Timetable
				</a>
			</div>
		@endpermission
	


		<div class="form-group float-right mx-2">
			@permission('delete.timetable')
			<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
				<option value="">Delete</option>
			</select>
			<a data-toggle="modal" data-target="#deleteModal">
				<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
			</a>
			@endpermission
		</div>
		</div>

		
		<div class="mx-4 py-4 mt-4">
		<table id="timetable" class="export_table table table-striped table-bordered table-list-search table-responsive-lg">
			<thead>
				<tr>
					{{-- <th style="width: 2%;"><input type="checkbox" id="options"></th> --}}
					<th style="width: 10%;" class="text-center">Period</th>
					<th style="width: 10%;" class="text-center">Monday</th>
					<th style="width: 10%;" class="text-center">Tuesday</th>
					<th style="width: 10%;" class="text-center">Wednesday</th>
					<th style="width: 10%;" class="text-center">Thursday</th>
					<th style="width: 10%;" class="text-center">Friday</th>
					<th style="width: 10%;" class="text-center">Saturday</th>
				</tr>
			</thead>
			<tbody>
				@if($timetable)
				@foreach($timetable as $t)
				<tr>
				<td class="text-center">{{ $t->period }}</td>
				<td class="text-center">{{ $t->monday }}</td>
				<td class="text-center">{{ $t->tuesday }}</td>
				<td class="text-center">{{ $t->wednesday }}</td>
				<td class="text-center">{{ $t->thursday }}</td>
				<td class="text-center">{{ $t->friday }}</td>
				<td class="text-center">{{ $t->saturday }}</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>

		

	</div>

@foreach($timetable as $tb)
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
							{!! Form::model($tb, ['method'=>'DELETE', 'action'=>['TimetableController@deleteTb',  $tb->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteTb btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach

@foreach($timetable as $tb)
	<!--EDIT CLASS MODAL-->
	<div class="modal fade" id="viewTimetableModal{{$tb->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Timetable View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="container">
					@permission('pdf.timetable')
					<a target="_blank" name="gnrt_single" href="{{action('TimetableController@printTimetable', $tb->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print Timetable
					</a>
					{{-- <a target="_blank" name="gnrt_single" href="{{action('TimetableController@printAllTimetable', $tb->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print All Timetables
					</a> --}}
					@endpermission
				</div>

				<div class="container">

					<p class="mt-3"><strong>Class :</strong> {{$tb->class_name}}</p>

					<table class="text-center table table-bordered table-hover table-sortable table-responsive-lg mt-3">
						<thead>
						<tr>
							<th class="text-center">Period</th>
							<th class="text-center">Monday</th>
							<th class="text-center">Tuesday</th>
							<th class="text-center">Wednesday</th>
							<th class="text-center">Thursday</th>
							<th class="text-center">Friday</th>
							<th class="text-center">Saturday</th>

						</tr>
						</thead>
						<tbody>
						<tr>
							<td style="width: 10%;">{{$tb->period}}</td>
							<td style="width: 15%;">{{$tb->monday}}</td>
							<td style="width: 15%;">{{$tb->tuesday}}</td>
							<td style="width: 15%;">{{$tb->wednesday}}</td>
							<td style="width: 15%;">{{$tb->thursday}}</td>
							<td style="width: 15%;">{{$tb->friday}}</td>
							<td style="width: 15%;">{{$tb->saturday}}</td>
						</tr>
						</tbody>
					</table>

				</div>

			</div>
		</div>
	</div>
@endforeach


@permission('edit.timetable')
@foreach($timetable as $tb)
	<!--EDIT CLASS MODAL-->
	<div class="modal fade" id="editTimetableModal{{$tb->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Timetable View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="container">

				{!! Form::model($tb, ['method'=>'PATCH', 'action'=>['TimetableController@update',  $tb->id]]) !!}
				<!--CLASS DETAIL-->
					<div class="row mt-2">
						<div class="col-md-12">
							<h3 class="display-4 text-center">Edit Timetable</h3>
							<div class="form-group">
								{!! Form::label('class_id', 'Class', ['class'=>'control-label']) !!}
								{!! Form::select('class_id', [''=>'Choose Class'] + $classes, null,  ['class'=>'form-control bg-dark text-white']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('period', 'Period', ['class'=>'control-label']) !!}
								{!! Form::text('period', null, ['class'=>'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('monday', 'Monday', ['class'=>'control-label']) !!}
								{!! Form::text('monday', null, ['class'=>'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('tuesday', 'Tuesday', ['class'=>'control-label']) !!}
								{!! Form::text('tuesday', null, ['class'=>'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('wednesday', 'Wednesday', ['class'=>'control-label']) !!}
								{!! Form::text('wednesday', null, ['class'=>'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('thursday', 'Thursday', ['class'=>'control-label']) !!}
								{!! Form::text('thursday', null, ['class'=>'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('friday', 'Friday', ['class'=>'control-label']) !!}
								{!! Form::text('friday', null, ['class'=>'form-control']) !!}
							</div>
							<div class="form-group">
								{!! Form::label('saturday', 'Saturday', ['class'=>'control-label']) !!}
								{!! Form::text('saturday', null, ['class'=>'form-control']) !!}
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


@stop
