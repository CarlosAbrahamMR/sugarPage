<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promociones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'codigo',
        'monto',
        'porcentaje',
        'fecha_inicio',
        'fecha_fin',
        'status',
    ];
}
