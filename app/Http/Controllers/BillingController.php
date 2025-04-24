<?php

namespace App\Http\Controllers;

use App\Mail\NuevaSuscripcion;
use App\Models\Notificaciones;
use App\Models\PagosPublicacion;
use App\Models\Publicaciones;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;
use App\Models\Plan;
use App\Models\Suscripcion;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\CardException;
use App\Models\Pago;
use App\Models\Tarjeta;
use App\Models\Suscripcionstripe;
Use App\Models\Cuentas;
use App\Models\StripeProducto;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function paymentMethodForm() {
        //$countries = Country::all();
        //dd(dd(auth()->user()->defaultPaymentMethod()->id));
        return view('pagos.metodo_de_pago', [
            'intent' => auth()->user()->createSetupIntent()
        ]);
    }

    /**
     * @throws CustomerAlreadyCreated
     */
    public function processPaymentMethod(Request $request) {
        $this->validate(request(), [
            "pm" => "required|string|starts_with:pm_|max:50",
        ]);
        //dd($request);
        try {
            if (!auth()->user()->hasStripeId()) {
                auth()->user()->createAsStripeCustomer([
                    "address" => [
                        "country" => 'US',
                    ]
                ]);
            }
            auth()->user()->updateDefaultPaymentMethod($request->pm);

            $key = config('cashier.secret');
            $stripe = new StripeClient($key);
            $hola=$stripe->customers->createSource(
                auth()->user()->stripe_id,
                ['source' => $request->stripe]
            );


        } catch (CardException $exception) {
            //dd($exception,'una');
                return back()->with('error', $exception->getMessage());
        } catch (\Exception $exception) {
            //dd($exception,'dos');
            return back()->with('error', $exception->getMessage());
        }
        //dd($hola);
        $tarjeta= new Tarjeta;
        $tarjeta->user_id=auth()->user()->id;
        $tarjeta->id_tarjeta=$hola->id;
        $tarjeta->save();
        //dd($hola);

        return back()
        ->with('succes', 'Metodo de pago actualizado correctamente');
    }

    public function crearplan(){
        //auth()->user()->id;
        $planes= Plan::where('users_id', auth()->user()->id)->where('estatus',"!=","por cancelar")
        ->where('estatus', "!=", "Deleted")->get();
        $planesgratis= Plan::where('users_id', 9)->where('monto', 0)->first();

        if($planesgratis){
            $plangratis=true;
            $planpaga=false;
            $valor=1;
        }else{
            $plangratis=false;
            $planpaga=true;
            $valor=0;
        }
        //dd($planes,$planesgratis);
        return view('planes.crear', compact('planes','plangratis','planpaga','valor'));
    }

    public function editplan ($id){
        //auth()->user()->id;
        $planes= Plan::where('users_id', auth()->user()->id)->get();
        $plan= Plan::where('id', $id)->first();
//dd($plan);
        //dd($planes,$planesgratis);
        return view('planes.edit', compact('id', 'plan'));
    }

    public function deleteplan ($id){
        //auth()->user()->id;
        $plan= Plan::find($id);
        if(!$plan){
            return back()->with('Error', ['title' => __("Error"), 'message' => 'No se encontro un plan similar']);
        }
        $suscripciones= Suscripcion::where('plan_id',$plan->id)->get();
        $key = config('cashier.secret');
        $stripe = new StripeClient($key);
        //dd($suscripciones);
        if(count($suscripciones)){
            foreach($suscripciones as $suscripcion){
                $sus=DB::table('subscriptions')->find($suscripcion->subscription_id);
                //dd($sus,auth()->user()->id);
                $suscripcion->status ="deleted";
                $suscripcion->update();
                try{
                    $stripe->subscriptions->cancel(
                        $sus->stripe_id,
                        []
                    );

                } catch (\Exception $exception) {
                    //dd($exception);
                    return back()->with('Error', ['title' => __("Error"), 'message' => $exception->getMessage()]);
                }

                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $sus->user_id;
                $notifi->users_origen_id = auth()->user()->id;
                $notifi->descripcion = "El usuario ".auth()->user()->name." elimino su plan.";
                $notifi->save();


            }
            //dd($suscripciones);
        }
        // se elimina y crea uno nuevo
        try{
            $stripe->plans->delete(
                $plan->id_precio_stripe,
                []
            );
        } catch (\Exception $exception) {
            //dd($exception);
            return back()->with('Error', ['title' => __("Error"), 'message' => $exception->getMessage()]);
        }

        $plan->estatus="Deleted";
        $plan->update();


        //dd($plan);
        return redirect()->route('crear.plan')
          ->with('success', 'Se elimino correctamente');
    }

    public function editedplan (Request $request){
        $this->validate(request(), [
            "monto" => "required",
            "Tiempo" => "required",
            "tipo" => "required",
        ]);

        $plan= Plan::find($request->edit);

        if(!$plan){
            return back()->with('Error', ['title' => __("Error"), 'message' => 'No se encontro un plan similar']);
        }
        $key = config('cashier.secret');
        $stripe = new StripeClient($key);
        $suscripciones= Suscripcion::where('plan_id',$plan->id)->get();
        //dd($suscripciones);
        if(count($suscripciones)){
            foreach($suscripciones as $suscripcion){
                $sus=DB::table('subscriptions')->find($suscripcion->subscription_id);
                $suscripcion->status ="Cambio de plan";
                $suscripcion->update();
                try{
                    $stripe->subscriptions->cancel(
                        $sus->stripe_id,
                        []
                    );

                } catch (\Exception $exception) {
                    //dd($exception);
                    return back()->with('Error', ['title' => __("Error"), 'message' => $exception->getMessage()]);
                }
                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $sus->user_id;
                $notifi->users_origen_id = auth()->user()->id;
                $notifi->descripcion = "El usuario ".auth()->user()->name." edito el precio del plan.";
                $notifi->save();
            }
            //dd($suscripciones);
        }
        // se elimina y crea uno nuevo
            try{
                $stripe->plans->delete(
                    $plan->id_precio_stripe,
                    []
                );
            } catch (\Exception $exception) {
                //dd($exception);
                return back()->with('Error', ['title' => __("Error"), 'message' => $exception->getMessage()]);
            }
            $plan->estatus="por cancelar";
            $plan->update();
            $crearplan=$this->crearPlanstripe($request);

        //dd($plan);
        //dd($planes,$planesgratis);
        return redirect()->route('crear.plan')
          ->with('success', 'Se edito correctamente');
    }
    public function guardarplan(Request $request){
        $this->validate(request(), [
            "monto" => "required",
            "Tiempo" => "required",
            "tipo" => "required",
        ]);

        //dd($request,$pla);
        $pla=Plan::where('monto',$request->monto)->first();
        $nombrePlan=auth()->user()->id.'_'.auth()->user()->name.'_'.rand(0, 20).rand(0, 20).rand(0, 20).rand(0, 20);
        if($request->tipo == "1"){//gratis
            $plan= new Plan;
            $plan->users_id=auth()->user()->id;
            $plan->monto=$request->monto;
            $plan->estatus="activo";
            $plan->nombre=$nombrePlan;
            $plan->save();

        }else if($request->tipo == "0"){
            $monto=$request->monto*100;
            if($request->Tiempo == "12"){
                $interval_count=1;
                $intervalo="year";

            }else{
                $interval_count=$request->Tiempo;
                $intervalo="month";

            }

            $pla=Plan::where('intervalo',$intervalo)->where('users_id', auth()->user()->id)->where('estatus', 'activo')->first();
            if($pla){
                return back()->withInput()->with('Error', 'Ya tienes un plan similar');
            }
            //dd($pla,$nombrePlan);
            try {
                $key = config('cashier.secret');
                $stripe = new StripeClient($key);
                $stripeP= StripeProducto::where('users_id',auth()->user()->id)->first();
                if($stripeP){
                    $stripeProd=$stripeP;
                    $idproducto=$stripeProd->id_producto_stripe;
                }else{
                    $productoStripe =$stripe->products->create(
                        [
                        'name' => $nombrePlan,
                            /* 'default_price_data' => [
                                'unit_amount' => $monto,
                                'currency' => 'usd',
                                'recurring' => [
                                    'interval' => $intervalo,
                                    'interval_count'=> $interval_count
                                ],
                            ], */
                        ]
                    );

                    $idproducto=$productoStripe->id;

                    $stripeProd= new StripeProducto();
                    $stripeProd->users_id= auth()->user()->id;
                    $stripeProd->id_producto_stripe= $productoStripe->id;
                    $stripeProd->tipo="paga";
                    $stripeProd->save();
                }


                $planidstripe=$stripe->plans->create([
                    'amount' => $monto,
                    'currency' => 'usd',
                    'interval' => $intervalo,
                    'interval_count'=> $interval_count,
                    'product' => $idproducto,
                  ]);
                  //dd($planidstripe,$productoStripe);
                User::where('id', Auth::user()->id)
                    ->update(
                        [
                            'autorizo_marketing' => $request->has('autorizo_marketing')
                        ]
                    );
            } catch (CardException $exception) {
                dd($exception);
                return redirect()->route(
                    'cashier.payment',
                    [$exception->payment->id, 'redirect' => route("billing.my_subscription")]
                );
            } catch (\Exception $exception) {
                dd($exception);
                return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
            }
            /* $planStripe=$stripe->prices->create(
                [
                'product' => 'prod_Met9Jcqo38KOJy',
                'unit_amount' => 10000,
                'currency' => 'usd',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count'=> 2
                    ],
                ]
            ); */
            //dd($$planStripe->id);




            $plan= new Plan;
            $plan->users_id=auth()->user()->id;
            $plan->id_plan_stripe= $idproducto;
            $plan->id_precio_stripe= $planidstripe->id;
            $plan->monto=$request->monto;
            $plan->intervalo=$intervalo;
            $plan->interval_count=$interval_count;
            $plan->estatus="activo";
            $plan->nombre=$nombrePlan;
            $plan->plan_id= $stripeProd->id;
            $plan->save();
        }

        //dd($planStripe->id, $planStripe->default_price->id);

        /* $plans =$stripe->products->retrieve(
            'prod_MfCvC1PChIl1MH',
            []
          ) ; */
        return back()->with('success', 'Se creó el plan exitosamente');

        //      return back()->with('success', ['title' => __("¡Método de pago actualizado!"), 'message' => __("Tu método de pago ha sido actualizado correctamente")]);

    }

    public function processSubscription(Request $request){
       // dd($request);
        $this->validate(request(), [
            "precio" => "required|string",
            "creador"=> "required",
            "tipo"=> "required"
        ]);

        if($request->tipo == "0"){

            $susstripe= new Suscripcionstripe;
            $susstripe->user_id=auth()->user()->id;
            $susstripe->name="default";
            $susstripe->stripe_id='sin';
            $susstripe->stripe_status="active";
            $susstripe->save();

            $inicio=$DateAndTime = date('Y-m-d h:i:s');
            $suscripcion= new Suscripcion;
            $suscripcion->users_fan_id= auth()->user()->id;
            $suscripcion->users_creador_id=$request->creador;
            $suscripcion->status= "Activa";
            $suscripcion->fecha_inicio= $inicio;
            $suscripcion->subscription_id=$susstripe->id;
            $suscripcion->save();

            $user_destino = User::where('id', $request->creador)->first();

                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $request->creador;
                $notifi->users_origen_id = auth()->user()->id;
                $notifi->descripcion = "El usuario " . auth()->user()->name . " Acaba de suscribirse a su perfil.";
                $notifi->save();

                if ($user_destino->recibir_notificacion) {
                    //enviar correo
                    Mail::to([$user_destino->email])->send(new NuevaSuscripcion($user_destino));
                }

            return back()
            ->with('notification', ['title' => __("¡Gracias por contratar un plan!"), 'message' => __('Te has suscrito al plan  correctamente, recuerda revisar tu correo electrónico por si es necesario confirmar el pago')]);
        }

        $key = config('cashier.secret');
        $stripe = new StripeClient($key);
        $plan = $stripe->plans->retrieve(request("precio"));
        $price=$plan->amount/100;
        //dd($plan,$price);
        $inicio= date('Y-m-d h:i:s');
        if($plan->interval == "year"){
            $fechafin=date("Y-m-d h:i:s",strtotime($inicio."+ ".$plan->interval_count." year"));
        }else{
            $fechafin=date("Y-m-d h:i:s",strtotime($inicio."+ ".$plan->interval_count." month"));
        }
        //dd($plan,$inicio,$fechafin);
        if (auth()->user()->stripe_id == null) {
            return redirect()->to('/payment-method')->with('error', 'Ingresa un método de pago para poder suscribirte');
        }
        try {
            $sus=auth()
                ->user()
                ->newSubscription('default', request("precio"))
                ->create(auth()->user()->defaultPaymentMethod()->id);
                /* $sus=$stripe->subscriptions->create([
                    'customer' => auth()->user()->stripe_id ,
                    'items' => [
                      ['price' => request("precio")],
                    ],
                  ]); */
                //dd($sus);


        } catch (CardException $exception) {
            dd($exception);
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route("billing.my_subscription")]
            );
        } catch (\Exception $exception) {
            dd($exception);
            return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
        }

        $plan=Plan::where('id_precio_stripe',request("precio"))->where('users_id', $request->creador)->first();
        $inicio=$DateAndTime = date('Y-m-d h:i:s');
        $suscripcion= new Suscripcion;
        $suscripcion->users_fan_id= auth()->user()->id;
        $suscripcion->users_creador_id=$request->creador;
        $suscripcion->status= "Activa";
        $suscripcion->fecha_inicio= $inicio;
        $suscripcion->fecha_fin= $fechafin;
        $suscripcion->subscription_id=$sus->id;
        $suscripcion->plan_id=$plan->id;
        $suscripcion->save();


        $pago= new Pago;
        $pago->user_recive=$request->creador;
        $pago->user_paga=auth()->user()->id;
        $pago->estatus='Complet';
        $pago->tipo='suscripcion';
        $pago->fecha_pago=$inicio;
        $pago->fecha_cobro=$inicio;
        $pago->importe=$price;
        $pago->save();

        $user_destino = User::where('id', $request->creador)->first();

            // Notificacion
            $notifi = new Notificaciones();
            $notifi->users_id = $request->creador;
            $notifi->users_origen_id = auth()->user()->id;
            $notifi->descripcion = "El usuario " . auth()->user()->name . " Acaba de suscribirse a su perfil.";
            $notifi->save();

            if ($user_destino->recibir_notificacion) {
                //enviar correo
                Mail::to([$user_destino->email])->send(new NuevaSuscripcion($user_destino));
            }
            return back()
            ->with('notification', ['title' => __("¡Gracias por contratar un plan!"), 'message' => __('Te has suscrito al plan  correctamente, recuerda revisar tu correo electrónico por si es necesario confirmar el pago')]);
    }

    public function guardarcuenta(Request $request){
        //dd($request,auth()->user()->stripe_id);
        $this->validate(request(), [
            "account_number" => "required",
            "nombre"=> "required",
            "pais" => "required"
        ]);
        $arreglo=[];
        $currency=$this->traer($request->pais);
        $arreglo['object']='bank_account';
        $arreglo['country']=$request->pais;
        $arreglo['currency']=$currency;
        $arreglo['account_number']=$request->account_number;
        if($request->pais == "US"){
            //dd('entra');
            if($request->iban != null){
                $arreglo['routing_number']=$request->iban;

            }else{
                return back()->with('Error', ['title' => __("Error"), 'message' => 'ACH routing number required']);
            }

        }
        $arreglo['account_holder_name']=$request->nombre;
        $arreglo['account_holder_type']='individual';
        //dd($request,$arreglo);
        try{
            $key = config('cashier.secret');
            $stripe = new StripeClient($key);
            $hola=$stripe->customers->createSource(
                auth()->user()->stripe_id,
                ['source' => $arreglo]
            );
            //dd($hola);
        } catch (CardException $exception) {
            //dd($exception->getMessage(),'uno');
            return back()->with('Error', ['title' => __("Error"), 'message' => $exception->getMessage()]);

        } catch (\Exception $exception) {
            //dd($exception->getMessage(),'dos');
            return back()->with('Error', ['title' => __("Error"), 'message' => $exception->getMessage()]);
        }
        $cuenta= new Cuentas();
        $cuenta->user=auth()->user()->id;
        $cuenta->id_cuenta=$hola->id;
        $cuenta->status='Activa';
        $cuenta->save();
        return back()->with('succes', ['message' => 'La cuenta se dio de alta']);
        return back()->with('succes', 'La cuenta se dio de alta');
        dd($hola);
    }

    public function cuenta(){
        return view('cuentadebanco.index');
    }

    public function traer($pais){
        //dd($pais);
        switch ($pais) {
            case "US":
                $pais= "US";
                $curre= "usd";
                break;
            case "MX":
                $pais= "MX";
                $curre= "mxn";
                break;
        }

        return $curre;
    }

    public function crearPlanstripe($request){
        $pla=Plan::where('monto',$request->monto)->first();
        $nombrePlan=auth()->user()->id.'_'.auth()->user()->name.'_'.rand(0, 20).rand(0, 20).rand(0, 20).rand(0, 20);
        if($request->tipo == "1"){//gratis
            $plan= new Plan;
            $plan->users_id=auth()->user()->id;
            $plan->monto=$request->monto;
            $plan->estatus="activo";
            $plan->nombre=$nombrePlan;
            $plan->save();

        }else if($request->tipo == "0"){
            $monto=$request->monto*100;
            if($request->Tiempo == "12"){
                $interval_count=1;
                $intervalo="year";

            }else{
                $interval_count=$request->Tiempo;
                $intervalo="month";

            }

            $pla=Plan::where('interval_count',$interval_count)->where('intervalo',$intervalo)->where('estatus', 'activo')->first();
            if($pla){
                return back()->with('Error', 'Ya tienes un plan similar');
            }
            //dd($pla,$nombrePlan);
            try {
                $key = config('cashier.secret');
                $stripe = new StripeClient($key);
                $stripeP= StripeProducto::where('users_id',auth()->user()->id)->first();
                if($stripeP){
                    $stripeProd=$stripeP;
                    $idproducto=$stripeProd->id_producto_stripe;
                }else{
                    $productoStripe =$stripe->products->create(
                        [
                        'name' => $nombrePlan,
                            /* 'default_price_data' => [
                                'unit_amount' => $monto,
                                'currency' => 'usd',
                                'recurring' => [
                                    'interval' => $intervalo,
                                    'interval_count'=> $interval_count
                                ],
                            ], */
                        ]
                    );

                    $idproducto=$productoStripe->id;

                    $stripeProd= new StripeProducto();
                    $stripeProd->users_id= auth()->user()->id;
                    $stripeProd->id_producto_stripe= $productoStripe->id;
                    $stripeProd->tipo="paga";
                    $stripeProd->save();
                }


                $planidstripe=$stripe->plans->create([
                    'amount' => $monto,
                    'currency' => 'usd',
                    'interval' => $intervalo,
                    'interval_count'=> $interval_count,
                    'product' => $idproducto,
                  ]);
                  //dd($planidstripe,$productoStripe);
                User::where('id', Auth::user()->id)
                    ->update(
                        [
                            'autorizo_marketing' => $request->has('autorizo_marketing')
                        ]
                    );
            } catch (CardException $exception) {
                //dd($exception);
                return redirect()->route(
                    'cashier.payment',
                    [$exception->payment->id, 'redirect' => route("billing.my_subscription")]
                );
            } catch (\Exception $exception) {
                //dd($exception);
                return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
            }
            /* $planStripe=$stripe->prices->create(
                [
                'product' => 'prod_Met9Jcqo38KOJy',
                'unit_amount' => 10000,
                'currency' => 'usd',
                'recurring' => [
                    'interval' => 'month',
                    'interval_count'=> 2
                    ],
                ]
            ); */
            //dd($$planStripe->id);




            $plan= new Plan;
            $plan->users_id=auth()->user()->id;
            $plan->id_plan_stripe= $idproducto;
            $plan->id_precio_stripe= $planidstripe->id;
            $plan->monto=$request->monto;
            $plan->intervalo=$intervalo;
            $plan->interval_count=$interval_count;
            $plan->estatus="activo";
            $plan->nombre=$nombrePlan;
            $plan->plan_id= $stripeProd->id;
            $plan->save();
        }
        return 'ok';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comprarPublicacionStripe(Request $request) {

        $publicacion = Publicaciones::find($request->get('id'));
        $userPaga = Auth::user();
        if(!$publicacion){
            return response()->json([
                'estatus' => false
            ]);
        }

        try {
            $key = config('cashier.secret');
            $stripe = new StripeClient($key);
            $monto = $publicacion->precio*100;
            $stripe->charges->create([
                'amount' => intval($monto),
                'currency' => 'usd',
                'customer' => $userPaga->stripe_id,
                'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
            ]);

        } catch (CardException $exception) {
            return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
        } catch (\Exception $exception) {
            return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
        }

        try {
            \DB::beginTransaction();

            $pago= new Pago;
            $pago->user_recive=$publicacion->users->id;
            $pago->user_paga=Auth::user()->id;
            $pago->estatus='Complet';
            $pago->tipo='contenido';
            $pago->importe=$publicacion->precio;
            $pago->save();

            $pp = new PagosPublicacion();
            $pp->users_id = Auth::user()->id;
            $pp->publicaciones_id = $publicacion->id;
            $pp->pagos_id = $pago->id;
            $pp->importe = $publicacion->precio;
            $pp->save();

            \DB::commit();
            return response()->json([
                'estatus' => true
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'estatus' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}
