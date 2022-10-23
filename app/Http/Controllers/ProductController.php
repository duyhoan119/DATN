<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriesResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return new CategoriesResource(Product::paginate(10));
    }
}
