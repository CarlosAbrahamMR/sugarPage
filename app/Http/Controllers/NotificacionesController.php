<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificacionesRequest;
use App\Http\Requests\UpdateNotificacionesRequest;
use App\Models\Notificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listado()
    {
        $arrayNotificaciones = [];
        $user = Auth::user();
        $notificaciones = Notificaciones::where('users_id', $user->id)->where('visto', 'false')->OrderBy('created_at', 'DESC')->limit(6)->get();
        if ($notificaciones->isEmpty()) {
            return response()->json([
                'notificaciones' => [],
                'estatus' => false
            ]);
        }

        foreach ($notificaciones as $notificacion) {
            $image = $notificacion->UserOrigen->path_imagen_perfil;
            if (!$image) {
                $image = asset('images/user-profile.png');
            }
            $arrayNotificaciones[]=[
                'var' => $notificacion->id,
                'nombre' => $notificacion->UserOrigen->name,
                'imagen' => $image,
                'descripcion' => $notificacion->descripcion,
                'tiempo' => $notificacion->created_at->diffForHumans()
            ];
        }

        return response()->json([
            'notificaciones' => $arrayNotificaciones,
            'estatus' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $notificaciones = Notificaciones::find($request->get('id'));
            $notificaciones->visto = true;
            $notificaciones->update();

            return response()->json([
                'estatus' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'notificaciones' => $e->getMessage(),
                'estatus' => false
            ]);
        }
    }

}
