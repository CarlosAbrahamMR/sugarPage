<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeProducto extends Model
{
    use HasFactory;
    protected $table = 'productos_stripe';
    
}
