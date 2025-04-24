<?php

namespace App\Http\Controllers;

use App\Models\Publicaciones;
use App\Models\Seguidores;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $contenido = "false";

        if(Cache::has('images')){
            Cache::put('images', []);
        }
        if(Cache::has('videos')){
            Cache::put('videos', []);
        }
        return view('hometmp', compact('contenido'));
    }

    /**
     * Vista del contenido que se muestra en menu 'Content List'
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function contenido() {
        Cache::put('images', []);
        if(Cache::has('images')){
            Cache::put('images', []);
        }
        $publicaciones = [];
        $contenido = "true";

        return view('hometmp', compact('publicaciones', 'contenido'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function multimedia() {
        if(Cache::has('videos')){
            Cache::put('videos', []);
        }
        return view('publicaciones.multimedia');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function contenidoListado() {
        return view('contenido.ver-contenido');
    }

    public function getFollow(){
        $user = Auth::user();
        if ($user->roles_id === 2) {
            $suscripciones = Suscripcion::where('users_fan_id', $user->id)->where('status', 'Activa')->count();
            $suscripciones .= $suscripciones === 1 ? ' Suscripcion' : ' Suscripciones';
            $seguidores = Seguidores::where('users_origen_id', $user->id)->count();
            $seguidores .= $seguidores === 1 ? ' Seguido' : ' Seguidos';
        } else {
            $suscripciones = Suscripcion::where('users_creador_id', $user->id)->where('status', 'Activa')->count();
            $suscripciones .= $suscripciones === 1 ? ' Fan' : ' Fans';
            $seguidores = Seguidores::where('users_destino_id', $user->id)->count();
            $seguidores .= $seguidores === 1 ? ' Seguidor' : ' Seguidores';
        }

        return response()->json(['data' => ['suscripciones'=>$suscripciones, 'seguidores'=>$seguidores], 'status'=>'exito']);
    }
}
