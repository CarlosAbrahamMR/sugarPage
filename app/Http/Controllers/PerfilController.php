<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Publicaciones;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use App\Models\Suscripcion;

class PerfilController extends Controller
{

    /**
     * @param $tipo
     * @param $username
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function index(Request $request, $username)
    {
        $creador=User::where('username',$username)->where('creador', 1)->first();
        if (!$creador) {
            return redirect()->to('home');
        }
        $planes=Plan::where('users_id',$creador->id)->where('estatus',"activo")->get();
        $arreglo_planes=[];
        $key = config('cashier.secret');
        if($planes){
            foreach($planes as $index => $plan){
                if($plan->monto != 0){
                    /* $stripe = new StripeClient($key);
                    $precioStripe=$stripe->prices->retrieve(
                        $plan->id_precio_stripe,
                        []
                    ); */
                    $arreglo_planes[$index]['id_precio']=$plan->id_precio_stripe;
                    $arreglo_planes[$index]['id_plan']=$plan->id_plan_stripe;
                    $arreglo_planes[$index]['monto']=$plan->monto;
                    $arreglo_planes[$index]['currency']='usd';
                    $arreglo_planes[$index]['interval'] = $plan->intervalo;
                }else if($plan->monto == 0){
                    $arreglo_planes[$index]['id_precio']=0;
                    $arreglo_planes[$index]['id_plan']=0;
                    $arreglo_planes[$index]['monto']=0;
                    $arreglo_planes[$index]['currency']='usd';
                    $arreglo_planes[$index]['interval'] = $plan->intervalo;
                }

            }

        }
        // saber si ya esta suscrito o no
        $suscrito=false;
        $suscripcion=Suscripcion::where('users_fan_id',auth()->user()->id)->where('users_creador_id',$creador->id)->where('status', 'Activa')->first();
        if($suscripcion){
            $suscrito=true;
        }

        $intent = auth()->user()->createSetupIntent();
        $multimedia = (boolean) $request->has('mult');

        $productos = Producto::where('users_id', $creador->id)->with('imagenes')->get();
        return view('contenido.ver-contenido-creador', compact('creador','arreglo_planes', 'intent','suscrito', 'productos', 'multimedia'));
    }

    public function viewContent(Request $request){
        $user = auth()->user();
        $filter_gender = [];
        $filter_skin = [];
        $filter_hair = [];
        $filter_residence = [];
        $filter_language = [];
        $filter_complexion = [];

        if ($request->has('filtros')) {
            foreach ($request->filtros as $filter) {
                $explode = explode('-', $filter);
                switch ($explode[0]){
                    case 'GENDER':
                        $filter_gender[] = $explode[1];
                        break;
                    case 'HAIR':
                        $filter_hair[] = $explode[1];
                        break;
                    case 'SKIN':
                        $filter_skin[] = $explode[1];
                        break;
                    case 'RES':
                        $filter_residence[] = str_replace('_', ' ', $explode[1]);
                        break;
                    case 'LANG':
                        $filter_language[] = $explode[1];
                        break;
                    case 'COM':
                        $filter_complexion[] = $explode[1];
                        break;
                }
            }
        }

        $suscripciones = Suscripcion::select('users_creador_id as id')
        ->where('status',"Activa")
        ->where('users_fan_id', $user->id)->get()->toArray();

        $usuarios=User::join('datos_personales as dp', 'users.id', '=', 'dp.users_id')
            ->whereNotIn('users.id', $suscripciones)
            ->where('users.id','!=',auth()->user()->id)
            ->when($filter_gender, function ($query, $filter_gender){
                return $query->whereIn('dp.sexo', $filter_gender);
            })
            ->when($filter_skin, function ($query, $filter_skin){
                return $query->whereIn('dp.color_piel', $filter_skin);
            })
            ->when($filter_hair, function ($query, $filter_hair){
                return $query->whereIn('dp.color_cabello', $filter_hair);
            })
            ->when($filter_residence, function ($query, $filter_residence){
                return $query->whereIn('dp.residencia', $filter_residence);
            })
            ->when($filter_language, function ($query, $filter_language){
                return $query->whereIn('dp.idioma', $filter_language);
            })
            ->when($filter_complexion, function ($query, $filter_complexion){
                return $query->whereIn('dp.complexion', $filter_complexion);
            })
            ->get();

            //dd($suscripciones,$user->id);
        session()->flashInput($request->input());
        return view('contenido.listado-contenido', compact('usuarios'));
        dd('entra');
    }

    /**
     * @param $username
     * @param $id
     */
    public function profileUser($username, $id) {

        $user = User::where('username', '=', $username)->where('id', '=', $id)->first();
        $publicaciones = Publicaciones::where('users_id', $user->id)->where('deleted', 0)->orderBy('created_at', 'DESC')->get();

        return view('user.timeline', ['publicaciones' => $publicaciones, 'user' => $user]);
    }

}
