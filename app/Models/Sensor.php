<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sensor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'descripcion', 'dispositivo_id'];

    public function dispositivos()
    {
        return $this->belongsToMany(Dispositivo::class, 'registros', 'sensor_id', 'dispositivo_id');
    }
    
    public function registro()
    {
        return $this->hasMany(Registro::class, 'sensor_id');
    }
}
