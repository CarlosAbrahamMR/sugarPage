<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BallanceController extends Controller
{
    public function index(){
        $total= Pago::where('user_recive',auth()->user()->id)
            ->select(DB::raw('sum(importe) as Total'))
            ->get();



        $user_promocion =DB::table('user_comisiones')->where('user_id',Auth::user()->id)->first();
        $porcentajecreador=80;
        if($user_promocion){
            // dd('trae'); validar fecha
        }
        $total=number_format($total[0]->Total*$porcentajecreador/100 ,2);
        //dd($user_promocion,$total[0]->Total,$tot);
        //$pagos->select(\DB::raw('sum(importe) as Total, nombre as Categoria'));
        //dd($pagos);
        return view('balance.index', compact('total'));

    }

    public function ballance(){
        $pagos= Pago::where('user_recive',auth()->user()->id)
            ->join("users","users.id", "=", "pagos.user_paga")
            ->select('pagos.importe','users.username','pagos.fecha_pago','pagos.tipo')
            ->get();

        return response()->json(['pagos'=>$pagos]);

    }

    public function ballanceFiltro(Request $request){
        $fecha_inicio = $request->get('dateinicio') ?: null;
        $fecha_fin = $request->get('datefin');

        $pagos= Pago::where('user_recive',auth()->user()->id)
            ->join("users","users.id", "=", "pagos.user_paga")
            ->select('pagos.importe','users.username','pagos.fecha_pago','pagos.tipo')
            ->when($fecha_inicio, function ($query) use ($fecha_inicio, $fecha_fin){
                $query->whereBetween('pagos.fecha_pago', [$fecha_inicio, $fecha_fin]);
            })
            ->get();

        return response()->json(['pagos'=>$pagos]);
    }
}
