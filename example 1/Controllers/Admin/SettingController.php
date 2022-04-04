<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {
        return view('Admin.Settings.index');
    }


    public function create()
    {
        return view('Admin.Settings.create');
    }


    public function store(Request $request)
    {
        //
    }

    public function edit(Setting $setting)
    {
        return view('Admin.Settings.edit',compact('setting'));
    }


    public function update(Request $request, Setting $setting)
    {
        //
    }


    public function destroy(Setting $setting)
    {
        //
    }
}
