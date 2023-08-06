<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/feeds/temperature', [RegistroController::class, 'temperature']);
Route::get('/feeds', [RegistroController::class, 'index']);