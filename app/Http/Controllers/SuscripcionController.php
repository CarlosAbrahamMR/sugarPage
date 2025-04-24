<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSuscripcionRequest;
use App\Http\Requests\UpdateSuscripcionRequest;
use App\Models\Seguidores;
use App\Models\Suscripcion;
use App\Models\User;
use Stripe\Exception\CardException;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
//        $suscripciones = Suscripcion::select('users_creador_id as id')->where('users_fan_id', $user->id)->get()->toArray();
        $creadores = User::join('suscripciones', 'users.id', '=', 'suscripciones.users_creador_id')
            ->join('plans', 'suscripciones.plan_id', '=', 'plans.id')
            ->where('suscripciones.users_fan_id', $user->id)
            ->where('suscripciones.status', '=', 'Activa')
            ->get();
        //dd($creadores);
        return view('contenido.mis-suscripciones', compact('user', 'creadores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unsubscribe($userid)
    {
        $creador= User::where('username',$userid)->first();
        $suscripcion= Suscripcion::where('users_fan_id',auth()->user()->id)->where('users_creador_id',$creador->id)->where('status', 'Activa')->first();
        if($suscripcion){
            $id=DB::table('subscriptions')->find($suscripcion->subscription_id);
            //dd($id);
            try{
                $key = config('cashier.secret');
                $stripe = new StripeClient($key);
                $cancelarsuscripcion=$stripe->subscriptions->cancel(
                    $id->stripe_id,
                    []
                  );
                //dd($cancelarsuscripcion);
            } catch (CardException $exception) {
                //dd($exception->getMessage(),'uno');
                return back()->with('error', $exception->getMessage());

            } catch (\Exception $exception) {
                //dd($exception->getMessage(),'dos');
                return back()->with('error', $exception->getMessage());
            }

            $suscripcion->status="Cancelar";
            $suscripcion->update();

            return back()->with('success', "Unsuscribe ok");
        }
        //dd($suscripcion);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSuscripcionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSuscripcionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return \Illuminate\Http\Response
     */
    public function show(Suscripcion $suscripcion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return \Illuminate\Http\Response
     */
    public function edit(Suscripcion $suscripcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSuscripcionRequest  $request
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSuscripcionRequest $request, Suscripcion $suscripcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suscripcion  $suscripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Suscripcion $suscripcion)
    {
        //
    }
}
