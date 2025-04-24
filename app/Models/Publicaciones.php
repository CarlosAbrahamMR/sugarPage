<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'users_id',
        'descripcion',
        'deleted',
        'tipo',
        'precio',
    ];

    public function imagenes() {
        return $this->belongsToMany(Imagenes::class, 'imagenes_publicaciones');
    }

    public function Users(){
        return $this->hasOne(User::class, "id", "users_id");
    }

    public function comentarios() {
        return $this->hasMany(Comentarios::class);
    }
}
