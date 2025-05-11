<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register'])->name('user.register');
Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [UserController::class, 'user'])->name('user.info');
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('user.refresh-token');
});
