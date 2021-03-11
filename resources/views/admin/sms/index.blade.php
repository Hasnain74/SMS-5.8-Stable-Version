@extends('layouts.dashboard')

@section('content')


	<div class="header-backgroud">
		<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
			<div class="ml-2">
				@include('includes.school_profile')

			</div>
		</nav>

	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-envelope-open"></i> Inbox</h1>
				</div>
			</div>

		</div>
	</header>
	</div>

	<div class="mx-4 py-4">

		@if ($message = Session::get('delete_sms'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('delete_smses'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		@if ($message = Session::get('send_sms'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		<div class="my-3">
			@permission('view.sms')
			<select name="class_id_sms_index" class="browser-default custom-select bg-dark text-white" >
				<option value="">Select Class</option>
				@foreach ($classes as $key => $value)
					<option value="{{ $key }}">{{ $value }}</option>
				@endforeach
			</select>
			@endpermission
		</div>

		<div class="filterable">

				<div class="float-right mx-2">
					@permission('delete.sms')
					<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
						<option value="">Delete</option>
					</select>
					<a data-toggle="modal" data-target="#deleteModal">
						<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
					</a>
					@endpermission
				</div>

				<div class="mb-2">
					@permission('create.sms')
					<a href="{{route('admin.sms.create')}}">
						<button class="btn purple-gradient"><span class="fas fa-share-square"></span> Send New Message</button>
					</a>
					@endpermission
				</div>

			<table id="studentsData_sms_index" class="export_table table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						{{-- <th class="align-middle text-center"><input type="checkbox" id="options"></th> --}}
						<th class="text-center">Student ID</th>
						<th class="text-center">Student Name</th>
						<th class="text-center">Sent to</th>
						<th class="text-center">Message</th>
						<th class="text-center">Sent By</th>
						<th class="text-center">Sent Date</th>
						{{-- <th class="align-middle">Actions</th> --}}
					</tr>
				</thead>
				<tbody>
					@if($inbox)
					@foreach($inbox as $s)
					<tr>
					<td class="text-center">{{ $s->student_id }}</td>
					<td class="text-center">{{ $s->student_name}}</td>
					<td class="text-center">{{ $s->guardian_phone_no}}</td>
					<td class="text-center">{{ $s->message}}</td>
					<td class="text-center">{{ $s->sent_by}}</td>
					<td class="text-center">{{ $s->created_at->DiffForHumans()}}</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		

	</div>



	@foreach($inbox as $sms)
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
								{!! Form::model($sms, ['method'=>'DELETE', 'action'=>['SmsController@deleteSms',  $sms->id]]) !!}
								{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteSms btn btn-danger mb-3 float-right']) !!}
								{!! Form::close() !!}
								<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	@endforeach

	@permission('delete.sms')
	@foreach($inbox as $sms)
		<div class="modal fade" id="deleteSmsModal{{$sms->id}}">
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
								{!! Form::model($sms, ['method'=>'DELETE', 'action'=>['SmsController@destroy',  $sms->id]]) !!}
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
