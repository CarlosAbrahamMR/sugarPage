<?php

namespace App\Http\Controllers;

use App\Mail\MensajeGenerico;
use App\Mail\NuevaOfertaSubasta;
use App\Models\Notificaciones;
use App\Models\OfertaSubasta;
use App\Models\Pago;
use App\Models\Plan;
use App\Models\Subastas;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;

class AlertasController extends Controller
{
    public function alertasSubastas(){// alerta subastas que se terminan
        $fecha_actual = date("Y-m-d");
        //sumo 1 día
        $fechahoy= date("Y-m-d",strtotime($fecha_actual."+ 1 days")); 
        
        $subastas=Subastas::where('estatus','activo')->where('fecha_fin',$fechahoy)->get();
        foreach($subastas as $subasta){
            $ofertas=OfertaSubasta::where('subastas_id',$subasta->id)->select('users_id')->distinct('users_id')->get()->toArray();
            foreach($ofertas as $oferta){
                $user_destino = User::where('id', $oferta['users_id'])->first();
                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $oferta['users_id'];
                $notifi->users_origen_id = $subasta->users_id;
                $notifi->descripcion = "La subasta terminara pronto.";
                $notifi->save();

                if ($user_destino->recibir_notificacion){
                    //enviar correo
                    $data=[
                        "mensaje"=> "La subasta terminara pronto.",
                        "nombre" => $user_destino->name
                    ];
                    Mail::to([$user_destino->email])->send(new MensajeGenerico($data));
                }
            }
        }
        return response()->json('ok');
        dd($fechahoy,$subastas);
    }

    public function actualizarsuscripciones(){// actualiza las suscripciones
        $suscripciones= Suscripcion::where('status','Activa')
        ->join("subscriptions","subscriptions.id", "=", "suscripciones.subscription_id")
        ->get();
        
        $fecha_actual = date("Y-m-d");
        foreach($suscripciones as $suscripcion){
            $fechasuscripcionfin= date("Y-m-d",strtotime($suscripcion->fecha_fin));
            $fecha_actual= strtotime($fecha_actual."- 1 days");// se le resta uno para verificar que sean iguales y solo poner las que pasaron ayer
            $fechasuscripcionfin= strtotime($fechasuscripcionfin);
            if($fecha_actual == $fechasuscripcionfin ){
                $plan= Plan::find($suscripcion->plan_id);
                //dd($suscripciones,$plan);
                $key = config('cashier.secret');
                $stripe = new StripeClient($key);

                try{
                    $sus=$stripe->subscriptions->retrieve(
                        $suscripcion->stripe_id,
                        []
                      );

                } catch (\Exception $exception) {
                    //dd($exception);
                    return true;
                }
                if($sus->status == "active"){// generar pago en DB
                    
                    $pago= new Pago;
                    $pago->user_recive=$suscripcion->users_creador_id;
                    $pago->user_paga=$suscripcion->users_fan_id;
                    $pago->estatus='Complet';
                    $pago->tipo='suscripcion';
                    $pago->fecha_pago= date("Y-m-d H:m:s");
                    $pago->fecha_cobro= date("Y-m-d H:m:s");
                    $pago->importe=$plan->monto;
                    $pago->save();

                    $nuevafechavencimiento= date("Y-m-d H:m:s",strtotime($suscripcion->fecha_fin."+ 1 month"));
                    //dd($nuevafechavencimiento);
                    $suscripcion->fecha_fin= $nuevafechavencimiento;
                    $suscripcion->update();
                }else{
                    $suscripcion->status=$sus->status;
                    $suscripcion->update();
                }
                    
            }
            //dd($fecha_actual,$fechasuscripcionfin,$sus);
        }
        return response()->json('no');
       // dd($suscripciones);
    }
}
