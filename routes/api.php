<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Route::prefix('/products')->name('products.')->group(function () {

    Route::get('/',[ProductController::class,'index'])->name('products'); 
    Route::get('/category',[ProductController::class,'add'])->name('add');
    Route::post('/category',[ProductController::class,'save'])->name('save');

    // Route::get('/', [ProductController::class, 'index'])->name('list'); //Products.list
    // Route::get('/changeStatus', [ProductController::class, 'changeStatus'])->name('changeStatus'); //Products.changeStatus
    // Route::delete('/delete/{product}', [ProductController::class, 'delete'])->name('delete'); //name: Products.delete
    // Route::get('/create', [ProductController::class, 'create'])->name('create');
    // Route::post('/store', [ProductController::class, 'store'])->name('store');
    // Route::get('/edit/{product?}', [ProductController::class, 'edit'])->name('edit');
    // Route::put('/update/{product?}', [ProductController::class, 'update'])->name('update');
});  



