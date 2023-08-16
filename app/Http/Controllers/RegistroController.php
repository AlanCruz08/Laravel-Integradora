<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Dispositivo;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Jobs\DataJob;
use Illuminate\Support\Env;

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
        try {
            DataJob::dispatch();

            return response()->json([
                'message' => 'Cola en proceso...'
            ], 200);
        } catch (\Exception $e) {
            // Registra la excepción en el archivo de registro
            Log::error('Error al ejecutar la cola: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al ejecutar la cola',
                'error' => $e->getMessage()
            ], 500);
        }
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

    public function ada()
    {
        try {
            $response = $this->client->get($this->username . '/feeds');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $feeds,
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

            $response = $this->client->get($this->username . '/feeds/tempvalue');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            //dd($filteredFeed);

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

            $response = $this->client->get($this->username . '/feeds/distvalue');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'] . 'cm',
            ];

            //dd($filteredFeed);

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

            $response = $this->client->get($this->username . '/feeds/humidityvalue');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            //dd($filteredFeed);

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

            //dd($feeds);

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

            //dd($feeds);

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

            //dd($feeds);

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
    private function dispositivo()
    {
        try {

            $response = $this->client->get($this->username . '/feeds/id');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            return $valor;
        } catch (\Exception $e) {
            return response()->json([
                'msg' => 'Error al recuperar registros!',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function dataLoop()
    {
        while (true) {
            $dispositivo_id = $this->dispositivo();
            $temp = $this->temperatura($dispositivo_id);
            $dist = $this->distancia($dispositivo_id);
            $hume = $this->humedad($dispositivo_id);
            $pir = $this->pir($dispositivo_id);
            $alcohol = $this->alcohol($dispositivo_id);
            $humo = $this->humo($dispositivo_id);

            sleep(15);
        }

        // $result = [
        //     'temperatura' => $temp,
        //     'distancia' => $dist,
        //     'humedad' => $hume,
        //     'pir' => $pir,
        //     'alcohol' => $alcohol,
        //     'humo' => $humo
        // ];
    }

    public function humedadAll()
{
    try {
        $response = $this->client->get($this->username . '/feeds/humidityvalue/data');
        $data = $response->getBody()->getContents();
        $feeds = json_decode($data, true);

        // Crear un array para almacenar los valores de humedad
        $valoresHumedad = [];

        // Recorrer el array de objetos y extraer el valor de humedad de cada uno
        foreach ($feeds as $feed) {
            $valorHumedad = $feed['value'];
            $valoresHumedad[] = $valorHumedad;
        }

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $valoresHumedad,
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
public function temperaturaAll()
{
    try {
        $response = $this->client->get($this->username . '/feeds/tempvalue/data');
        $data = $response->getBody()->getContents();
        $feeds = json_decode($data, true);

        // Crear un array para almacenar los valores de humedad
        $valoresTemperatura = [];

        // Recorrer el array de objetos y extraer el valor de humedad de cada uno
        foreach ($feeds as $feed) {
            $valorTemperatura = $feed['value'];
            $valoresTemperatura[] = $valorTemperatura;
        }

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $valoresTemperatura,
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
public function distanciaAll()
{
    try {
        $response = $this->client->get($this->username . '/feeds/distvalue/data');
        $data = $response->getBody()->getContents();
        $feeds = json_decode($data, true);

        // Crear un array para almacenar los valores de humedad
        $valoresDistancia = [];

        // Recorrer el array de objetos y extraer el valor de humedad de cada uno
        foreach ($feeds as $feed) {
            $valorDistancia = $feed['value'];
            $valoresDistancia[] = $valorDistancia;
        }

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $valoresDistancia,
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
public function pirAll()
{
    try {
        $response = $this->client->get($this->username . '/feeds/pir/data');
        $data = $response->getBody()->getContents();
        $feeds = json_decode($data, true);

        // Crear un array para almacenar los valores de humedad
        $valoresPir = [];

        // Recorrer el array de objetos y extraer el valor de humedad de cada uno
        foreach ($feeds as $feed) {
            $valorPir = $feed['value'];
            $valoresPir[] = $valorPir;
        }

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $valoresPir,
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
public function alcoholAll()
{
    try {
        $response = $this->client->get($this->username . '/feeds/alcohol/data');
        $data = $response->getBody()->getContents();
        $feeds = json_decode($data, true);

        // Crear un array para almacenar los valores de humedad
        $valoresAlcohol = [];

        // Recorrer el array de objetos y extraer el valor de humedad de cada uno
        foreach ($feeds as $feed) {
            $valorAlcohol = $feed['value'];
            $valoresAlcohol[] = $valorAlcohol;
        }

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $valoresAlcohol,
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
public function humoAll()
{
    try {
        $response = $this->client->get($this->username . '/feeds/tempvalue/data');
        $data = $response->getBody()->getContents();
        $feeds = json_decode($data, true);

        // Crear un array para almacenar los valores de humedad
        $valoresHumo = [];

        // Recorrer el array de objetos y extraer el valor de humedad de cada uno
        foreach ($feeds as $feed) {
            $valorHumo = $feed['value'];
            $valoresHumo[] = $valorHumo;
        }

        return response()->json([
            'msg' => 'Registros recuperados con exito!',
            'data' => $valoresHumo,
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

    
}
