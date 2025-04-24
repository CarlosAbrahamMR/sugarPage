<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosPersonales extends Model
{
    use HasFactory;

     protected $fillable = [
        'nombre',
        'edad',
        'sexo',
        'color_piel',
        'color_cabello',
        'estatura',
        'complexion',
        'idioma',
        'descripcion',
        'users_id',
    ];

    public function Users(){
        return $this->hasOne("App\User", "id", "users_id");
    }
}
