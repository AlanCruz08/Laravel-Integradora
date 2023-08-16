<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Models\Dispositivo;
use App\Models\Registro;

class DataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

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

    public function handle()
    {
        $dispositivo_id = $this->dispositivo();
        $dispositivo = Dispositivo::find($dispositivo_id);

        if (!$dispositivo) {
            $this->release(15); // Reintentar después de 60 segundos
            return;
        }

        $this->temperatura($dispositivo_id);
        $this->distancia($dispositivo_id);
        $this->humedad($dispositivo_id);
        $this->pir($dispositivo_id);
        $this->alcohol($dispositivo_id);
        $this->humo($dispositivo_id);
    }

    public function temperatura(int $dispositivo_id)
    {
        try {

            $response = $this->client->get($this->username . '/feeds/temperature');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
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
                throw new \Exception('Registro fallido!');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    public function distancia(int $dispositivo_id)
    {
        try {

            $response = $this->client->get($this->username . '/feeds/distancia');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
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
                throw new \Exception('Registro fallido!');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    public function humedad(int $dispositivo_id)
    {
        try {

            $response = $this->client->get($this->username . '/feeds/humedad');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
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
                throw new \Exception('Registro fallido!');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    public function pir(int $dispositivo_id)
    {
        try {

            $response = $this->client->get($this->username . '/feeds/pir');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
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
                throw new \Exception('Registro fallido!');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    public function humo(int $dispositivo_id)
    {
        try {

            $response = $this->client->get($this->username . '/feeds/humo');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
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
                throw new \Exception('Registro fallido!');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    public function alcohol(int $dispositivo_id)
    {
        try {

            $response = $this->client->get($this->username . '/feeds/alcohol');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
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
                throw new \Exception('Registro fallido!');
            }
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
    private function dispositivo()
    {
        try {

            $response = $this->client->get($this->username . '/feeds/id');

            $data = $response->getBody()->getContents();
            $feeds = json_decode($data, true);

            $filteredFeed = [
                'name' => $feeds['name'],
                'last_value' => $feeds['last_value'],
            ];

            $valor = (int)$filteredFeed['last_value'];
            return $valor;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
