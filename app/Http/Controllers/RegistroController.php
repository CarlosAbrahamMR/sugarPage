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
    // Sanear y normalizar el email
    $cleanEmail = strtolower(trim(filter_var($request->email, FILTER_SANITIZE_EMAIL)));

    // Verificar si ya existe un usuario con ese email
    $user = User::where('email', $cleanEmail)->first(['id']);

    if ($user) {
        return redirect()->back()->withInput()->with('error', 'The email already exists.');
    }

    try {
        \DB::beginTransaction();

        $maxId = \DB::table('users')->max('id');
        $maxId = $maxId !== null ? $maxId : 1;

        $user = new User;
        $user->email = $cleanEmail;
        $user->name = $this->filtro($request->name);
        $user->username = $maxId . mt_rand(10000000, 99999999);
        $user->password = \Hash::make($request->password);
        $user->confirmation_code = Str::random(25);
        $user->roles_id = 2;
        $user->confirmed = 1;
        $user->save();

        \DB::commit();

        // Enviar correo de confirmación
        $data = [
            'confirmation_code' => $user->confirmation_code,
            'name' => $user->name,
            'email' => $user->email,
            'appName' => env('APP_NAME', 'MysugarFan'),
        ];

        Mail::send('email.confirmation', $data, function($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject('Por favor confirma tu correo');
        });

        return redirect()->to('/login')->with('success', 'Registro exitoso, deberás confirmar tu correo para poder iniciar sesión.');
        
    } catch (\Exception $e) {
        \DB::rollBack();

        // Borra el usuario parcialmente creado si aplica
        if (isset($user->id)) {
            \DB::table('users')->where('id', $user->id)->delete();
        }

        return redirect()->back()->withInput()->with('error', 'An error occurred, try again later.');
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
