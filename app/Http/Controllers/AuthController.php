<?php

namespace App\Http\Controllers;

use App\Models\Seguidores;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $languageController;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(LanguageController $languageController)
    {
        $this->languageController = $languageController;
    }

    public function index(){
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $this->languageController->switchLang($lang);
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        if (!is_null($user)) {
            if ($user->confirmed) {
                if (Auth::attempt($credentials)) {
                    return redirect()->to('/home');
                } else {
                    return redirect()->back()->withInput()->with('error', 'An error occurred, try again later.');
                }
            }else {
                $name = $user->name;
                $email = $user->email;
                return view('auth.verificar-correo', compact('name', 'email'));
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Usuario no existe.');
        }
    }

}
