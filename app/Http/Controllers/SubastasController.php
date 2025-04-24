<?php

namespace App\Http\Controllers;

use App\Mail\MensajeGenerico;
use App\Mail\NuevaOfertaSubasta;
use App\Mail\NuevaSuscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subastas;
use App\Models\ImgSubasta;
use App\Models\Imagenes;
use App\Models\Suscripcion;
use App\Models\OfertaSubasta;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stripe\StripeClient;
use Stripe\Exception\CardException;
use App\Models\Notificaciones;
use App\Models\Pago;
use App\Models\Recompensa;

class SubastasController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $subastas=Subastas::where('users_id',$user->id)->where('estatus','activo')->orWhere('estatus','Pago')->with('imagenes')->get();
        //dd($subastas);
        return view('subastas.subastas', compact('user','subastas'));
    }
    public function new()
    {
        $user = Auth::user();

        return view('subastas.subastas-new', compact('user'));
    }

    public function create(Request $request){

//        dd($request);

        $this->validate(request(), [
            "subasta" => "required",
            "name"=> "required",
            "price" => "required",
            "descripcion"=> "required",
            "Fecha_inicio" => "required",
            "Fecha_fin" => "required"
        ]);
        $subasta= Subastas::where('estatus','imagen')->where('user_final_id',$request->subasta)->first();
        if (!$subasta){
            return redirect()->back()->withInput()->with(['Error' => 'Debes subir una imagen']);
        }
        $img=false;
        if($request->tiporecompensa == null ){
            return back()->with('Error', ['title' => __("Error"), 'message' => 'Error intentar de nuevo']);
        }
        $recompensa= new Recompensa;
        $recompensa->tipo= strval($request->tiporecompensa);

        if($request->tiporecompensa == 0  ){// 0 live chat
            $this->validate(request(), [
                "Fecha_recompensa" => "required"
            ]);
            $recompensa->fecha_evento= $request->Fecha_recompensa;

        }else if($request->tiporecompensa == 1  ){// 0 otro
            $this->validate(request(), [
                "recompensa" => "required",
                "fechaRecompensa" => "required"
            ]);
            if($request->fechaRecompensa == 0){
                // validar que se alla subido la imagen
                $img=true;
            }
            $fecha_actual = date("d-m-Y");
            $fecha= date("Y-m-d",strtotime($fecha_actual."+ ".$request->fechaRecompensa." days"));
            $recompensa->fecha_recompensa= $fecha;
            $recompensa->descripcion= $request->recompensa;

        }

        $recompensa->save();

        if($subasta){
            $subasta->nombre= $request->name;
            $subasta->descripcion=$request->descripcion;
            $subasta->precio_inicial=$request->price;
            $subasta->user_final_id=NULL;
            $subasta->Fecha_inicio=$request->Fecha_inicio;
            $subasta->Fecha_fin=$request->Fecha_fin;
            $subasta->estatus='activo';
            $subasta->recompensa_id= $recompensa->id;
            $subasta->update();
        }else{
            $subasta= new Subastas;
            $subasta->users_id= Auth::user()->id;
            $subasta->user_final_id=$request->subasta;
            $subasta->nombre= $request->name;
            $subasta->descripcion=$request->descripcion;
            $subasta->precio_inicial=$request->price;
            $subasta->user_final_id=NULL;
            $subasta->Fecha_inicio=$request->Fecha_inicio;
            $subasta->Fecha_fin=$request->Fecha_fin;
            $subasta->estatus='activo';
            $subasta->recompensa_id= $recompensa->id;
            $subasta->save();
        }

        if($img){
            return redirect()->route('auctions.img',$recompensa->id)->with('succes', 'La subasta se creo correctamente');
        }

        return redirect()->route('auctions.list')->with('succes', 'La subasta se creo correctamente');

    }

    public function imagen($recompensa){
        return view('subastas.subir-imagen', compact('recompensa'));
    }

    public function subir_imagen(Request $request){
        //dd($request);
        try {
            $this->validate($request, [
                'file' => 'required|image|mimes:jpg,png,jpg,gif,svg|max:1000000',
                'subasta' => 'required'
            ]);

            $file = $request->file('file');
            $fileName = uniqid() . $file->getClientOriginalName();
            $path = '/images/subastas/'.$fileName;

            Storage::disk('public')->put($path, file_get_contents($file));

            $subasta= new Subastas;
            $subasta->users_id= Auth::user()->id;
            $subasta->user_final_id=$request->subasta;
            $subasta->estatus='imagen';
            $subasta->save();

            /* $imagen= new Imagenes;
            $imagen->path= $path;
            $imagen->nombre=$fileName;
            $imagen->save(); */

            $imagen         = new Imagenes();
            $imagen->path   = '/images/subastas/';
            $imagen->nombre = $fileName;
            $imagen->save();
            $subasta->imagenes()->attach($imagen);


            /* $imagensubasta= new ImgSubasta;
            $imagensubasta->subastas_id= $subasta->id;
            $imagensubasta->imagenes_id = $imagen->id;
            $imagensubasta->save(); */



            return response()->json([
                'estatus' => true,
                'mensaje' => ''
            ]);

        } catch (\Exception $e) {
            //dd($e);
            return response()->json([
                'estatus' => false,
                'mensaje' => $e->getMessage()
            ]);
        }

    }

    public function subir_imagen_recompensa(Request $request){
        //dd($request);
        try {
            $this->validate($request, [
                'file' => 'required|image|mimes:jpg,png,jpg,gif,svg|max:1000000',
                'subasta' => 'required'
            ]);

            $file = $request->file('file');
            $fileName = uniqid() . $file->getClientOriginalName();
            $path = '/images/subastas/recompensas/'.$fileName;

            Storage::disk('public')->put($path, file_get_contents($file));


            $recompensa= Recompensa::find($request->subasta);

            /* $imagen= new Imagenes;
            $imagen->path= $path;
            $imagen->nombre=$fileName;
            $imagen->save(); */

            $imagen         = new Imagenes();
            $imagen->path   = '/images/subastas/recompensas/';
            $imagen->nombre = $fileName;
            $imagen->save();
            $recompensa->imagenes()->attach($imagen);


            /* $imagensubasta= new ImgSubasta;
            $imagensubasta->subastas_id= $subasta->id;
            $imagensubasta->imagenes_id = $imagen->id;
            $imagensubasta->save(); */



            return response()->json([
                'estatus' => true,
                'mensaje' => ''
            ]);

        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'estatus' => false,
                'mensaje' => $e->getMessage()
            ]);
        }

    }

    public function verSubastas(){
        $user = Auth::user();
        $suscripciones= Suscripcion::where('users_fan_id',$user->id)->select('users_creador_id')->get();
        $ids=[];
        foreach($suscripciones as $suscripcion){
            $ids[]=$suscripcion->users_creador_id;
        }
        //dd($suscripciones,$ids);
        $subastas=Subastas::whereIn('users_id',$ids)
        ->where('estatus','activo')
        ->with('imagenes')
        ->get();
        //dd($subastas);
        return view('subastas.subastas-creador', compact('user','subastas'));

    }

    public function detail($id){

        $subasta=Subastas::where('id',$id)->with('imagenes')->first();
        $imagenes=[];
        foreach($subasta->imagenes as $key => $imagen){
            $imagenes[$key]['src']=$imagen->path.$imagen->nombre;

        }
        $fecha_actual = strtotime(date("Y-m-d 00:00:00",time()));
        $fecha_inicio = strtotime($subasta->inicio);
        $fecha_fin = strtotime($subasta->fecha_fin);
        //dd($fecha_actual, $subasta->fecha_fin);
        $inicio=false;
        $fin=false;

        if($fecha_actual >= $fecha_inicio){
            $inicio=true;
        }
        if($fecha_actual <= $fecha_fin){
            $fin=true;
        }
        $ofertar=false;
        if($inicio && $fin){
            $ofertar=true;
        }
        $fecha1 = new \DateTime();
        $fecha1->format("Y-m-d H:i:s");

        $fecha2 = new \DateTime($subasta->fecha_fin);
        $fecha2->format("Y-m-d H:i:s");
        $dias= $fecha1->diff($fecha2)->format("%d");
        $meses= $fecha1->diff($fecha2)->format("%m");
        $hora= $fecha1->diff($fecha2)->format("%H");

        //dd($tiemporestante,date("Y-m-d", $horas));
        //dd($fecha1,$fecha2);
        $ofertas=OfertaSubasta::where('subastas_id',$id)->orderBy('precio', 'desc')->first();
        $ganando=false;
        if($ofertas){
            if($ofertas->users_id == auth()->user()->id){
                $ganando=true;
            }
        }

        //dd($ofertas);

        return view('subastas.subastas-show',compact('imagenes','subasta','ofertas','ofertar','dias','meses','ganando','hora'));
        dd($id);

    }

    public function ofertar(Request $request, $id){
        $subasta=Subastas::find($id);
        $user = auth()->user();
        $oferta= new OfertaSubasta;
        $oferta->precio=$request->precio;
        $oferta->subastas_id =$id;
        $oferta->users_id =Auth::user()->id;
        $oferta->save();

        $ofertas=OfertaSubasta::where('subastas_id',$id)->select('users_id')->distinct('users_id')->get()->toArray();


        foreach($ofertas as $oferta){
            $user_destino = User::where('id', $oferta['users_id'])->first();
            if($oferta['users_id'] != $user->id){
                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $oferta['users_id'];
                $notifi->users_origen_id = auth()->user()->id;
                $notifi->descripcion = "Otro usuario acaba de hacer una nueva oferta.";
                $notifi->save();

                if ($user_destino->recibir_notificacion){
                    //enviar correo
                    Mail::to([$user_destino->email])->send(new NuevaOfertaSubasta($user_destino));
                }

            }

        }


        return back()->with('bien');
        dd($id,$request);
    }

    public function detailCreador($id){
        //dd($id);
        $subasta=Subastas::where('id',$id)->with('imagenes')->first();
        $imagenes=[];
        foreach($subasta->imagenes as $key => $imagen){
            $imagenes[$key]['src']=$imagen->path.$imagen->nombre;

        }

        $fecha_actual = strtotime(date("Y-m-d 00:00:00",time()));
        $fecha_entrada = strtotime($subasta->fecha_fin);
        //dd($subasta, $fecha_entrada);
        $cobrar=false;
        if($fecha_actual >= $fecha_entrada && $subasta->estatus != "Pago"){

            $cobrar=true;
        }

        $ofertas=OfertaSubasta::where('subastas_id',$id)->get();
        //dd($subasta->imagenes,$imagenes);

        return view('subastas.subastas-show-creador',compact('imagenes','subasta','ofertas','cobrar'));

    }


    public function cobrar(Request $request, $id){

        $subasta=Subastas::where('id',$id)->first();
        $recompensa= Recompensa::find($subasta->recompensa_id);
        $imagenes=[];
        $ofertas=OfertaSubasta::where('subastas_id',$id)->orderBy('precio','DESC')->first();
        $usuarioGanador=User::find($ofertas->users_id);


        //dd($subasta,$recompensa,$request);

        if($usuarioGanador){
            if(!$usuarioGanador->stripe_id){
                return redirect()->back()->whith('Error','Hay un error');
            }
        }




        try {
            $key = config('cashier.secret');
            $stripe = new StripeClient($key);

            /* $hola=$stripe->customers->allSources(
                'cus_MlGB1qeV8Ev5sL',
                ['object' => 'card', 'limit' => 3]
              );
            dd($hola); */
            $monto=$ofertas->precio*100;
           // dd($monto);
            $stripe->charges->create([
                'amount' => intval($monto),
                'currency' => 'usd',
                'customer' => $usuarioGanador->stripe_id,
                'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
              ]);


        } catch (CardException $exception) {
            dd($exception,'pay');
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route("billing.my_subscription")]
            );
        } catch (\Exception $exception) {
            dd($exception,'no');
            return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
        }

        $subasta->estatus='Pago';
        $subasta->user_final_id=$usuarioGanador->id;
        $subasta->update();

        $inicio=$DateAndTime = date('Y-m-d h:i:s');
        $pago= new Pago;
        $pago->user_recive=auth()->user()->id;
        $pago->user_paga=$usuarioGanador->id;
        $pago->estatus='Complet';
        $pago->tipo='subasta';
        $pago->fecha_pago=$inicio;
        $pago->fecha_cobro=$inicio;
        $pago->importe=$ofertas->precio;
        $pago->save();

        if($recompensa){
            if($recompensa->tipo == 0){//livechat
                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $usuarioGanador->id;
                $notifi->users_origen_id = auth()->user()->id;
                $notifi->descripcion = "Felicidades tu oferta fue la mejor, fecha del evento sera el $recompensa->fecha_evento";
                $notifi->save();
                if ($usuarioGanador->recibir_notificacion) {
                    //enviar correo
                    $data=[
                        "mensaje"=> "La fecha del evento sera el $recompensa->fecha_evento",
                        "nombre" => $usuarioGanador->name
                    ];
                    Mail::to([$usuarioGanador->email])->send(new MensajeGenerico($data));
                }
            }elseif ($recompensa->tipo == 1){//otro
                // Notificacion
                $notifi = new Notificaciones();
                $notifi->users_id = $usuarioGanador->id;
                $notifi->users_origen_id = auth()->user()->id;
                $notifi->descripcion = "Felicidades tu oferta fue la mejor";
                $notifi->save();
                if ($usuarioGanador->recibir_notificacion) {
                    //enviar correo
                    $data=[
                        "mensaje"=> "Felicidades tu oferta fue la mejor",
                        "nombre" => $usuarioGanador->name
                    ];
                    Mail::to([$usuarioGanador->email])->send(new MensajeGenerico($data));
                }
            }

        }

        $ofertasdos=OfertaSubasta::where('subastas_id',$subasta->id)->select('users_id')->distinct('users_id')->get()->toArray();
            foreach($ofertasdos as $oferta){
                $user_destino = User::where('id', $oferta['users_id'])->first();
                if($oferta['users_id'] != $usuarioGanador->id){
                    // Notificacion
                    $notifi = new Notificaciones();
                    $notifi->users_id = $oferta['users_id'];
                    $notifi->users_origen_id = $subasta->users_id;
                    $notifi->descripcion = "La subasta termino agradecemos su participación.";
                    $notifi->save();

                    if ($user_destino->recibir_notificacion){
                        //enviar correo
                        $data=[
                            "mensaje"=> "La subasta termino agradecemos su participación.",
                            "nombre" => $user_destino->name
                        ];
                        Mail::to([$user_destino->email])->send(new MensajeGenerico($data));
                    }
                }
            }

        return back()->with('succes','La subasta se cobro correctamente');
        dd($ofertas,$subasta,$usuarioGanador);
    }

    public function validarimagen($id){
        $recompensa= Recompensa::find($id);

        $imagenes=\DB::table('imagenes_recompensas')->where('recompensas_id',$id)->first();
        if($imagenes){
            return redirect()->route('auctions.list')->with('succes', 'La Imagen se agrego se creo correctamente');
        }else{
            return back()->with('Error', ['title' => __("Error"), 'message' => 'Debes subir al menos una imagen']);
        }
        dd($imagenes,$id);
    }
}
