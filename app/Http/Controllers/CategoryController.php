<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\UpdateCategoryResource;
use App\Models\Category;

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
        return new UpdateCategoryResource(Category::find($id));
    }

    public function store($id,UpdateCategoryRequest $request)
    {
        return Category::query()->find($id)->update($request->Validated());
    }

    public function delete($id) {
        if (!empty($id)) {
            $Category = Category::where('id', '=', $id);
            $data = [
                'status' => 0
            ];
            $Category->update($data);
            return true;
        }
    }
}
