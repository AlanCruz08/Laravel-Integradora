<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::prefix('user')->group(function () {
    Route::get('/check', function() { return 'ok'; });
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/validate', [LoginController::class, 'validar']);
});


//Route::get('/user', [LoginController::class,'getUserData']);
