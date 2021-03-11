@extends('layouts.dashboard')

@section('content')


@include('includes.report_nav')

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">

					<h1><i class="fas fa-list-alt"></i> Reports List</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				@permission('pdf.reports|view.reports|create.reports|delete.reports|edit.reports|print.reports|post.reports')
				<a href="{{route('admin.reports.index')}}">
					<div class="active-item port-item p-3 d-none d-md-block">
						<i class="fas fa-list-alt fa-3x d-block pointer"></i>
						<span>Reports List</span>
					</div>
				</a>
				@endpermission
				@permission('create.report_categories|delete.report_categories|edit.report_categories')
				<a href="{{route('admin.reports.rep_cats.index')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-list-alt fa-3x d-block pointer"></i>
						<span>Report Categories</span>
					</div>
				</a>
				@endpermission
				@permission('create_subject.reports|delete_subject.reports|edit_subject.reports|
				add_marks.reports')
				<a href="{{route('subject_marks')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-book fa-3x d-block pointer"></i>
						<span>Subject Marks</span>
					</div>
				</a>
				@endpermission
			</div>

		</div>
	</header>
</div>


	<div class="mx-4 py-3">

		@if ($message = Session::get('delete_report'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('delete_reports'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('create_report'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('update_report'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

			@if ($message = Session::get('post_report'))
				<div class="alert dusty-grass-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

			@if ($message = Session::get('unpost_report'))
				<div class="alert peach-gradient alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
			@endif

		<div class="filterable">

				@permission('delete.reports')
					<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
						<option value="">Delete</option>
					</select>
					<a data-toggle="modal" data-target="#deleteModal">
						<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
					</a>
				@endpermission

			{!! Form::open(['method'=>'GET', 'action'=>'ReportController@reportsActions']) !!}

			@permission('post.reports')
			<div class="d-flex float-right">
				<select name="options" class="browser-default custom-select bg-dark text-white mt-2">

					<option value="select">Select Action</option>
					@permission('print.fee')
					<option value="post">Post Record</option>
					@endpermission
					@permission('pdf.fee')
					<option value="unpost">Unpost Record</option>
					@endpermission

				</select>
				<a><input value="Apply" type="submit" name="apply" class="btn blue-gradient mx-2"></a>
			</div>
			@endpermission

			@permission('view.reports')
			<div class="form-group">
				<select name="class_id_index_reports" class="browser-default custom-select bg-dark text-white" >
					<option value="">Select Class</option>
					@foreach ($classes as $key => $value)
						<option value="{{ $key }}">{{ $value }}</option>
					@endforeach
				</select>
			</div>

			<div class="my-3">
				<select name="student_id_index_reports" class="browser-default custom-select bg-dark text-white" >
					<option value="">Select Student ID</option>
					@foreach ($student_id as $key => $value)
						<option value="{{ $value }}">{{ $value }}</option>
					@endforeach
				</select>
			</div>
			@endpermission

			<table id="studentsData_index_reports" class="table table-striped table-bordered mt-5 table-responsive-lg">
				<thead>
					<tr class="filters">
						{{-- <th class="align-middle text-center"><input type="checkbox" id="options"></th> --}}
						<th class="text-center">Student ID</th>
						<th class="text-center">Student Name</th>
						<th class="text-center">Report Category</th>
						<th class="text-center">Subject</th>
						<th class="text-center">Report Created By</th>
						{{-- <th style="width: 15%;" class="align-middle text-center">Actions</th> --}}
					</tr>
				</thead>
				<tbody>
					@if($report)
					@foreach($report as $r)
					<tr>
					<td class="text-center">{{ $r->student_id }}</td>
					<td class="text-center">{{ $r->student_name}}</td>
					<td class="text-center">{{ $r->report_categories_name }}</td>
					<td class="text-center">{{ $r->subject }}</td>
					<td class="text-center">{{ $r->date }}</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			{!! Form::close() !!}

			<a target="_blank" href="{{action('ReportController@export')}}"
				   class="btn text-white float-right mt-2 bg-dark">
					<i class="fas fa-print"></i> Export Data
				</a>
			
		</div>
	</div>

@foreach($reports as $report)
	@permission('delete.reports')
	<div class="modal fade" id="deleteReportModal{{$report->id}}">
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
							{!! Form::model($report, ['method'=>'DELETE', 'action'=>['ReportController@destroy',  $report->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	@endpermission
@endforeach


@foreach($reports as $report)
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
							{!! Form::model($report, ['method'=>'DELETE', 'action'=>['ReportController@deleteReport',  $report->id]]) !!}
							{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'delete_students_report btn btn-danger mb-3 float-right']) !!}
							{!! Form::close() !!}
							<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endforeach


@foreach($reports as $report)
	@permission('edit.reports')
    <!--EDIT CLASS MODAL-->
    <div class="modal fade" id="editReportModal{{$report->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header header-backgroud text-white">
                    <h5 class="modal-title">Edit Report</h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="container mt-3">

                {!! Form::model($report, ['method'=>'PATCH', 'action'=>['ReportController@update', $report->id], 'files'=>true]) !!}
                <!--CLASS DETAIL-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="md-form md-outline">
                                {!! Form::label('class_id', 'Class', ['class'=>'control-label']) !!}
                                {!! Form::text('class_id', null,  ['class'=>'form-control', 'readonly']) !!}
                            </div>
                            <div class="md-form md-outline">
                                {!! Form::label('student_id', 'Student ID', ['class'=>'control-label']) !!}
                                {!! Form::text('student_id', null,  ['class'=>'form-control', 'readonly']) !!}
                            </div>
                            <div class="md-form md-outline">
                                {!! Form::label('student_name', 'Student Name', ['class'=>'control-label']) !!}
                                {!! Form::text('student_name', null,  ['class'=>'form-control', 'readonly']) !!}
                            </div>
                            <div class="md-form md-outline">
                                {!! Form::label('subject', 'Subject', ['class'=>'control-label']) !!}
                                {!! Form::text('subject', null, ['class'=>'form-control']) !!}
                            </div>
                            <div class="md-form md-outline">
                                {!! Form::label('teacher_name', 'Teacher Name', ['class'=>'control-label']) !!}
                                {!! Form::text('teacher_name', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
							<div class="md-form md-outline">
								{!! Form::hidden('report_categories_id', null,  ['class'=>'form-control', 'readonly']) !!}
							</div>
							<div class="md-form md-outline">
								{!! Form::label('report_categories_name', 'Student Name', ['class'=>'control-label']) !!}
								{!! Form::text('report_categories_name', null,  ['class'=>'form-control', 'readonly']) !!}
							</div>
                            <div class="md-form md-outline">
                                <label class="control-label">Total Marks <span style="color: red">*</span></label>
                                {!! Form::text('total_marks', null, ['class'=>'form-control Tmarks_edit_student_reports val', 'readonly']) !!}
                            </div>
                            <div class="md-form md-outline">
                                <label class="control-label">Obtained Marks <span style="color: red">*</span></label>
                                {!! Form::number('obtained_marks', null, ['class'=>'form-control Omarks_edit_student_reports val']) !!}
                            </div>
                            <div class="md-form md-outline">
                                {!! Form::label('percentage', 'Percentage', ['class'=>'control-label']) !!}
								{!! Form::text('percentage', null, ['class'=>'form-control percentage_edit_student_reports','readonly']) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            {!! Form::button(' Save', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient mb-3']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
	@endpermission
	@endforeach


@foreach($reports as $report)
	<!--INVOICE VIEW MODAL-->
	<div class="modal fade" id="viewReportModal{{$report->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Report View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="d-flex">
				{{-- @permission('pdf.reports')
				<div class="col-md-6">
					<a target="_blank" name="gnrt_single" href="{{action('ReportController@downloadReportPDF', $report->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Generate Report PDF
					</a>
				</div>
				@endpermission --}}

				@permission('print.reports')
				<div class="col-md-12">
					<a target="_blank" name="print_single" href="{{action('ReportController@printReport', $report->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print Report
					</a>
				</div>
				@endpermission
				</div>

				<div class="d-flex">
				<div class="col-md-6">
					<a target="_blank" name="print_single" href="{{action('ReportController@print_award_list_report', $report->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print Award List
					</a>
				</div>

				<div class="col-md-6">
					<a data-toggle="modal" data-target="#printDMC{{$report->id}}" class="btn dusty-grass-gradient btn-block mt-2"
						style="color:black;">
						<i class="fas fa-print"></i> Print DMC
					</a>
				</div>
				</div>

				<div class="d-flex">
				<div class="col-md-6">
					<a target="_blank" name="print_single" href="{{action('ReportController@print_empty_award_list', $report->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print Marks Entry Proforma
					</a>
				</div>
				<div class="col-md-6">
					<a target="_blank" href="{{action('ReportController@print_subject_for_whole_class', $report->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print Subject For Whole Class
					</a>
				</div>
				</div>


				<div class="col-md-12 py-2 ">

					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">
								<div>
									<i class="fas fa-arrow-circle-down"></i> Report View
								</div>
							</h5>
						</div>

						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<label>Created Date</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->created_at}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Class</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->class_name}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Student Name</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->student_name}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Student ID</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->student_id}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Subject</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->subject}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Teacher</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->teacher_name}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Report Type</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->report_categories_name}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Total Marks</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->total_marks}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Obtained Marks</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->obtained_marks}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Percentage</label>
								</div>
								<div class="col-md-6">
									<p>{{number_format($report->percentage, 2, '.', ',')}} %</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Position</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->position}}</p>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	@endforeach

	@foreach($reports as $report)
	<div class="modal fade" id="printDMC{{$report->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Print DMC</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="col-md-12 py-2 ">
					@if($dmc_setup)
					{!! Form::model($report, ['method'=>'POST', 'action'=>['ReportController@print_dmc', $report->id], 'files'=>true, 'class' => 'mb-5', 'target'=>'blank']) !!}

					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">
								<div>
									<i class="fas fa-arrow-circle-down"></i> Report View
								</div>
							</h5>
						</div>

						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<label>Class</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->class_name}}</p>
								</div>
							</div>
							{!! Form::hidden('class_id', null, ['class'=>'form-control']) !!}
							<div class="row">
								<div class="col-md-6">
									<label>Student Name</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->student_name}}</p>
								</div>
							</div>
							{!! Form::hidden('student_name', null, ['class'=>'form-control']) !!}
							<div class="row">
								<div class="col-md-6">
									<label>Student ID</label>
								</div>
								<div class="col-md-6">
									<p>{{$report->student_id}}</p>
								</div>
							</div>


							{{-- {{-- <div class="col-md-12"> --}}
								<div class="row">
							{!! Form::hidden('student_id', null, ['class'=>'form-control']) !!}
							


							<div class="col-md-6">
								@foreach($dmc_setup as $dmc)
								<div class="col-md-12">
									<label>{{ $dmc['report_type'] }}</label>
									{!! Form::hidden('dmc[]', $dmc['report_type'], null, ['class'=>'form-control']) !!}
								</div>	
								@endforeach
							</div>	
							
							
							<div class="col-md-6">
							@if($dmc_setup)
							@foreach($dmc_setup as $dmc)
							
							{{-- @if($dmc['report_type'] == 'First Term')  --}}
							<div class="col-md-12">
								<select name="year[]" class="browser-default custom-select mb-2" >
									<option value="">Select Year</option>
									@foreach ($unique_array as $key => $value)
										<option value="{{ $value }}">{{ $value }}</option>
									@endforeach
								</select>
							</div>							
							{{-- @else 

							<div class="col-md-12">
							
							</div> --}}
							
							{{-- @endif --}}

							
							@endforeach
							@endif
						</div>

							<div class="col-md-12 mt-4">
								{!! Form::button(' Print', ['type'=>'submit', 'class'=>'fas fa-folder-open btn btn-block peach-gradient mb-3']) !!}
							</div>
						{!! Form::close() !!}
						
								</div>
							{{-- </div> --}}

						</div>
					</div>
					@endif

				</div>
			</div>
		</div>
	</div>


@endforeach


@stop







