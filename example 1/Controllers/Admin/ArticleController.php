<?php

namespace App\Http\Controllers\Admin;

use App\DB\ArticleRepo;
use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;

class ArticleController extends AdminController
{
    private function deleteImages($images): void
    {
        foreach ($images as $key => $image) {
            $substrings = explode('/', $image);
            $path = $substrings[1] . '/' . $substrings[2] . '/' . $substrings[3] . '/' . $substrings[4];
            unlink($path);
        }
    }

    public function index()
    {
        return view('Admin.Articles.index');
    }

    public function create()
    {
        $categories = ArticleRepo::selectCategories();
        return view('Admin.Articles.create', compact('categories'));
    }

    public function store(ArticleRequest $request)
    {
        try {
            $imagesUrl = $this->uploadImages($request->file('images'),true);

            ArticleRepo::createArticle($request->all(), $imagesUrl,auth()->user());

            return redirect(route('articles.index'))->with("status", $request->title);

        } catch (\Exception $exception) {

            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('articles.index'))->with("error", true);
        }

    }

    public function edit(Article $article)
    {
        $categories = ArticleRepo::selectCategories();
        return view('Admin.Articles.edit', compact('article', 'categories'));
    }

    public function update(ArticleRequest $request, Article $article)
    {

        try {
            $file = $request->file('images');
            $inputs = $request->all();

            if ($file) {
                $this->deleteImages($article->images['images']);
                $inputs['images'] = $this->uploadImages($request->file('images'),true);
            } else {
                $inputs['images'] = $article->images;
                $inputs['images']['thumb'] = $inputs['imagesThumb'];

            }

            unset($inputs['imagesThumb']);

            ArticleRepo::updateArticle($article, $inputs);

            return redirect(route('articles.index'))->with("edit", $article->title);
        } catch (\Exception $exception) {

            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('articles.index'))->with("error", true);
        }
    }

}
