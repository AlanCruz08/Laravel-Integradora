<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/feeds/temperatura/{dispositivoID}', [RegistroController::class, 'temperatura'])
    ->where('dispositivoID', '[0-9]+');

Route::get('/feeds/distancia/{dispositivoID}', [RegistroController::class, 'distancia'])
    ->where('dispositivoID', '[0-9]+');

Route::get('/feeds/humedad/{dispositivoID}', [RegistroController::class, 'humedad'])
    ->where('dispositivoID', '[0-9]+');

Route::get('/feeds/pir/{dispositivoID}', [RegistroController::class, 'pir'])
    ->where('dispositivoID', '[0-9]+');

Route::get('/feeds/alcohol/{dispositivoID}', [RegistroController::class, 'alcohol'])
    ->where('dispositivoID', '[0-9]+');

Route::get('/feeds/humo/{dispositivoID}', [RegistroController::class, 'humo'])
    ->where('dispositivoID', '[0-9]+');

Route::get('/feeds/{dispositivoID}', [RegistroController::class, 'index'])
    ->where('dispositivoID', '[0-9]+');


include_once __DIR__ . '/Login/RLogin.php';