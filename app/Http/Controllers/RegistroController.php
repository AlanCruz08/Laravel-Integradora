<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
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

    public function index()
    {
        $temp = $this->temperatura();
        $dist = $this->distancia();
        $hume = $this->humedad();
        $pir = $this->pir();
        $alcohol = $this->alcohol();
        $humo = $this->humo();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function show(Registro $registro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function edit(Registro $registro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registro $registro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registro $registro)
    {
        //
    }
    
    public function temperatura()
    {
        try {

            $response = $this->client->get($this->username . '/feeds/temperature');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'] . '°C',
            ];

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $filteredFeed,
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
    public function distancia()
    {
        try {

            $response = $this->client->get($this->username . '/feeds/distancia');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'] . 'cm',
            ];

            //dd($filteredFeed);

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $filteredFeed,
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
    public function humedad()
    {
        try {

            $response = $this->client->get($this->username . '/feeds/humedad');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            //dd($feeds);

            $filteredFeed = [
                'username' => $feeds['username'],
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            //dd($filteredFeed);

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $filteredFeed,
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
    public function pir()
    {
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

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $filteredFeed,
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
    public function humo()
    {
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

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $filteredFeed,
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
    public function alcohol()
    {
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

            return response()->json([
                'msg' => 'Registros recuperados con exito!',
                'data' => $filteredFeed,
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
