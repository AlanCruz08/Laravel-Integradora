<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Dispositivo;


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

public function temperatura (int $dispositivo_id)
    {
        // Busca un dispositivo por su ID.
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            // Si el dispositivo no se encuentra, devuelve una respuesta de error 404.
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            // Realiza una solicitud GET a un recurso específico ('/feeds/distancia') utilizando un cliente.
            $response = $this->client->get($this->username . '/feeds/temperatura');

            // Obtiene el contenido de la respuesta y lo decodifica como JSON.
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            // Filtra y prepara los datos obtenidos.
            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            // Convierte el valor de la distancia en un entero.
            $valor = (int)$filteredFeed['last_value'];

            // Crea un nuevo registro en la base de datos.
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => '°C',
                'sensor_id' => 1,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                // Si no se puede guardar el registro, devuelve una respuesta de error 500.
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            // Si el registro se guarda con éxito, devuelve una respuesta exitosa con el registro y el código de estado de la respuesta original.
            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            // Si ocurre una excepción, devuelve una respuesta de error 500 con detalles del error.
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }


    public function distancia(int $dispositivo_id)
    {
        // Busca un dispositivo por su ID.
        $dispositivo = Dispositivo::find($dispositivo_id);
        if (!$dispositivo) {
            // Si el dispositivo no se encuentra, devuelve una respuesta de error 404.
            return response()->json([
                'msg' => 'Dispositivo no encontrado!',
                'status' => 404
            ], 404);
        }

        try {
            // Realiza una solicitud GET a un recurso específico ('/feeds/distancia') utilizando un cliente.
            $response = $this->client->get($this->username . '/feeds/distancia');

            // Obtiene el contenido de la respuesta y lo decodifica como JSON.
            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            // Filtra y prepara los datos obtenidos.
            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            // Convierte el valor de la distancia en un entero.
            $valor = (int)$filteredFeed['last_value'];

            // Crea un nuevo registro en la base de datos.
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => 'cm',
                'sensor_id' => 2,
                'dispositivo_id' => $dispositivo_id
            ]);

            if (!$registro) {
                // Si no se puede guardar el registro, devuelve una respuesta de error 500.
                return response()->json([
                    'msg' => 'Error al guardar registro!',
                    'data' => $registro,
                    'status' => 500
                ], 500);
            }

            // Si el registro se guarda con éxito, devuelve una respuesta exitosa con el registro y el código de estado de la respuesta original.
            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $registro,
                'status' => 200
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            // Si ocurre una excepción, devuelve una respuesta de error 500 con detalles del error.
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
         /*   $valorTexto = $filteredFeed['last_value'];
        $valorNumerico = ($valorTexto === 'ON') ? 1 : 0;

        $registro = Registro::create([
            'valor' => $valorNumerico,
            'unidades' => $valorNumerico ? 'ON' : 'OFF',
            'sensor_id' => 4,
            'dispositivo_id' => $dispositivo_id
        ]);*/


        $valor = (int)$filteredFeed['last_value'];
        $valorN = ($valor === 1) ? 0 : 1;
        $registro = Registro::create([
            'valor' => $valor,
            'unidades' => $valorN ? 'OFF' : 'ON', // Cambiado OFF y ON
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
            $valorN = ($valor === 1) ? 0 : 1;
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => $valorN ? 'Sin Detectar' : 'Detectado', // Cambiado OFF y ON
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
            $valorN = ($valor === 1) ? 0 : 1;
            $registro = Registro::create([
                'valor' => $valor,
                'unidades' => $valorN ? 'Sin Detectar' : 'Detectado', // Cambiado OFF y ON
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
   

    public function getRegistrosDistanciaAll()
    {
        try {
            $sensorId = 2; // Cambiar este valor según el sensor que desees consultar

            // Realiza una consulta a la base de datos para obtener registros específicos del sensor
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    // Transforma cada registro en un formato deseado
                    return [
                        'id' => $registro->id,
                        'valor' => $registro->valor,
                        'unidades' => $registro->unidades,
                        'fecha' => date('Y-m-d', strtotime($registro->created_at)), // Obtiene la fecha en formato 'YYYY-MM-DD'
                        'hora' => date('H:i:s', strtotime($registro->created_at)), // Obtiene la hora en formato 'HH:MM:SS'
                    ];
                });

            // Devuelve una respuesta JSON con los registros formateados
            return response()->json([
                'msg' => 'Registros recuperados con éxito!',
                'data' => $registros,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            // En caso de error, devuelve una respuesta JSON con un mensaje de error y el código de estado 500
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
            $sensorId = 1; // Cambiar este valor según el sensor que desees consultar

            // Realiza una consulta a la base de datos para obtener registros específicos del sensor
            $registros = Registro::select('id', 'valor', 'unidades', 'created_at')
                ->where('sensor_id', $sensorId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($registro) {
                    // Transforma cada registro en un formato deseado
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
