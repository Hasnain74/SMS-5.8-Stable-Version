<div class="header-backgroud">
<nav class="navbar navbar-expand-md navbar-light" id="main-nav">
    <div class="ml-2">
        @include('includes.school_profile')

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav">
                @permission('edit.teachers_account|delete.teachers_account|delete.teachers_roles')
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('admin.teachers.index')}}" class="nav-link text-white">Manage Staff</a>
                </li>
                @endpermission
                @permission('edit.teachers_account|delete.teachers_account')
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('teacher_accounts')}}" class="nav-link text-white">Staff Accounts</a>
                </li>
                @endpermission
                @permission('delete.teachers_roles')
                <li class="nav-item d-lg-none d-md-none">
                    <a href="{{route('teacher_roles')}}" class="nav-link text-white">Staff Roles</a>
                </li>
                @endpermission
            </ul>
        </div>

    </div>
    <button class="navbar-toggler bg-light" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>