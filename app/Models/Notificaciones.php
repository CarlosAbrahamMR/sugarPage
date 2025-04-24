<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'descripcion',
        'visto',
        'users_origen_id',
    ];

    public function Users(){
        return $this->hasOne(User::class, "id", "users_id");
    }

    public function UserOrigen(){
        return $this->hasOne(User::class, "id", "users_origen_id");
    }
}
