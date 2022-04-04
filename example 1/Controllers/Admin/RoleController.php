<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\RoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        return view('Admin.Roles.index');
    }

    public function create()
    {
        $permissions = self::selectPermissions();
        return view('Admin.Roles.create',compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        self::createRole($request['name'],$request['permission_id']);

        return redirect(route('roles.index'))->with('status' , $request->name);

    }

    public function edit(Role $role)
    {
        $permissions = self::selectPermissions();
        return view('Admin.Roles.edit',compact('role','permissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        self::updateRole($role, $request['name'],$request['permission_id']);
        return redirect(route('roles.index'))->with('edit' , $request->name);
    }

    public function destroy(Role $role)
    {

    }
}
