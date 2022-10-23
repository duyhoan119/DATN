<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupplierController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('suppliers', SupplierController::class);
Route::get('category/{id}',[CategoryController::class,'getCategory']);
Route::put('category/{id}',[CategoryController::class,'store']);
Route::get('categories',[ProductController::class,'index']);
Route::post('categories',[CategoryController::class,'save']);

