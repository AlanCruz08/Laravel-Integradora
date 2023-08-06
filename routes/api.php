<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/feeds/temperatura', [RegistroController::class, 'temperatura']);
Route::get('/feeds/distancia', [RegistroController::class, 'distancia']);
Route::get('/feeds/humedad', [RegistroController::class, 'humedad']);
Route::get('/feeds/pir', [RegistroController::class, 'pir']);
Route::get('/feeds/alcohol', [RegistroController::class, 'alcohol']);
Route::get('/feeds/humo', [RegistroController::class, 'humo']);
Route::get('/feeds', [RegistroController::class, 'index']);