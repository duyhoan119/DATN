<?php

namespace App\Http\Controllers;
use App\http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\UpdateProductResource;

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

    public function getProduct($id)
    {
        return new UpdateProductResource(Product::find($id));
    }

    public function store($id,UpdateProductRequest $request)
    {
        return Product::query()->find($id)->update($request->Validated());
    }
}
