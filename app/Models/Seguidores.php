<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidores extends Model
{
    use HasFactory;
    protected $table = 'seguidores_publicaciones';

    protected $fillable = [
        'users_origen_id',
        'users_destino_id',
        'status',
    ];

    public function userOrigen(){
        return $this->hasOne(User::class, "id", "users_origen_id");
    }

    public function userDestino(){
        return $this->hasOne(User::class, "id", "users_destino_id");
    }

}
