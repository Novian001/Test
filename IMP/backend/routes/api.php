<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
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

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [UserController::class, 'logout']);
});

Route::get("/posts", [PostController::class, "index"]);
Route::get("/posts/list", [PostController::class, "list"]);
Route::get("/post/{id}", [PostController::class, "show"]);
Route::group(['prefix' => 'posts','middleware' => 'auth:sanctum'], function () use ($router){
    Route::post('', [PostController::class, 'store']);
    Route::post('update/{id}', [PostController::class, 'update']);
    Route::delete('{id}', [PostController::class, 'destroy']);
});
