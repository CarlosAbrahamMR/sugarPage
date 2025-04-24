<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RecompensasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\SubastasController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SuscripcionController;
use App\Http\Controllers\SeguidoresController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BallanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return view('web.index');
});






Route::get('/about', function () {
    return view('web.about');
});
Route::get('/events', function () {
    return view('web.events');
});
Route::get('/contact', function () {
    return view('web.contact');
});
Route::get('/career', function () {
    return view('web.career');
});

Route::get('/prueba', function () {
    dd("¡La ruta funciona!");
});



Auth::routes();
Route::get('/verification/register/{code}', [VerificationController::class, 'verification']);
Route::post('/iniciar-sesion', [AuthController::class, 'login'])->name('iniciar-sesion');


Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('home')->with('addMessage','Registro exitoso ');
    }
    return view('register.register');
})->name('register');

Route::get('/fan-register', function () {
    if (Auth::check()) {
        return redirect()->route('home')->with('addMessage','Registro exitoso ');
    }
    return view('register.fan-register');
})->name('fan-register');

Route::group(['middleware' => 'auth'], function () {
    Route::post('obtener/publicaciones/contenido', [PublicacionesController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/contenido', [HomeController::class, 'contenido'])->name('contenido');
    Route::get('/contenido/multimedia', [HomeController::class, 'multimedia'])->name('multimedia');
    Route::get('/contenido/listado', [HomeController::class, 'contenidoListado'])->name('contenido.listado');
    Route::post('save-image', [PublicacionesController::class, 'store']);
    Route::post('save-video', [PublicacionesController::class, 'storeMultimedia']);
    Route::post('/eliminar/contenido/creador', [PublicacionesController::class, 'contenidoEliminar']);
    Route::post('save-publication', [PublicacionesController::class, 'save'])->name('save-publication');
    Route::post('guardar/comentario', [PublicacionesController::class, 'guardarComentario']);
    Route::post('buscar/publicacion', [PublicacionesController::class, 'busquedaComentario']);
    Route::post('obtener/publicaciones', [PublicacionesController::class, 'obtenerPublicaciones']);
    Route::post('obtener/videos', [PublicacionesController::class, 'obtenerVideos']);
    Route::get('get-user-info', [UserController::class, 'getUserInfo']);
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('save-personal-information', [UserController::class, 'guardarDatosPersonales'])->name('save-personal-information');
    Route::get('registrar-datos-personales', [UserController::class, 'viewDatosPersonales'])->name('registrar-datos-personales');
    Route::get('edit-profile', [UserController::class, 'editProfile'])->name('edit-profile');
    Route::patch('edit-cuenta/{id}', [UserController::class, 'editCuenta'])->name('edit-cuenta');
    /*Rutas Admin*/
    Route::get('users', [AdminController::class, 'index'])->name('users');
    Route::get('get-users', [AdminController::class, 'getUsers'])->name('get-users');
    Route::patch('update-users/{email}', [AdminController::class, 'upadateUser']);
    Route::delete('delete-users/{email}', [AdminController::class, 'deleteUser']);
    Route::get('promotions', [PromotionController::class, 'index'])->name('promotions');
    Route::get('get-promotions', [PromotionController::class, 'getPromotions'])->name('get-promotions');
    Route::post('create-promotion', [PromotionController::class, 'createPromotion']);
    Route::post('edit-promotion', [PromotionController::class, 'editPromotion']);
    Route::delete('delete-promotion/{id}', [PromotionController::class, 'deletePromotion']);

    /*Rutas Productos*/
    Route::get('products', [ProductoController::class, 'getProductos']);
    Route::post('save-product', [ProductoController::class, 'createProducto']);
    Route::get('view-product/{id}', [ProductoController::class, 'viewProduct']);
    Route::post('update-product/{id}', [ProductoController::class, 'updateProduct']);
    Route::post('save-image-offer', [ProductoController::class, 'saveImage']);
    Route::delete('delete-product/{id}', [ProductoController::class, 'deleteProduct']);
    Route::get('create-products', function (){
        return view('user.products.create');
    });
    Route::get('products/{creador}', [ProductoController::class, 'getProductsByCreator']);
    Route::post('comprar-producto', [ProductoController::class, 'comprarProducto'])->name('comprar-producto');
    Route::get('offers-creators', [ProductoController::class, 'getAllProducts'])->name('offers.list.creadores');
    Route::get('offers/img/{recompensa}', [ProductoController::class, 'imagen'])->name('offers.img');
    Route::post('subir-recompensa', [ProductoController::class, 'uploadRewards'])->name('upload.img');
    Route::post('subir-img-recompensa', [ProductoController::class, 'saveImageRewards'])->name('save.img');
    Route::get('get-comentarios-recompensas/{id}', [ProductoController::class, 'getComentariosRecompensas']);

    /*Rutas Subastas*/
    Route::get('auctions', [SubastasController::class, 'index'])->name('auctions.list');
    Route::get('auctions-creators', [SubastasController::class, 'verSubastas'])->name('auctions.list.creadores');
    Route::get('auctions/new', [SubastasController::class, 'new'])->name('auctions.new');
    Route::post('auctions/create', [SubastasController::class, 'create'])->name('auctions.create');
    Route::get('auctions/img/{recompensa}', [SubastasController::class, 'imagen'])->name('auctions.img');
    Route::get('auctions/img/{recompensa}/validar', [SubastasController::class, 'validarimagen'])->name('auctions.validar');
    Route::post('auctions-save-image', [SubastasController::class, 'subir_imagen']);
    Route::post('auctions-save-image-recompensa', [SubastasController::class, 'subir_imagen_recompensa']);
    Route::get('auctions/detail/{id}', [SubastasController::class, 'detail'])->name('auctions.detail');
    Route::post('auctions/detail/{id}/ofertar', [SubastasController::class, 'ofertar'])->name('auctions.ofertar');
    Route::post('auctions/detail/{id}/cobrar', [SubastasController::class, 'cobrar'])->name('auctions.cobrar');
    Route::get('auctions/detail/{id}/creador', [SubastasController::class, 'detailCreador'])->name('auctions.detail.creador');

    /*Rutas suscripciones contenido*/
    Route::get('search/content', [PerfilController::class, 'viewContent'])->name('search.content');
    Route::post('search/content', [PerfilController::class, 'viewContent'])->name('list.content');
    Route::get('my-subscriptions', [SuscripcionController::class, 'index'])->name('mis-suscripciones');


    Route::get("/payment-method", [BillingController::class,"paymentMethodForm"])->name("pago");
    Route::post("/payment-method", [BillingController::class,"processPaymentMethod"])->name("agreagar.tarjeta");

    Route::get("/plan/edit/{id}", [BillingController::class,"editplan"])->name("edit.plan");
    Route::post("/plan/delete/{id}", [BillingController::class,"deleteplan"])->name("delete.plan");
    Route::post("/plan/edited", [BillingController::class,"editedplan"])->name("edited.plan");
    Route::get("/plan/create", [BillingController::class,"crearplan"])->name("crear.plan");
    Route::post("/plan/new", [BillingController::class,"guardarplan"])->name("nuevo.plan");
    Route::post("/cuenta/new", [BillingController::class,"guardarcuenta"])->name("nuevo.cuenta");
    Route::get("/bankAccount", [BillingController::class,"cuenta"])->name("cuenta.index");
    Route::post("/buy/publication", [BillingController::class, "comprarPublicacionStripe"]);


    Route::post("/crear/suscripcion", [BillingController::class,"processSubscription"])->name("crear.suscripcion");
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('update-password');
    Route::post('/upload-photo-profile',[UserController::class , 'uploadPhotoProfile' ] )->name('upload-photo-profile');
    Route::post('/upload-photo-portada',[UserController::class , 'uploadPhotoPortada' ] )->name('upload-photo-portada');


    Route::get('/notificaciones/listado', [NotificacionesController::class, 'listado'])->name('notificacion.listado');
    Route::post('/notificaciones/update', [NotificacionesController::class, 'update'])->name('notificacion.update');


    /* Guardar Seguidor */
    Route::post('/seguidor/guardar', [SeguidoresController::class, 'guardarSeguidor']);
    Route::get('/my-following', [SeguidoresController::class, 'following'])->name('mis-seguidos');
    Route::get('/unfollow', [SeguidoresController::class, 'unfollow'])->name('unfollow');
    Route::get('/fans-list', [UserController::class, 'dashboard'])->name('fans.list');

    /*Rutas balance*/
    Route::get('/ballance', [BallanceController::class, 'index'])->name('ballance.index');
    Route::get('/get-ballance', [BallanceController::class, 'ballance']);

    Route::get('get-follow', [HomeController::class, 'getFollow']);

    Route::get('suscripcion-filtro', [BallanceController::class, 'ballanceFiltro']);

    Route::get('recompensas', [RecompensasController::class, 'listaRecompensas'])->name('lista.recompensas');
    Route::get('recompensas-fan', [RecompensasController::class, 'listaRecompensasfan'])->name('fan.recompensas');
    Route::post('valorar-recompensa', [RecompensasController::class, 'valorarRecompensa'])->name('valorar.recompensa');


});
Route::get('verification-notice', [UserController::class, 'verificationNotice'])->name('verification.notice');
Route::post('verification-resend', [UserController::class, 'verificationResend'])->name('verification.resend');
/*Rutas Usuario*/
Route::get('/{username}', [PerfilController::class, 'index'])->name('userByUsername')->middleware('auth');
Route::post('usuario', [RegistroController::class, 'crearUsuario']);
Route::get('/special-code', [PromotionController::class, 'view'])->name('special-code');
Route::post('/redeem-code', [PromotionController::class, 'redeemCode'])->name('redeem-code');
Route::get('/recover-pasword', [UserController::class, 'recoverPassword'])->name('recover-pasword');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
Route::post('token-recapcha-verify', [UserController::class, 'VerifyToken'])->name('token-recapcha-verify');

/*Rutas Suscripcion*/
Route::post('suscripcion', [SuscripcionController::class, 'crearsuscripcion'])->name('suscripcion');
Route::get('unsubscribe/{username}', [SuscripcionController::class, 'unsubscribe'])->name('unsubscribe');


Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Conexión a la base de datos establecida correctamente.";
    } catch (\Exception $e) {
        return "Error al conectar a la base de datos: " . $e->getMessage();
    }
});

