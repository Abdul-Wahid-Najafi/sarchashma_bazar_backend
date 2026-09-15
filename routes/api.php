<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Shop\ShopSocialController;
use App\Http\Controllers\Api\Shop\ShopProductController;
use App\Http\Controllers\Api\Chat\ConversationController;
use App\Http\Controllers\Api\Chat\MessageController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\FormDataController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\BlockController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::prefix('auth')->group(function () {
    Route::get('status', function() {
        return response()->json(['status' => 'ok']);
    });
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('google-login', [AuthController::class, 'googleLogin']); 
    // سیستم لاگین سنتی با ایمیل و پسورد
    Route::post('login-email', [AuthController::class, 'loginWithEmail']);
    
});

        
        
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});
            
            
            
            
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    
    Route::delete('/profile/delete', [ProfileController::class, 'delete']);
});
                
                
                
Route::middleware('auth:sanctum')->get('/shop/products', [ShopProductController::class, 'myProducts']);
Route::get('/shop/{slug}/products', [ShopProductController::class, 'productsByShop']);
Route::middleware('auth:sanctum')->get('/shop/stats', [ShopSocialController::class, 'myShopStats']);
Route::get('/shop/{slug}/stats', [ShopSocialController::class, 'shopStats']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/products/{slug}/similar', [ProductController::class, 'similar']);
Route::middleware('auth:sanctum')->put('/products/{product}', [ProductController::class, 'update']);

Route::middleware('auth:sanctum')->delete('/products/{product}', [ProductController::class, 'destroy']);

Route::prefix('shop')->group(function () {
    Route::post('socials', [ShopSocialController::class, 'update']);
    Route::get('{slug}', [ShopSocialController::class, 'show']);
    // Route::post('create', [ShopSocialController::class, 'store']);   
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('shop/create', [ShopSocialController::class, 'store']);   
    Route::get('my-shop', [ShopSocialController::class, 'myShop']);
    Route::post('update', [ShopSocialController::class, 'updateMyShop']);
});


Route::get('form-dependencies', FormDataController::class);


Route::middleware('auth:sanctum')->group(function () {
   Route::middleware('auth:sanctum')->post('/products', [ProductController::class, 'store']);
});

Route::get('/categories', [CategoryController::class, 'index']);



Route::middleware('auth:sanctum')->post('/shop/{slug}/follow', [ShopSocialController::class, 'follow']);
Route::middleware('auth:sanctum')->delete('/shop/{slug}/follow', [ShopSocialController::class, 'unfollow']);

Route::middleware('auth:sanctum')->get('/me/followed-shops/products', [FollowingController::class, 'products']);
Route::middleware('auth:sanctum')->get('/me/followed-shops', [FollowingController::class, 'shops']);
Route::middleware('auth:sanctum')->get('/shops/discover', [FollowingController::class, 'discover']);

Route::middleware('auth:sanctum')->post('/products/{slug}/favorite', [FavoriteController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/products/{slug}/favorite', [FavoriteController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/me/favorites', [FavoriteController::class, 'index']);


Route::middleware('auth:sanctum')->prefix('conversations')->group(function () {
    Route::get('/', [ConversationController::class, 'index']);
    Route::post('/', [ConversationController::class, 'store']);
    Route::get('/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/{conversation}/messages', [MessageController::class, 'store']);
    Route::post('/{conversation}/messages/read', [MessageController::class, 'markRead']);
    Route::post('/{conversation}/messages/delivered', [MessageController::class, 'markDelivered']);
    Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->post('/users/{userId}/block', [BlockController::class, 'store']);
Route::middleware('auth:sanctum')->delete('/users/{userId}/block', [BlockController::class, 'destroy']);