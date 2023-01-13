<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\SearchProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\productDetailResource;
use App\Http\Resources\UpdateProductResource;
use App\Models\productDetail;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    public function index(SearchProductRequest $request)
    {
        $product =  Product::query()->when($request->keyword, function (Builder $query, string $keyword) {
            $query->where('name', 'like', '%' . $keyword . '%')->orWhere('sku', 'like', '%' . $keyword . '%');
        })->get();

        return $product;
    }

    public function save(ProductRequest $request)
    {
        $product = new product();
        $product->fill($request->all());
        $product->image = $request->image;

        $KiTuDau = strtoupper(substr($product->Category->name, 0, 2));
        $KiTuRandom = Str::random(10);
        $sku = "$KiTuDau $KiTuRandom";
        $product->sku = $sku;

        if ($request->file('image')) {
            $product['image'] = $this->uploadFile($request->file('image'));
        }
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
        if ($request->file('image')) {
            $product['image'] = $this->uploadFile($request->file('image'));
        }
        return Product::query()->find($id)->update($request->all());
    }

    public function show($id)
    {
        $productDetail = productDetail::query()->where('product_id', $id)->get();
        return new productDetailResource($productDetail);
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $Product = Product::where('id', '=', $id);
            $data = [
                'status' => 0
            ];
            $Product->update($data);
            return true;
        }
    }

    public function getProductDetail($id)
    {
        $productDetail = productDetail::query()->find($id);
        return $productDetail;
    }
}
