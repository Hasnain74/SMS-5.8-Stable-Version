$(document).ready(function () {

    //------------------------------------------------------------------------//
    //-------------------------Common in All--------------------------------//
    //------------------------------------------------------------------------//

   
    $('.export_table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel'
        ]
    });


    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $('#table').DataTable();
    $('#studentsData_teachers_attendance').DataTable();
    $('#studentsData_attendance_register').DataTable();
    $('#datesheet').DataTable();
    $('#timetable').DataTable();
    $('#studentsData_index_reports').DataTable();
    $('#studentsData_sms_index').DataTable();

    $('body').on('click', '#options', function() {
        if( $(this).is(':checked') )
        {
            $('.checkBoxes').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $('.checkBoxes').each(function() {
                $(this).prop('checked', false);
            });
        }
    });

    //------------------------------------------------------------------------//
    //----------------------------Students Index-----------------------------//
    //------------------------------------------------------------------------//

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(".deleteStudents").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/students/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });


    //------------------------------------------------------------------------//
    //----------------------------Students Create And edit-------------------//
    //------------------------------------------------------------------------//

    jQuery(document).ready(function () {

        $('.discount').keyup(function () {
            var price = parseInt($('#default_fee').val());
            var discount_percent = parseFloat($(this).val()) / 100;
            var final_price = "";
            if (isNaN(discount_percent) || isNaN(price)) {
                final_price = parseInt($('#default_fee').val());
            } else {
                final_price = price - (price * discount_percent);
            }
            $('#final_fee').val(final_price);
        });

    });

    jQuery(document).ready(function () {
        jQuery('select[id="class_id"]').on('change',function(){
            var ClassID = jQuery(this).val();
            if(ClassID)
            {
                jQuery.ajax({
                    url : '/ajaxFee/' +ClassID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('input[name="default_fee"]').empty();
                        jQuery.each(data, function(key,value){
                            $('#default_fee').val(value);
                            $('#final_fee').val(value);
                        });
                    }
                });
            }
            else {
                $('input[name="default_fee"]').empty();
            }
        });
    });

    jQuery(document).ready(function() {
        jQuery('select[id="class_id_edit_form"]').on('change', function() {
            var ClassID = jQuery(this).val();
            if (ClassID) {
                jQuery.ajax({
                    url: '/ajaxFee/' + ClassID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        jQuery('input[name="default_fee"]').empty();
                        jQuery.each(data, function(key, value) {
                            $('#default_fee').val(value);

                            if ($('#final_fee').attr('data-total-fee')) {
                                $('#final_fee').val($('#final_fee').attr('data-total-fee'));
                                $('#final_fee').removeAttr('data-total-fee');
                            } else {
                                $('#final_fee').val(value);
                            }

                        });
                    }
                });
            }
        }).trigger('change');
    });


    //------------------------------------------------------------------------//
    //--------------------------Students Create Attendance-------------------//
    //------------------------------------------------------------------------//

    $('select[name="class_id_create_attendance"]').on('change', function() {
        var classID = $(this).val();
        if(classID) {

            $.ajax({

                url: '/myform/ajax/'+classID,
                type: "GET",
                dataType: "json",
                success:function(data) {

                    var attendance = `<div class="form-group">
                        <select class="browser-default custom-select" id="attendance" name="attendance[]">
                            <option>Present</option>
                            <option>Absent</option>
                            <option>Leave</option>
                        </select>
                    	</div>`;

                    var markup = '';
                    markup += '<tr> <th class="text-center">Student ID</th> <th class="text-center">Student Name</th> <th class="text-center">Attendance</th> <tr>';

                    $.each(data, function(key, value) {

                        markup += '<tr> <td class="text-center"><input type="hidden" value="'+value.student_id+'" name="student_id[]">' + value.student_id + '</td> <td class="text-center"><input type="hidden" value="'+value.first_name+'" name="first_name[]"><input type="hidden" value="'+value.last_name+'" name="last_name[]">' + value.first_name+ ' '  + value.last_name  + '</td> <td class="text-center"> ' +  attendance + '</td> <tr>';

                    });
                    $('table[id="create_attendance_table"]').html(markup);
                }
            });
        }
    });

    $('body').on('click', '#save-btn', function(e) {
        e.preventDefault();
        var data = $('#create_attendance_table').find('select, input').serialize();
        data = {
            data: data,
            class_id: $('[name="class_id_create_attendance"]').val(),
            date: $('[name="date"]').val(),
            attendance_type: $('[name="attendance_type"]').val(),
        };

        $.post('/students/attendance', data, function(response) {
            window.location.href = response;
        });
    });



  //------------------------------------------------------------------------//
    //-------------------------Students PROMORIONS-------------------//
    //------------------------------------------------------------------------//


    $('select[name="from_class_id"]').on('change', function() {
        var classID = $(this).val();
        if(classID) {

            $.ajax({

                url: '/students/student_promotions_ajax/'+classID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    var markup = '';
                    markup += '<tr> <th class="align-middle text-center"><input type="checkbox" id="options"></th> <th class="text-center">Student ID</th> <th class="text-center">Student Name</th> <th class="text-center">Class</th> <tr>';

                    $.each(data, function(key, value) {

                        markup += '<tr>  <td class="text-center"><input class="checkBoxes align-middle" type="checkbox" name="checkBoxArray[]" value="'+value.student_id+'"></td>          <td class="text-center"><input type="hidden" value="'+value.student_id+'" name="student_id[]">' + value.student_id + '</td> <td class="text-center"><input type="hidden" value="'+value.first_name+'" name="first_name[]"><input type="hidden" value="'+value.last_name+'" name="last_name[]">' + value.first_name+ ' '  + value.last_name  + '<td class="text-center"><input type="hidden" value="'+value.students_class_id+'" name="students_class_id[]">' + value.students_class_name + '</td>' +  '<tr>';

                    });
                    $('table[id="promotion_table"]').html(markup);
                }
            });
        }
    });

    $('body').on('click', '#save-student-promotion-btn', function(e) {
        e.preventDefault();
        var data = [];

        $('.checkBoxes').each(function(idx, el){
            if($(el).is(':checked'))
            { 
                data.push($(el).val())
            }
    
        });   
        data = {
            data: data,
            to_class_id: $('[name="to_class_id"]').val(),
        };

        $.post('/students/students_promotions', data, function(response) {
            window.location.href = response;
        });
    });


    //------------------------------------------------------------------------//
    //-------------------------Students Attendance Register-------------------//
    //------------------------------------------------------------------------//


    $(".deleteAttendance").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/attendance/delete",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });

    // Change events of both student and class
    $('select[name="class_id_attendance_register"], select[name="student_id_attendance_register"]').on('change', function() {
        LaravelDataTables.dataTableBuilder.ajax.reload();
    });

    $('table[id="studentsData_attendance_register"]').on('click', '#options', function() {

        if( $(this).is(':checked') )
        {
            $('.checkBoxes').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $('.checkBoxes').each(function() {
                $(this).prop('checked', false);
            });
        }

    });


    //------------------------------------------------------------------------//
    //-------------------------Teachers Index--------------------------------//
    //------------------------------------------------------------------------//

    $(".deleteTeachers").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/teachers/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });


    //------------------------------------------------------------------------//
    //-------------------------Teachers Attendance----------------------------//
    //------------------------------------------------------------------------//

    $(".deleteAtd").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/teachers/attendance/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });



    $('select[name="teacher_id_teachers_attendance"]').on('change', function() {
        var Teacher_id = $(this).val();
        if (Teacher_id) {

            $.ajax({

                url: '/teacher_attendance/ajax/' + Teacher_id,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="studentsData_teachers_attendance"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th style="width: 15%" class="text-center">Staff ID</th> <th style="width: 15%" class="text-center">Staff Name</th> <th style="width: 15%" class="text-center">Date</th> <th style="width: 15%" class="text-center">Attendance</th> <th style="width: 15%;" class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {

                        markup += '<tr> <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class="text-center align-middle"><input type="hidden" value="' + value.teacher_id + '" name="teacher_id[]">' + value.teacher_id + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.teacher_name + '" name="teacher_name[]">' + value.teacher_name + '<td class="text-center align-middle"><input type="hidden" value="' + value.date + '" name="date[]">' + value.date + '</td>' +'<td class="text-center align-middle"><input type="hidden" value="' + value.attendance + '" name="attendance[]">' + value.attendance + '</td>' + '<td style=" width=12%" class="text-center"> <a data-toggle="modal" data-target="#editAtdModal' + value.id + '"""><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a>  <a data-toggle="modal" data-target="#deleteAtdModal' + value.id + '"><button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button></a> </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    var table = $('table[id="studentsData_teachers_attendance"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });

    //------------------------------------------------------------------------//
    //-------------------------Teachers Salary--------------------------------//
    //------------------------------------------------------------------------//
    

    $('#table_teachers_salary').DataTable();

    $('select[name="teacher_id_teachers_salary"]').on('change', function() {
        var Teacher_id = $(this).val();
        if (Teacher_id) {

            $.ajax({

                url: '/teacher_salary/ajaxSalaryList/' + Teacher_id,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="table_teachers_salary"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th class="text-center align-middle">Invoice No</th> <th class="text-center">Staff ID</th> <th class="text-center">Staff Name</th> <th class="text-center">Issue Date</th> <th class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {

                        markup += '<tr> <td><input class="checkBoxes text-center align-middle" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class="text-center align-middle"><input type="hidden" value="' + value.invoice_no + '" name="invoice_no[]">' + value.invoice_no + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.teacher_id + '" name="teacher_id[]">' + value.teacher_id + '<td class="text-center align-middle"><input type="hidden" value="' + value.teacher_name + '" name="teacher_name[]">' + value.teacher_name + '</td>' +'<td class="text-center align-middle"><input type="hidden" value="' + value.date + '" name="date[]">' + value.date + '</td>' + '<td style=" width=12%" class="text-center"> <a data-toggle="modal" data-target="#editModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a data-toggle="modal" data-target="#viewModal' + value.id + '"><button title="Edit" class="btn btn-warning"><span class="fas fa-eye"></span></button></a>  <a data-toggle="modal" data-target="#deleteInvModal' + value.id + '"><button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button></a> </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    var table = $('table[id="table_teachers_salary"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });

    $(".deleteInv_teachers_salary").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax({
            url: "/inv/delete",
            type: 'DELETE',
            dataType: "JSON",
            data: {
                "_token": token,
                "checkBoxArray": checkBoxArray,
            },
        });
    });

    jQuery(document).ready(function ()
    {

    jQuery('select[name="teacher_id"]').on('change',function(){
        var TeacherID = jQuery(this).val();
        if(TeacherID)
        {
            jQuery.ajax({
                url : '/teachers/ajaxName/' + TeacherID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    console.log(data);
                    jQuery('select[name="teacher_name"]').empty();
                    jQuery.each(data, function(key,value){
                        $('input[name="teacher_name"]').val(value);
                    });
                }
            });
        }
        else
        {
            $('input[name="teacher_name"]').empty();
        }
    });


        jQuery('select[name="teacher_id"]').on('change',function(){
        var TeacherID = jQuery(this).val();
        var date = $('#date').val();
        if(TeacherID) {
            jQuery.ajax({
                url : '/ajaxTeachersAttendance/',
                type : "GET",
                dataType : "json",
                data: { 
                    teacher_id: TeacherID,
                    date: date
                },
                success:function(data) {
                    console.log(data);
                    jQuery('input[name="absent_days"]').empty();
                    jQuery.each(data, function(key,value){
                        $('input[name="absent_days"]').val(value);
                    });
                }
            });
        } else {
            $('input[name="absent_days"]').empty();
            }
        });

        jQuery('select[name="teacher_id"]').on('change',function(){
            var TeacherID = jQuery(this).val();
            if(TeacherID) {
                jQuery.ajax({
                    url : '/teacherPhNo/' + TeacherID,
                    type : "GET",
                    dataType : "json",
                    success:function(data) {
                        console.log(data);
                        jQuery('input[name="teacherPhNo"]').empty();
                        jQuery.each(data, function(key,value){
                            $('input[name="teacherPhNo"]').val(value);
                        });
                    }
                });
            } else {
                $('input[name="teacherPhNo"]').empty();
            }
        });

        jQuery('select[name="teacher_id"]').on('change',function(){
            var TeacherID = jQuery(this).val();
            if(TeacherID)
            {
                jQuery.ajax({
                    url : '/teachers/ajaxSalary/' + TeacherID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[name="payable_amount"]').empty();
                        setTimeout(function(){
                            jQuery.each(data, function(key,value){
                                $('input[name="payable_amount"]').val( value );
                                $('input[name="paid_amount"]').val( value );
                            });
                        }, 500);
                    }
                });
            }
            else
            {
                $('input[name="payable_amount"]').empty();
                $('input[name="paid_amount"]').empty();
            }
        });


    });


    //------------------------------------------------------------------------//
    //-------------------------Timetable-------------------------------------//
    //------------------------------------------------------------------------//


    $(".deleteTb").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/timetable/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });


    $('select[name="class_id_timetable"]').on('change', function() {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/timetable/ajax/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {

                    var markup = '';
                    markup = '<tr><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th style="width: 10%" class="text-center align-middle">Period</th> <th style="width: 10%" class="text-center align-middle">Monday</th> <th style="width: 10%" class="text-center align-middle">Tuesday</th> <th style="width: 10%" class="text-center align-middle">Wednesday</th> <th style="width: 10%" class="text-center align-middle">Thursday</th> <th style="width: 10%" class="text-center align-middle">Friday</th><th style="width: 10%" class="text-center align-middle">Saturday</th><th style="width: 10%;" class="align-middle text-center">Edit</th><tr>';

                    $.each(data, function (key, value) {

                        markup += '<tr> <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class=" align-middle"><input type="hidden" value="' + value.period + '" name="period[]">' + value.period + '</td> <td class=" align-middle"><input type="hidden" value="' + value.monday + '" name="monday[]">' + value.monday + '</td> <td class=" align-middle"><input type="hidden" value="' + value.thursday + '" name="thursday[]">' + value.thursday + '<td class=" align-middle"><input type="hidden" value="' + value.wednesday + '" name="wednesday[]">' + value.wednesday + '</td>' + '<td class=" align-middle"><input type="hidden" value="' + value.thursday + '" name="thursday[]">' + value.thursday + '</td>' + '<td class=" align-middle"> <input type="hidden" value="' + value.friday + '" name="friday[]">' + value.friday + '</td>' + '<td class=" align-middle"><input type="hidden" value="' + value.saturday + '" name="saturday[]">' + value.saturday + '</td>' + '<td style=" width=12%" class="text-center d-flex">  <a data-toggle="modal" data-target="#editTimetableModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a data-toggle="modal" data-target="#viewTimetableModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a> </td>' + '</td> <tr>';

                    });
                    $('table[id="timetable"]').html(markup);
                }
            });
        }

    });

    // FOR CHECKBOXES
    $('table[id="timetable"]').on('click', '#options', function() {

        if( $(this).is(':checked') )
        {
            $('.checkBoxes').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            $('.checkBoxes').each(function() {
                $(this).prop('checked', false);
            });
        }

    });


    //------------------------------------------------------------------------//
    //-------------------------Datesheet-------------------------------------//
    //------------------------------------------------------------------------//


    $(".deleteDs").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/datesheet/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });

    //FOR LOADING STUDENTS
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select[name="class_id_datesheet"]').on('change', function() {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/datesheet/ajax/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {

                    var markup = '';
                    markup = '<tr><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th style="width: 10%" class="text-center align-middle">Monday</th> <th style="width: 10%" class="text-center align-middle">Tuesday</th> <th style="width: 10%" class="text-center align-middle">Wednesday</th> <th style="width: 10%" class="text-center align-middle">Thursday</th> <th style="width: 10%" class="text-center align-middle">Friday</th><th style="width: 10%" class="text-center align-middle">Saturday</th><th style="width: 10%;" class="align-middle text-center">Edit</th><tr>';

                    $.each(data, function (key, value) {

                        markup += '<tr> <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class=" align-middle"><input type="hidden" value="' + value.monday + '" name="monday[]">' + value.monday + '</td> <td class=" align-middle"><input type="hidden" value="' + value.thursday + '" name="thursday[]">' + value.thursday + '<td class=" align-middle"><input type="hidden" value="' + value.wednesday + '" name="wednesday[]">' + value.wednesday + '</td>' + '<td class="align-middle"><input type="hidden" value="' + value.thursday + '" name="thursday[]">' + value.thursday + '</td>' + '<td class="align-middle"><input type="hidden" value="' + value.friday + '" name="friday[]">' + value.friday + '</td>' + '<td class="align-middle"><input type="hidden" value="' + value.saturday + '" name="saturday[]">' + value.saturday + '</td>' + '<td style=" width=12%" class="text-center d-flex"> <a data-toggle="modal" data-target="#editDatesheetModal' + value.id + '"""><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a data-toggle="modal" data-target="#viewModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a> </td>' + '</td> <tr>';

                    });
                    $('table[id="datesheet"]').html(markup);
                }
            });
        }

    });

    // FOR CHECKBOXES
    $('table[id="datesheet"]').on('click', '#options', function() {

        if(this.checked) {
            $('.checkBoxes').each(function() {
                this.checked = true;
            });
        } else {
            $('.checkBoxes').each(function() {
                this.checked = false;
            });
        }

    });


    //------------------------------------------------------------------------//
    //-------------------------Craete Reports--------------------------------//
    //------------------------------------------------------------------------//

        jQuery('select[id="class_id_create_reports"]').on('change',function(){
            var ClassID = jQuery(this).val();
            if(ClassID)
            {
                jQuery.ajax({
                    url : '/reports/ajaxID/' +ClassID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[id="student_id_create_reports"]').empty();
                        $('select[id="student_id_create_reports"]').append('<option value=""> Choose ID </option>');
                        jQuery.each(data, function(key,value){
                            $('select[id="student_id_create_reports"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }
            else
            {
                $('select[id="student_id"]').empty();
            }
        });

        jQuery('select[id="student_id_create_reports"]').on('change',function(){
            var StudentID = jQuery(this).val();
            if(StudentID)
            {
                jQuery.ajax({
                    url : '/reports/ajaxName/' + StudentID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[id="student_name_create_reports"]').empty();
                        jQuery.each(data, function(key,value){
                            $('input[id="student_name_create_reports"]').val(value);
                        });
                    }
                });
            }
            else
            {
                $('input[id="student_name_create_reports"]').empty();
            }
    });



    //------------------------------------------------------------------------//
    //-------------------------Index Reports----------------------------------//
    //------------------------------------------------------------------------//

    $('.val').keyup(function() {
        var parent = $(this).closest('div').parent();

        var Tmarks = parseInt($('.Tmarks_edit_student_reports', parent).val());
        var Omarks = parseInt($('.Omarks_edit_student_reports', parent).val());
        var percentage = "";

        if (isNaN(Tmarks) || isNaN(Omarks)) {
            percentage = " ";
        } else {
            percentage = ((Omarks / Tmarks) * 100).toFixed(2);
        }

        $('.percentage_edit_student_reports', parent).val(percentage);
    });

    $(".delete_students_report").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];
        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/reports/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });

    $('select[name="class_id_index_reports"]').on('change', function () {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/reports/ajax/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="studentsData_index_reports"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th class="text-center">Student ID</th> <th class="text-center">Student Name</th> <th class="text-center">Class</th> <th class="text-center">Report Type</th> <th class="text-center">Subject</th> <th class="text-center">Obatined Marks</th> <th class="text-center">Date</th> <th class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {

                        markup += '<tr> <td class="text-center"><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_id + '" name="student_id[]">' + value.student_id + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_name + '" name="student_name[]">' + value.student_name + '<td class="text-center align-middle"><input type="hidden" value="' + value.class_name + '" name="class_name[]">' + value.class_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.report_categories_name + '" name="report_categories_name[]">' + value.report_categories_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.subject + '" name="subject[]">' + value.subject + '</td>' + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.obtained_marks + '" name="obtained_marks[]">' + value.obtained_marks + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.date + '" name="date[]">' + value.date +   '<td style=" width=12%" class="text-center"> <a  data-toggle="modal" data-target="#editReportModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a  data-toggle="modal" data-target="#viewReportModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a><a  data-toggle="modal" data-target="#deleteReportModal' + value.id + '"><button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button></a>  </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    var table = $('table[id="studentsData_index_reports"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });

    $('select[name="student_id_index_reports"]').on('change', function () {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/rpt/ajax/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="studentsData_index_reports"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th class="text-center">Student ID</th> <th class="text-center">Student Name</th> <th class="text-center">Class</th> <th class="text-center">Report Type</th> <th class="text-center">Subject</th> <th class="text-center">Obatined Marks</th> <th class="text-center">Date</th> <th class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {

                        markup += '<tr> <td class="text-center"><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_id + '" name="student_id[]">' + value.student_id + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_name + '" name="student_name[]">' + value.student_name + '<td class="text-center align-middle"><input type="hidden" value="' + value.class_name + '" name="class_name[]">' + value.class_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.report_categories_name + '" name="report_categories_name[]">' + value.report_categories_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.subject + '" name="subject[]">' + value.subject + '</td>' +  '<td class="text-center align-middle"><input type="hidden" value="' + value.obtained_marks + '" name="obtained_marks[]">' + value.obtained_marks + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.date + '" name="date[]">' + value.date + '</td>' +  '<td style=" width=12%" class="text-center"> <a  data-toggle="modal" data-target="#editReportModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a  data-toggle="modal" data-target="#viewReportModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a><a  data-toggle="modal" data-target="#deleteReportModal' + value.id + '"><button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button></a>  </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    var table = $('table[id="studentsData_index_reports"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });

    //------------------------------------------------------------------------//
    //-------------------------Subjects ------------------------------------//
    //------------------------------------------------------------------------//

    $('#subjects').DataTable();

    $('select[name="class_id_subjects"]').on('change', function () {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/subjects/ajax/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="subjects"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><th class="text-center">Subject Name</th> <th class="text-center">Subject Teacher</th> <th class="text-center">Report Name</th> <th class="text-center">Subject Marks</th> <th class="text-center">Class</th> <th class="align-middle text-center">Actions</th> </tr></thead><tbody>';
                    $.each(data, function (key, value) {

                        markup += '<tr> <td class="text-center align-middle"><input type="hidden" value="' + value.subject_name + '" name="subject_name[]">' + value.subject_name + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.subject_teacher + '" name="subject_teacher[]">' + value.subject_teacher + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.report_type_name + '" name="report_type_name[]">' + value.report_type_name + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.subject_marks + '" name="subject_marks[]">' + value.subject_marks + '<td class="text-center align-middle"><input type="hidden" value="' + value.class_name + '" name="class_name[]">' + value.class_name + '</td>'  +  '<td style=" width=12%" class="text-center"> <a  data-toggle="modal" data-target="#edit' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a href="http://127.0.0.1:8000/add_marks?' + value.id + '"><button title="Add Marks" class="btn  purple-gradient"><span class="fas fa-clipboard-list"></span></button></a> <a  data-toggle="modal" data-target="#deleteSubject' + value.id + '"><button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button></a> </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    var table = $('table[id="subjects"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });


    $('tbody[id="checkboxes"]').on('click', '#students', function() {

        if(this.checked) {
            $('.stdCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.stdCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#students_classes', function() {

        if(this.checked) {
            $('.clsCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.clsCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#teachers', function() {

        if(this.checked) {
            $('.tchCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.tchCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#timetable', function() {

        if(this.checked) {
            $('.tbCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.tbCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#report_categories', function() {

        if(this.checked) {
            $('.rpCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.rpCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#reports', function() {

        if(this.checked) {
            $('.rpsCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.rpsCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#manage_roles', function() {

        if(this.checked) {
            $('.mrCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.mrCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#fee', function() {

        if(this.checked) {
            $('.feeCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.feeCB').each(function() {
                this.checked = false;
            });
        }

    });


    $('tbody[id="checkboxes"]').on('click', '#attendance', function() {

        if(this.checked) {
            $('.atdCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.atdCB').each(function() {
                this.checked = false;
            });
        }

    });

    $('tbody[id="checkboxes"]').on('click', '#datesheet', function() {

        if(this.checked) {
            $('.dsCB').each(function() {
                this.checked = true;
            });
        } else {
            $('.dsCB').each(function() {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#teachers_attendance', function() {

        if (this.checked) {
            $('.TACB').each(function () {
                this.checked = true;
            });
        } else {
            $('.TACB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#teachers_salary', function() {

        if (this.checked) {
            $('.TSCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.TSCB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#expense', function() {

        if (this.checked) {
            $('.expCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.expCB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#sms', function() {

        if (this.checked) {
            $('.smsCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.smsCB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#students_account', function() {

        if (this.checked) {
            $('.saCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.saCB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#teachers_account', function() {

        if (this.checked) {
            $('.taCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.taCB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#teachers_roles', function() {

        if (this.checked) {
            $('.trCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.trCB').each(function () {
                this.checked = false;
            });
        }
    });

    $('tbody[id="checkboxes"]').on('click', '#dashboard', function() {

        if (this.checked) {
            $('.dsCB').each(function () {
                this.checked = true;
            });
        } else {
            $('.dsCB').each(function () {
                this.checked = false;
            });
        }
    });


    //------------------------------------------------------------------------//
    //-------------------------Fee For Index----------------------------------//
    //------------------------------------------------------------------------//

    $('#studentsData_fee_index').DataTable();


    $('.paid_amount, .concession').keyup(function () {
        var parent = $(this).closest('form');
        var paid_amount = parseInt( parent.find('.paid_amount').val() );
        var concession = parseInt( parent.find('.concession').val() );
        var amount = parent.find('.amount');
        var final_amount = parseInt( amount.data('amount'));

        if(paid_amount > 0){
            final_amount = final_amount - paid_amount;
        }

        if(concession > 0){
            final_amount = final_amount - concession;
        }

        amount.val(final_amount);
    });



    $('select[name="class_id_fee_index"]').on('change', function () {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/fee/ajax/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="studentsData_fee_index"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th style="width: 10%" class="text-center">Invoice ID</th> <th style="width: 10%" class="text-center">Student ID</th> <th style="width: 15%" class="text-center">Student Name</th> <th style="width: 5%" class="text-center">Total Amount</th> <th style="width: 10%" class="text-center align-middle">Status</th> <th style="width: 10%" class="text-center">Paid Date</th> <th style="width: 5%" class="text-center">Class</th> <th style="width: 10%" class="text-center">Month</th> <th style="width: 15%;" class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {
                        markup += '<tr><td class="text-center align-middle"><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td class="text-center align-middle"> <td class="text-center align-middle"><input type="hidden" value="' + value.invoice_no + '" name="invoice_no[]">' + value.invoice_no + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_id + '" name="student_id[]">' + value.student_id + '<td class="text-center align-middle"><input type="hidden" value="' + value.student_name + '" name="student_name[]">' + value.student_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.total_amount + '" name="total_amount[]">' + value.total_amount + '</td>' + '<td class="text-center align-middle">Paid : <input type="hidden" value="' + value.paid_amount + '" name="paid_amount[]">' + value.paid_amount + ' Rs<br>Pre fee : <input type="hidden" value="' + value.previous_month_fee + '" name="previous_month_fee[]">' + value.previous_month_fee + ' Rs<br>'  + value.other_fee_type + ' : <input type="hidden" value="' + value.other_amount + '" name="other_amount[]">' + value.other_amount  + ' Rs<br> Payable fee : <input type="hidden" value="' + value.total_payable_fee + '" name="total_payable_fee[]">' + value.total_payable_fee + ' Rs<br>Remaining : <input type="hidden" value="' + value.arrears + '" name="arrears[]">' + value.arrears + ' Rs</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.paid_date + '" name="paid_date[]">' + value.paid_date + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.class_name + '" name="class_name[]">' + value.class_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.month + '" name="month[]">' + value.month + '</td>' +  '<td style=" width=12%" class="text-center d-flex"> <a  data-toggle="modal" data-target="#editModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a  data-toggle="modal" data-target="#viewInvoiceModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a> </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    table = $('table[id="studentsData_fee_index"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });

    $('select[name="status"]').on('change', function () {
        var status = $(this).val();
        if (status) {
            $.ajax({
                url: '/fee/status/' + status,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="studentsData_fee_index"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th style="width: 10%" class="text-center">Invoice ID</th> <th style="width: 10%" class="text-center">Student ID</th> <th style="width: 15%" class="text-center">Student Name</th> <th style="width: 5%" class="text-center">Total Amount</th> <th style="width: 10%" class="text-center align-middle">Status</th> <th style="width: 10%" class="text-center">Paid Date</th> <th style="width: 5%" class="text-center">Class</th> <th style="width: 10%" class="text-center">Month</th> <th style="width: 15%;" class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {
                        markup += '<tr><td class="text-center align-middle"><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td class="text-center align-middle"> <td class="text-center align-middle"><input type="hidden" value="' + value.invoice_no + '" name="invoice_no[]">' + value.invoice_no + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_id + '" name="student_id[]">' + value.student_id + '<td class="text-center align-middle"><input type="hidden" value="' + value.student_name + '" name="student_name[]">' + value.student_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.total_amount + '" name="total_amount[]">' + value.total_amount + '</td>' + '<td class="text-center align-middle">Paid : <input type="hidden" value="' + value.paid_amount + '" name="paid_amount[]">' + value.paid_amount + ' Rs<br>Pre fee : <input type="hidden" value="' + value.previous_month_fee + '" name="previous_month_fee[]">' + value.previous_month_fee + ' Rs<br>'  + value.other_fee_type + ' : <input type="hidden" value="' + value.other_amount + '" name="other_amount[]">' + value.other_amount  + ' Rs<br> Payable fee : <input type="hidden" value="' + value.total_payable_fee + '" name="total_payable_fee[]">' + value.total_payable_fee + ' Rs<br>Remaining : <input type="hidden" value="' + value.arrears + '" name="arrears[]">' + value.arrears + ' Rs</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.paid_date + '" name="paid_date[]">' + value.paid_date + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.class_name + '" name="class_name[]">' + value.class_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.month + '" name="month[]">' + value.month + '</td>' +  '<td style=" width=12%" class="text-center d-flex"> <a  data-toggle="modal" data-target="#editModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a  data-toggle="modal" data-target="#viewInvoiceModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a> </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    table = $('table[id="studentsData_fee_index"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });

    $('select[name="std_id_fee_index"]').on('change', function () {
        var classID = $(this).val();
        if (classID) {

            $.ajax({

                url: '/fee/throughId/' + classID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var table = $('table[id="studentsData_fee_index"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup = '<thead><tr class="filters"><th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th><th style="width: 10%" class="text-center">Invoice ID</th> <th style="width: 10%" class="text-center">Student ID</th> <th style="width: 15%" class="text-center">Student Name</th> <th style="width: 5%" class="text-center">Total Amount</th> <th style="width: 10%" class="text-center align-middle">Status</th> <th style="width: 10%" class="text-center">Paid Date</th> <th style="width: 5%" class="text-center">Class</th> <th style="width: 10%" class="text-center">Month</th> <th style="width: 15%;" class="align-middle text-center">Actions</th> </tr></thead><tbody>';

                    $.each(data, function (key, value) {
                        markup += '<tr><td class="text-center align-middle"><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td class="text-center align-middle"> <td class="text-center align-middle"><input type="hidden" value="' + value.invoice_no + '" name="invoice_no[]">' + value.invoice_no + '</td> <td class="text-center align-middle"><input type="hidden" value="' + value.student_id + '" name="student_id[]">' + value.student_id + '<td class="text-center align-middle"><input type="hidden" value="' + value.student_name + '" name="student_name[]">' + value.student_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.total_amount + '" name="total_amount[]">' + value.total_amount + '</td>' + '<td class="text-center align-middle">Paid : <input type="hidden" value="' + value.paid_amount + '" name="paid_amount[]">' + value.paid_amount + ' Rs<br>Pre fee : <input type="hidden" value="' + value.previous_month_fee + '" name="previous_month_fee[]">' + value.previous_month_fee + ' Rs<br>'  + value.other_fee_type + ' : <input type="hidden" value="' + value.other_amount + '" name="other_amount[]">' + value.other_amount  + ' Rs<br> Payable fee : <input type="hidden" value="' + value.total_payable_fee + '" name="total_payable_fee[]">' + value.total_payable_fee + ' Rs<br>Remaining : <input type="hidden" value="' + value.arrears + '" name="arrears[]">' + value.arrears + ' Rs</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.paid_date + '" name="paid_date[]">' + value.paid_date + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.class_name + '" name="class_name[]">' + value.class_name + '</td>' + '<td class="text-center align-middle"><input type="hidden" value="' + value.month + '" name="month[]">' + value.month + '</td>' +  '<td style=" width=12%" class="text-center d-flex"> <a  data-toggle="modal" data-target="#editModal' + value.id + '"><button title="Edit" class="btn btn-primary"><span class="fas fa-pencil-alt"></span></button></a> <a  data-toggle="modal" data-target="#viewInvoiceModal' + value.id + '"><button title="View" class="btn btn-warning"><span class="fas fa-eye"></span></button></a> </td>' + '</td> </tr>';

                    });
                    markup += '</tbody>';
                    table = $('table[id="studentsData_fee_index"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }

    });


    $(".deleteInvoice_fee_index").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        console.log(checkBoxArray);
        $.ajax(
            {
                url: "/invoice/delete",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });


    //------------------------------------------------------------------------//
    //-------------------------Fee For Create----------------------------------//
    //------------------------------------------------------------------------//

    jQuery(document).ready(function ()
    {
        jQuery('select[id="class_id_admfee_create"]').on('change',function(){
            var ClassID = jQuery(this).val();
            if(ClassID)
            {
                jQuery.ajax({
                    url : '/invoice/ajaxAdmFeeID/' + ClassID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        jQuery('select[id="student_id_adm_create"]').empty();
                        $('select[id="student_id_adm_create"]').append('<option name="all_students" value="all_students"> All Students </option>');
                        jQuery.each(data, function(key,value){
                            $('select[id="student_id_adm_create"]').append('<option value="'+ value +'">'+ value +'</option>');
                        });
                    }
                });
            }
            else
            {
                $('select[id="student_id_adm_create"]').empty();
            }
        });
    });

    jQuery('select[id="student_id_adm_create"]').on('change',function(){
        // var StudentID = jQuery(this).val();
        var StudentID = $("#student_id_adm_create option:selected").html();
        // console.log(StudentID);
        if(StudentID)
        {
            jQuery.ajax({
                url : '/invoice/ajaxAdmFeeName/',
                type : "GET",
                dataType : "json",
                data: { 
                    name: StudentID
                },
                success:function(data)
                {
                    // console.log(data);
                    jQuery('select[id="student_name_adm_create"]').empty();
                    jQuery.each(data, function(key,value){
                        $('input[id="student_name_adm_create"]').val(value);
                    });
                }
            });
        }
        else
        {
            $('input[id="student_name_adm_create"]').empty();
        }
    });

    // jQuery('select[id="class_id_adm_edit"]').on('change',function(){
    //     var ClassID = jQuery(this).val();
    //     console.log(ClassID);
    //     if(ClassID)
    //     {
    //         jQuery.ajax({
    //             url : '/invoice/ajaxAdmEditID/' + ClassID,
    //             type : "GET",
    //             dataType : "json",
    //             success:function(data)
    //             {
    //                 console.log(data);
    //                 // jQuery('select[id="student_name_adm_edit"]').empty();
    //                 jQuery('select[id="student_id_adm_edit"]').empty();
    //                 $('select[id="student_id_adm_edit"]').append('<option name="all_students" value="all_students"> All Students </option>');
    //                 jQuery.each(data, function(key,value){
    //                     $('select[id="student_id_adm_edit"]').append('<option value="'+ value +'">'+ value +'</option>');
    //                 });
    //             }
    //         });
    //     }
    //     else
    //     {
    //         $('select[id="student_id_adm_edit"]').empty();
    //     }
    // });
// });

    // jQuery('select[id="student_id_adm_edit"]').on('change',function(){
    //     var StudentID = $("#student_id_adm_edit option:selected").html();
    //     console.log(StudentID);
    //     if(StudentID)
    //     {
    //         jQuery.ajax({
    //             url : '/invoice/ajaxAdmEditName/',
    //             type : "POST",
    //             dataType : "json",
    //             data: { 
    //                 name: StudentID
    //             },
    //             success:function(data)
    //             {
    //                 console.log(data);
    //                 jQuery('select[id="student_name_adm_edit"]').empty();
    //                 jQuery.each(data, function(key,value){
    //                     $('input[id="student_name_adm_edit"]').val(value);
    //                 });
    //             }
    //         });
    //     }
    //     else
    //     {
    //         $('input[id="student_name_adm_edit"]').empty();
    //     }
    // });



    jQuery(document).ready(function ()
    {
        jQuery('select[id="student_feeSetup_fee_create"]').on('change',function(){
            var id = document.getElementById('class_id_fee_create').value;
            var id1 = jQuery(this).val();
            console.log(id1, id);
                jQuery.ajax({
                    url : "/invoice/ajaxID/" ,
                    type : "GET",
                    dataType : "json",
                    data: { 
                        id: id,
                        id1: id1
                    },
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[id="student_id_fee_create"]').empty();
                        $('select[id="student_id_fee_create"]').append('<option name="all_students" value="all_students"> All Students IDs </option>');
                        jQuery.each(data, function(key,value){
                            $('select[id="student_id_fee_create"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
        });
    });

    jQuery(document).ready(function ()
    {
        jQuery('select[id="student_id_fee_create"]').on('change',function(){
            var StudentID = jQuery(this).val();
            if(StudentID)
            {
                jQuery.ajax({
                    url : '/invoice/ajaxName/' + StudentID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[id="student_name_fee_create"]').empty();
                        $('select[id="student_name_fee_create"]').append('<option name="all_students" value="all_students"> All Students Names </option>');
                        jQuery.each(data, function(key,value){
                            $('select[id="student_name_fee_create"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }
            else
            {
                $('select[id="student_name_fee_create"]').empty();
            }
        });
    });

    //    jQuery(document).ready(function ()
    // {
    //     jQuery('select[id="student_feeSetup_fee_create"]').on('change',function(){
    //         var id = document.getElementById('class_id_fee_create').value;
    //         var id1 = jQuery(this).val();
    //             jQuery.ajax({
    //                 url : "/invoice/ajaxID/" ,
    //                 type : "POST",
    //                 dataType : "json",
    //                 data: { 
    //                     id: id,
    //                     id1: id1
    //                 },
    //                 success:function(data)
    //                 {
    //                     console.log(data);
    //                     jQuery('select[id="student_id_fee_create"]').empty();
    //                     $('select[id="student_id_fee_create"]').append('<option name="all_students" value="all_students"> All Students </option>');
    //                     jQuery.each(data, function(key,value){
    //                         $('select[id="student_id_fee_create"]').append('<option value="'+ key +'">'+ value +'</option>');
    //                     });
    //                 }
    //             });
    //     });
    // });

    //------------------------------------------------------------------------//
    //-------------------------Expense Report----------------------------------//
    //------------------------------------------------------------------------//

    $(".deleteReports").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/expense/deleteReport",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });


    //------------------------------------------------------------------------//
    //------------------------------SMS Create--------------------------------//
    //------------------------------------------------------------------------//


    jQuery(document).ready(function ()
    {
        jQuery('select[id="class_id_sms_create"]').on('change',function(){
            var ClassID = jQuery(this).val();
            if(ClassID)
            {
                jQuery.ajax({
                    url : '/sms/students/' +ClassID,
                    type : "GET",
                    dataType : "json",
                    success:function(data)
                    {
                        console.log(data);
                        jQuery('select[id="student_id_sms_create"]').empty();
                        $('select[id="student_id_sms_create"]').append('<option name="all_students" value="all_students"> All Students </option>');
                        jQuery.each(data, function(key,value){
                            $('select[id="student_id_sms_create"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }
            else
            {
                $('select[id="student_id_sms_create"]').empty();
            }
        });
    });

    jQuery('select[id="student_id_sms_create"]').on('change',function(){
        var StudentID = jQuery(this).val();
        if(StudentID)
        {
            jQuery.ajax({
                url : '/invoice/ajaxName/' + StudentID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    console.log(data);
                    jQuery('select[id="student_name_sms_create"]').empty();
                    jQuery.each(data, function(key,value){
                        $('input[id="student_name_sms_create"]').val(value);
                    });
                }
            });
        }
        else
        {
            $('input[id="student_name_sms_create"]').empty();
        }
    });

    jQuery('select[id="student_id_sms_create"]').on('change',function(){
        var StudentID = jQuery(this).val();
        if(StudentID)
        {
            jQuery.ajax({
                url : '/sms/ajaxNumber/' + StudentID,
                type : "GET",
                dataType : "json",
                success:function(data)
                {
                    console.log(data);
                    jQuery('select[id="phone_no_sms_create"]').empty();
                    jQuery.each(data, function(key,value){
                        $('input[id="phone_no_sms_create"]').val(value);
                    });
                }
            });
        }
        else
        {
            $('input[id="phone_no_sms_create"]').empty();
        }
    });

    //------------------------------------------------------------------------//
    //------------------------------SMS Index--------------------------------//
    //------------------------------------------------------------------------//


    $('select[name="class_id_sms_index"]').on('change', function() {
        var classID = $(this).val();
        if(classID) {

            $.ajax({

                url: '/sms/ajax/'+classID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    var table = $('table[id="studentsData_sms_index"]');
                    table.DataTable().destroy();
                    var markup = '';
                    markup += '<thead><tr class="filters"> <th style="width: 2%" class="align-middle text-center"><input type="checkbox" id="options"></th> <th class="text-center">Student ID</th> <th class="text-center">Student Name</th> <th class="text-center">Sent To</th> <th class="text-center">Message</th> <th class="text-center">Sent By</th> <th class="text-center">Sent Date</th> <th class="text-center align-middle">Actions</th> </tr></thead><tbody>';

                    $.each(data, function(key, value) {

                        markup += '<tr> <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="' + value.id + '"></td> <td class="text-center"><input type="hidden" value="'+value.student_id+'" name="student_id[]">' + value.student_id + '</td> <td class="text-center"><input type="hidden" value="'+value.student_name+'" name="student_name[]">' + value.student_name+ '</td> <td class="text-center"><input type="hidden" value="'+value.guardian_phone_no+'" name="guardian_phone_no[]">' + value.guardian_phone_no+ '</td><td class="text-center"><input type="hidden" value="'+value.message+'" name="message[]">' + value.message+ '</td> <td class="text-center"><input type="hidden" value="'+value.sent_by+'" name="sent_by[]">' + value.sent_by+ '</td> <td class="text-center"><input type="hidden" value="'+value.created_at+'" name="created_at[]">' + value.created_at+ '</td> <td style=" width=12%" class="text-center"> <a  data-toggle="modal" data-target="#deleteSmsModal' + value.id + '"><button title="Delete" class="btn btn-danger"><span class="fas fa-trash-alt"></span></button></a>  </td> </tr>';

                    });
                    markup += '</tbody>';
                    var table = $('table[id="studentsData_sms_index"]');
                    table.html(markup);
                    table.DataTable();
                }
            });
        }
    });


    $(".deleteSms").click(function(){
        var token = $('[name="csrf-token"]').attr('content');
        var checkBoxArray = [];

        $('[name="checkBoxArray[]"]:checked').each(function(){ checkBoxArray.push($(this).val()); });
        $.ajax(
            {
                url: "/sms/delete",
                type: 'DELETE',
                dataType: "JSON",
                data: {
                    "_token": token,
                    "checkBoxArray": checkBoxArray,
                },
            });

    });


});




