<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class AdminController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if ($user->roles_id === 1) {
            $users = User::select('name','username','email', 'creador', 'fan', 'status')->where('roles_id', '!=', 1)->get();

            return view('admin.users.usersList')->with(['users' => $users]);
        } else {
            redirect()->to('/promotions')->with(['message'=>'You are not authorized to access this option.', 'error'=>true]);
        }
    }

    public function upadateUser($email, Request $request)
    {
        $user = \Auth::user();
        if ($user->roles_id === 1) {
            $users = User::where('email', $email)->first();
            $users->status = $request->status;
            $users->save();

            redirect()->to('/users');
        } else {
            redirect()->to('/users')->with(['message'=>'You are not authorized to access this option.', 'error'=>true]);
        }

    }

    public function suscripciones(){
        $data['confirmation_code']='asdfg';
        $data['name'] =  'daniel cron';
            $data['email'] =  'pantaleondan@gmail.com';

            $data['appName'] = env('APP_NAME', 'MysugarFan');
            Mail::send('email.confirmation', $data, function($message) use ($data) {
                $message->to($data['email'], $data['name'])->subject('Por favor confirma tu correo');
            });
    }
}
