<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;

class CategoryController extends Controller

{
    public function save(CategoryRequest $request)
    {
        if (Category::insert($request->all())) {
            return Response();
        }
        return false;
    }

    public function getCategoryById($id)
    {
        $data = Category::find($id);
        if ($data !== null) {
            return response()->json($data, 200);
        }

        return [];
    }

    public function saved(CategoryRequest $request, $id)
    {
        $updateCategoryData = $request->all();
        $category = Category::find($id);
        $category->saved($updateCategoryData);
    }
}
