<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
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

//Authentication is not required for these endpoints
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//Authentication is required for these endpoints (apply middleware auth:sanctum)
Route::group(['middleware' => ["auth:sanctum"]], function () {
    Route::get('userProfile', [AuthController::class, 'userProfile']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::put('changePassword', [AuthController::class, 'changePassword']);


    Route::prefix('book')->group(function(){
        Route::get('index',[BookController::class, 'index']);
        Route::post('store',[BookController::class, 'store']);
        Route::post('review',[BookController::class, 'addBookReview']);
        Route::put('review/{id}',[BookController::class, 'updateBookReview']);
    });
});


