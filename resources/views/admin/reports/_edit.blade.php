@permission('edit.reports')
    <!--EDIT CLASS MODAL-->
    <div class="modal fade">
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
