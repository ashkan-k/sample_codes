<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
    public function index()
    {
        if (cache()->has('articles')) {
            $articles = cache('articles');
        } else {
            $articles=Article::where('status','1')->latest()->take(8)->get();
            cache(['articles'=>$articles], now()->addDay(1));
        }



        if (cache()->has('courses')) {
            $courses = cache('courses');
        } else {
            $courses=Course::where('is_remove','0')->latest()->take(8)->get();
            cache(['courses'=>$courses], now()->addDay(1));
        }

        return view('Front.home', compact('articles', 'courses'));

    }
}
