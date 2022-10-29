<?php

namespace App\Http\Controllers;
 
use App\http\Requests\AttributeProductRequest;
use App\Models\AttributeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\UpdateAttributeProductRequest;
use App\Http\Resources\UpdateAttributeProductResource;

class AttributeProductController extends Controller
{
    public function index(Request $request)
    { 
            return Response()->json(AttributeProduct::paginate(10),200); 
    }  
    public function save(AttributeProductRequest $request)
    {
        // dd($request->all());
        if (AttributeProduct::insert($request->all())) {
            return true;
        }
        return false;
    }  
    public function getAttributeProduct($id)
    {
        return new UpdateAttributeProductResource(AttributeProduct::find($id));
    }

    public function store($id,UpdateAttributeProductRequest $request)
    {
        return AttributeProduct::query()->find($id)->update($request->Validated());
    }
    public function delete($id) {
        if ($id) {
            $AttributeProduct = AttributeProduct::find($id);
            
            if ($AttributeProduct->delete()) {
                // return redirect()->back();
                return true;
            }
        } 
    }
}
