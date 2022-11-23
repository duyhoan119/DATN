<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImportShipmentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\ExportShipmentController;

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

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'save']);
Route::get('products/{id}', [ProductController::class, 'getProduct']);
Route::put('products/{id}', [ProductController::class, 'store']);
Route::delete('/products/{id}', [ProductController::class, 'delete']);

// attributes

Route::get('attributes', [AttributeController::class, 'index']);
Route::post('attributes', [AttributeController::class, 'save']);
Route::get('attributes/{id}', [AttributeController::class, 'getAttribute']);
Route::put('attributes/{id}', [AttributeController::class, 'store']);
Route::delete('attributes/{id}', [AttributeController::class, 'delete']);
// attribute_product
Route::get('attribute-products', [AttributeProductController::class, 'index']);
Route::post('attribute-products', [AttributeProductController::class, 'save']);
Route::get('attribute-products/{id}', [AttributeProductController::class, 'getAttributeProduct']);
Route::put('attribute-products/{id}', [AttributeProductController::class, 'store']);
Route::delete('attribute-products/{id}', [AttributeProductController::class, 'delete']);

Route::resource('suppliers', SupplierController::class);
Route::get('category/{id}', [CategoryController::class, 'getCategory']);
Route::put('category/{id}', [CategoryController::class, 'store']);
Route::delete('category/{id}', [CategoryController::class, 'delete']);
Route::get('categories', [CategoryController::class, 'index']);
Route::post('categories', [CategoryController::class, 'save']);

Route::prefix('import-shipment')->group(function () {
    Route::post('/', [ImportShipmentController::class, 'save']);
    Route::get('/{import_id}', [ImportShipmentController::class, 'getDetail']);
    Route::get('/', [ImportShipmentController::class, 'index']);
});

Route::prefix('export-shipment')->group(function () {
    Route::post('/', [ExportShipmentController::class, 'save']);
    Route::get('/{export_id}', [ExportShipmentController::class, 'getDetail']);
    Route::get('/', [ExportShipmentController::class, 'index']);

});
