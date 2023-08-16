<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use GuzzleHttp\Client;
use App\Models\Dispositivo;
use App\Models\User;

class RegistroController extends Controller
{
    public function index()
    {
        $ultimosRegistros = Registro::select('sensor_id', 'valor', 'unidades', 'created_at')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('registros')
                    ->groupBy('sensor_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $ultimosRegistros,
            'status' => 200
        ], 200);
    }

    public function userDispositivo(int $userID, int $dispositivoID, string $token)
    {
        $dispositivo = Dispositivo::find($dispositivoID);

        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'data' => $dispositivo,
                'status' => 404
            ], 404);
        }

        $user = User::find($userID);

        if (!$user) {
            return response()->json([
                'msg' => 'Usuario no encontrado!',
                'data' => $user,
                'status' => 404
            ], 404);
        }

        if ($dispositivo->user_id != $userID) {
            return response()->json([
                'msg' => 'Dispositivo no pertenece al usuario!',
                'status' => 401
            ], 401);
        }

        if ($dispositivo->token != $token) {
            return response()->json([
                'msg' => 'Token incorrecto!',
                'status' => 401
            ], 401);
        }

        return response()->json([
            'msg' => 'Dispositivo encontrado!',
            'data' => $dispositivo,
            'status' => 200
        ], 200);
    }

    public function temperatura(int $dispositivo_id)
    {
    }
    public function distancia(int $dispositivo_id)
    {
    }
    public function humedad(int $dispositivo_id)
    {
    }
    public function pir(int $dispositivo_id)
    {
    }
    public function humo(int $dispositivo_id)
    {
    }
    public function alcohol(int $dispositivo_id)
    {
    }
    private function dispositivo()
    {
    }
}
