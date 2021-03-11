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
					<h1><i class="fas fa-building"></i> Classes List</h1>
				</div>
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-3">

		@if ($message = Session::get('create_class'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('update_class'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@include('includes.form_errors')

		<div class="filterable">

			<div>
				<div class="mb-2">
					@permission('create.students_classes')
					<a data-toggle="modal" data-target="#addNewClassModal">
						<button class="btn purple-gradient"><span class="fas fa-plus-square"></span> Add New Class</button>
					</a>
					@endpermission
				</div>
			</div>

			<table id="table" class="export_table table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="text-center">Class Name</th>
						<th class="text-center">Class Fee</th>
						<th class="text-center">Teacher Name</th>
						<th class="text-center">Male Students</th>
						<th class="text-center">Female Students</th>
						<th class="align-middle text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
				@if($result)
					@foreach($result as $class)
					<tr>
						<td class="text-center align-middle">{{$class['class_name']}}</td>
						<td class="text-center align-middle">{{$class['class_fee']}}</td>
						<td class="text-center align-middle">{{$class['class_teacher']}}</td>
						<td class="text-center align-middle">{{$class['male']}}</td>
						<td class="text-center align-middle">{{$class['female']}}</td>
						<td class="text-center">
							@permission('edit.students_classes')
							<a data-toggle="modal" data-target="#editClassModal{{$class['id']}}">
							<button title="Edit" class="btn btn-primary mx-2"><span class="fas fa-pencil-alt"></span>
							</button>
							</a>
							@endpermission
                            <a target="_blank" href="{{route('print_classes', $class['id']) }}">
                                <button type="button" title="Print" class="btn dusty-grass-gradient">
                                    <span class="fas fa-print"></span>
                                </button>
                            </a>
						</td>
					</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
	</div>




	<!--ADD NEW CLASS MODAL-->
	<div class="modal fade" id="addNewClassModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Class View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
				{!! Form::open(['method'=>'POST', 'action'=>'StudentsClassController@store', 'class' => 'mb-3']) !!}
						<!--CLASS DETAIL-->
						<div class="row mt-2">
							<div class="col-md-12">
								<div class="md-form md-outline">
									<label class="control-label">Class Name <span style="color: red">*</span></label>
									{!! Form::text('class_name', null, ['class'=>'form-control']) !!}
								</div>
								<div class="md-form md-outline">
									<label class="control-label">Define Fee <span style="color: red">*</span></label>
									{!! Form::text('class_fee', null, ['class'=>'form-control']) !!}
								</div>
								<div class="md-form  md-outline">
									<select name="class_teacher" class="browser-default custom-select" >
										<option value="">Select Teacher</option>
										@foreach ($teacher_name as $key => $value)
											<option value="{{ $value }}">{{ $value }}</option>
										@endforeach
									</select>
									{{-- <label class="control-label">Class Teacher Name <span style="color: red">*</span></label>
									{!! Form::text('class_teacher', null, ['class'=>'form-control']) !!} --}}
								</div>
								{!! Form::button(' Add Class', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>
	

@foreach($result as $class)
	<!--EDIT CLASS MODAL-->
	<div class="modal fade" id="editClassModal{{$class['id']}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Class View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>
				<div class="container">

				{!! Form::model($class, ['method'=>'PATCH', 'action'=>['StudentsClassController@update',  $class['id']]]) !!}
						<!--CLASS DETAIL-->
						<div class="row mt-2">
							<div class="col-md-12">
								<h3 class="display-4 text-center">Class Detail</h3>
								<div class="md-form md-outline">
									<label class="control-label">Class Name <span style="color: red">*</span></label>
									{!! Form::text('class_name', null, ['class'=>'form-control']) !!}
								</div>
								<div class="md-form md-outline">
									<label class="control-label">Define Fee <span style="color: red">*</span></label>
									{!! Form::text('class_fee', null, ['class'=>'form-control']) !!}
								</div>
								<div class="md-form  md-outline">
									<select class="form-control mb-4" name="class_teacher">
										<option>Select Teacher</option>                           
										@foreach ($teacher_name as $teacher)
										  <option value="{{ $teacher }}" {{ ( $teacher == $class['class_teacher']) ? 'selected' : '' }}> {{ $teacher }} </option>
										@endforeach    
									</select>
								</div>
								{!! Form::button(' Edit Class', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>
	@endforeach

	
@stop


