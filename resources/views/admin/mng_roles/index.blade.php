@extends('layouts.dashboard')

@section('content')

	<div class="header-backgroud">
		<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
			<div class="mx-2">
				@include('includes.school_profile')
			</div>
		</nav>


	<!--HEADER-->
	<header id="main-header" class="py-2 text-white">
		<div class="mx-4">

			<a href="/" class="btn aqua-gradient float-right mr-4 a-resp">
				<i class="fas fa-arrow-left"></i> Back
			</a>

			<div class="row">
				<div class="col-md-12 text-center">
					<h1><i class="fas fa-user-circle"></i> Roles List</h1>
				</div>
			</div>

		</div>
	</header>
</div>

			<div class="filterable mx-4 py-4">

				@if ($message = Session::get('delete_role'))
					<div class="alert peach-gradient alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>{{ $message }}</strong>
					</div>
				@endif

				@if ($message = Session::get('create_role'))
					<div class="alert dusty-grass-gradient alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>{{ $message }}</strong>
					</div>
				@endif

				@if ($message = Session::get('update_role'))
					<div class="alert dusty-grass-gradient alert-block">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<strong>{{ $message }}</strong>
					</div>
				@endif

				@permission('create.manage_roles')
				<a href="{{route('admin.mng_roles.create')}}">
					<button type="button" class="btn purple-gradient mb-3"><span class="fas fa-plus-square"></span> Add New Role</button>
				</a>
				@endpermission

				<table id="table" class="table table-striped table-bordered my-4 table-responsive-lg">
					<thead>
						<tr class="filters">
							<th class="text-center">Role Name</th>
							<th class="text-center">Created at</th>
							<th class="align-middle text-center">Actions</th>
						</tr>
					</thead>
					<tbody>
					@if($roles)
						@foreach($roles as $role)
							<tr>
								<td class="text-center align-middle">{{$role->name}}</td>
								<td class="text-center align-middle">{{$role->created_at->diffForHumans()}}</td>
								<td class="text-center">
									@permission('edit.manage_roles')
									<a href="{{route('admin.mng_roles.edit', $role->id)}}">
										<button type="button" title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button>
									</a>
									@endpermission
									@permission('delete.manage_roles')
									<a data-toggle="modal" data-target="#deleteModal{{$role->id}}">
										<button type="button" title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button>
									</a>
									@endpermission
								</td>
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
				
			</div>

@foreach($roles as $role)
	<div class="modal fade" id="deleteModal{{$role->id}}">
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
							{!! Form::model($role, ['method'=>'DELETE', 'action'=>['RolesController@destroy',  $role->id]]) !!}
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