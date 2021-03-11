@extends('layouts.dashboard')


@section('content')


@include('includes.teacher_nav')


	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-chalkboard-teacher"></i> Staff List</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				@permission('edit.teachers_account|delete.teachers_account|delete.teachers_roles')
				<a href="{{route('admin.teachers.index')}}">
					<div class="active-item hover-effect port-item p-3 d-none d-md-block">
						<i class="fas fa-chalkboard-teacher fa-3x d-block pointer"></i>
						<span>Manage Staff</span>
					</div>
				</a>
				@endpermission
				@permission('edit.teachers_account|delete.teachers_account')
				<a href="{{route('teacher_accounts')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-user-circle fa-3x d-block pointer"></i>
						<span>Staff Accounts</span>
					</div>
				</a>
				@endpermission
				@permission('delete.teachers_roles')
				<a href="{{route('teacher_roles')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
						<i class="fas fa-award fa-3x d-block pointer"></i>
						<span>Staff Roles</span>
					</div>
				</a>
				@endpermission
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-3">

		@if ($message = Session::get('delete_teacher'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('delete_multiple_teacher'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('create_teacher'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('update_teacher'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@permission('delete.teachers')
				<div class="float-right">
					<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
						<option value="">Delete</option>
					</select>
					<a data-toggle="modal" data-target="#deleteModal">
						<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
					</a>
				</div>
			@endpermission

			@permission('create.teachers')
					<div class="mb-2">
						<a href="{{route('admin.teachers.create')}}">
						<button class="btn purple-gradient"><span class="fas fa-plus-square"></span> Add New Staff</button>
						</a>
					</div>
			@endpermission


			<table id="table" class="table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="align-middle text-center"><input type="checkbox" id="options"></th>
						<th class="align-middle text-center">Photo</th>
						<th class="align-middle text-center">Staff ID</th>
						<th class="text-center">Name</th>
						<th class="text-center">Subject</th>
						<th class="text-center">Phone Number</th>
						<th class="align-middle text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
				@if($users)
					@foreach($users as $user)
					<tr>
						<td class="text-center"><input class="checkBoxes align-middle" type="checkbox" name="checkBoxArray[]" value="{{$user->id}}"></td>
						<td class="text-center align-middle">
							<a href="{{$user->photo ? $user->photo->file :  asset('img/avatar.png')}}" data-toggle="lightbox">
							<img style="border-radius: 50%;" height="100" src="{{$user->photo ? $user->photo->file :  asset('img/avatar.png')}}" alt="">
							</a>
						</td>
						<td class="text-center align-middle">{{$user->teacher_id}}</td>
						<td class="text-center align-middle">{{$user->first_name}} {{$user->last_name}}</td>
						<td class="text-center align-middle">{{  $user->teacher_subject == null ? "No Subject" : $user->teacher_subject }}</td>
						<td class="text-center align-middle">{{$user->phone_no}}</td>
						<td class="text-center align-middle">
							@permission('edit.teachers')
							<a href="{{route('edit', $user->id)}}">
								<button title="Edit" class="btn btn-primary">
									<span class="fas fa-pencil-alt"></span>
								</button>
							</a>
							@endpermission
							@permission('view.teachers')
							<a data-toggle="modal" data-target="#viewTeacherModal{{$user->id}}" class="mx-2">
								<button title="View" class="btn btn-warning">
									<span class="fas fa-eye"></span>
								</button>
							</a>
							@endpermission
							@permission('delete.teachers')
							<a data-toggle="modal" data-target="#deleteTeacherModal{{$user->id}}">
								<button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
							</a>
							@endpermission
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>

			<a target="_blank" href="{{action('TeacherController@export')}}"
			class="btn text-white float-right mt-2 bg-dark">
			 <i class="fas fa-print"></i> Export Data
		 </a>

			
	</div>





	<!--TEACHER VIEW MODAL-->
@foreach($users as $user)
	<div class="modal fade" id="viewTeacherModal{{$user->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Staff Profile</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				@permission('pdf.teachers')
				<div class="container">
					<a target="_blank" name="gnrt_single" href="{{action('TeacherController@downloadPDF', $user->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Generate PDF
					</a>
				</div>
				@endpermission

				<div class="col-md-12">

					<div class="card hovercard py-4 mt-2">
						<div class="card-background">
							<img class="card-bkimg" alt="" src="{{$user->photo ? $user->photo->file : 'http://placeholder.it/400x400'}}">
						</div>
						<div class="useravatar">
							<img alt="" src="{{$user->photo ? $user->photo->file : 'http://placeholder.it/400x400'}}">
						</div>
						<div class="card-info">
							<span class="card-title">{{$user->first_name}} {{$user->last_name}}</span>
						</div>
					</div>

					<div class="container py-3 mb-3">

						<!--ACCORDION-->
						<div id="accordion">
							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse1" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Staff Detail
										</div>
									</h5>
								</div>

								<div id="collapse1" class="collapse show">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>Join Date</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->join_date}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Created Date</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->created_at->DiffForHumans()}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>First Name</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->first_name}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Last Name</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->last_name}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Staff ID</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->teacher_id}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Date Of Birth</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->date_of_birth}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Gender</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->gender}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Subject</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->teacher_subject}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Qualification</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->teacher_qualification}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Experience Detail</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->exp_detail}}</p>
											</div>
										</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse2" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Staff Contact
										</div>
									</h5>
								</div>

								<div id="collapse2" class="collapse">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>NIC Number</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->nic_no}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Phone Number</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->phone_no}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Emergency Number</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->emergency_no}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Full Address</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->full_address}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse3" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Staff Salary
										</div>
									</h5>
								</div>

								<div id="collapse3" class="collapse">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>Amount</label>
											</div>
											<div class="col-md-6">
												<p>{{$user->salary}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
	@endforeach

@foreach($users as $user)
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
							{!! Form::model($user, ['method'=>'DELETE', 'action'=>['TeacherController@deleteTeachers',  $user->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteTeachers btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach

@foreach($users as $user)
	<div class="modal fade" id="deleteTeacherModal{{$user->id}}">
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
							{!! Form::model($user, ['method'=>'DELETE', 'action'=>['TeacherController@destroy',  $user->id]]) !!}
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


@stop
