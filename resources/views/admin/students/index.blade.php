@extends('layouts.dashboard')

@section('content')

@include('includes.students_nav')

<!--HEADER-->
<header id="main-header" class="py-2 text-white">
	<div class="ml-4">

		<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
			<i class="fas fa-arrow-left i-resp"></i> Back
		</a>

		<div class="row">
			<div class="col-md-12 text-center">
				<h1><i class="fas fa-address-book"></i> Students List</h1>
			</div>
		</div>

		<div class="d-flex text-white text-center">
			@permission('delete.students_account|edit.students_account')
			<a href="{{route('admin.students.index')}}">
				<div class="active-item hover-effect port-item p-3 d-none d-md-block">
					<i class="fas fa-user-graduate fa-3x d-block pointer"></i>
					<span>Manage Students</span>
				</div>
			</a>
			<a href="{{route('student_accounts')}}">
				<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
					<i class="fas fa-user-circle fa-3x d-block pointer"></i>
					<span>Student Accounts</span>
				</div>
			</a>

			<a href="{{route('student_promotions')}}">
				<div class="hover-effect port-item p-3 d-none d-md-block" style="color:    #FFC300">
					<i class="fas fa-arrow-circle-right fa-3x d-block pointer"></i>
					<span>Students Promotion</span>
				</div>
			</a>
			@endpermission
		</div>

	</div>
</header>
</div>

	<div class="mx-4 py-3">

		@if ($message = Session::get('delete_student'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('delete_multiple_student'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('create_student'))
				<div class="alert dusty-grass-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('update_student'))
				<div class="alert dusty-grass-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

				<div class="float-right">
					@permission('delete.students')
					<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
						<option value="">Delete</option>
					</select>
					<a data-toggle="modal" data-target="#deleteModal">
						<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
					</a>
					@endpermission
				</div>
				@permission('create.students')
					<div class="mb-2">
						<a href="{{route('admin.students.create')}}">
						<button type="button" class="btn purple-gradient px-4"><span class="fas fa-plus-square b-resp"></span> Add New Students</button>
						</a>
					</div>
				@endpermission

			<table id="table" class="table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="align-middle text-center"><input type="checkbox" id="options"></th>
						<th class="text-center">Photo</th>
						<th class="text-center">Student ID</th>
						<th class="text-center">Name</th>
						<th class="text-center">Guardian Name</th>
						<th class="text-center">Class</th>
						<th class="text-center">Status</th>
						<th class="align-middle text-center">Actions</th>
					</tr>
				</thead>
				@permission('view.students')
				<tbody>
				@if($students)
					@foreach($students as $student)
					<tr>
						<td class="text-center"><input class="checkBoxes align-middle" type="checkbox" name="checkBoxArray[]" value="{{$student->id}}"></td>
						<td class="text-center align-middle">
							<a href="{{$student->photo ? $student->photo->file :  asset('img/avatar.png')}}"  data-toggle="lightbox">
							<img style="border-radius: 50%;" height="100" src="{{$student->photo ? $student->photo->file :  asset('img/avatar.png')}}" alt="">
							</a>
						</td>
						<td class="text-center align-middle">{{$student->student_id}}</td>
						<td class="text-center align-middle">{{$student->first_name}} {{$student->last_name}}</td>
						<td class="text-center align-middle">{{$student->guardian_name}}</td>
						<td class="text-center align-middle">{{$student->studentsClass['class_name']}}</td>
						<td class="text-center align-middle">{{$student->status}}</td>
						<td class="text-center align-middle">
							@permission('edit.students')
							<a href="{{route('edit_student', $student->id)}}">
								<button type="button" title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
							</a>
							@endpermission
							<a data-toggle="modal" data-target="#viewStudentModal{{$student->id}}">
								<button type="button" title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button>
							</a>
							@permission('delete.students')
							<a data-toggle="modal" data-target="#deleteStudentModal{{$student->id}}">
								<button type="button" title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
							</a>
							@endpermission
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
				@endpermission
			</table>

				<a target="_blank" href="{{action('StudentsController@export')}}"
				   class="btn text-white float-right mt-2 bg-dark">
					<i class="fas fa-print"></i> Export Data
				</a>
			
	</div>





@foreach($students as $student)
	<!--STUDENT VIEW MODAL-->
	<div class="modal fade" id="viewStudentModal{{$student->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Student Profile</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				@permission('pdf.students')
				<div class="container">
					<a target="_blank" name="gnrt_single" href="{{action('StudentsController@downloadPDF', $student->id)}}"
					   class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Generate PDF
					</a>
				</div>
				@endpermission

				<div class="col-md-12">

					<div class="card hovercard py-4 mt-2">
						<div class="card-background">
							<img class="card-bkimg" alt="" src="{{$student->photo ? $student->photo->file :  asset('img/avatar.png')}}">
						</div>
						<div class="useravatar">
							<img alt="" src="{{$student->photo ? $student->photo->file :   asset('img/avatar.png')}}">
						</div>
						<div class="card-info">
							<span class="card-title">{{$student->first_name}} {{$student->last_name}}</span>
						</div>
					</div>

					<div class="container py-3 mb-3">

						<!--ACCORDION-->
						<div id="accordion">
							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse1" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Student Detail
										</div>
									</h5>
								</div>

								<div id="collapse1" class="collapse show">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>Admission Date</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->admission_date}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Created Date</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->created_at->DiffForHumans()}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Student ID</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->student_id}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>First Name</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->first_name}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Last Name</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->last_name}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Date Of Birth</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->DOB}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Class</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->studentsClass['class_name']}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Gender</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->gender}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Blood Group</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->blood_group}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Religion</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->religion}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse2" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Student Contact
										</div>
									</h5>
								</div>

								<div id="collapse2" class="collapse">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>Phone Number</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->student_phone_no}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Full Address</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->student_address}}</p>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse3" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Guardian Detail
										</div>
									</h5>
								</div>

								<div id="collapse3" class="collapse">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>Guardian Name</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->guardian_name}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Gender</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->guardian_gender}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Relation</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->guardian_relation}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Occupation</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->guardian_occupation}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Phone Number</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->guardian_phone_no}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>NIC Number</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->NIC_no}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Address</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->guardian_address}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header pointer">
									<h5 class="mb-0">
										<div href="#collapse4" data-toggle="collapse" data-parent="#accordion">
											<i class="fas fa-arrow-circle-down"></i> Student Fee Detail
										</div>
									</h5>
								</div>

								<div id="collapse4" class="collapse">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<label>Fee Setup</label>
											</div>
											<div class="col-md-6">
												<p>{{ucfirst(trans($student->fee_setup))}}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Class Fee</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->studentsClass->class_fee}} Rs</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Discount Percent</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->discount_percent}} %</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Final Fee</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->total_fee}} Rs</p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Transport Fee</label>
											</div>
											<div class="col-md-6">
												<p>{{$student->transport_fee}} Rs</p>
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

@foreach($students as $student)
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
							{!! Form::model($student, ['method'=>'DELETE', 'action'=>['StudentsController@deleteStudents',  $student->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'btn btn-danger mb-3 float-right deleteStudents']) !!}
							{!! Form::close() !!}
							<button value="cancel" type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach

@foreach($students as $student)
	<div class="modal fade" id="deleteStudentModal{{$student->id}}">
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
							{!! Form::model($student, ['method'=>'DELETE', 'action'=>['StudentsController@destroy',  $student->id]]) !!}
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
