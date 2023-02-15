<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\UserController;
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
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('user', [UserController::class, 'all']);
    Route::post('logout', [UserController::class, 'logout']);
});

Route::get('now-playing', [MovieController::class, 'nowPlaying']);
Route::get('popular', [MovieController::class, 'popular']);
Route::get('top-rated', [MovieController::class, 'topRated']);
Route::get('upcoming', [MovieController::class, 'upcoming']);
Route::group(['prefix' => 'movie', 'middleware' => 'auth:sanctum'], function () {
    Route::post('store', [MovieController::class, 'store']);
    Route::put('update/{id}', [MovieController::class, 'update']);
    Route::delete('delete/{id}', [MovieController::class, 'delete']);
});

Route::get('fetch', [CategoryController::class, 'fetch']);
Route::get('fetch-by-id/{id}', [CategoryController::class, 'fetchById']);
Route::get('fetch-movies-by-category/{id}', [CategoryController::class, 'fetchMoviesByCategory']);
Route::group(['prefix' => 'category', 'middleware' => 'auth:sanctum'], function () {
    Route::post('store', [CategoryController::class, 'store']);
    Route::put('update/{id}', [CategoryController::class, 'update']);
    Route::delete('delete/{id}', [CategoryController::class, 'destroy']);
});