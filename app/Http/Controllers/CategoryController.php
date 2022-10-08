<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

class CategoryController extends Controller


{
    public function save(CategoryRequest $request){
        if(Category::insert($request->all())){
            return Response();
        }
        return ;
    }
}
