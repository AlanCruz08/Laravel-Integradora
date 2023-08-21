<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->prefix('feeds')
    ->group(function () {

        //Route::get('{dispositivoID}', [RegistroController::class, 'index']);

        Route::get('/temperatura/{dispositivoID}', [RegistroController::class, 'temperatura'])
            ->where('dispositivoID', '[0-9]+');

        Route::get('/distancia/{dispositivoID}', [RegistroController::class, 'distancia'])
            ->where('dispositivoID', '[0-9]+');

        Route::get('/humedad/{dispositivoID}', [RegistroController::class, 'humedad'])
            ->where('dispositivoID', '[0-9]+');

        Route::get('/pir/{dispositivoID}', [RegistroController::class, 'pir'])
            ->where('dispositivoID', '[0-9]+');

        Route::get('/alcohol/{dispositivoID}', [RegistroController::class, 'alcohol'])
            ->where('dispositivoID', '[0-9]+');

        Route::get('/humo/{dispositivoID}', [RegistroController::class, 'humo'])
            ->where('dispositivoID', '[0-9]+');

        Route::get('/ada', [RegistroController::class, 'ada']);

        //query all
        Route::get('/distanciaAll', [RegistroController::class, 'getRegistrosDistanciaAll']);
        Route::get('/temperaturaAll', [RegistroController::class, 'getRegistrosTemperaturaAll']);
        Route::get('/humedadAll', [RegistroController::class, 'getRegistrosHumedadAll']);
        Route::get('/pirAll', [RegistroController::class, 'getRegistrosPirAll']);
        Route::get('/humoAll', [RegistroController::class, 'getRegistrosHumoAll']);
        Route::get('/alcoholAll', [RegistroController::class, 'getRegistrosAlcoholAll']);

        //filtro
        Route::get('/filtro', [RegistroController::class, 'getRegistrosPorRangoDeFechas']);
    });
