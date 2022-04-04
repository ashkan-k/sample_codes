<?php

namespace App\Http\Controllers\Admin;

use App\DB\EpisodeRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EpisodeRequest;
use App\Models\Course;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends AdminController
{
    public function index()
    {
        $episodes = EpisodeRepo::selectEpisodes();
        return view('Admin.Episodes.index', compact('episodes'));
    }

    public function create()
    {
        $courses = EpisodeRepo::selectCourses();
        return view('Admin.Episodes.create', compact('courses'));
    }

    public function store(EpisodeRequest $request)
    {
        try {
            $course_id = $request->input('course_id');
            $request_all = $request->all();
            $user = auth()->user();

            $episode = EpisodeRepo::createEpisode($request_all, $user, $course_id);

            $this->setCourseTime($episode);

            return redirect(route("episodes.index"))->with("status", $request->title);

        } catch (\Exception $exception) {

            return $this->ErrorRedirect();

        }
    }

    public function edit(Episode $episode)
    {
        $courses = EpisodeRepo::selectCourses();
        return view('Admin.Episodes.edit', compact('courses', 'episode'));
    }

    public function update(EpisodeRequest $request, Episode $episode)
    {
        try {
            $course_id = $request->input('course_id');
            $request_all = $request->all();

            EpisodeRepo::updateEpisode($episode, $request_all, $course_id);
            $this->setCourseTime($episode);

            return redirect(route("episodes.index"))->with("edit", $request->title);

        } catch (\Exception $exception) {
            return $this->ErrorRedirect();
        }
    }

    private function ErrorRedirect()
    {
        alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");
        return redirect(route('episodes.index'))->with("error", true);
    }
}
