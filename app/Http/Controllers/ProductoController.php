<?php

    namespace App\Http\Controllers;

    use App\Models\ComentarioRecompensa;
    use App\Models\Imagenes;
    use App\Models\Producto;
    use App\Models\ProductoVendido;
    use App\Models\Recompensa;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Storage;
    use Stripe\StripeClient;

    class ProductoController extends Controller
    {
        public function getProductos()
        {
            $user      = \Auth::user();
            $productos = Producto::where('users_id', $user->id)->with('imagenes')->get();

            return view('user.products.products')->with(['productos'=>$productos]);
        }

        public function createProducto(Request $request)
        {
//            dd($request);
            if (count(Cache::get("images")) > 0) {
                try {
                    $img=false;
                    if($request->tiporecompensa == null ){
                        return back()->with('Error', ['title' => __("Error"), 'message' => 'Error intentar de nuevo']);
                    }
                    $recompensa= new Recompensa;
                    $recompensa->tipo= strval($request->tiporecompensa);

                    if($request->tiporecompensa == 0  ){// 0 live chat
                        $this->validate(request(), [
                            "Fecha_recompensa" => "required",
                            "link_livechat" => "required"
                        ]);
                        $recompensa->fecha_evento= $request->Fecha_recompensa;
                        $recompensa->link_livechat= $request->link_livechat;

                    }else if($request->tiporecompensa == 1  ){// 0 otro
                        $this->validate(request(), [
                            "recompensa" => "required",
                            "fechaRecompensa" => "required"
                        ]);
                        if($request->fechaRecompensa == 0){
                            // validar que se alla subido la imagen
                            $img=true;
                        }
                        $fecha_actual = date("d-m-Y");
                        $fecha= date("Y-m-d",strtotime($fecha_actual."+ ".$request->fechaRecompensa." days"));
                        $recompensa->fecha_recompensa= $fecha;
                        $recompensa->descripcion= $request->recompensa;

                    }

                    $recompensa->save();

                    $user = \Auth::user();
                    $producto = new Producto();
                    $producto->users_id = $user->id;
                    $producto->nombre = $request->nombre;
                    $producto->descripcion = $request->descripcion;
                    $producto->precio = $request->price;
                    $producto->estatus = 'DISPONIBLE';
                    $producto->cantidad_disponible = $request->has('cantidad_disponible') ? $request->cantidad_disponible : ($request->has('cantidad_participantes') && $request->cantidad_participantes == 1 ? 1 : 0);
                    $producto->cantidad_participantes = $request->has('cantidad_disponible') ? null : $request->cantidad_participantes;
                    $producto->cantidad_vendida = 0;
                    $producto->recompensas_id = $recompensa->id;
                    $producto->save();

                    $storageCache = Cache::get("images");
                    if ($location = isset($storageCache['path'])) {
                        if (isset($storageCache['files'])) {
                            foreach ($storageCache['files'] as $file) {
                                $imagen = new Imagenes();
                                $imagen->path = $storageCache['path'];
                                $imagen->nombre = $file;
                                $imagen->save();
                                $producto->imagenes()->attach($imagen);
                            }
                        }
                    }

                    Cache::put('images', []);
                    if($img){
                        return redirect()->route('offers.img',$recompensa->id)->with('success', 'El producto se creo correctamente');
                    }
                    return redirect()->to('/products')->with('success', 'Se registró con éxito');
                }catch (\Exception $e){
                    dd($e);
                    return  redirect()->back()->with('error', $e->getMessage());
                }
            }else {
                return  redirect()->back()->withInput()->with('error', 'Debes subir por lo menos una imagen');
            }
        }

        public function imagen($recompensa){
            return view('user.products.recompensa', compact('recompensa'));
        }
        public function uploadRewards(Request $request){
            try {
                $this->validate($request, [
                    'producto' => 'required'
                ]);
                $recompensa= Recompensa::find($request->producto);

                $storageCache = Cache::get("images");
                if ($location = isset($storageCache['path'])) {
                    if (isset($storageCache['files'])) {
                        foreach ($storageCache['files'] as $file) {
                            $imagen = new Imagenes();
                            $imagen->path = $storageCache['path'];
                            $imagen->nombre = $file;
                            $imagen->save();
                            $recompensa->imagenes()->attach($imagen);
                        }
                    }
                }
                Cache::put('images', []);
                return redirect()->to('/products')->with(['success' => 'Se cargó la recompensa exitosamente']);

            } catch (\Exception $e) {
                dd($e);
                return response()->json([
                    'estatus' => false,
                    'mensaje' => 'Ocurrió un problema'
                ]);
            }
        }

        public function saveImageRewards(Request $request)
        {
            try {
                $this->validate($request, [
                    'file' => 'required|image|mimes:jpg,png,jpg,gif,svg|max:1000000',
                    'producto' => 'required'
                ]);

                $file = $request->file('file');
                $fileName = uniqid() . $file->getClientOriginalName();
                $path = "images/offers/recompensas/".$fileName;

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
                    'path' => '/images/offers/recompensas/',
                    'files' => $images
                ];

                Cache::put('images', $data);

                return response()->json([
                    'estatus' => true,
                    'mensaje' => ''
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'estatus' => false,
                    'mensaje' => $e->getMessage()
                ]);
            }

        }

        public function saveImage(Request $request)
        {
            try {
                $this->validate($request, [
                    'file' => 'required|image|mimes:jpg,png,jpg,gif,svg|max:1000000'
                ]);

                $file = $request->file('file');
                $fileName = uniqid() . $file->getClientOriginalName();
                $path = "images/offers/".$fileName;

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
                    'path' => '/images/offers/',
                    'files' => $images
                ];

                Cache::put('images', $data);

                return response()->json([
                    'estatus' => true,
                    'mensaje' => ''
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'estatus' => false,
                    'mensaje' => $e->getMessage()
                ]);
            }

        }

        function viewProduct($idProduct){
            $user      = \Auth::user();
            $product = Producto::select('productos.descripcion as descripcion',
                'recompensas.descripcion as recompensa_desc',
                'recompensas.fecha_recompensa',
                'recompensas.fecha_evento',
                'recompensas.tipo',
                'productos.nombre',
                'productos.precio',
                'productos.cantidad_disponible',
                'productos.cantidad_vendida',
                'productos.cantidad_participantes',
                'recompensas.created_at',
                'recompensas.updated_at',
                'productos.id',
                'recompensas.link_livechat'
            )
                ->join('recompensas', 'productos.recompensas_id', 'recompensas.id')
                ->where('productos.users_id', $user->id)
                ->where('productos.id', $idProduct)
                ->with('imagenes')
                ->first();
            $imagenes = $product->imagenes;
//            dd($product);
            return view('user.products.view')->with(['product'=>$product])->with('imagenes', $imagenes);
        }

        function updateProduct(Request $request, $idProduct){
//            dd($request);
            try {
                $user      = \Auth::user();
                $product = Producto::where('users_id', $user->id)->where('id', $idProduct)->first();
                $product->update([
                    'precio' => $request->price,
                    'descripcion' => $request->descripcion,
                    'nombre' => $request->nombre,
                    'cantidad_disponible' => $request->has('cantidad_disponible') ? $request->cantidad_disponible : ($request->has('cantidad_participantes') && $request->cantidad_participantes == 1 ? 1 : 0),
                    'cantidad_participantes' => $request->has('cantidad_disponible') ? null : $request->cantidad_participantes
                ]);

                $reward = Recompensa::where('id', $product->recompensas_id)->first();
                if ($reward) {
                    $fecha_actual = date("d-m-Y");
                    $fecha = date("Y-m-d", strtotime($fecha_actual . "+ " . $request->fechaRecompensa . " days"));
                    $reward->tipo = $request->tiporecompensa;
                    $reward->fecha_recompensa = $fecha;
                    $reward->descripcion = $request->recompensa;
                    $reward->link_livechat = $request->link_livechat;
                    $reward->update();
                }
//                dd($reward);
                $storageCache = Cache::get("images");
                if ($location = isset($storageCache['path'])) {
                    if (isset($storageCache['files'])) {
                        foreach ($storageCache['files'] as $file) {
                            $imagen         = new Imagenes();
                            $imagen->path   = $storageCache['path'];
                            $imagen->nombre = $file;
                            $imagen->save();
                            $product->imagenes()->attach($imagen);
                        }
                    }
                }

                Cache::put('images', []);

                $productos = Producto::where('users_id', $user->id)->with('imagenes')->get();
                $imagenes = $product->imagenes;

                return redirect()->to('/products')->with(['productos'=>$productos])->with('imagenes', $imagenes);
            } catch (\Exception $e){
                return redirect()->to('/products')->with(['error' => $e->getMessage()]);
            }
        }
        function deleteProduct($idProduct){
            $user      = \Auth::user();
            $product = Producto::where('users_id', $user->id)->where('id', $idProduct)->first();
            $product->delete();

            return response()->json(['message' => 'Se eliminó con éxito']);
        }
        function getProductsByCreator($creador){
            $user      = User::where('username', $creador)->first();
            $productos = Producto::select(
                'productos.nombre',
                'productos.id',
                'productos.descripcion',
                'productos.cantidad_disponible',
                'productos.precio',
                'productos.recompensas_id',
                'productos.cantidad_participantes',
                'recompensas.descripcion as recompensaDesc',
                'recompensas.fecha_recompensa',
                'recompensas.fecha_evento',
                'recompensas.tipo',
                \DB::raw("AVG(comentarios_recompensas.valoracion) as resenias"),
                \DB::raw("COUNT(comentarios_recompensas.valoracion) as cantidad")
            )->with('imagenes')
                ->join('users', 'productos.users_id', '=', 'users.id')
                ->join('recompensas', 'productos.recompensas_id', '=', 'recompensas.id')
                ->leftJoin('comentarios_recompensas', 'recompensas.id', '=', 'comentarios_recompensas.recompensas_id')
                ->where('productos.users_id', $user->id)
                ->groupBy('productos.nombre',
                    'productos.id',
                    'productos.descripcion',
                    'productos.cantidad_disponible',
                    'productos.precio',
                    'productos.recompensas_id',
                    'productos.cantidad_participantes',
                    'recompensas.descripcion',
                    'recompensas.fecha_recompensa',
                    'recompensas.fecha_evento',
                    'recompensas.tipo')
                ->get();

            return view('user.products.sell')->with(['productos'=>$productos, 'creador' => $user]);
        }
        function getAllProducts(){

            $productos = Producto::select(
                'productos.nombre',
                'productos.id',
                'productos.descripcion',
                'productos.cantidad_disponible',
                'productos.cantidad_vendida',
                'productos.precio',
                'productos.recompensas_id',
                'productos.cantidad_participantes',
                'recompensas.descripcion as recompensaDesc',
                'recompensas.fecha_recompensa',
                'recompensas.fecha_evento',
                'recompensas.tipo',
                \DB::raw("AVG(comentarios_recompensas.valoracion) as resenias"),
                \DB::raw("COUNT(comentarios_recompensas.valoracion) as cantidad")
            )->with('imagenes')
                ->join('users', 'productos.users_id', '=', 'users.id')
                ->join('suscripciones', 'users.id', 'suscripciones.users_creador_id')
                ->join('recompensas', 'productos.recompensas_id', '=', 'recompensas.id')
                ->leftJoin('comentarios_recompensas', 'recompensas.id', '=', 'comentarios_recompensas.recompensas_id')
                ->where('suscripciones.users_fan_id', Auth::user()->id)
                ->groupBy('productos.nombre',
                    'productos.id',
                    'productos.descripcion',
                    'productos.cantidad_disponible',
                    'productos.cantidad_vendida',
                    'productos.precio',
                    'productos.recompensas_id',
                    'productos.cantidad_participantes',
                    'recompensas.descripcion',
                    'recompensas.fecha_recompensa',
                    'recompensas.fecha_evento',
                    'recompensas.tipo')
                ->get();

            return view('user.products.list')->with(['productos'=>$productos]);
        }
        function comprarProducto(Request $request) {
            $comprador = Auth::user();
            $producto = Producto::where('id', $request->producto_id)->first();
//            dd($request->tipoRecompensa, $producto->cantidad_participantes, $request->cantidad, $producto->cantidad_vendida);

            try {
                $key = config('cashier.secret');
                $stripe = new StripeClient($key);

                /* $hola=$stripe->customers->allSources(
                    'cus_MlGB1qeV8Ev5sL',
                    ['object' => 'card', 'limit' => 3]
                  );
                dd($hola); */
                $monto=$producto->precio*100;
               // dd($monto);
                $stripe->charges->create([
                    'amount' => intval($monto),
                    'currency' => 'usd',
                    'customer' => $comprador->stripe_id,
                    'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
                  ]);


            } catch (CardException $exception) {
                dd($exception,'pay');
                return redirect()->route(
                    'cashier.payment',
                    [$exception->payment->id, 'redirect' => route("billing.my_subscription")]
                );
            } catch (\Exception $exception) {
                dd($exception,'no');
                return back()->with('notification', ['title' => __("Error"), 'message' => $exception->getMessage()]);
            }

            $compra = new ProductoVendido();
            $compra->users_id = $comprador->id;
            $compra->productos_id = $producto->id;
            $compra->cantidad = $request->cantidad;
            $compra->save();

            $producto->update([
                'cantidad_disponible' => $request->tipoRecompensa == 0 && $producto->cantidad_participantes != 1 ? $producto->cantidad_disponible : $producto->cantidad_disponible - $request->cantidad,
                'cantidad_vendida' => $producto->cantidad_vendida + intval($request->cantidad)
            ]);

            return redirect()->back()->with(['succes' => 'Product successfully bought.']);
        }

        function getComentariosRecompensas($id){

            $comentarios = ComentarioRecompensa::select(
                'comentarios_recompensas.id',
                'users.name',
                'users.path_imagen_perfil',
                'comentarios_recompensas.comentario',
                'comentarios_recompensas.created_at as fecha',
            )
                ->where('recompensas_id', $id)
                ->join('users', 'comentarios_recompensas.users_id', 'users.id')
                ->get();

            return response()->json(['comentarios' => $comentarios]);
        }
    }
