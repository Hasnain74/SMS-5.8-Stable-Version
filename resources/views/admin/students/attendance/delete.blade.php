<div class="modal fade" id="deleteAdmissionFee">
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
						<p class="text-center">Do you really want to delete?</p>
						<form method="DELETE" action="/students/attendance/destroy/{{$attendance->id}}">
							@csrf
							<button type="button" class="btn btn-danger mb-3 float-right post-form"> Delete</button>
						</form>
						<button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>