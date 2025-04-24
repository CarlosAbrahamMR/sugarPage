<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserComision extends Model
{
    protected $table = 'user_comisiones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'user_id',
        'promocion_id',
        'porcentaje_usuario',
        'porcentaje_plataforma',
        'promocion_inicia',
        'promocion_termina',
    ];
}
