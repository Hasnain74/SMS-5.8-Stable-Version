<div class="modal fade" id="editFee{{$attendance->id}}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header header-backgroud text-white">
				<h5 class="modal-title">Edit Student Attendance</h5>
				<button class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="container">
			{!! Form::model($attendance, ['route' => ['students.attendance.update', $attendance->id]]) !!}
				<div class="row mt-2">
					<div class="col-md-12">
						<div class="md-form md-outline">
							<label class="control-label">First name <span style="color: red">*</span></label><br><br>
							{!! Form::text('first_name', null, ['class'=>'form-control']) !!}
						</div>
						<div class="md-form md-outline">
							<label class="control-label">Last name <span style="color: red">*</span></label><br><br>
							{!! Form::text('last_name', null, ['class'=>'form-control']) !!}
						</div>
						<div class="md-form md-outline">
							...
						</div>
						{!! Form::button(' Update', ['type'=>'button', 'class'=>'btn peach-gradient btn-block post-form']) !!}
                        <br>
					</div>
				</div>
				{!! Form::close() !!}
			</div>

		</div>
	</div>
</div>