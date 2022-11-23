<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\UpdateProductResource;

class ProductController extends Controller
{
    public function index(Request $request)
    { 
        $keyword = $request->get('keyword');

        if ($keyword) {
            return Response()->json(Product::where('status', '=', 1)->where('name', 'like', '%' . $keyword . '%')->paginate(10), 200);
        } else if ($keyword) {
            return Response()->json(Product::where('status', '=', 1)->where('sku', 'like', '%' . $keyword . '%')->paginate(10), 200);
        } else {
            return Response()->json(Product::where('status', '=', 1)->paginate(10), 200);
        }
    }

    public function save(ProductRequest $request) 
    {
        $product = new product(); 
        $product->fill($request->all());  
        $product->image = $request->image;

        $KiTuDau = strtoupper(substr($product->Category->name, 0 ,2)); 
        $KiTuRandom = Str::random(10);
        $sku = "$KiTuDau  $KiTuRandom"; 
        $product->sku = $sku; 

        if ($request->file('image')) { 
            $product['image'] = $this->uploadFile($request->file('image'));
        } 
        dd($product);   
        $product->save();
        return $product;
    }
 
    public function uploadFile($file)
    { 
            $filename =  time() . '_' . $file->getClientOriginalName();
            return $file->storeAs('imagesProduct', $filename,  'public'); 
    }
 
    public function getProduct($id)
    {
        return new UpdateProductResource(Product::where('status', '=', 1)->find($id));
    }

    public function store($id, UpdateProductRequest $request)
    {
        return Product::query()->find($id)->update($request->Validated());
    } 
    public function delete($id) { 
        if (!empty($id)) {
            $Product = Product::where('id', '=', $id);
            $data = [
                'status' => 0
            ];
            $Product->update($data);
            return true;
        }
    }
}
