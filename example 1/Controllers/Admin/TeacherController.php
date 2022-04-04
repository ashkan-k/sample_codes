<?php

namespace App\Http\Controllers\Admin;

use App\DB\TeacherRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Models\Teacher;


class TeacherController extends Controller
{

    private static function getArrayTeacherFilds(TeacherRequest $request): array
    {
        return [
            'cv' => $request->input("cv"),
            'description' => $request->input("description"),
            'nationalCode' => $request->input("nationalCode"),
            'address' => $request->input("address"),
            'status' => $request->input("status"),
            'phoneNumber' => $request->input("phoneNumber"),
            'name' => $request->input("name"),
            'lastName' => $request->input("lastName"),
        ];
    }

    private static function updateTeacher(TeacherRequest $request, Teacher $teacher): void
    {

        $user = TeacherRepo::findUser($request->input("user_id"));

        TeacherRepo::userUpdate($user, $request['phoneNumber'], $request['barthDay']);

        ####################################################################


        $teacherFilds = static::getArrayTeacherFilds($request);

        TeacherRepo::editTeacher($teacher, $teacherFilds);
    }

    private static function storeTeacher(TeacherRequest $request)
    {
        $user_id = $request->input("user_id");
        $user = TeacherRepo::findUser($user_id);

        $phoneNumber = $request['phoneNumber'];
        $barthDay = $request['barthDay'];
        TeacherRepo::userUpdate($user, $phoneNumber, $barthDay);

        ####################################################################

        $teacherFilds = static::getArrayTeacherFilds($request);

        return TeacherRepo::createTeacher($user_id, $teacherFilds);
    }

    public function index()
    {
        return view('Admin.Teachers.index');
    }

    public function create()
    {
        $users = TeacherRepo::selectUsers();

        return view('Admin.Teachers.create', compact('users'));
    }

    public function store(TeacherRequest $request)
    {

        $teacher = TeacherRepo::selectTeacherCreate($request->input('user_id'));

        if ($teacher) {
            alert()->error("مدرسی با این user وجود دارد !", 'خطا 😟')->persistent("فهمیدم");
            return back()->with("status", true)->withInput();
        }

        $teacher = static::storeTeacher($request);

        return redirect(route("teachers.index"))->with("status", "$teacher->name $teacher->lastName");

    }

    public function edit(Teacher $teacher)
    {
        $users = TeacherRepo::selectUsers();

//       \Request::session()->put('user_id' ,$teacher->user->id);

        return view('Admin.Teachers.edit', compact('teacher', 'users'));
    }

    public function update(TeacherRequest $request, Teacher $teacher)
    {

//        $user = User::query()->find(session('user_id'));

        static::updateTeacher($request, $teacher);

//        \Request::session()->forget('user_id');

        return redirect(route("teachers.index"))->with("edit", "$teacher->name $teacher->lastName");
    }

    public function trash()
    {
        return view('Admin.Teachers.trash_list_teacher');
    }

}
