<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispositivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['alias', 'descripcion', 'codigo'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($dispositivo) {
            // Generar un número aleatorio de 4 dígitos entre 1000 y 9999
            $codigo = mt_rand(1000, 9999);
            $dispositivo->codigo = $codigo;
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_dispositivos', 'dispositivo_id', 'user_id');
    }

    public function registros()
    {
        return $this->hasMany(Registro::class, 'dispositivo_id');
    }
}
