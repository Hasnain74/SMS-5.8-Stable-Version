<!--Main Page-->
<div class="header-backgroud">
<nav class="navbar navbar-expand-md navbar-light">
    <div class="ml-2">
        @include('includes.school_profile')

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">
                @permission('delete.students_account|edit.students_account')
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('admin.students.index')}}" class="nav-link text-white">Manage Students</a>
                </li>

                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('student_accounts')}}" class="nav-link text-white">Student Accounts</a>
                </li>

                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('student_promotions')}}" class="nav-link text-white">Student Promotions</a>
                </li>
                @endpermission
            </ul>
        </div>

    </div>

    <button class="navbar-toggler bg-light" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
