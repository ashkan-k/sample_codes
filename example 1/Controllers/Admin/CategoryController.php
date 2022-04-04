<?php

namespace App\Http\Controllers\Admin;

use App\DB\CategoryRepo;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        return view('Admin.Categories.index');
    }

    public function create()
    {
        $categories = CategoryRepo::selectCategoriesForCreate();
        return view('Admin.Categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Category $category)
    {
        $categories = CategoryRepo::selectCategoriesForEdit($category->id);
        return view('Admin.Categories.edit', compact('category', 'categories'));
    }

    public function trash()
    {
        return view('Admin.Categories.trash_list_category');
    }

}
