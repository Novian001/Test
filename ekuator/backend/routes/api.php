<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('user', [AuthController::class, 'all']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// products
Route::group(['prefix' => "product" ,'middleware' => 'auth:sanctum'], function () {
    Route::get('', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('', [ProductController::class, 'store'])->middleware('role:admin');
    Route::put('/{id}', [ProductController::class, 'update'])->middleware('role:admin');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('role:admin');
});

// Transaction
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{uuid}', [TransactionController::class, 'show']);
    Route::post('transactions', [TransactionController::class, 'store'])->middleware('role:customer');
});