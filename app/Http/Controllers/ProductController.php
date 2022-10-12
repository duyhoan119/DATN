<?php

namespace App\Http\Controllers;
use App\http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    public function index()
    {
        return Response()->json(Product::paginate(10),200);
    }

    public function save(ProductRequest $request){
        if(Product::insert($request->all())){
            return Response();
        }
        return false;
    }
}
