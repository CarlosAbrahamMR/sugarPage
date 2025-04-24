@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
@endsection

@section('content')

    @if(Auth::user()->roles_id !== 1 && Auth::user()->status === 'enabled')
        <div class="st-pusher" id="content">

            <!-- sidebar effects INSIDE of st-pusher: -->
            <!-- st-effect-3, st-effect-6, st-effect-7, st-effect-8, st-effect-14 -->

            <!-- this is the wrapper for the content -->
            <div id="app" class="st-content">

                <!-- extra div for emulating position:fixed of the menu -->
                <div class="st-content-inner">

                    <div class="container-fluid">

                        <div class="cover profile">
                            <div class="wrapper">
                                <div class="image">
                                    <img src="{{ asset('images/portada_generica.png')}}" alt="people" />
                                </div>
                                <ul class="friends">
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/guy-6.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/woman-3.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/guy-2.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/guy-9.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/woman-9.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/guy-4.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/guy-1.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <img src="{{ asset('dist/themes/social-1/images/people/110/woman-4.jpg')}}" alt="people" class="img-responsive">
                                        </a>
                                    </li>
                                    <li><a href="#" class="group"><i class="fa fa-group"></i></a></li>
                                </ul>
                            </div>
                            <div class="cover-info">
                                <div class="avatar">
                                    <img src="{{ $user->path_imagen_perfil ? url('storage'.$user->path_imagen_perfil) : asset('images/user-profile.png') }}" alt="people" />
                                </div>
                                <div class="name"><a href="#">{{ $user->name }}</a></div>
                                <ul class="cover-nav">

                                    <li class="active"><a href="index.html"><i class="fa fa-fw icon-ship-wheel"></i> Timeline</a></li>
                                    <li><a href="{{ route('profile') }}"><i class="fa fa-fw icon-user-1"></i> About</a></li>
                                    <li><a href="users.html"><i class="fa fa-fw fa-users"></i> Friends</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="timeline row" data-toggle="isotope">

                            @if( $publicaciones != null )
                                @foreach($publicaciones as $publicacion)
                                    <div class="col-xs-12 col-md-6 col-lg-4 item">
                                        <div class="timeline-block">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <a href="">
                                                                <img src="{{ asset('dist/themes/social-1/images/people/50/guy-2.jpg')}}" class="media-object">
                                                            </a>
                                                        </div>
                                                        <div class="media-body">
                                                            <a href="#" class="pull-right text-muted"><i class="icon-reply-all-fill fa fa-2x "></i></a>
                                                            <a href="">{{ $publicacion->users->name }}</a>
                                                            <span>On {{ $publicacion->created_at->format('j F, Y') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($publicacion->imagenes->isempty())
                                                    <div class="panel-body">
                                                        <p>{{ $publicacion->descripcion  }}</p>
                                                    </div>
                                                @elseif($publicacion->imagenes->count() > 1)
                                                    <div class="panel-body">
                                                        <p>{{ $publicacion->descripcion }}</p>
                                                        <div class="timeline-added-images">
                                                            @foreach($publicacion->imagenes as $imagen)
                                                                <img src="{{ url( 'storage'. $imagen->path.$imagen->nombre ) }}" width="90" alt="photo" />
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="panel-body">
                                                        <p>{{ $publicacion->descripcion }}</p>
                                                        <img src="{{ url( 'storage'. $publicacion->imagenes()->first()->path.$publicacion->imagenes()->first()->nombre )}}" class="img-responsive">
                                                    </div>
                                                @endif

                                                <ul class="comments">
                                                    <li class="comment-form">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" />
                                                            <span class="input-group-btn">
                                                                   <a href="" class="btn btn-default">Comentar</a>
                                                                </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>

                </div>
                <!-- /st-content-inner -->

            </div>
            <!-- /st-content -->

        </div>
    @else
        <div class="st-pusher" id="content">
            <div class="st-content">
                <div class="st-content-inner">
                    <div data-app id="app" class="container-fluid">
                        @if(Auth::user()->status === 'disabled')
                            <div class="page-section">
                                <div class="row">
                                    <div class="col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <p class="page-section-heading">Your account has been deactivated, contact the administrator</p>
                                                <div class="form-group margin-none">
                                                    <div class="col-sm-offset-3 col-sm-9">
                                                        <a type="button" class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')

@endsection

