<?php

namespace App\Http\Controllers\Auth;

use App\Photo;
use App\SchoolLogo;
use App\Teacher;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Kodeine\Acl\Models\Eloquent\Permission;
use Kodeine\Acl\Models\Eloquent\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string',
            'user_id' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $request = request();

        $file = $request->file('photo_id');
        $name = time().$file->getClientOriginalExtension();
        $file->move('images', $name);
        $photo = Photo::create(['file' => $name]);
        $input['photo_id'] = $photo->id;

        $user = User::create([
            'username' => $data['username'],
            'status' => 'admin',
            'photo_id'=>$input['photo_id'],
            'user_id'=>$data['user_id'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'api_token' => Str::random(80),
        ]);

        $role = Role::create([
            'name' => \request('name'),
            'slug' => strtolower(\request('name')),
            'description' => \request('description'),
        ]);

        $input = \Illuminate\Support\Facades\Input::all();

        foreach ($input['modules'] as $module) {
            if(isset( $input[$module.'Actions'])) {
                $slug = [];
                foreach ($input[$module . 'Actions'] as $action) {
                    $slug[$action] = true;
                }

                $permissions = Permission::create([
                    'name' => $module,
                    'slug' => $slug
                ]);
            }
            $role->assignPermission(array($permissions));
        }

        $user->assignRole($role);

        return $user;


    }
}
