<!--Main Page-->
<div class="header-backgroud">
    <nav class="navbar navbar-expand-md navbar-light" id="main-nav">
        <div class="ml-2">
            @include('includes.school_profile')

            {{-- <div class="collapse navbar-collapse" id="navbarCollapse">
                @permission('create.teachers_salary|delete.teachers_salary|edit.teachers_salary|
                view.teachers_salary')
                <ul class="navbar-nav">
                    <li class="nav-item d-lg-none d-md-none">
                        <a  href="{{route('index')}}" class="nav-link">Teachers Salary</a>
                    </li>
                    <li class="nav-item d-lg-none d-md-none">
                        <a href="{{route('manage_salary_invoice')}}" class="nav-link">Manage Invoice</a>
                    </li>
                </ul>
                @endpermission
            </div> --}}

        </div>
    </nav>