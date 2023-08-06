<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;
    protected $fillable = ['sensor_id', 'valor', 'unidades', 'dispositivo_id'];

    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dispositivo_id');
    }
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
}
