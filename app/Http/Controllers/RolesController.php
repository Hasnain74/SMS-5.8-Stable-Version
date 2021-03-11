<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests\RolesRequest;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Permission;
use Kodeine\Acl\Models\Eloquent\Role;

class RolesController extends Controller
{

    public function index()
    {
        $roles = Role::where('name', '!=', 'ecloudRole')->get();
        return view('admin.mng_roles.index', compact('roles'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'id')->all();
        return view('admin.mng_roles.create', compact('roles'));
    }


    public function store() {

        \request()->validate([
            'name' => 'required|unique:roles,name',
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

        return redirect('/admin/mng_roles')->with('create_role','The Role has been created successfully !');

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->getPermissions();
        return view('admin.mng_roles.edit', compact('role', 'permissions'));
    }


    public function update(Request $request, $id) {

        $permissions_list = DB::table('permission_role')
            ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->where('permission_role.role_id', $id)
            ->pluck('permission_role.permission_id', 'permissions.name')
            ->toArray();

        \request()->validate([
            'name' => 'required',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'slug' => strtolower($request->name),
            'description' => $request->description
        ]);

        if (!$request->modules) {
            foreach ($role->getPermissions() as $permission_name => $permission) {
                $slug_arr = [];
                foreach ($permission as $slug => $status) {
                    if (!is_null($request->get($permission_name . "Actions"))) {
                        if (in_array($slug, $request->get($permission_name . "Actions"))) {
                            $slug_arr[$slug] = true;
                        } else {
                            $slug_arr[$slug] = false;
                        }
                    }
                }
                $permissions = Permission::find($permissions_list[$permission_name]);
                $permissions->update([
                    'slug' => $slug_arr,
                ]);
            }
        } else {
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
        }

        return redirect('/admin/mng_roles')->with('update_role','The Role has been updated successfully !');
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->back()->with('delete_role','The Role has been deleted successfully !');
    }
}
