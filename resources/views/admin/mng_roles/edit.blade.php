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

            <a href="{{route('admin.mng_roles.index')}}" class="btn aqua-gradient float-right mr-4 a-resp">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <div class="row">
                <div class="col-md-12 text-center">

                    <h1><i class="fas fa-user-circle"></i> Edit Role</h1>
                </div>
            </div>

        </div>
    </header>
    </div>


    {!! Form::open(['method'=>'PATCH', 'action'=>['RolesController@update', $role->id], 'files'=>true, 'class' => 'py-4 mx-4']) !!}

    @include('includes.form_errors')

    <!--CLASS DETAIL-->
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="md-form md-outline">
                <label class="control-label">Role Name <span style="color: red">*</span></label>
                {!! Form::text('name', $role->name, ['class'=>'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="mt-3">
        <table id="myTable" class="table table-striped table-bordered table-responsive-lg">
            <thead>
            <tr class="filters">
                <th class="text-center">Module Name</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody id="checkboxes">
            <tr>
                <td class="align-middle"><input name="modules[]" value="dashboard" id="dashboard" type="checkbox" class="mx-2">Dashboard</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="dashboardActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="dashboardActions[]" value="view" type="checkbox" class="mx-2 dsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'dashboard', 'view')}}>View
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="students" id="students" type="checkbox" class="mx-2">Students</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="studentsActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="studentsActions[]" value="view" type="checkbox" class="mx-2 stdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students', 'view')}}>View
                            </div>
                            <div class="form-group">
                                <input name="studentsActions[]" value="delete" type="checkbox" class="mx-2 stdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="studentsActions[]" value="create" type="checkbox" class="mx-2 stdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students', 'create')}}>Create
                            </div>
                            <div class="form-group">
                                <input name="studentsActions[]" value="pdf" type="checkbox" class="mx-2 stdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students', 'pdf')}}>Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="studentsActions[]" value="edit" type="checkbox" class="mx-2 stdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students', 'edit')}}>Edit
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="students_account" id="students_account" type="checkbox" class="mx-2">Students Accounts</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="students_accountActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="students_accountActions[]" value="delete" type="checkbox" class="mx-2 saCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students_account', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="students_accountActions[]" value="edit" type="checkbox" class="mx-2 saCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students_account', 'edit')}}>Edit
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="attendance" id="attendance" type="checkbox" class="mx-2">Students Attendance</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="attendanceActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="attendanceActions[]" value="create" type="checkbox" class="mx-2 atdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'attendance', 'create')}}>Take Attendance
                            </div>
                            <div class="form-group">
                                <input name="attendanceActions[]" value="delete" type="checkbox" class="mx-2 atdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'attendance', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="attendanceActions[]" value="edit" type="checkbox" class="mx-2 atdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'attendance', 'edit')}}>Edit
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="attendanceActions[]" value="view" type="checkbox" class="mx-2 atdCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'attendance', 'view')}}>View
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="students_classes" id="students_classes" type="checkbox" class="mx-2">Students Classes</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="students_classesActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="students_classesActions[]" value="create" type="checkbox" class="mx-2 clsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students_classes', 'create')}}>Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="students_classesActions[]" value="edit" type="checkbox" class="mx-2 clsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'students_classes', 'edit')}}>Edit
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input  name="modules[]" value="teachers" id="teachers" type="checkbox" class="mx-2">Staff</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="teachersActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="teachersActions[]" value="create" type="checkbox" class="mx-2 tchCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers', 'create')}}>Create
                            </div>
                            <div class="form-group">
                                <input name="teachersActions[]" value="delete" type="checkbox" class="mx-2 tchCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachersActions[]" value="edit" type="checkbox" class="mx-2 tchCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers', 'edit')}}>Edit
                            </div>
                            <div class="form-group">
                                <input name="teachersActions[]" value="pdf" type="checkbox" class="mx-2 tchCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers', 'pdf')}}>Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachersActions[]" value="view" type="checkbox" class="mx-2 tchCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers', 'view')}}>View
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="teachers_account" id="teachers_account" type="checkbox" class="mx-2">Staff Account</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="teachers_accountActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="teachers_accountActions[]" value="delete" type="checkbox" class="mx-2 taCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_account', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_accountActions[]" value="edit" type="checkbox" class="mx-2 taCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_account', 'edit')}}>Edit
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="teachers_roles" id="teachers_roles" type="checkbox" class="mx-2">Staff Roles</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="teachers_roles[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="teachers_rolesActions[]" value="delete" type="checkbox" class="mx-2 trCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_account', 'delete')}}>Delete
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="teachers_attendance" id="teachers_attendance"
                                                type="checkbox" class="mx-2">Staff Attendance</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="teachers_attendanceActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="create" type="checkbox" class="mx-2 TACB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_attendance', 'create')}}>Take Attendance
                            </div>
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="view" type="checkbox" class="mx-2 TACB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_attendance', 'view')}}>View
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="edit" type="checkbox" class="mx-2 TACB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_attendance', 'edit')}}>Edit
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="delete" type="checkbox" class="mx-2 TACB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_attendance', 'delete')}}>Delete
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="teachers_salary" id="teachers_salary" type="checkbox" class="mx-2">Staff Salary</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="teachers_salaryActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="create" type="checkbox" class="mx-2 TSCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_salary', 'create')}}>Create Invoice
                            </div>
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="view" type="checkbox" class="mx-2 TSCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_salary', 'view')}}>View
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="edit" type="checkbox" class="mx-2 TSCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_salary', 'edit')}}>Edit
                            </div>
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="pdf" type="checkbox" class="mx-2 TSCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_salary', 'pdf')}}>Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="delete" type="checkbox" class="mx-2 TSCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'teachers_salary', 'delete')}}>Delete
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="timetable" id="timetable" type="checkbox" class="mx-2">Timetable</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="timetableActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="timetableActions[]" value="view" type="checkbox" class="mx-2 tbCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'timetable', 'view')}}>View
                            </div>
                            <div class="form-group">
                                <input name="timetableActions[]" value="delete" type="checkbox" class="mx-2 tbCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'timetable', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="timetableActions[]" value="create" type="checkbox" class="mx-2 tbCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'timetable', 'create')}}>Create
                            </div>
                            <div class="form-group">
                                <input name="timetableActions[]" value="pdf" type="checkbox" class="mx-2 tbCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'timetable', 'pdf')}}>Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="timetableActions[]" value="edit" type="checkbox" class="mx-2 tbCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'timetable', 'edit')}}>Edit
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="datesheet" id="datesheet" type="checkbox" class="mx-2">Datesheet</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="datesheetActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="datesheetActions[]" value="view" type="checkbox" class="mx-2 dsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'datesheet', 'view')}}>View
                            </div>
                            <div class="form-group">
                                <input name="datesheetActions[]" value="delete" type="checkbox" class="mx-2 dsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'datesheet', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="datesheetActions[]" value="create" type="checkbox" class="mx-2 dsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'datesheet', 'create')}}>Create
                            </div>  <div class="form-group">
                                <input name="datesheetActions[]" value="pdf" type="checkbox" class="mx-2 dsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'datesheet', 'pdf')}}>Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="datesheetActions[]" value="edit" type="checkbox" class="mx-2 dsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'datesheet', 'edit')}}>Edit
                            </div>

                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="report_categories" id="report_categories" type="checkbox" class="mx-2">Report Categories</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="report_categoriesActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="report_categoriesActions[]" value="create" type="checkbox" class="mx-2 rpCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'report_categories', 'create')}}>Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="report_categoriesActions[]" value="edit" type="checkbox" class="mx-2 rpCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'report_categories', 'edit')}}>Edit
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="report_categoriesActions[]" value="delete" type="checkbox" class="mx-2 rpCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'report_categories', 'delete')}}>Delete
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="reports" id="reports" type="checkbox" class="mx-2">Reports</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="reportsActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="reportsActions[]" value="create" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'create')}}>Create
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="print" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'print')}}>Print
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="post" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'post')}}>Post Report
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="delete_subject" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'delete_subject')}}>Delete Subject
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="reportsActions[]" value="edit" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'edit')}}>Edit
                            </div>
                            <div class="form-group">
                                <input  name="reportsActions[]" value="pdf" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'pdf')}}>Generate PDF
                            </div>
                            <div class="form-group">
                                <input  name="reportsActions[]" value="add_marks" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'add_marks')}}>Add Marks
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="reportsActions[]" value="view" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'view')}}>View
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="delete" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'reports', 'delete')}}>Delete
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="edit_subject" type="checkbox" class="mx-2 rpsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'edit_subject', 'delete')}}>Edit Subject
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="manage_roles" id="manage_roles" type="checkbox" class="mx-2">Manage Roles</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="manage_rolesActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="manage_rolesActions[]" value="create" type="checkbox" class="mx-2 mrCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'manage_roles', 'create')}}>Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="manage_rolesActions[]" value="delete" type="checkbox" class="mx-2 mrCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'manage_roles', 'delete')}}>Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="manage_rolesActions[]" value="edit" type="checkbox" class="mx-2 mrCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'manage_roles', 'edit')}}>Edit
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="fee" id="fee" type="checkbox" class="mx-2">Fee</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="feeActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="feeActions[]" value="create" type="checkbox" class="mx-2 feeCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'fee', 'create')}}>Create
                            </div>
                            <div class="form-group">
                                <input name="feeActions[]" value="print" type="checkbox" class="mx-2 feeCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'fee', 'print')}}>Print
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="feeActions[]" value="pdf" type="checkbox" class="mx-2 feeCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'fee', 'pdf')}}>Generate PDF
                            </div>
                            <div class="form-group">
                                <input name="feeActions[]" value="sms" type="checkbox" class="mx-2 feeCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'fee', 'sms')}}>Send SMS
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="feeActions[]" value="view" type="checkbox" class="mx-2 feeCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'fee', 'view')}}>View
                            </div>
                            <div class="form-group">
                                <input name="feeActions[]" value="delete" type="checkbox" class="mx-2 feeCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'fee', 'delete')}}>Delete
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="expense" id="expense" type="checkbox" class="mx-2">Expense</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="expenseActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="expenseActions[]" value="salary" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'salary')}}>View Salary Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="create" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'create')}}>Create Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="delete" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'delete')}}>Delete
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="total_summary" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'total_summary')}}>View Total Summary
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="expenseActions[]" value="monthly_summary" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'monthly_summary')}}>View Monthly Summary
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="fee" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'fee')}}>View Fee Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="edit" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'edit')}}>Edit Report
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="expenseActions[]" value="yearly_summary" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'yearly_summary')}}>View Yearly Summary
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="exp" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'exp')}}>View Expense Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="" type="checkbox" class="mx-2 expCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'expense', 'delete')}}>Generate PDF
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="sms" id="sms" type="checkbox" class="mx-2">SMS</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <input name="smsActions[]" value="test" type="checkbox" checked hidden>
                            <div class="form-group">
                                <input name="smsActions[]" value="create" type="checkbox" class="mx-2 smsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'sms', 'create')}}>Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="smsActions[]" value="view" type="checkbox" class="mx-2 smsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'sms', 'view')}}>View
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="smsActions[]" value="delete" type="checkbox" class="mx-2 smsCB"
                                        {{\App\Http\Helpers\permissionsHelper::checkValidation($permissions, 'sms', 'delete')}}>Delete
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        {!! Form::button('Save', ['type'=>'submit', 'class'=>'btn peach-gradient btn-block mb-3']) !!}
    </div>
    {!! Form::close() !!}



@stop