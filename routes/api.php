<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;

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
    Route::get('/products',[ProductController::class, 'getProducts'])->name('getProducts');
    Route::get('/products/{product_id}',[ProductController::class ,'getProductById'])->name('getProductById')->where(['product_id' => '[0-9]+']);
    //Route::get('/products',[ProductController::class, 'getProductsByType']);
    Route::get('products/reviews', [ReviewController::class, 'getReviews']);
    Route::get('/products/{productId}/reviews',[ProductController::class, 'getProductReviews']);

    Route::group(['middleware' => ['auth:api']], function(){
        Route::get('user', [UserController::class, 'getUser']); 
        Route::patch('user', [UserController::class, 'updateUser']);
        Route::post('products/{product_id}/reviews',[ReviewController::class, 'addReview']);   
        Route::post('/address', [AddressController::class, 'addAddress']);
        Route::patch('/address/{id}', [AddressController::class, 'updateAddress'])->name('updateAddress');
        Route::get('/address', [AddressController::class, 'getUserAddress'])->name('getUserAddress');  
        Route::get('/orders', [OrderController::class, 'orderList']);
        Route::post('/orders',[OrderController::class, 'addOrder']);
        Route::get ('/address/{address_id}', [AddressController::class, 'getAddressById'])->name('getAddressById');
    });

    Route::group(['middleware' => ['admin']], function (){
        Route::delete('products/{product_id}', [ProductController::class, 'deleteProductById'])->name('deleteProductById');
        Route::post('products', [ProductController::class, 'addProduct'])->name('addProduct');
        Route::post('products/{product_id}',[ProductController::class, 'updateProduct'])->name('updateProduct');
        Route::delete('products/{product_id}/reviews/{review_id}',[ProductController::class, 'deleteProductReview'])->name('deleteProductReview');
        
        Route::get('users', [UserController::class, 'getAllUsers']);
        Route::delete('users/{user_id}', [UserController::class, 'deleteUser']);

        Route::get('', [OrderController::class, 'getOrders']);
        Route::delete('/{order_id}', [OrderController::class, 'deleteOrderById']);
    });
});
