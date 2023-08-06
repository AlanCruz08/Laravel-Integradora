<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Env;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $temp = $this->temperatura();
        $dist = $this->distancia();
        $hume = $this->humedad();
        $result = [
            'temperatura' => $temp,
            'distancia' => $dist,
            'humedad' => $hume
        ];

        return $result;

        // $key = env('AIO_KEY');
        // $username = env('AIO_USERNAME');
        // $api = env('AIO_API');

        // $client = new Client([
        //     'base_uri' => $api,
        //     'headers' => [
        //         'X-AIO-Key' => $key,
        //     ],
        //     'verify' => false,
        // ]);

        // try {
        //     $response = $client->get($username . '/feeds');

        //     $data = $response->getBody()->getContents();
        //     $feeds = json_decode($data, true);

        //     //dd($feeds);

        //     $filteredFeeds = [];
        //     foreach ($feeds as $feed) {
        //         $filteredFeeds[] = [
        //             'username' => $feed['username'],
        //             'name' => $feed['name'],
        //             'last_value' => $feed['last_value'],
        //         ];
        //     }

        //     //dd($filteredFeed);

        //     return response()->json([
        //         'msg' => 'Registros recuperados con exito!',
        //         'data' => $feeds,
        //         'status' => 200
        //     ], $response->getStatusCode());
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'msg' => 'Error al recuperar registros!',
        //         'error' => $e->getMessage(),
        //         'status' => 500
        //     ], 500);
        // }
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
        $key = env('AIO_KEY');
        $username = env('AIO_USERNAME');
        $api = env('AIO_API');

        $client = new Client([
            'base_uri' => $api,
            'headers' => [
                'X-AIO-Key' => $key,
            ],
            'verify' => false,
        ]);

        try {
            $response = $client->get($username . '/feeds/temperature');

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
    public function distancia()
    {
        $key = env('AIO_KEY');
        $username = env('AIO_USERNAME');
        $api = env('AIO_API');

        $client = new Client([
            'base_uri' => $api,
            'headers' => [
                'X-AIO-Key' => $key,
            ],
            'verify' => false,
        ]);

        try {
            $response = $client->get($username . '/feeds/distancia');

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
    public function humedad()
    {
        $key = env('AIO_KEY');
        $username = env('AIO_USERNAME');
        $api = env('AIO_API');

        $client = new Client([
            'base_uri' => $api,
            'headers' => [
                'X-AIO-Key' => $key,
            ],
            'verify' => false,
        ]);

        try {
            $response = $client->get($username . '/feeds/humedad');

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

}
