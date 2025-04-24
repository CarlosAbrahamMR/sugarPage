<?php

namespace App\Http\Controllers;

use App\Models\ComentarioRecompensa;
use App\Models\ProductoVendido;
use App\Models\Recompensa;
use App\Models\Subastas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecompensasController extends Controller
{
    public function listaRecompensas(){
        $user = Auth::user();
        $recompensas = ProductoVendido::where('users_id', $user->id)->get();
        return view('recompensas.lista');
    }
    public function listaRecompensasfan(){
        $user = Auth::user();
        $subastas= Subastas::where('user_final_id',$user->id)->select('recompensa_id')->get()->toArray();
        $productos= ProductoVendido::join('productos', 'productos_vendidos.productos_id', '=', 'productos.id')
            ->where('productos_vendidos.users_id', $user->id)
            ->select('productos.recompensas_id')
            ->get()
            ->toArray();

        $recompensasSubastas= Recompensa::whereIn('id',$subastas)->with('imagenes')->with('comentarios')->get();
        $recompensasProductos= Recompensa::whereIn('id',$productos)->with('imagenes')->with('comentarios')->get();
        $recompensas =$recompensasProductos->concat($recompensasSubastas);
//        dd($subastas,$recompensas);
        return view('recompensas.lista-fan',compact('recompensas'));
    }

    public function valorarRecompensa(Request $request) {
        $user = Auth::user();

        $comentario = ComentarioRecompensa::where('users_id', $user->id)->where('recompensas_id', $request->idRecompensa)->first();
        if ($comentario){
            $comentario->comentario = $request->comentario;
            $comentario->valoracion = $request->valoracion;
            $comentario->update();

            return redirect()->back();

        } else {
            $comentario = new ComentarioRecompensa();
            $comentario->comentario = $request->comentario;
            $comentario->valoracion = $request->valoracion;
            $comentario->users_id = $user->id;
            $comentario->recompensas_id = $request->idRecompensa;
            $comentario->save();

            return redirect()->back();
        }
    }
}
