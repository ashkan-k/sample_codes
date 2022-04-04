<?php

namespace App\Http\Controllers\Admin;

use App\DB\CourseRepo;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Course;

class CourseController extends AdminController
{

    private static function deleteImages(Course $course): void
    {
        foreach ($course->images['images'] as $key => $image) {
            $substrings = explode('/', $image);
            $path = $substrings[1] . '/' . $substrings[2] . '/' . $substrings[3] . '/' . $substrings[4];
            unlink($path);
        }
    }

    public function index()
    {
        $courses = CourseRepo::selectCourses();
        return view('Admin.Courses.index', compact('courses'));
    }


    public function create()
    {
        $categories = CourseRepo::selectCategories();
        $teachers = CourseRepo::selectTeachers();
        return view('Admin.Courses.create', compact('categories', 'teachers'));
    }


    public function store(CourseRequest $request)
    {
        try {
            $imagesUrl = $this->uploadImages($request->file('images'),true);
            $user = auth()->user();
            CourseRepo::createCourse($request->all(), $imagesUrl,$user);
//            Cache::forget('courses');
            return redirect(route("courses.index"))->with("status", $request->title);

        } catch (\Exception $exception) {

            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('courses.index'))->with("error", true);
        }
    }


    public function edit(Course $course)
    {
        $categories = CourseRepo::selectCategories();
        $teachers = CourseRepo::selectTeachers();
        return view('Admin.Courses.edit', compact('categories', 'teachers', 'course'));
    }


    public function update(CourseRequest $request, Course $course)
    {

        try {
            $file = $request->file('images');
            $inputs = $request->all();

            if ($file) {
                static::deleteImages($course);
                $inputs['images'] = $this->uploadImages($request->file('images'),true);
            } else {
                $inputs['images'] = $course->images;
                $inputs['images']['thumb'] = $inputs['imagesThumb'];
            }

            unset($inputs['imagesThumb']);

            CourseRepo::updateCourse($course, $inputs);
            return redirect(route("courses.index"))->with("status", $request->title);

        } catch (\Exception $exception) {
            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('courses.index'))->with("error", true);
        }
    }

    public function trash()
    {
        return view('Admin.Courses.trash_list_course');
    }


}
