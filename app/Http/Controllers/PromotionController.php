<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\UserComision;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        if ($user->roles_id === 1) {
            $promotions = Promotion::all();

            return view('admin.promotions.promotionsList')->with(['promotions' => $promotions]);
        } else {
            redirect()->to('/promotions')->with(['message'=>'You are not authorized to access this option.', 'error'=>true]);
        }

    }

    public function getPromotions()
    {
        $user = \Auth::user();
        if ($user->roles_id === 1) {
            $promotions = Promotion::all();

            return response()->json($promotions);
        } else {
            return redirect()->to('/promotions')->with(['message'=>'You are not authorized to access this option.', 'error'=>true]);
        }
    }

    public function createPromotion(Request $request)
    {
        $user = \Auth::user();
        if ($user->roles_id === 1) {
            $promotion = new Promotion;
            $promotion->codigo = $request->code;
            $promotion->porcentaje = $request->percent  ?: 0;
            $promotion->fecha_inicio = $request->fecha_inicio;
            $promotion->fecha_fin = $request->fecha_fin;
            $promotion->tiempo = $request->tiempo;
            $promotion->save();

            $promotions = Promotion::all();
            return redirect()->to('/promotions')->with('promotions', $promotions);
        } else {
            return redirect()->to('/promotions')->with(['message'=>'You are not authorized to access this option.', 'error'=>true]);
        }
    }

    public function editPromotion(Request $request)
    {
        $user = \Auth::user();
        if ($user->roles_id === 1) {
            Promotion::where('id', $request->id)
                ->update(
                    [
                        'fecha_inicio' => $request->fecha_inicio,
                        'fecha_fin' => $request->fecha_fin,
                    ]
                );

            $promotions = Promotion::all();
            return redirect()->to('/promotions')->with('promotions', $promotions);
        } else {
            return redirect()->to('/promotions')->with(['message'=>'You are not authorized to access this option.', 'error'=>true]);
        }
    }

    function deletePromotion($id){
        $product = Promotion::where('id', $id)->first();
        $product->delete();

        return response()->json(['message' => 'Se eliminó con éxito']);
    }

    public function redeemCode(Request $request) {
        $user = \Auth::user();
        $promocion = Promotion::where('codigo', $request->codeRedeem)->first();
        $promocion_inicia = date("Y-m-d");
        $promocion_termina = date("Y-m-d", strtotime($promocion_inicia . " + " . $promocion->tiempo . " month"));

        if ($promocion->fecha_fin < $promocion_inicia) {
            return redirect()->to('/profile')->with(['error' => __('traducciones.redeem_vigencia')]);
        }

        try {
            $redeem = new UserComision();
            $redeem->user_id = $user->id;
            $redeem->promocion_id = $promocion->id;
            $redeem->porcentaje_usuario = $promocion->porcentaje;
            $redeem->porcentaje_plataforma = 100 - $promocion->porcentaje;
            $redeem->promocion_inicia = $promocion_inicia;
            $redeem->promocion_termina = $promocion_termina;
            $redeem->save();

            return redirect()->to('/profile')->with(['success' => __('traducciones.redeem_exito')]);
        } catch (\Exception $e) {
            return redirect()->to('/profile')->with(['error' => __('traducciones.redeem_error')]);
        }

    }

    public function view(){
        $user = \Auth::user();
        $promocion = UserComision::where('user_id', $user->id)->first();
        return view('user.redeemCode', compact('promocion'));
    }
}
