<?php
session_start();

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImportShipmentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeProductController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\ExportShipmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderRefundController;
use App\Http\Controllers\StatisticalController;


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

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'Login']);
Route::get('/logout', [LoginController::class, 'Logout']);

Route::get('/user', [UserController::class, 'index']);
Route::post('/user', [UserController::class, 'save']);
Route::get('user/{id}', [UserController::class, 'getUser']);
Route::put('user/{id}', [UserController::class, 'store']);
Route::delete('/user/{id}', [UserController::class, 'delete']);

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'save']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'store']);
Route::get('product-detail/{id}', [ProductController::class, 'getProductDetail']);
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

Route::prefix('/statistical')->group(function () {
    Route::get('/', [StatisticalController::class, 'show']);
    Route::post('/supplier', [StatisticalController::class, 'supplier']);
    Route::post('/product', [StatisticalController::class, 'product']);
    Route::post('/inventoryProduct', [StatisticalController::class, 'inventoryProduct']);
    Route::post('/inventorySupplier', [StatisticalController::class, 'inventorySupplier']);
});


Route::prefix('/refund-order')->group(function () {
    Route::get('/', [OrderRefundController::class, 'index']);
    Route::post('/', [OrderRefundController::class, 'store']);
    Route::get('/{refund_export_id}', [OrderRefundController::class, 'show']);
});
Route::get('/export-shipment', [OrderRefundController::class, 'SearchExportShipment']);
