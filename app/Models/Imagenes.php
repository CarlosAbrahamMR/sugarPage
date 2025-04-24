<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagenes extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'nombre',
    ];

    public function Users(){
        return $this->belongsToMany("App\Models\User");
    }

    public function publicaciones() {
        return $this->belongsToMany(Publicaciones::class, 'imagenes_publicaciones');
    }

    public function productos() {
        return $this->belongsToMany(Producto::class, 'imagenes_productos');
    }

    public function subastas() {
        return $this->belongsToMany(Subastas::class, 'imagenes_subastas');
    }

    public function recompensas() {
        return $this->belongsToMany(Recompensa::class, 'imagenes_recompensas');
    }

}
