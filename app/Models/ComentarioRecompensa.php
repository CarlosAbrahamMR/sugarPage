<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ComentarioRecompensa extends Model
{
    use HasFactory;
    protected $table = 'comentarios_recompensas';

    public function recompensa() {
        $user = Auth::user();
        return $this->belongsTo(Recompensa::class, 'id', 'recompensas_id')->where('users_id', $user->id);
    }

    public function users(){
        return $this->hasOne(User::class, "id", "users_id");
    }
}
