<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\UpdateProductResource;

class ProductController extends Controller
{
    public function index(Request $request)
    { 
        $keyword = $request->get('keyword' ); 

        if($keyword){
            return Response()->json(Product::where('name','like','%'.$keyword.'%')->paginate(10),200);   
        }else if($keyword){
            return Response()->json(Product::where('sku','like','%'.$keyword.'%')->paginate(10),200);   
        }else{
            return Response()->json(Product::paginate(10),200); 
        }
    }
    // public function generateUserSku(int $length = 10): string
    // {
    //     $sku = Str::random($length); 
    //     $exists = DB::table('products')
    //     ->where('sku', '=', $sku)
    //     ->get(['sku']); 
    //     if (isset($exists[0]->sku)) { 
    //         return self::generateUserSku(); 
    //     }
    //     return $sku; 
    // }  
    public function save(ProductRequest $request){  
        $sku = Str::random(10);
        $product = new product(); 
        $product->fill($request->all()); 
            $product->sku = $sku;
        $product->save();
        dd($product);
        // return redirect()->route('products.list'); 
        // if(Product::insert($request->all() )){
        //     return true;
        // }
        // return true;
    } 
    public function getProduct($id)
    {
        return new UpdateProductResource(Product::find($id));
    } 
    public function store($id, UpdateProductRequest $request)
    { 
        return Product::query()->find($id)->update($request->Validated());
    }
    public function delete($id) {
        if ($id) {
            $product = Product::find($id);
            if ($product->delete()) {
                // return redirect()->back();
                return true;
            }
        }
    }
}  
