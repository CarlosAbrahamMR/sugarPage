<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Seguidores;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class SeguidoresController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardarSeguidor(Request $request) {

        try {
            $user = User::where('username', '=',  $request->get("username"))->first();

            \DB::beginTransaction();
            $seg = new Seguidores();
            $seg->users_origen_id = Auth::user()->id;
            $seg->users_destino_id = $user->id;
            $seg->status = "Activa";
            $seg->save();
            \DB::commit();

            return response()->json([
                'estatus' => true,
                'mensaje' => 'Success'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'estatus' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    public function following()
    {
        $user = auth()->user();

        $suscripcionesActivas = Suscripcion::select('users_creador_id as idCreador')
            ->where('status', 'Activa')
            ->distinct()
            ->get()
            ->toArray();

        $following = Seguidores::select(
            'users.id AS idCreador',
            'users.path_imagen_perfil',
            'users.name',
            'users.username',
            'seguidores_publicaciones.users_destino_id',
            'seguidores_publicaciones.users_origen_id'
        )
            ->join('users', 'seguidores_publicaciones.users_destino_id', 'users.id')
            //->leftJoin('suscripciones', 'seguidores_publicaciones.users_destino_id', '=', 'suscripciones.users_creador_id')
            //->where('suscripciones.status', '=', 'Activa')
            ->where('seguidores_publicaciones.users_origen_id', $user->id)
            ->where('seguidores_publicaciones.status', '=', 'Activa')
            ->distinct()
            ->get();

            $nuevosSeguidores = collect();
//dd($following);
            foreach ($following as $index => $follow){
                if ($this->in_array_r($follow->idCreador, $suscripcionesActivas)){
    //                $follow->delete();
    //                $following->pull($index);
                }else{
                    $nuevosSeguidores->push($follow);
                }
            }
    //dd($nuevosSeguidores);
        foreach ($nuevosSeguidores as $creador) {
            //dd($creador);
            $suscripcion = Suscripcion::where('users_creador_id', $creador->idCreador)->where('users_fan_id', $user->id)->first();
            $planes = Plan::where('users_id', $creador->idCreador)->where('estatus','activo')->get();
            $key = config('cashier.secret');
            $arreglo_planes = [];
            if ($planes) {
                foreach ($planes as $index => $plan) {
                    if ($plan->monto != 0) {
                        $stripe = new StripeClient($key);
                        $precioStripe = $stripe->prices->retrieve(
                            $plan->id_precio_stripe,
                            []
                        );
                        $arreglo_planes[$index]['id_precio'] = $precioStripe->id;
                        $arreglo_planes[$index]['id_plan'] = $precioStripe->product;
                        $arreglo_planes[$index]['monto'] = $precioStripe->unit_amount / 100;
                        $arreglo_planes[$index]['currency'] = $precioStripe->currency;
                        $arreglo_planes[$index]['interval'] = $plan->intervalo;
                        $arreglo_planes[$index]['idCreador'] = $plan->users_id;
                    } else if ($plan->monto == 0) {
                        $arreglo_planes[$index]['id_precio'] = 0;
                        $arreglo_planes[$index]['id_plan'] = 0;
                        $arreglo_planes[$index]['monto'] = 0;
                        $arreglo_planes[$index]['currency'] = 'usd';
                        $arreglo_planes[$index]['interval'] = $plan->intervalo;
                        $arreglo_planes[$index]['idCreador'] = $plan->users_id;
                    }
                }
            }
            $creador->planes = $arreglo_planes;
            $creador->suscrito = $suscripcion ? true : false;
        }
        $following=$nuevosSeguidores;
//dd($following);
        return view('contenido.mis-seguidos', compact('user', 'following'));
    }

    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    public function unfollow(Request $request) {
        try {
            $userFan = Auth::user();
            $user = User::where('username', '=',  $request->get("username"))->first();

            \DB::beginTransaction();
            $seg = Seguidores::where('users_destino_id', $user->id)->where('users_origen_id', $userFan->id)->first();
            $seg->delete();
            \DB::commit();

            return redirect()->back()->with('success', 'You unfollowed '.$request->get("username"));

        } catch (\Exception $e) {
            \DB::rollback();

            return redirect()->back()->with('error', 'An error occurred, try again later. '.$e->getMessage());
        }
    }

}
