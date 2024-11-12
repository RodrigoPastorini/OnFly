<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('orders')->group(function () {
    Route::post('/register', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/{id}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::get('/{id}/detail', [OrderController::class, 'detail'])->name('orders.detail');
    Route::get('/list', [OrderController::class, 'list'])->name('orders.list');
});

Route::prefix('user')->group(function () {
    Route::post('/register', [UserController::class, 'store'])->name('user.store');
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware('auth:api')->get('user', [AuthController::class, 'me']);
Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);

