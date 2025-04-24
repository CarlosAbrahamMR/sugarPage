<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{

    protected $fillable = [
        'id',
        'publicaciones_id',
        'users_id',
        'descripcion',
        'deleted',
        'parent_id',
    ];

    use HasFactory;
    protected $table = 'comentarios_publicacion';

    public function publicaciones() {
        return $this->belongsTo(Publicaciones::class);
    }

    public function users(){
        return $this->hasOne(User::class, "id", "users_id");
    }

}
