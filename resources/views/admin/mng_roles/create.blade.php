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

                    <h1><i class="fas fa-user-circle"></i> Create Role</h1>
                </div>
            </div>

        </div>
    </header>
    </div>

    {!! Form::open(['method'=>'POST', 'action'=>'RolesController@store', 'class' => 'py-4 mx-4']) !!}
    @include('includes.form_errors')
    <!--CLASS DETAIL-->
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="md-form md-outline">
                <label class="control-label">Role Name <span style="color: red">*</span></label>
                {!! Form::text('name', null, ['class'=>'form-control']) !!}
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
                                <input name="dashboardActions[]" value="view" type="checkbox" class="mx-2 dsCB">View
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
                            <div class="form-group">
                                <input name="studentsActions[]" value="view" type="checkbox" class="mx-2 stdCB">View
                            </div>
                            <div class="form-group">
                                <input name="studentsActions[]" value="delete" type="checkbox" class="mx-2 stdCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="studentsActions[]" value="create" type="checkbox" class="mx-2 stdCB">Create
                            </div>
                            <div class="form-group">
                                <input name="studentsActions[]" value="pdf" type="checkbox" class="mx-2 stdCB">Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="studentsActions[]" value="edit" type="checkbox" class="mx-2 stdCB">Edit
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
                            <div class="form-group">
                                <input name="students_accountActions[]" value="delete" type="checkbox" class="mx-2 saCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="students_accountActions[]" value="edit" type="checkbox" class="mx-2 saCB">Edit
                            </div>
                        </div>
                    </div>

                </td>
            <tr>
                <td class="align-middle"><input name="modules[]" value="attendance" id="attendance" type="checkbox" class="mx-2">Students Attendance</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <div class="form-group">
                                <input name="attendanceActions[]" value="create" type="checkbox" class="mx-2 atdCB">Take Attendance
                            </div>
                            <div class="form-group">
                                <input name="attendanceActions[]" value="delete" type="checkbox" class="mx-2 atdCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="attendanceActions[]" value="edit" type="checkbox" class="mx-2 atdCB">Edit
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="attendanceActions[]" value="view" type="checkbox" class="mx-2 atdCB">View
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
                            <div class="form-group">
                                <input name="students_classesActions[]" value="create" type="checkbox" class="mx-2 clsCB">Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="students_classesActions[]" value="edit" type="checkbox" class="mx-2 clsCB">Edit
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
                            <div class="form-group">
                                <input name="teachersActions[]" value="create" type="checkbox" class="mx-2 tchCB">Create
                            </div>
                            <div class="form-group">
                                <input name="teachersActions[]" value="delete" type="checkbox" class="mx-2 tchCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachersActions[]" value="edit" type="checkbox" class="mx-2 tchCB">Edit
                            </div>
                            <div class="form-group">
                                <input name="teachersActions[]" value="pdf" type="checkbox" class="mx-2 tchCB">Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachersActions[]" value="view" type="checkbox" class="mx-2 tchCB">View
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
                            <div class="form-group">
                                <input name="teachers_accountActions[]" value="delete" type="checkbox" class="mx-2 taCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_accountActions[]" value="edit" type="checkbox" class="mx-2 taCB">Edit
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
                            <div class="form-group">
                                <input name="teachers_rolesActions[]" value="delete" type="checkbox" class="mx-2 trCB">Delete
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
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="create" type="checkbox" class="mx-2 TACB">Take Attendance
                            </div>
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="view" type="checkbox" class="mx-2 TACB">View
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="edit" type="checkbox" class="mx-2 TACB">Edit
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_attendanceActions[]" value="delete" type="checkbox" class="mx-2 TACB">Delete
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            <tr>
                <td class="align-middle"><input name="modules[]" value="teachers_salary" id="teachers_salary"
                                                type="checkbox" class="mx-2">Staff Salary</td>
                <td>
                    <div class="row align-items-start">
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="create" type="checkbox" class="mx-2 TSCB">Create Invoice
                            </div>
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="view" type="checkbox" class="mx-2 TSCB">View
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="edit" type="checkbox" class="mx-2 TSCB">Edit
                            </div>
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="pdf" type="checkbox" class="mx-2 TSCB">Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="teachers_salaryActions[]" value="delete" type="checkbox" class="mx-2 TSCB">Delete
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
                            <div class="form-group">
                                <input name="timetableActions[]" value="view" type="checkbox" class="mx-2 tbCB">View
                            </div>
                            <div class="form-group">
                                <input name="timetableActions[]" value="delete" type="checkbox" class="mx-2 tbCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="timetableActions[]" value="create" type="checkbox" class="mx-2 tbCB">Create
                            </div>
                            <div class="form-group">
                                <input name="timetableActions[]" value="pdf" type="checkbox" class="mx-2 tbCB">Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="timetableActions[]" value="edit" type="checkbox" class="mx-2 tbCB">Edit
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
                            <div class="form-group">
                                <input name="datesheetActions[]" value="view" type="checkbox" class="mx-2 dsCB">View
                            </div>
                            <div class="form-group">
                                <input name="datesheetActions[]" value="delete" type="checkbox" class="mx-2 dsCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="datesheetActions[]" value="create" type="checkbox" class="mx-2 dsCB">Create
                            </div>  <div class="form-group">
                                <input name="datesheetActions[]" value="pdf" type="checkbox" class="mx-2 dsCB">Generate PDF
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="datesheetActions[]" value="edit" type="checkbox" class="mx-2 dsCB">Edit
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
                            <div class="form-group">
                                <input name="report_categoriesActions[]" value="create" type="checkbox" class="mx-2 rpCB">Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="report_categoriesActions[]" value="edit" type="checkbox" class="mx-2 rpCB">Edit
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="report_categoriesActions[]" value="delete" type="checkbox" class="mx-2 rpCB">Delete
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
                            <div class="form-group">
                                <input name="reportsActions[]" value="create" type="checkbox" class="mx-2 rpsCB">Create Subjects
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="print" type="checkbox" class="mx-2 rpsCB">Print
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="post" type="checkbox" class="mx-2 rpsCB">Post Report
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="delete_subject" type="checkbox" class="mx-2 rpsCB">Delete Subject
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="reportsActions[]" value="edit" type="checkbox" class="mx-2 rpsCB">Edit
                            </div>
                            <div class="form-group">
                                <input  name="reportsActions[]" value="pdf" type="checkbox" class="mx-2 rpsCB">Generate PDF
                            </div>
                            <div class="form-group">
                                <input  name="reportsActions[]" value="add_marks" type="checkbox" class="mx-2 rpsCB">Add Marks
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="reportsActions[]" value="view" type="checkbox" class="mx-2 rpsCB">View
                            </div>
                            <div class="form-group">
                                <input name="reportsActions[]" value="delete" type="checkbox" class="mx-2 rpsCB">Delete
                            </div>
                            <div class="form-group">
                                <input  name="reportsActions[]" value="edit_subject" type="checkbox" class="mx-2 rpsCB">Edit Subject
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
                            <div class="form-group">
                                <input name="manage_rolesActions[]" value="create" type="checkbox" class="mx-2 mrCB">Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="manage_rolesActions[]" value="delete" type="checkbox" class="mx-2 mrCB">Delete
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="manage_rolesActions[]" value="edit" type="checkbox" class="mx-2 mrCB">Edit
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
                            <div class="form-group">
                                <input name="feeActions[]" value="create" type="checkbox" class="mx-2 feeCB">Create
                            </div>
                            <div class="form-group">
                                <input name="feeActions[]" value="print" type="checkbox" class="mx-2 feeCB">Print
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="feeActions[]" value="pdf" type="checkbox" class="mx-2 feeCB">Generate PDF
                            </div>
                            <div class="form-group">
                                <input name="feeActions[]" value="sms" type="checkbox" class="mx-2 feeCB">Send SMS
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="feeActions[]" value="view" type="checkbox" class="mx-2 feeCB">View
                            </div>
                            <div class="form-group">
                                <input name="feeActions[]" value="delete" type="checkbox" class="mx-2 feeCB">Delete
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
                            <div class="form-group">
                                <input name="expenseActions[]" value="salary" type="checkbox" class="mx-2 expCB">View Salary Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="create" type="checkbox" class="mx-2 expCB">Create Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="delete" type="checkbox" class="mx-2 expCB">Delete
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="total_summary" type="checkbox" class="mx-2 expCB">View Total Summary
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="expenseActions[]" value="monthly_summary" type="checkbox" class="mx-2 expCB">View Monthly Summary
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="fee" type="checkbox" class="mx-2 expCB">View Fee Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="edit" type="checkbox" class="mx-2 expCB">Edit Report
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="expenseActions[]" value="yearly_summary" type="checkbox" class="mx-2 expCB">View Yearly Summary
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="exp" type="checkbox" class="mx-2 expCB">View Expense Report
                            </div>
                            <div class="form-group">
                                <input name="expenseActions[]" value="pdf" type="checkbox" class="mx-2 expCB">Generate PDF
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
                            <div class="form-group">
                                <input name="smsActions[]" value="create" type="checkbox" class="mx-2 smsCB">Create
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="smsActions[]" value="view" type="checkbox" class="mx-2 smsCB">View
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <input name="smsActions[]" value="delete" type="checkbox" class="mx-2 smsCB">Delete
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