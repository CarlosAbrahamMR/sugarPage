<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Recompensa extends Model
{
    use HasFactory;
    protected $table = 'recompensas';

    public function imagenes() {
        return $this->belongsToMany(Imagenes::class, 'imagenes_recompensas','recompensas_id');
    }

    public function comentarios() {
        $user = Auth::user();
        return $this->hasMany(ComentarioRecompensa::class, 'recompensas_id', 'id')->where('users_id', $user->id);
    }

    public function productos() {
        return $this->hasOne(Producto::class, 'recompensas_id', 'id');
    }
}
