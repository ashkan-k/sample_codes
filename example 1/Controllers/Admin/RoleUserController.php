<?php

namespace App\Http\Controllers\Admin;

use App\DB\RoleUserRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleUserController extends Controller
{
    public function index()
    {
        return view('Admin.Roles_User.index');
    }

    public function create()
    {
        $users = RoleUserRepo::selectUsers();
        $roles = RoleUserRepo::selectRoles();
        return view('Admin.Roles_User.create', compact('users', 'roles'));
    }

    public function store(RoleUserRequest $request)
    {
        RoleUserRepo::createRoleUser($request['user_id'],$request['roles_id']);
        return redirect(route('role-user.index'))->with("status", $request->title);
    }

    public function edit($id)
    {
        $user = RoleUserRepo::findUser($id);
        $roles = RoleUserRepo::selectRoles();
        return view('Admin.Roles_User.edit', compact('user', 'roles'));
    }

    public function update(RoleUserRequest $request, $id)
    {
        RoleUserRepo::updateRoleUser($id, $request['roles_id']);
        return redirect(route('role-user.index'))->with("edit", $request->title);
    }

}
