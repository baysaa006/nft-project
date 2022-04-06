<?php

use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;

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
        });
    });
    
});


Route::get('auth/{provider}', [SocialController::class, 'socialRedirect']);
Route::get('auth/{provider}/callback', [SocialController::class, 'loginWithFacebook']);