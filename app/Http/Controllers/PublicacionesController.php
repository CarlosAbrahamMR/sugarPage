<?php

namespace App\Http\Controllers;

use App\Models\Comentarios;
use App\Models\Imagenes;
use App\Models\PagosPublicacion;
use App\Models\Publicaciones;
use App\Models\Seguidores;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class PublicacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tipo = $request->get('tipo', 0);
        $creador=User::where('username',$request->get('username'))->where('creador', 1)->first();
        // saber si ya esta suscrito o no
        $suscrito=false;
        $suscripcion=Suscripcion::where('users_fan_id',auth()->user()->id)->where('users_creador_id',$creador->id)->where('status', 'Activa')->first();
        if($suscripcion){
            $suscrito=true;
        }

        // Se valida si el tipo es 1, pero no esta suscrito, se regresa el contenido publico
        if ($tipo==1 && !$suscrito) {
            $tipo = 0;
        }

        $publications = "active";
        $contenido = "";
        if ($tipo==1) {
            $publications = "";
            $contenido = "active";
        }

        $publicaciones = null;
        if ($request->get('isContenList', 0) || ($suscrito && $tipo==0)) {
            $publicaciones = Publicaciones::where('users_id', $creador->id)->where('deleted', 0)->where('precio', '=', null)->whereNotIn('tipo', [2])->orderBy('created_at', 'DESC')->get();
        } elseif($tipo == 1) {
            $publicaciones = Publicaciones::where('users_id', $creador->id)->where('deleted', 0)->where('precio', '!=', null)->whereNotIn('tipo', [2])->orderBy('created_at', 'DESC')->get();
        } else {
            $publicaciones = Publicaciones::where('users_id', $creador->id)->where('tipo', $tipo)->where('deleted', 0)->orderBy('created_at', 'DESC')->get();
        }

        $arrPublicaciones = [];
        foreach ($publicaciones as $publicacion) {
            $imgPerfil = $creador->path_imagen_perfil ? url('storage'.$creador->path_imagen_perfil) :asset("images/user-profile.png");
            $arrImages = [];
            $verificaPago = null;
            if ($publicacion->precio != null) {
                $verificaPago = PagosPublicacion::where('users_id', Auth::user()->id)->where('publicaciones_id', $publicacion->id)->first();
            }

            $precio = $publicacion->precio;
            if (!$verificaPago && $publicacion->precio != null) {
                $arrImages[] = asset("images/censorship.png");
            } else {
                $precio = 0;
                if ($publicacion->imagenes->count() > 1) {
                    foreach ($publicacion->imagenes as $imagen) {
                        $arrImages[] = url('storage' . $imagen->path . $imagen->nombre);
                    }
                } elseif(!$publicacion->imagenes->isempty()) {
                    $arrImages[] = url('storage'.$publicacion->imagenes()->first()->path.$publicacion->imagenes()->first()->nombre);
                }
            }
            $arrComentarios = [];
            if (!$publicacion->comentarios->isempty()) {
                $arrComentarios = [
                    "user" => $publicacion->comentarios->last()->users,
                    "profileImage" => $publicacion->comentarios->last()->users->path_imagen_perfil ? url('storage'.$publicacion->comentarios->last()->users->path_imagen_perfil) : asset("images/user-profile.png"),
                    "publicacion" => $publicacion->comentarios->last(),
                    'date' => $publicacion->comentarios->last()->created_at->diffForHumans(),
                    'date2' => $publicacion->comentarios->last()->created_at->format('j F Y'),
                ];
            }
//            dd($arrImages);
            $arrPublicaciones[] = [
                'id' => $publicacion->id,
                'tipo' => $publicacion->tipo,
                'descripcion' => $publicacion->descripcion,
                'precio' => $precio,
                'imgPerfil' => $imgPerfil,
                'nameUserPublicacion' => $publicacion->users->name,
                'date' => $publicacion->created_at->diffForHumans(),
                'date2' => $publicacion->created_at->format('j F Y'),
                'arrImages' => $arrImages,
                'comentarios' => $arrComentarios,
                'cantidadComentarios' => $publicacion->comentarios->count(),
            ];
        }

        return response()->json([
            'estatus' => true,
            'publications' => $publications,
            'contenido' => $contenido,
            'data' => $arrPublicaciones
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        try {
//            dd(Cache::get("images"), Cache::get("videos"));
            \DB::beginTransaction();

            $user = \Auth::user();
            $publicacion = new Publicaciones();
            $publicacion->descripcion = $request->get("texto");
            $publicacion->users_id = $user->id;
            $publicacion->tipo = $request->get("privado");

            if ((boolean) $request->get("incluyePrecio", false)) {
                $publicacion->precio = $request->get("precioPublicacion", null);
            }
            $publicacion->save();

            $storageCache = Cache::get("images");
            if($location = isset($storageCache['path'])) {
                if (isset($storageCache['files'])) {
                    foreach ($storageCache['files'] as $file) {
                        $img = new Imagenes();
                        $img->path = $storageCache['path'];
                        $img->nombre = $file;
                        $img->save();
                        $publicacion->imagenes()->attach($img);
                    }
                }
            }

            // publicacion con videos
            $storageVideo = Cache::get("videos");
            if($location = isset($storageVideo['path'])) {
                if (isset($storageVideo['files'])) {
                    foreach ($storageVideo['files'] as $file) {
                        $img = new Imagenes();
                        $img->path = $storageVideo['path'];
                        $img->nombre = $file;
                        $img->save();
                        $publicacion->imagenes()->attach($img);
                    }
                }
            }

            $publicacion->save();

            Cache::put('images', []);

            \DB::commit();

            return response()->json([
                'estatus' => true,
                'mensaje' => ''
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'estatus' => false,
                'mensaje' => 'Ocurrió un problema'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'file' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm,jpg,png,jpg,gif,svg|max:1000000'
            ]);
            $extensionesVideo = ['mp4','ogx','oga','ogv','ogg','webm'];
            $file = $request->file('file');
            $fileName = uniqid() . Auth::user()->id . $file->getClientOriginalName();
            $fileExtencion = pathinfo($fileName, PATHINFO_EXTENSION);
            if (in_array($fileExtencion, $extensionesVideo)){
                $this->storeMultimedia($request);
                return response()->json([
                    'estatus' => true,
                    'mensaje' => ''
                ]);
            }else{
                $path = '/images/publications/'.$fileName;

                Storage::disk('public')->put($path, file_get_contents($file));

                $images = [$fileName];
                $storageCache = Cache::get("images");
                if($location = isset($storageCache['path'])) {
                    if (isset($storageCache['files'])) {
                        $images = array_merge($images, $storageCache['files']);
                    }
                }

                $data = [
                    'id' => Auth::user()->id,
                    'path' => '/images/publications/',
                    'files' => $images
                ];

                Cache::put('images', $data);

                return response()->json([
                    'estatus' => true,
                    'mensaje' => ''
                ]);
            }

        } catch (\Exception $e) {
            throw $e;
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeMultimedia(Request $request)
    {
        set_time_limit(0);
        try {
            $this->validate($request, [
                'file' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            ]);

            $file = $request->file('file');
            $fileName = uniqid() . Auth::user()->id . $file->getClientOriginalName();
            $path = '/videos/'.$fileName;

            Storage::disk('public')->put($path, file_get_contents($file));

            $images = [$fileName];
            $storageCache = Cache::get("videos");
            if($location = isset($storageCache['path'])) {
                if (isset($storageCache['files'])) {
                    $images = array_merge($images, $storageCache['files']);
                }
            }

            $data = [
                'id' => Auth::user()->id,
                'path' => '/videos/',
                'files' => $images
            ];

            Cache::put('videos', $data);

            return response()->json([
                'estatus' => true,
                'mensaje' => ''
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardarComentario(Request $request) {

        try {
            \DB::beginTransaction();

            $user = \Auth::user();
            $comment = new Comentarios();
            $comment->users_id = $user->id;
            $comment->publicaciones_id = $request->get("publicacion");
            $comment->descripcion = $request->get("texto");
            $comment->save();

            \DB::commit();

            return response()->json([
                'estatus' => true,
                'mensaje' => ''
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'estatus' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function busquedaComentario(Request $request) {

        $id = $request->get("publicacion");

        $data = [];
        $publicacion = Comentarios::where('publicaciones_id', $id)->OrderBy('created_at', 'DESC')->get();
        foreach ($publicacion as $key => $value) {
            $data[$key]['descripcion'] = $value->descripcion;
            $data[$key]['username'] = $value->users->name;
            $data[$key]['imagen_perfil'] = $value->users->path_imagen_perfil ? url('storage'.$value->users->path_imagen_perfil) :asset("images/user-profile.png");
            $data[$key]['filename'] = "";
            $data[$key]['date'] = $value->created_at->diffForHumans();
        }

        return response()->json([
            'data' => $data,
            'estatus' => true,
            'mensaje' => ''
        ]);

    }

    /**
     * Metodo para obtener las publicaciones que se muestran en el Home
     *
     * @param Request $request
     */
    public function obtenerPublicaciones(Request $request) {

        // Variable para saber si se muestran los seguidores o aleatorios
        $explorar = $request->get('explorar', 0);
        $creadores = [];

        $seguidos = "";
        $cExplorar = "";

        $seguidores = Seguidores::where('users_origen_id', Auth::user()->id)->distinct()->select('users_destino_id')->get();
        if ($explorar == 0) {
            $seguidos = "active";
            $suscripciones = Suscripcion::where('users_fan_id', '=' , Auth::user()->id)->where('status','Activa')->distinct()->get();

            foreach ($suscripciones as $suscripcion) {
                //dd($suscripciones);
                if (!in_array($suscripcion->userCreador->id, $creadores)) {
                    $creadores[] = $suscripcion->userCreador->id;
                }
            }

            if (!$seguidores->isempty()) {
                $seg = User::whereIn('id', [$seguidores->toarray()])->whereNotIn('id', [Auth::user()->id])->distinct()->select('id')->get();
                foreach ($seg as $item) {
                    if (!in_array($item->id, $creadores)) {
                        $creadores[] = $item->id;
                    }
                }
            }

        } else {
            $cExplorar = "active";

            $filter = [Auth::user()->id];
            if (!$seguidores->isempty()) {
                foreach ($seguidores as $seguidor) {
                    array_push($filter, $seguidor->users_destino_id);
                }
            }
            $cre = User::where('creador', '1')->whereNotIn('id', $filter)->distinct()->select('id')->get();
            foreach ($cre as $item) {
                if (!in_array($item->id, $creadores)) {
                    $creadores[] = $item->id;
                }
            }
        }
        $arrPublicaciones = [];
        $publicaciones = Publicaciones::whereIn('users_id', $creadores)->where('deleted', 0)->whereNotIn('tipo', [2])->OrderBy('created_at', 'DESC')->get();

        foreach ($publicaciones as $publicacion) {
            $suscripcion=Suscripcion::where('users_fan_id',auth()->user()->id)->where('status','Activa')->where('users_creador_id',$publicacion->users->id)->where('status', 'Activa')->first();
            if(!$suscripcion && $publicacion->tipo==1){
                continue;
            }
            $imgPerfil = $publicacion->users->path_imagen_perfil ? url('storage'.$publicacion->users->path_imagen_perfil) :asset("images/user-profile.png");
            $arrImages = [];

            $verificaPago = null;
            if ($publicacion->precio != null) {
                $verificaPago = PagosPublicacion::where('users_id', Auth::user()->id)->where('publicaciones_id', $publicacion->id)->first();
            }

            $precio = $publicacion->precio;
            if (!$verificaPago && $publicacion->precio != null) {
                $arrImages[] = [asset("images/censorship.png")];
            } else {
                $precio = 0;
                if($publicacion->imagenes->count() > 1) {
                    $arrImages[] = $publicacion->imagenes->toArray();
                } elseif(!$publicacion->imagenes->isempty()) {
                    $arrImages[] = [url('storage'.$publicacion->imagenes()->first()->path.$publicacion->imagenes()->first()->nombre)];
                }
            }

            $arrComentarios = [];
            if (!$publicacion->comentarios->isempty()) {
                $arrComentarios = [
                    "user" => $publicacion->comentarios->last()->users,
                    "profileImage" => $publicacion->comentarios->last()->users->path_imagen_perfil ? url('storage'.$publicacion->comentarios->last()->users->path_imagen_perfil) : asset("images/user-profile.png"),
                    "publicacion" => $publicacion->comentarios->last(),
                    'date' => $publicacion->comentarios->last()->created_at->diffForHumans(),
                    'date2' => $publicacion->comentarios->last()->created_at->format('j F Y'),
                ];
            }
            $arrPublicaciones[] = [
                'id' => $publicacion->id,
                'descripcion' => $publicacion->descripcion,
                'imgPerfil' => $imgPerfil,
                'precio' => $precio,
                'nameUserPublicacion' => $publicacion->users->name,
                'username' => $publicacion->users->username,
                'date' => $publicacion->created_at->diffForHumans(),
                'date2' => $publicacion->created_at->format('j F Y'),
                'arrImages' => $arrImages,
                'comentarios' => $arrComentarios,
                'cantidadComentarios' => $publicacion->comentarios->count(),
            ];
        }

        return response()->json([
            'estatus' => true,
            'seguidos' => $seguidos,
            'cExplorar' => $cExplorar,
            'data' => $arrPublicaciones
        ]);
    }

    /**
     * @param Request $request
     */
    public function contenidoEliminar(Request $request)
    {
        try {
            $id = $request->get("publicacion");

            \DB::beginTransaction();

            Publicaciones::where('id', $id)->where('users_id', Auth::user()->id)
                ->update(
                [
                    'deleted' => 1
                ]
            );

            \DB::commit();
            return response()->json([
                'estatus' => true,
                'mensaje' => 'Contenido Eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'estatus' => false,
                'mensaje' => $e->getMessage()
            ]);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerVideos(Request $request){
        $username = $request->get('username');
        $user = User::where('username', $username)->first();

        $publicaciones = Publicaciones::where('users_id', $user->id)->where('deleted', 0)->where('tipo', 2)->OrderBy('created_at', 'DESC')->get();

        $arrPublicaciones = [];
        foreach ($publicaciones as $publicacion) {
            $imgPerfil = $publicacion->users->path_imagen_perfil ? url('storage'.$publicacion->users->path_imagen_perfil) :asset("images/user-profile.png");
            $arrImages = [];

            if ($publicacion->imagenes->count() > 1) {
                foreach ($publicacion->imagenes as $imagen) {
                    $arrImages[] = url('storage' . $imagen->path . $imagen->nombre);
                }
            } elseif(!$publicacion->imagenes->isempty()) {
                $arrImages[] = url('storage'.$publicacion->imagenes()->first()->path.$publicacion->imagenes()->first()->nombre);
            }


            $arrPublicaciones[] = [
                'id' => $publicacion->id,
                'descripcion' => $publicacion->descripcion,
                'imgPerfil' => $imgPerfil,
                'arrVideos' => $arrImages,
                'nameUserPublicacion' => $publicacion->users->name,
                'username' => $publicacion->users->username,
                'date' => $publicacion->created_at->diffForHumans(),
            ];
        }

        return response()->json([
            'estatus' => true,
            'data' => $arrPublicaciones
        ]);
    }

}
