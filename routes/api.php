<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlertasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'registro'], function () {
    
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('usuario', [RegistroController::class, 'crearUsuario']);

Route::get('/suscripciones', [AdminController::class, 'suscripciones']);

Route::get('/alertas/subastafin', [AlertasController::class, 'alertasSubastas']);

Route::get('/actualizarsuscripciones', [AlertasController::class, 'actualizarsuscripciones']);

//Route::get('/alertas/subastaganador', [AlertasController::class, 'alertasSubastas']);
