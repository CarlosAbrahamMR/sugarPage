<!-- Sidebar component with st-effect-1 (set on the toggle button within the navbar) -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"
/>
{{--<style>--}}
{{--    .profile-icons {--}}
{{--        font-size: 12px !important;--}}
{{--        font-weight: bold;--}}
{{--        color: #efefef;--}}
{{--    }--}}
{{--</style>--}}
<div
    class="sidebar left sidebar-size-2 sidebar-offset-0 sidebar-visible-desktop sidebar-visible-mobile sidebar-skin-dark"
    id="sidebar-menu" data-type="collapse">
    <div data-scrollable>
        @if(Auth::user()->status !== 'disabled')
            <br>
            <a href="#" style="pointer-events: none; cursor: default;">
                <img src="{{ Auth::user()->path_imagen_perfil ? url('storage'.Auth::user()->path_imagen_perfil) : asset('images/user-profile.png') }}"
                    width="50" alt="Bill" class="img-circle"/>
            </a>
            <p></p>
            <p><b>&nbsp{{ Auth::user()->name }}</b></p>
            <div class="profile-icons margin-none" id="infoMen">

            </div>
            <hr>
            <ul class="sidebar-menu">
                @if(Auth::user()->roles_id !== 1)
                    <li class="{{ (request()->getPathInfo() == '/home') ? 'open' : '' }}"><a href="{{route('home')}}"><i class="fa fa-home"></i> <span>{{__('traducciones.inicio')}}</span></a></li>
                    <li class="{{ (request()->getPathInfo() == '/profile') ? 'open' : '' }}"><a href="{{route('profile')}}"><i class="icon-user-1"></i> <span>{{__('traducciones.perfil')}}</span></a></li>
                    <li class=""><a href="#"><i class="icon-newspaper"></i> <span>{{__('traducciones.noticias')}}</span></a></li>
                    <li class="hasSubmenu {{ (request()->getPathInfo() == '/search/content') || (request()->getPathInfo() == '/my-subscriptions') || (request()->getPathInfo() == '/my-following') ? 'open' : '' }}">
                        <a href="#components"><i class="icon-heart-fill"></i> <span>{{__('traducciones.favoritos')}}</span></a>
                        <ul id="components" class="{{ (request()->getPathInfo() == '/search/content') || (request()->getPathInfo() == '/my-subscriptions') || (request()->getPathInfo() == '/my-following') ? 'in collapse' : '' }}">
                            <li class="{{ (request()->getPathInfo() == '/search/content') ? 'active' : '' }}"><a href="{{route('list.content')}}"><i class="icon-tag-3"></i> <span>{{__('traducciones.buscar')}}</span></a></li>
                            <li class="{{ (request()->getPathInfo() == '/my-subscriptions') ? 'active' : '' }}"><a href="{{route('mis-suscripciones')}}"><i class="icon-clipboard"></i> <span>{{__('traducciones.mis_suscripciones')}}</span></a></li>
                            <li class="{{ (request()->getPathInfo() == '/my-following') ? 'active' : '' }}"><a href="{{route('mis-seguidos')}}"><i class="icon-reply-all-fill"></i> <span>{{__('traducciones.seguidos')}}</span></a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->roles_id === 2)
                    <li class="{{ (request()->getPathInfo() == '/registrar-datos-personales' || request()->getPathInfo() == '/recompensas-fan') ? 'open' : '' }}"><a href="{{route('registrar-datos-personales')}}"><i class="icon-dollar"></i> <span>{{__('traducciones.ser_creador')}}</span></a></li>
                    <li class="hasSubmenu {{ (request()->getPathInfo() == '/auctions') || (request()->getPathInfo() == '/auctions-creators' || request()->getPathInfo() == '/recompensas-fan') ? 'open' : '' }}">
                        <a href="#auctions"><i class="md-local-offer"></i> <span>{{__('Bids & Offers')}}</span></a>
                        <ul id="auctions" class="{{ (request()->getPathInfo() == '/auctions-creators' || request()->getPathInfo() == '/recompensas-fan')  ? 'in collapse' : '' }}">
                            <li class="{{ (request()->getPathInfo() == '/auctions-creators') ? 'active' : '' }}"><a href="{{route('auctions.list.creadores')}}"><i class="icon-clipboard"></i> <span>{{__('Bids')}}</span></a></li>
                            <li class="{{ (request()->getPathInfo() == '/offers-creators') ? 'active' : '' }}"><a href="{{route('offers.list.creadores')}}"><i class="icon-clipboard"></i> <span>{{__('Offers')}}</span></a></li>
                            <li class="{{ (request()->getPathInfo() == '/recompensas-fan') ? 'active' : '' }}"><a href="{{route('fan.recompensas')}}"><i class="icon-refresh-star-fill"></i>
                                <span>{{__('traducciones.recompensas')}}</span></a></li>
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->roles_id === 1)
                    <li class="{{ (request()->getPathInfo() == '/users') ? 'open' : '' }}"><a href="{{route('users')}}"><i class="icon-user-1"></i> <span>{{__('traducciones.usuario')}}</span></a></li>
                    <li class="{{ (request()->getPathInfo() == '/promotions') ? 'open' : '' }}"><a href="{{route('promotions')}}"><i class="icon-tag-3"></i> <span>{{__('traducciones.promocion')}}</span></a></li>
                @endif
                @if(Auth::user()->roles_id !== 1)
                    <li class="hasSubmenu {{ (request()->getPathInfo() == '/payment-method') ? 'open' : '' }}">
                        <a href="#account-settings"><i class="icon-settings-wheel-fill"></i> <span>{{__('traducciones.configuracion')}}</span></a>
                        <ul id="account-settings" class="{{ (request()->getPathInfo() == '/payment-method') ? 'in collapse' : '' }}">
                            <li><a href="#"><i class="icon-user-1"></i> <span>{{__('traducciones.editar_perfil')}}</span></a></li>
                            <li class="{{ (request()->getPathInfo() == '/payment-method') ? 'active' : '' }}"><a href="{{route('pago')}}"><i class="icon-credit-card-back"></i> <span>{{__('traducciones.aniadir_tarjeta')}}</span></a></li>

                            <li><a href="#"><i class="md-lock"></i> <span>{{__('traducciones.cambiar_contrasenia')}}</span></a></li>
                            <li><a href="#"><i class="md-delete"></i> <span>{{__('traducciones.eliminar_cuenta')}}</span></a></li>
                        </ul>
                    </li>
                @endif
            <!-- Sample 2 Level Collapse -->
                @if(Auth::user()->roles_id === 3 && Auth::user()->DatosPersonales)
                    <li class="hasSubmenu {{ (request()->getPathInfo() == '/ballance' ||
                             request()->getPathInfo() == '/plan/create' ||
                             request()->getPathInfo() == '/bankAccount' ||
                             request()->getPathInfo() == '/special-code' ||
                             request()->getPathInfo() == '/contenido' ||
                             request()->getPathInfo() == '/contenido/listado' ||
                             request()->getPathInfo() == '/fans-list' ||
                             request()->getPathInfo() == '/auctions' ||
                             request()->getPathInfo() == '/products' ||
                             request()->getPathInfo() == '/recompensas') ? 'open' : '' }}">
                        <a href="#submenu"><i class="fa fa-chevron-circle-down"></i> <span>Creador</span></a>
                        <ul id="submenu" class="{{ (request()->getPathInfo() == '/ballance' ||
                             request()->getPathInfo() == '/plan/create' ||
                             request()->getPathInfo() == '/bankAccount' ||
                             request()->getPathInfo() == '/special-code' ||
                             request()->getPathInfo() == '/contenido' ||
                             request()->getPathInfo() == '/contenido/listado' ||
                             request()->getPathInfo() == '/contenido/multimedia' ||
                             request()->getPathInfo() == '/fans-list' ||
                             request()->getPathInfo() == '/auctions' ||
                             request()->getPathInfo() == '/products' ||
                             request()->getPathInfo() == '/recompensas') ? 'in collapse' : '' }}">
                            <li class="hasSubmenu {{ (request()->getPathInfo() == '/ballance' ||
                             request()->getPathInfo() == '/plan/create' ||
                             request()->getPathInfo() == '/bankAccount' ||
                             request()->getPathInfo() == '/special-code') ? 'open' : '' }}">
                                <a href="#account"><i class="icon-wrench-fill"></i> <span>{{__('Manage account')}}</span></a>
                                <ul id="account" class="{{ (request()->getPathInfo() == '/ballance' ||
                             request()->getPathInfo() == '/plan/create' ||
                             request()->getPathInfo() == '/bankAccount' ||
                             request()->getPathInfo() == '/special-code') ? 'in collapse' : '' }}">
                                    <li class="{{ (request()->getPathInfo() == '/plan/create') ? 'active' : '' }}"><a href="{{route('crear.plan')}}"><i class="fa fa-edit"></i> <span>{{__('traducciones.agregar_plan_pago')}}</span></a></li>
                                    {{-- <li><a href="#"><i class="fa fa-edit"></i> <span>{{__('traducciones.editar_contenido')}}</span></a></li> --}}
                                    <li class="{{ (request()->getPathInfo() == '/ballance') ? 'active' : '' }}"><a href="{{ route('ballance.index') }}"><i class="icon-clipboard"></i> <span>{{__('traducciones.saldo')}}</span></a></li>
                                    <li class="{{ (request()->getPathInfo() == '/bankAccount') ? 'active' : '' }}"><a href="{{ route('cuenta.index') }}"><i class="icon-clipboard"></i> <span>{{__('Cuenta de banco')}}</span></a></li>
                                    @if(Auth::user()->roles_id === 3)
                                        <li class="{{ (request()->getPathInfo() == '/special-code') ? 'active' : '' }}"><a href="{{ route('special-code') }}"><i class="icon-clipboard"></i> <span>{{__('Special Guest Code')}}</span></a></li>
                                    @endif
                                </ul>
                            </li>
                            <li class="hasSubmenu {{ (request()->getPathInfo() == '/contenido' ||
                             request()->getPathInfo() == '/contenido/listado' || request()->getPathInfo() == '/contenido/multimedia' ||
                             request()->getPathInfo() == '/bankAccount') ? 'open' : '' }}">
                                <a href="#contentSection"><i class="icon-wrench-fill"></i> <span>{{__('traducciones.administrar_contenido')}}</span></a>
                                <ul id="contentSection" class="{{ (request()->getPathInfo() == '/contenido' ||
                             request()->getPathInfo() == '/contenido/listado' || request()->getPathInfo() == '/contenido/multimedia' ||
                             request()->getPathInfo() == '/bankAccount') ? 'in collapse' : '' }}">
{{--                                    <li class="{{ (request()->getPathInfo() == '/contenido/multimedia') ? 'active' : ''}} "><a href="{{ route('multimedia') }}"><i class="icon-movie-camera"></i> <span>Subir Multimedia</span></a></li>--}}
                                    <li class="{{ (request()->getPathInfo() == '/contenido') ? 'active' : '' }}"><a href="{{ route('contenido') }}"><i class="icon-photo-camera-fill"></i> <span>{{__('traducciones.subir_contenido')}}</span></a></li>
                                    {{-- <li><a href="#"><i class="fa fa-edit"></i> <span>{{__('traducciones.editar_contenido')}}</span></a></li> --}}
                                    <li class="{{ (request()->getPathInfo() == '/contenido/listado') ? 'active' : '' }}"><a href="{{ route('contenido.listado') }}"><i class="icon-clipboard"></i> <span>{{__('traducciones.lista_contenido')}}</span></a></li>
                                </ul>
                            </li>

                            <li class="hasSubmenu {{ (request()->getPathInfo() == '/fans-list') ? 'open' : '' }}">
                                <a href="#fanslist"><i class="icon-heart-fill"></i> <span>{{__('traducciones.mis_fans')}}</span></a>
                                <ul id="fanslist" class="{{ (request()->getPathInfo() == '/fans-list') ? 'in collapse' : '' }}">
                                    <li class="{{ (request()->getPathInfo() == '/fans-list') ? 'active' : '' }}"><a href="{{route('fans.list')}}"><i class="icon-tag-3"></i> <span>{{__('traducciones.lista_fans')}}</span></a></li>
                                </ul>
                            </li>

                            <li class="hasSubmenu {{ (request()->getPathInfo() == '/auctions' || request()->getPathInfo() == '/products' || request()->getPathInfo() == '/recompensas') ? 'open' : '' }}">
                                <a href="#offers"><i class="icon-percent"></i> <span>{{__('Bids & Offers')}}</span></a>
                                <ul id="offers" class="{{ (request()->getPathInfo() == '/auctions' || request()->getPathInfo() == '/products' || request()->getPathInfo() == '/recompensas') ? 'in collapse' : '' }}">
                                    <li class="{{ (request()->getPathInfo() == '/auctions') ? 'active' : '' }}"><a href="{{route('auctions.list')}}"><i class="icon-clipboard"></i> <span>{{__('Bids')}}</span></a></li>
                                    <li class="{{ (request()->getPathInfo() == '/products') ? 'active' : '' }}"><a href="/products"><i class="icon-clipboard"></i> <span>{{__('traducciones.ofertas')}}</span></a></li>
                                    <li class="{{ (request()->getPathInfo() == '/recompensas') ? 'active' : '' }}"><a href="{{route('lista.recompensas')}}"><i class="icon-refresh-star-fill"></i>
                                             <span>{{__('traducciones.recompensas')}}</span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> <span>{{ __('traducciones.cerrar_sesion') }}</span></a>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @endif
    </div>
</div>
