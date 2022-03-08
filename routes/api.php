<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('rest')->group(function(){
    Route::post('/login',[UserController::class, 'login']);
    Route::post('/signUp',[UserController::class, 'register']);
    Route::get('/products',[ProductController::class, 'getProducts']);
    Route::get('/products/{product_id}',[ProductController::class ,'getProductById'])->name('getProductById')->where(['product_id' => '[0-9]+']);
    Route::get('/products',[ProductController::class, 'getProductsByType']);
    Route::get('/products/{productId}/reviews',[ProductController::class, 'getProductReviews']);
});
