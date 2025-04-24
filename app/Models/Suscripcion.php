<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{

    protected $fillable = [
        'id',
        'users_fan_id',
        'users_creador_id',
        'status',
        'fecha_inicio',
        'fecha_fin',
        'subscription_id',
        'plan_id'
    ];

    use HasFactory;
    protected $table = 'suscripciones';

    public function userCreador(){
        return $this->hasOne(User::class, "id", "users_creador_id");
    }

    public function userFan(){
        return $this->hasOne(User::class, "id", "users_fan_id");
    }

    public function users() {
        return $this->belongsToMany(User::class, 'suscripciones');
    }

}
