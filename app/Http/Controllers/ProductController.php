<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $data = Product::paginate(10);
        dd(response()->join($data));
    }
}
