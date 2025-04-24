<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subastas extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'precio_inicial',
        'precio_final',
        'users_id',
    ];

    public function Users(){
        return $this->hasOne("App\User", "id", "users_id");
    }

    public function imagenes() {
        return $this->belongsToMany(Imagenes::class, 'imagenes_subastas');
    }

}
