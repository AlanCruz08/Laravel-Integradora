<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['nombre' => 'Temperatura', 'descripcion' => 'Sensor de temperatura en celsius'],
            ['nombre' => 'Distancia', 'descripcion' => 'Sensor de distancia en centimetros'],
            ['nombre' => 'Humedad', 'descripcion' => 'Sensor de humedad en porcentaje'],
            ['nombre' => 'Pir', 'descripcion' => 'Sensor de deteccion de movimiento en ON/OFF'],
            ['nombre' => 'Humo', 'descripcion' => 'Sensor de humo en ppm'],
            ['nombre' => 'Alcohol', 'descripcion' => 'Sensor de alcohol en grados'],
        ];

        DB::table('sensors')->insert($data);
    }
}
