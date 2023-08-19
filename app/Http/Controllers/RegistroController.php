<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Dispositivo;
use App\Models\User;

class RegistroController extends Controller
{
    private $key;
    private $username;
    private $api;
    private $client;

    public function __construct()
    {
        $this->key = env('AIO_KEY');
        $this->username = env('AIO_USERNAME');
        $this->api = env('AIO_API');

        $this->client = new Client([
            'base_uri' => $this->api,
            'headers' => [
                'X-AIO-Key' => $this->key,
            ],
            'verify' => false,
        ]);
    }

    public function index(int $dispositivo_id = null)
    {
        if ($dispositivo_id == null) {
            return response()->json([
                'msg' => 'Dispositivo no enviado!',
                'status' => 404
            ], 404);
        }

        $temp = $this->temperatura($dispositivo_id);
        $dist = $this->distancia($dispositivo_id);
        $hume = $this->humedad($dispositivo_id);
        $pir = $this->pir($dispositivo_id);
        $alcohol = $this->alcohol($dispositivo_id);
        $humo = $this->humo($dispositivo_id);
        $result = [
            'temperatura' => $temp,
            'distancia' => $dist,
            'humedad' => $hume,
            'pir' => $pir,
            'alcohol' => $alcohol,
            'humo' => $humo
        ];
        return $result;
    }
    public function temperatura(int $dispositivo_id)
    {
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            $response = $this->client->get($this->username . '/feeds/temperature');
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => '°C',
                'sensor_id' => 1,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function distancia(int $dispositivo_id)
    {
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            $response = $this->client->get($this->username . '/feeds/distancia');
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'] . 'cm',
            ];

            $valor = (int)$filteredFeed['last_value'];
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => 'cm',
                'sensor_id' => 2,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function humedad(int $dispositivo_id)
    {
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            $response = $this->client->get($this->username . '/feeds/humedad');
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => '%',
                'sensor_id' => 3,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function pir(int $dispositivo_id)
    {
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            $response = $this->client->get($this->username . '/feeds/pir');
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => 'ON/OFF',
                'sensor_id' => 4,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function humo(int $dispositivo_id)
    {
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            $response = $this->client->get($this->username . '/feeds/humo');
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => 'ppm',
                'sensor_id' => 5,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function alcohol(int $dispositivo_id)
    {
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            $response = $this->client->get($this->username . '/feeds/alcohol');
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => 'grados',
                'sensor_id' => 6,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function getRegistrosTemperaturaAll()
    {
        try {
            $sensorId = 1;
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function getRegistrosDistanciaAll()
    {
        try {
            $sensorId = 2;
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function getRegistrosHumedadAll()
    {
        try {
            $sensorId = 3;
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function getRegistrosPirAll()
    {
        try {
            $sensorId = 4;
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function getRegistrosHumoAll()
    {
        try {
            $sensorId = 5;
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function getRegistrosAlcoholAll()
    {
        try {
            $sensorId = 6;
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
    public function getRegistrosPorRangoDeFechas(Request $request)
    {
        try {
            $sensorId = $request->input('sensor_id');
            $fechaInicial = $request->input('fecha_inicial');
            $fechaFinal = $request->input('fecha_final');

            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->whereDate('created_at', '>=', $fechaInicial)
                ->whereDate('created_at', '<=', $fechaFinal)
                ->get()
                ->map(function ($registro) {
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)),
                        'hora' => date('H:i:s', strtotime($registro->created_at)),
                    ];
                });

            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
