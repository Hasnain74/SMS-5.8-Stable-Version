<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Photo;
use App\StudentsClass;
use App\Teacher;
use App\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Kodeine\Acl\Models\Eloquent\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherExport;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Teacher::all()->sortByDesc('created_at');
        return view('admin.teachers.index', compact('users'));
    }

    public function deleteTeachers(Request $request) {
        if(!empty($request->checkBoxArray)) {
            foreach ($request->checkBoxArray as $id) {
                $teachers = DB::table('teachers')->where('id', '=', $id)->get();
                DB::table('users')->where('user_id', '=', $teachers[0]->teacher_id)->delete();
                DB::table('teachers')->where('id', '=', $id)->delete();
            }
            return redirect()->back()->with('delete_multiple_teacher','The teacher has been deleted successfully !');
        }
        return redirect()->back()->with('delete_multiple_teacher','The teacher has been deleted successfully !');
    }

    public function delete_teacher_account($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('delete_teacher_account','The account has been deleted successfully !');
    }

    public function delete_role($id) {
        $role = DB::table('role_user')->where('role_user.id', '=', $id);
        $role->delete();
        return redirect()->back()->with('delete_teacher_role','The role has been deleted successfully !');
    }

    public function delete_account($id) {
        $user = User::find($id);
        $role = DB::table('users')->where('user_id', '=', $user->user_id);
        $role->delete();
        return redirect('/students/student_accounts')->with('delete_student_account','The account has been deleted successfully !');
    }

    public function update_account(Request $request, $id) {
        $user = User::find($id);
        if (trim($request->password) == '') {
            $input = $request->except('password');
            $user->update($input);
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($request->password);
            $user->update($input);
        }
        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file'=>$name]);
            $input['photo_id'] = $photo->id;
        }
        if ($request->roles = '') {
            $input =$request->except('roles');
        } else {
            $inputRoles = $input['roles'];
        }
        $user->update($input);
        $user->assignRole($inputRoles);

        return redirect('teachers/teacher_accounts')->with('update_teacher_account','The teacher has been updated successfully !');
    }


    public function downloadPDF($id) {
        $users = [ Teacher::findOrFail($id) ];
        return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->
        loadView('admin.teachers.pdf', compact( 'users'))->stream();
    }

    public function teacher_accounts() {
        $accounts = User::where('email', '!=', 'hasnain@ecloud.school')->orderby('id', 'desc')->get();
        return view('admin.teachers.teacher_accounts', compact('accounts'));
    }

    public function edit_account( $id) {
        $user = User::find($id);
        $userRoles = array_keys($user->getRoles());
        $roles = Role::where('name', '!=', 'ecloudRole')->pluck('name', 'id')->all();
        return view('admin.teachers.edit_account', compact('roles', 'userRoles', 'user'));
    }

    public function teacher_roles() {
        $roles = DB::table('role_user')
            ->where('role_id', '!=', '1')
            ->join('users', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.username', 'roles.name', 'role_user.id', 'users.email', 'users.user_id', 'users.photo_id')
            ->orderby('id', 'desc')
            ->get();
        return view('admin.teachers.teacher_roles', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = StudentsClass::pluck('class_name', 'id')->all();
        $roles = Role::where('name', '!=', 'ecloudRole')->pluck('name', 'id');
        return view('admin.teachers.create', compact('classes', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherRequest $request)
    {
        // do{
        //     $randomNo = rand(0, 10000);
        //     $rand =  "-".$randomNo;
        // }
        // while(!empty(Teacher::where('teacher_id',$rand)->first()));

        $input = $request->all();

            if ($file = $request->file('photo_id')) {
                $name = time() . $file->getClientOriginalName();
                $file->move('images', $name);
                $photo = Photo::create(['file' => $name]);
                $input['photo_id'] = $photo->id;
            }

            $input['password'] = bcrypt($request->password);

            $statement = DB::select("SHOW TABLE STATUS LIKE 'students'");
            $Id = $statement[0]->Auto_increment;

            if($request->photo_id == null) {
                if($request->teacher_subject == null) {
                    Teacher::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'teacher_id' => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                     .'-'. date('Y', strtotime($request->join_date)).'-'.$Id,
                        'date_of_birth' => $request->date_of_birth,
                        'join_date' => $request->join_date,
                        'gender' => $request->gender,
                        'teacher_qualification' => $request->teacher_qualification,
                        'nic_no' => $request->nic_no,
                        'exp_detail' => $request->exp_detail,
                        'phone_no' => $request->phone_no,
                        'emergency_no' => $request->emergency_no,
                        'full_address' => $request->full_address,
                        'salary' => $request->salary
                    ]);
                } else {
                    Teacher::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'teacher_id' => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                        .'-'. date('Y', strtotime($request->join_date)).'-'.$Id,
                        'date_of_birth' => $request->date_of_birth,
                        'join_date' => $request->join_date,
                        'gender' => $request->gender,
                        'teacher_qualification' => $request->teacher_qualification,
                        'teacher_subject' => $request->teacher_subject,
                        'nic_no' => $request->nic_no,
                        'exp_detail' => $request->exp_detail,
                        'phone_no' => $request->phone_no,
                        'emergency_no' => $request->emergency_no,
                        'full_address' => $request->full_address,
                        'salary' => $request->salary
                    ]);
                }
                
            } else {
                if($request->teacher_subject == null) {
                    Teacher::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'teacher_id' => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                        .'-'. date('Y', strtotime($request->join_date)).'-'.$Id,
                        'date_of_birth' => $request->date_of_birth,
                        'join_date' => $request->join_date,
                        'gender' => $request->gender,
                        'photo_id' => $input['photo_id'],
                        'teacher_qualification' => $request->teacher_qualification,
                        'nic_no' => $request->nic_no,
                        'exp_detail' => $request->exp_detail,
                        'phone_no' => $request->phone_no,
                        'emergency_no' => $request->emergency_no,
                        'full_address' => $request->full_address,
                        'salary' => $request->salary
                    ]);
                } else {
                    Teacher::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'teacher_id' => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                        .'-'. date('Y', strtotime($request->join_date)).'-'.$Id,
                        'date_of_birth' => $request->date_of_birth,
                        'join_date' => $request->join_date,
                        'gender' => $request->gender,
                        'photo_id' => $input['photo_id'],
                        'teacher_qualification' => $request->teacher_qualification,
                        'teacher_subject' => $request->teacher_subject,
                        'nic_no' => $request->nic_no,
                        'exp_detail' => $request->exp_detail,
                        'phone_no' => $request->phone_no,
                        'emergency_no' => $request->emergency_no,
                        'full_address' => $request->full_address,
                        'salary' => $request->salary
                    ]);
                }
                
            }

        $validation = Validator::make($request->all(), [
            'email' => 'required|unique:users,email',
            'password' => 'string|min:6',
            'roles' => 'required'
        ]);

        if (!$validation->fails()) {
            if($request->photo_id == null) {
                $user = User::create([
                    'username' =>  $request->first_name.' '.$request->last_name,
                    'user_id' => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                    .'-'. date('Y', strtotime($request->join_date)).'-'.$Id,
                    'status' =>  $request->status,
                    'email' =>  $request->email,
                    'password' =>  bcrypt($request->password),
                    'api_token' => Str::random(80),
                ]);
            } else {
                $user = User::create([
                    'username' =>  $request->first_name.' '.$request->last_name,
                    'user_id' => 'ESSM'. '-' .strtolower($request->first_name).'-'.strtolower($request->last_name)
                    .'-'. date('Y', strtotime($request->join_date)).'-'.$Id,
                    'status' =>  $request->status,
                    'photo_id' =>  $input['photo_id'],
                    'email' =>  $request->email,
                    'password' =>  bcrypt($request->password),
                    'api_token' => Str::random(80),
                ]);
            }
           
            $inputRoles = $input['roles'];
            $user->assignRole($inputRoles);
        }

        return redirect('/admin/teachers')->with('create_teacher','The teacher has been created successfully !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Teacher::findOrFail($id);
        $classes = StudentsClass::pluck('class_name', 'id');
        return view('admin.teachers.edit', compact('classes', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $teacher = Teacher::findOrFail($id);

        $input = $request->all();

        if ($file = $request->file('photo_id')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images', $name);
            $photo = Photo::create(['file'=>$name]);
            $input['photo_id'] = $photo->id;
            $user = User::where('user_id', '=', $teacher->teacher_id)->get(); 
            foreach($user as $u) {
                $u->update([
                    'photo_id' => $photo->id
                ]);
            }
        }

        $teacher->update($input);

        return redirect('/admin/teachers')->with('update_teacher','The teacher has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Teacher::findOrFail($id);
        if($user->photo_id == null) {
            User::where('user_id', '=', $user->teacher_id)->delete();
            $user->delete();
        } else  {
            User::where('user_id', '=', $user->teacher_id)->delete();
            unlink(public_path() . $user->photo->file);
            $user->delete();
        }
        
        return redirect()->back()->with('delete_teacher','The teacher has been deleted successfully !');
    }


    public function export() {
        return (new TeacherExport)->download('staff.xlsx');
    }

}
