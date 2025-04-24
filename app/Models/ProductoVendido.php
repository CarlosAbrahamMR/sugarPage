<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoVendido extends Model
{
    use HasFactory;
    protected $table = 'productos_vendidos';
    protected $fillable = [
        'id',
        'users_id',
        'productos_id',
        'cantidad',
    ];
}
