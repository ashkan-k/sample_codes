<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function index()
    {
        return view('Admin.Permissions.index');
    }


    public function create()
    {
        return view('Admin.Permissions.create');
    }


    public function store(Request $request)
    {
        //
    }

    public function edit(Permission $permission)
    {
        return view('Admin.Permissions.edit',compact('permission'));
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Permission $permission)
    {

    }
}
