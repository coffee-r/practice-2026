<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// テスト環境にだけbasic認証をかける
Route::group(['middleware' => 'auth.basic.custom'], function() {

    Route::middleware('auth:sanctum')->group(function () {
        // いいね追加処理
        Route::post('/posts/{post_id}/likes', 'App\Http\Controllers\LikeApiController@upsert')->name('post.like.upsert');
    });
    
});