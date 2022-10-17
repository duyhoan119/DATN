<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Auth\Events\Validated;

class CategoryController extends Controller

{
    public function save(CategoryRequest $request)
    {
        if (Category::insert($request->all())) {
            return true;
        }
        return false;
    }

    public function getCategory($id)
    {
        return response()->json(Category::find($id), 200);
    }

    public function store($id,UpdateCategoryRequest $request)
    {
        return Category::query()->find($id)->update($request->Validated());
    }
}
