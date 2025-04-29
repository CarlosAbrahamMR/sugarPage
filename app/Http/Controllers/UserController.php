<?php

namespace App\Http\Controllers;

use App\Models\Imagenes;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\ProductoVendido;
use App\Models\Publicaciones;
use App\Models\Seguidores;
use App\Models\Subastas;
use App\Models\Suscripcion;
use App\Models\UserComision;
use Illuminate\Http\Request;
use App\Models\DatosPersonales;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class UserController extends Controller
{


    public function profile()
    {
        $user = \Auth::user();
        $personales = $user->DatosPersonales;
        $publicaciones = Publicaciones::where('users_id', $user->id)->where('deleted', 0)->with('imagenes')->get();
        $intent = auth()->user()->createSetupIntent();
        $imagenPerfil = Imagenes::where('id', $user->path_imagen_perfil)->first();
        $promocion = UserComision::where('user_id', $user->id)->first();
        $paises = \DB::table('paises')->select('iso', 'nombre')->get();

        return view('user.profile', compact('user', 'personales', 'publicaciones', 'intent', 'imagenPerfil', 'promocion', 'paises'));
    }

    public function editProfile()
    {
        return view('user.editProfile');
    }

    public function getUserInfo() {
        $user = \Auth::user();
        $datosPersonales = DatosPersonales::where('users_id', $user->id)->first();
        $user->personales = $datosPersonales;
        return response()->json($user);
    }

    public function viewDatosPersonales(){

        $user = Auth::user();
        $personales = $user->DatosPersonales;

        return view('user.datosPersonales', compact('user', 'personales'));
    }

    public function guardarDatosPersonales(Request $request)
    {
        $user = \Auth::user();
//        dd($request);
        try {
            \DB::beginTransaction();

            User::where('id', $user->id)
                ->update(
                    [
                        'creador' => true,
                        'roles_id' => 3
                    ]
                );

            $file = $request->file('identificacion');
            $fileName = null;
            if ($file) {
                $fileName = uniqid() . $file->getClientOriginalName();
                $path = '/images/identificacion/' . $fileName;

                Storage::disk('public')->put($path, file_get_contents($file));

                $imagen = new Imagenes();
                $imagen->path = '/images/identificacion/';
                $imagen->nombre = $fileName;
                $imagen->save();
            }

            $personal_information = DatosPersonales::where('nombre', $request->username)->first();

            $personal_information                    = $personal_information ?: new DatosPersonales();
            $personal_information->nombre            = $request->username?: $user->name;
            $personal_information->sexo              = $request->sexual_orientation;
            $personal_information->color_cabello     = $request->hair_color;
            $personal_information->color_piel        = $request->skin_color;
            $personal_information->estatura          = $request->height;
            $personal_information->complexion        = $request->complexion;
            $personal_information->edad              = (int)$request->age ?: '';
            $personal_information->rango_edad        = $request->age_range ?: '';
            $personal_information->residencia        = $request->recidence;
            $personal_information->idioma            = $request->language;
            $personal_information->users_id          = $user->id;
            $personal_information->bio               = $request->bio;
            if ($fileName) {
                $personal_information->path_identificacion = '/images/identificacion/' . $fileName;
            }
            $personal_information->save();

            \DB::commit();

            return redirect()->to('/profile#editarPerfil')->with('personales', $personal_information);
        } catch (\Exception $e) {
            \DB::rollback();

            return redirect()->to('/profile#editarPerfil')->withInput()->with('errorEditData', $e->getMessage());
        }

    }

    public function editCuenta(Request $request, $id){
        $user = \Auth::user();
        $findUsername = User::where('username', strtolower($request->username))->where('id', '!=', $id)->first();
        $findEmail = User::where('email', $request->email)->where('id', '!=', $id)->first();

        if ($findEmail) {
            return redirect()->to('/profile#cuenta')->with('error-cuenta', 'The email has already been used by another user.');
        } elseif ($findUsername) {
            return redirect()->to('/profile#cuenta')->with('error-cuenta', 'User not available.');
        }
        try {
            User::where('id', $user->id)
                ->update(
                    [
                        'username' => strtolower($request->username),
                        'email' => $request->email,
                        'bio_fan' => $request->bio_fan,
                        'pais_origen' => $request->pais_origen,
                        'recibir_notificacion' => $request->has('notificaciones')
                    ]
                );

            $personal_information = DatosPersonales::where('users_id', $id)->first();
            if ($personal_information){
                return redirect()->to('/profile#cuenta')->with('personales', $personal_information);
            } else {
                return redirect()->to('/profile#cuenta');
            }
        } catch (\Exception $e) {
            return redirect()->to('/profile#cuenta')->with('errorRegistro', 'An error occurred, try again later.');
        }
    }

    public function updatePassword(Request $request)
    {
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return redirect()->to('/profile#password')->with("error", "Old Password Doesn't match!");
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->to('/profile#password')->with("success", "Password changed successfully!");
    }

    public function uploadPhotoProfile(Request $request){
        $file = $request->file('imagen');

        $fileName = uniqid() . $file->getClientOriginalName();
        $path = '/images/profile/'.$fileName;

        Storage::disk('public')->put($path, file_get_contents($file));

        $imagen         = new Imagenes();
        $imagen->path   = '/images/profile/';
        $imagen->nombre = $fileName;
        $imagen->save();

        User::whereId(auth()->user()->id)->update([
            'path_imagen_perfil' => '/images/profile/'.$fileName
        ]);

        return back()->with("success-profile", "Image changed successfully!");
    }

    public function uploadPhotoPortada(Request $request){
        $file = $request->file('file');

        $fileName = uniqid() . $file->getClientOriginalName();
        $path = '/images/portada/'.$fileName;

        Storage::disk('public')->put($path, file_get_contents($file));

        $imagen         = new Imagenes();
        $imagen->path   = '/images/portada/';
        $imagen->nombre = $fileName;
        $imagen->save();

        User::whereId(auth()->user()->id)->update([
            'path_imagen_portada' => '/images/portada/'.$fileName
        ]);

        return back()->with("success-profile", "Image changed successfully!");
    }

    public function recoverPassword(){
        return view('auth.passwords.recover');
    }

    // public function VerifyToken(Request $request){
    //     $response = $request['response'];
    //     $secret = env('RECAPCHA_SECRET_KEY');
    //     $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret='.$secret."&response=".$response );
    //     \Log::info('reCAPTCHA response:', $body); // <--- importante para ver todo
    //     return $response;
    // }


    public function VerifyToken(Request $request)
    {
        $responseToken = $request->input('response'); // <-- más claro que $request['response']
        $secret = env('RECAPCHA_SECRET_KEY');
    
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $responseToken,
        ]);
    
        $body = $response->json(); // <-- aquí parseamos la respuesta
    
        \Log::info('reCAPTCHA response:', $body); // <-- ahora sí existe $body
    
        return response()->json([
            'success' => $body['success'] ?? false,
            'score' => $body['score'] ?? null,
            'action' => $body['action'] ?? null,
            'errors' => $body['error-codes'] ?? []
        ]);
    }

//     public function VerifyToken(Request $request)
// {
//     $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
//         'secret' => env('RECAPCHA_SECRET_KEY'),
//         'response' => $request->input('response'),
//     ]);

//     $body = $response->json();

//     \Log::info('reCAPTCHA response:', $body); // <--- importante para ver todo

//     return response()->json($body);
// }

    public function dashboard()
    {
        $user = \Auth::user();

        $suscripciones = Pago::select('up.name as name', \DB::raw('SUM(importe) as pago'))
            ->join('users as u', 'pagos.user_recive', '=', 'u.id')
            ->leftJoin('users as up', 'pagos.user_paga', 'up.id')
            ->where('pagos.tipo', 'suscripcion')
            ->where('pagos.user_recive', $user->id)
            ->groupBy('up.name')
            ->get();

        $productos = Pago::select('up.name as name', \DB::raw('SUM(importe) as pago'))
            ->join('users as u', 'pagos.user_recive', '=', 'u.id')
            ->leftJoin('users as up', 'pagos.user_paga', 'up.id')
            ->where('pagos.tipo', 'producto')
            ->where('pagos.user_recive', $user->id)
            ->groupBy('up.name')
            ->get();

        $subastas = Pago::select('up.name as name', \DB::raw('SUM(importe) as pago'))
            ->join('users as u', 'pagos.user_recive', '=', 'u.id')
            ->leftJoin('users as up', 'pagos.user_paga', 'up.id')
            ->where('pagos.tipo', 'subasta')
            ->where('pagos.user_recive', $user->id)
            ->groupBy('up.name')
            ->get();

        $contenidos = Pago::select('up.name as name', \DB::raw('SUM(importe) as pago'))
            ->join('users as u', 'pagos.user_recive', '=', 'u.id')
            ->leftJoin('users as up', 'pagos.user_paga', 'up.id')
            ->where('pagos.tipo', 'contenido')
            ->where('pagos.user_recive', $user->id)
            ->groupBy('up.name')
            ->get();

        $subscriptores = Suscripcion::where('users_creador_id', $user->id)->get();
        $seguidores = Seguidores::where('users_destino_id', $user->id)->get();
        $ing_suscripcion = Pago::where('user_recive', $user->id)->where('tipo', 'suscripcion')->sum('importe');
        $ing_subastas = Pago::where('user_recive', $user->id)->where('tipo', 'subasta')->sum('importe');
        $ing_contenido = Pago::where('user_recive', $user->id)->where('tipo', 'contenido')->sum('importe');
        $ing_ofertas = Producto::where('users_id', $user->id)->sum(\DB::raw("precio * cantidad_vendida"));

        return view('contenido.ver-lista-fans')->with([
            'subscriptores' => $subscriptores ? count($subscriptores) : 0,
            'seguidores' => $seguidores ? count($seguidores) : 0,
            'ing_suscripcion' => $ing_suscripcion,
            'ing_subastas' => $ing_subastas,
            'ing_ofertas' => $ing_ofertas,
            'ing_contenido' => $ing_contenido,
            'suscripciones' => $suscripciones,
            'productos' => $productos,
            'subastas' => $subastas,
            'contenidos' => $contenidos,
        ]);
    }

    public function dashboardS(){
        $user = \Auth::user();
        $subscriptores = Suscripcion::where('users_creador_id', $user->id)->get();
        $seguidores = Seguidores::where('users_destino_id', $user->id)->get();
        $ing_suscripcion = Pago::where('user_recive', $user->id)->where('tipo', 'suscripcion')->sum('importe');
        $ing_subastas = Pago::where('user_recive', $user->id)->sum('importe');
        $ing_ofertas = Producto::where('users_id', $user->id)
                            ->sum(\DB::raw("precio * cantidad_vendida"));

        return view('contenido.ver-lista-fans')->with([
            'subscriptores' => $subscriptores ? count($subscriptores) : 0,
            'seguidores' => $seguidores ? count($seguidores) : 0,
            'ing_suscripcion' => $ing_suscripcion,
            'ing_subastas' => $ing_subastas,
            'ing_ofertas' => $ing_ofertas
        ]);
    }

    public function verificationNotice(){
        $promocion = '';
        return view('auth.verificationNotice', compact('promocion'));
    }

    public function verificationResend(Request $request)
    {
        $data['name'] =  $request->name;
        $data['email'] =  $request->email;
        $data['confirmation_code'] =  Str::random(25);
        $data['appName'] = env('APP_NAME', 'MysugarFan');

        $user = User::where('email', $request->email)->first();
        $user->confirmation_code = $data['confirmation_code'];
        $user->update();

        Mail::send('email.confirmation', $data, function($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject('Por favor confirma tu correo');
        });

        return redirect()->to('login')->with('resent' , '');
    }
}
