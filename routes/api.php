<?php

use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('post/all', [PostController::class, 'all']);
    Route::get('post/slug/{post:slug}', [PostController::class, 'slug']);
    Route::get('category/all', [CategoryController::class, 'all']);
    Route::get('category/slug/{slug}', [CategoryController::class, 'slug']);
    Route::get('category/{category}/posts', [CategoryController::class, 'post']);

    Route::resource('category', CategoryController::class)->except(['create', 'edit']);
    Route::resource('post', PostController::class)->except(["create", "edit"]);
    Route::post('user/logout', [UserController::class, 'logout']);
    Route::post('post/upload/{post}', [PostController::class, 'upload']);
});


//Usuarios
Route::post('user/login', [UserController::class, 'login']);
