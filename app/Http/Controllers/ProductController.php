<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::paginate(10), 200);
    }
}
