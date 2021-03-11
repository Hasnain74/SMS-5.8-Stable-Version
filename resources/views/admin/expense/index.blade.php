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

					<h1><i class="fas fa-money-check-alt"></i> Fee Report</h1>
				</div>
			</div>

			<div class="d-flex text-white text-center">
				<a href="{{route('admin.expense.index')}}">
					<div class="active-item port-item p-3 d-none d-md-block">
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
					<div class="hover-effect port-item p-3 d-none d-md-block" style="color:  #FFC300">
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




	<section class="mx-4 py-4">

		<div class="filterable">

			<table id="table" class="export_table table table-striped table-bordered table-responsive-lg">
				<thead>
					<tr class="filters">
						<th class="text-center">Date</th>
						<th class="text-alignment text-center">Total Collected Students</th>
						<th class="text-alignment text-center">Total Collected Fee</th>
						<th width="15%" class="align-middle text-center">Actions</th>
					</tr>
				</thead>
				@permission('fee.expense')
				<tbody>
				@foreach($fees as $date => $fees_group)
					@php($total=0)
				@foreach($fees_group as $fee)
					@php($total += intval($fee->paid_amount) )
				@endforeach
					<tr>
						<td class="text-center align-middle">{{date('F, Y', strtotime($fee->paid_date))}}</td>
						<td class="text-center align-middle">{{count($fees_group)}}</td>
						<td class="text-center align-middle">{{$total}} RS</td>
						<td class="text-center">
							<a data-toggle="modal" data-target="#viewModal{{$fee->id}}">
								<button title="View" class="btn btn-warning">
									<span class="fas fa-eye"></span>
								</button>
							</a>
						</td>
					</tr>
				@endforeach
				</tbody>
				@endpermission
			</table>
		</div>

	</section>


@foreach($fees as $date => $fees_group)
	@php($total=0)
	@foreach($fees_group as $fee)
		@php($total += intval($fee->paid_amount) )
	@endforeach
	<!--COLLECTED FEE REPORT MODAL-->
	<div class="modal fade" id="viewModal{{$fee->id}}">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">

				<div class="modal-header header-backgroud text-white">
					<h5 class="modal-title">Collected Fee Report View</h5>
					<button class="close" data-dismiss="modal">
						<span>&times;</span>
					</button>
				</div>

				<div class="container">
					<a target="_blank" name="gnrt_single" href="{{action('ExpenseController@downloadFeePDF', $fee->id)}}"
					   class="btn dusty-grass-gradient btn-block mt-2">
						<i class="fas fa-print"></i> Print Report
					</a>
				</div>

				<div class="col-md-12 py-2 ">

					<div class="card">
						<div class="card-header">
							<h5 class="mb-0">
								<div>
									<i class="fas fa-arrow-circle-down"></i> Collected Fee Report View
								</div>
							</h5>
						</div>

						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<label>Date</label>
								</div>
								<div class="col-md-6">
									<p>{{date('F, Y', strtotime($fee->paid_date))}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Total Collected Students</label>
								</div>
								<div class="col-md-6">
									<p>{{count($fees_group)}}</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label>Total Collected Fee</label>
								</div>
								<div class="col-md-6">
									<p>{{$total}} RS</p>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	@endforeach


@stop


