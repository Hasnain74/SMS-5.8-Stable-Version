@extends('layouts.dashboard')

@section('content')

	@include('includes.expense_nav')

	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="ml-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">

					<h1><i class="fas fa-list-alt"></i> Expense Report List</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				<a href="{{route('admin.expense.index')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-money-check-alt fa-3x d-block pointer"></i>
						<span>Fee Report</span>
					</div>
				</a>
				<a href="{{route('salary_report')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-file-invoice-dollar fa-3x d-block pointer"></i>
						<span>Salary Report</span>
					</div>
				</a>
				<a href="{{route('expense_report')}}">
					<div class="active-item port-item p-3 d-none d-md-block">
						<i class="fas fa-money-check-alt fa-3x d-block pointer"></i>
						<span>Expense Report</span>
					</div>
				</a>
				@permission('monthly_summary.expense')
				<a href="{{route('monthly_summary')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-file-alt fa-3x d-block pointer"></i>
						<span>Monthly Summary</span>
					</div>
				</a>
				@endpermission
				@permission('yearly_summary.expense')
				<a href="{{route('yearly_summary')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-file-alt fa-3x d-block pointer"></i>
						<span>Yearly Summary</span>
					</div>
				</a>
				@endpermission
				@permission('total_summary.expense')
				<a href="{{route('total_summary')}}">
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
						<i class="fas fa-file-alt fa-3x d-block pointer"></i>
						<span>Total Summary</span>
					</div>
				</a>
				@endpermission
			</div>

		</div>
	</header>
	</div>




	<div class="mx-4 py-4">

		@if ($message = Session::get('delete_expense_invoice'))
			<div class="alert peach-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('create_expense_invoice'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@if ($message = Session::get('update_expense_invoice'))
			<div class="alert dusty-grass-gradient alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
		@endif

		@include('includes.form_errors')

		<div class="filterable">

				<div class="float-right mx-2">
					@permission('delete.expense')
					<select hidden name="checkBoxArray" class="form-control bg-dark text-white">
						<option value="">Delete</option>
					</select>
					<a data-toggle="modal" data-target="#deleteModal">
						<button type="button" class="btn btn-danger float-right fas fa-trash-alt"> Delete</button>
					</a>
					@endpermission
				</div>
				<div class="mb-2">
					@permission('create.expense')
					<a data-toggle="modal" data-target="#createNewReportModal">
						<button class="btn purple-gradient"><span class="fas fa-pencil-alt"></span> Create New Report</button>
					</a>
					@endpermission
				</div>

			<table id="table" class="export_table table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="align-middle text-center"><input type="checkbox" id="options"></th>
						<th class="text-center">Date</th>
						<th class=" text-center">Report Created By</th>
						<th class=" text-center">Total Amount</th>
						<th class="align-middle text-center">Note</th>
						<th class="align-middle text-center">Actions</th>
					</tr>
				</thead>
				@permission('exp.expense')
				<tbody>
				@if($reports)
					@foreach($reports->sortByDesc('updated_at') as $report)
					<tr>
						<td class="text-center"><input class="checkBoxes align-middle" type="checkbox" name="checkBoxArray[]" value="{{$report->id}}"></td>
						<td class="text-center align-middle">{{date('F, Y', strtotime($report->date))}}</td>
						<td class="text-center align-middle">{{$report->created_by}}</td>
						<td class="text-center align-middle">{{$report->total_amount}}</td>
						<td class="text-center align-middle">{{$report->note}}</td>
						<td class="text-center">
							@permission('edit.expense')
							<a data-toggle="modal" data-target="#editModel{{$report->id}}">
								<button title="Edit" class="btn btn-primary">
									<span class="fas fa-pencil-alt"></span>
								</button>
							</a>
							@endpermission

							<a data-toggle="modal" data-target="#viewModal{{$report->id}}">
								<button title="View" class="btn btn-warning">
									<span class="fas fa-eye"></span>
								</button>
							</a>
							@permission('delete.expense')
							<a data-toggle="modal" data-target="#deleteReportModal{{$report->id}}">
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
		</div>
		
	</div>

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
								{!! Form::model($report, ['method'=>'DELETE', 'action'=>['ExpenseController@deleteReport',  $report->id]]) !!}
								{!! Form::button(' Delete', ['type'=>'submit', 'class'=>'deleteReports btn btn-danger mb-3 float-right']) !!}
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
								{!! Form::model($report, ['method'=>'DELETE', 'action'=>['ExpenseController@destroy',  $report->id]]) !!}
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


	@foreach($reports as $report)
		<div class="modal fade" id="viewModal{{$report->id}}">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<div class="modal-header header-backgroud text-white">
						<h5 class="modal-title">Report View</h5>
						<button class="close" data-dismiss="modal">
							<span>&times;</span>
						</button>
					</div>

					@permission('pdf.expense')
					<div class="container">
						<a target="_blank" name="gnrt_single" href="{{action('ExpenseController@downloadReportPDF', $report->id)}}" class="btn dusty-grass-gradient btn-block mt-2">
							<i class="fas fa-print"></i> Print Report
						</a>
					</div>
					@endpermission

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
										<label>Date</label>
									</div>
									<div class="col-md-6">
										<p>{{$report->date}}</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Total Amount</label>
									</div>
									<div class="col-md-6">
										<p>{{$report->total_amount}}</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Note</label>
									</div>
									<div class="col-md-6">
										<p>{{$report->note}}</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Report Created By</label>
									</div>
									<div class="col-md-6">
										<p>{{$report->created_by}}</p>
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
	<div class="modal fade" id="editModel{{$report->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Edit Report</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
				{!! Form::model($report, ['method'=>'PATCH', 'action'=>['ExpenseController@update', $report->id],'class' => 'mb-3']) !!}
				<!--CLASS DETAIL-->
					<div class="row mt-2">
						<div class="col-md-12">
							<div class="md-form">
								{!! Form::date('date', null, ['class'=>'form-control', 'name'=>'date']) !!}
								<label class="mt-3">Issue Date <span style="color: red">*</span></label>
							</div>
							<div class="md-form md-outline">
								<label class="control-label">Total Amount <span style="color: red">*</span></label>
								{!! Form::text('total_amount', null, ['class'=>'form-control', 'name'=>'total_amount']) !!}
							</div>
							<div class="md-form md-outline">
								<label class="control-label">Note <span style="color: red">*</span></label>
								{!! Form::textarea('note', null, ['class'=>'form-control', 'name'=>'note']) !!}
							</div>
							{!! Form::button(' Add Report', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	@endforeach


	<!--CREATE REPORT MODAL-->
	<div class="modal fade" id="createNewReportModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Create New Report</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
				{!! Form::open(['method'=>'POST', 'action'=>'ExpenseController@store', 'class' => 'mb-3']) !!}
				<!--CLASS DETAIL-->
					<div class="row mt-2">
						<div class="col-md-12">
							<div class="md-form">
								{!! Form::date('date', null, ['class'=>'form-control', 'name'=>'date']) !!}
								<label class="mt-3">Issue Date <span style="color: red">*</span></label>
							</div>
							<div class="md-form md-outline">
								<label class="control-label">Total Amount <span style="color: red">*</span></label>
								{!! Form::text('total_amount', null, ['class'=>'form-control', 'name'=>'total_amount']) !!}
							</div>
							<div class="md-form md-outline">
								<label class="control-label">Note <span style="color: red">*</span></label>
								{!! Form::textarea('note', null, ['class'=>'form-control', 'name'=>'note']) !!}
							</div>
							{!! Form::button(' Add Report', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block']) !!}
						</div>
					</div>
					{!! Form::close() !!}
			</div>
		</div>
		</div>
	</div>

@stop
