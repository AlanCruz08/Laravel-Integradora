<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DispositivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['alias' => 'Bar El Rincon', 'descripcion' => 'Calle Juarez 123, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'La Cantina del Pueblo', 'descripcion' => 'Avenida Hidalgo 456, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'El Meson de los Amigos', 'descripcion' => 'Calle Zaragoza 789, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Cerveceria La Terraza', 'descripcion' => 'Boulevard Revolucion 234, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Bar El Patio', 'descripcion' => 'Avenida Morelos 567, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Cantina La Tradicion', 'descripcion' => 'Calle Madero 890, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Taberna Los Charros', 'descripcion' => 'Boulevard Constitucion 901, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Bar La Esquina', 'descripcion' => 'Avenida Allende 345, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Cerveceria El Alamo', 'descripcion' => 'Calle Obregon 678, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
            ['alias' => 'Cantina El Farol', 'descripcion' => 'Boulevard Miguel Hidalgo 1234, Torreon, Coahuila', 'codigo' => rand(1000, 9999)],
        ];

        DB::table('dispositivos')->insert($data);
    }
}
