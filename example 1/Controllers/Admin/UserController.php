<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends AdminController
{
    private static function updateUser(User $user, UserRequest $request, array $inputs): void
    {
        $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
        ]);
        if ($inputs['password']) {
            $user->password = bcrypt($request['password']);
        }
        $user->profile_photo_path = $inputs['profile_photo_path'];
        $user->status = $inputs['status'];
        $user->level = $inputs['level'];
        $user->barthDay = $inputs['barthDay'];
        $user->phoneNumber = $inputs['phoneNumber'];

        $user->save();
    }

    private static function deleteAvatar(User $user): void
    {
        if ($user->profile_photo_path && file_exists($user->profile_photo_path)) {
            $substrings = explode('/', $user->profile_photo_path);
            $path = $substrings[1] . '/' . $substrings[2] . '/' . $substrings[3] . '/' . $substrings[4] . '/' . $substrings[5];
            unlink($path);
        }
    }

    private static function createUser(UserRequest $request, string $imagesUrl): void
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            "profile_photo_path" => $imagesUrl,
            "status" => $request['status'],
            "level" => $request['level'],
            "barthDay" => $request['barthDay'],
            "phoneNumber" => $request['phoneNumber'],
        ]);

        $user->save();
    }

    public function index()
    {
        return view('Admin.Users.index');
    }


    public function create()
    {
        return view('Admin.Users.create');
    }


    public function store(UserRequest $request)
    {

        $imagesUrl = $this->uploadImages($request->file('profile_photo_path') , $request->username);

        self::createUser($request, $imagesUrl);

        return redirect(route('users.index'))->with("status", $request->name);

    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        return view('Admin.Users.edit', compact('user'));
    }


    public function update(UserRequest $request, User $user)
    {
        try {
            $file = $request->file('profile_photo_path');
            $inputs = $request->all();

            if ($file) {
                self::deleteAvatar($user);
                $inputs['profile_photo_path'] = $this->uploadImages($request->file('profile_photo_path') , $user->username);
            } else {
                $inputs['profile_photo_path'] = $user->profile_photo_path;
            }


            self::updateUser($user, $request, $inputs);

            return redirect(route('users.index'))->with("edit", $user->name);
        } catch (\Exception $exception) {

            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('users.index'))->with("error", true);
        }
    }


    public function destroy(User $user)
    {
        //
    }

    public function trash()
    {
        return view('Admin.Users.trash_list_user');
    }
}
