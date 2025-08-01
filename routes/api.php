<?php

use App\Domain\Notes\NoteController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register'])->name('user.register');
Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [UserController::class, 'user'])->name('user.info');
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('user.refresh-token');
    /** Notes */
    Route::apiResource('notes', NoteController::class);
    Route::get('/notes/{note}/render', [NoteController::class, 'render'])->name('notes.render');
    Route::post('/notes/{note}/spell-check', [NoteController::class, 'spellCheck'])->name('notes.spell-check');
    Route::post('/notes/file', [NoteController::class, 'file'])->name('notes.file');
});
