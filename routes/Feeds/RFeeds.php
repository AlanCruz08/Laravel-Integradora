<?php

use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::/*middleware('auth:sanctum')
    ->*/prefix('feeds')
    ->group(function () {

        Route::get('/{dispositivoID}', [RegistroController::class, 'index'])
            ->where('dispositivoID', '[0-9]+');

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


        Route::get('/humedad', [RegistroController::class, 'humedadAll']);
        Route::get('/temperatura', [RegistroController::class, 'temperaturaAll']);
        Route::get('/distancia', [RegistroController::class, 'distanciaAll']);
        Route::get('/humo', [RegistroController::class, 'humoAll']);
        Route::get('/pir', [RegistroController::class, 'pirAll']);
        Route::get('/alcohol', [RegistroController::class, 'alcoholAll']);

        
});