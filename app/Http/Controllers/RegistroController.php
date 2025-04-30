<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DatosPersonales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class RegistroController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function crearUsuario(Request $request)
{
    // Limpiar y normalizar el email correctamente
    $cleanEmail = strtolower(trim(filter_var($request->email, FILTER_SANITIZE_EMAIL)));

$user = User::where('email', $cleanEmail)->first();

\Log::info('Intentando registrar email: ' . $cleanEmail);
\Log::info('¿Existe en BD?: ' . ($user ? 'SÍ' : 'NO'));

dd([
    'Email filtrado' => $cleanEmail,
    'Usuario encontrado' => $user,
]);

    if ($user) {
        return redirect()->back()->withInput()->with('error', 'The email already exists2.');
    }

    try {
        \DB::beginTransaction();

        $maxId = \DB::table('users')->max('id') ?? 1;

        $user = new User;
        $user->email = $cleanEmail;
        $user->name = $request->name;
        $user->username = $maxId . mt_rand(10000000, 99999999);
        $user->password = \Hash::make($request->password);
        $user->confirmation_code = Str::random(25);
        $user->roles_id = 2;
        $user->confirmed = 1;
        $user->save();

        \DB::commit();

        return redirect()->to('/login')->with('success', 'Registro exitoso. Confirma tu correo para iniciar sesión.');
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Error al crear usuario: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Ocurrió un error: ' . $e->getMessage());
    }
}



    public function filtro($string){

        $Newvalue1 = str_replace("/", "", $string);
        $Newvalue2 = str_replace("*", "", $Newvalue1);
        $Newvalue3 = str_replace("?", "", $Newvalue2);
        $Newvalue4 = str_replace(";", "", $Newvalue3);
        $Newvalue5 = str_replace("&", "", $Newvalue4);
        $Newvalue6 = str_replace("\\", "", $Newvalue5);
        $Newvalue7 = str_replace("+", "", $Newvalue6);
        $Newvalue8 = str_replace("(", "", $Newvalue7);
        $Newvalue9 = str_replace(")", "", $Newvalue8);
        $Newvalue10 = str_replace("[", "", $Newvalue9);
        $Newvalue11 = str_replace("]", "", $Newvalue10);
        $Newvalue12 = str_replace("<", "", $Newvalue11);
        $Newvalue13 = str_replace(">", "", $Newvalue12);
        $Newvalue14 = str_replace("{", "", $Newvalue13);
        $Newvalue15 = str_replace("}", "", $Newvalue14);
        $Newvalue16 = str_replace("`", "", $Newvalue15);
        $Newvalue17 = str_replace("|", "", $Newvalue16);
        $Newvalue18 = str_replace("\"", "", $Newvalue17);
        $Newvalue19 = str_replace("'", "", $Newvalue18);

        $Newvalue20 = filter_var($Newvalue19, FILTER_SANITIZE_STRING);

        return $Newvalue20;

    }


}
