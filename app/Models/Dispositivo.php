<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispositivo extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsToMany(User::class, 'users_dispositivos', 'dispositivo_id', 'user_id');
    }
    
    public function sensor()
    {
        return $this->hasMany(Sensor::class, 'dispositivo_id');
    }
}
