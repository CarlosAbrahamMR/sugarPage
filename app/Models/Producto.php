<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'users_id',
        'estatus',
        'cantidad_disponible',
        'cantidad_vendida',
        'id'
    ];

    public function imagenes() {
        return $this->belongsToMany(Imagenes::class, 'imagenes_productos');
    }

    public function recompensas() {
        return $this->hasOne(Recompensa::class, 'id', 'recompensas_id');
    }
}
