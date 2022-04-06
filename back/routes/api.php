<?php

use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserController;

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

Route::prefix('v1')->group(function () {
    Route::prefix('param')->group(function () {
        Route::post('/category/list', [ParameterController::class, 'listCategory']);
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::prefix('post')->group(function () {
            Route::post('create', [PostController::class, 'storePost']);
            Route::post('update', [PostController::class, 'updatePost']);
            Route::post('own/list', [PostController::class, 'ownPostList']);
            Route::post('timeline/list', [PostController::class, 'timelineList']);
            Route::post('rate', [PostController::class, 'ratePost']);
            Route::post('make/nft', [PostController::class, 'makeNft']);
        });

        Route::prefix('user')->group(function () {
            Route::post('change/avatar', [UserController::class, 'changeAvatar']);
            Route::post('detail', [UserController::class, 'detailUser']);
            Route::post('connect/wallet', [UserController::class, 'connectWallet']);
            Route::post('remove/wallet', [UserController::class, 'removeWallet']);
        });
    });
    
});


Route::get('auth/{provider}', [SocialController::class, 'socialRedirect']);
Route::get('auth/{provider}/callback', [SocialController::class, 'loginWithSocial']);