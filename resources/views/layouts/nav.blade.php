<!-- Fixed navbar -->

<div class="navbar navbar-main navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="#sidebar-menu" data-effect="st-effect-1" data-toggle="sidebar-menu"
               class="toggle pull-left visible-xs"><i class="fa fa-bars"></i></a>
            <a href="#" data-toggle="dropdown" data-effect="st-effect-1" id="sinNotifMov" style="display: none" class="toggle pull-right visible-xs "><i class="fa fa-bell-o"></i></a>
            <a href="#" data-toggle="dropdown" data-effect="st-effect-1" id="notifNuevasMov" style="display: none"  class="toggle pull-right">
                <v-badge
                    bordered
                    color="red"
                    dot
                    overlap
                >
                    <i class="fa fa-bell-o"></i>
                </v-badge>
            </a>
            <ul class="dropdown-menu pull-right" id="listaNotificacionesMobil"></ul>
            <a class="navbar-brand navbar-brand-primary hidden-xs" href="{{ route('home') }}">MySugarFan</a>
        </div>
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav hidden-xs">
                <!-- messages -->
                <li class="dropdown notifications hidden-xs hidden-sm">
                    <a href="#" class="dropdown-toggle" id="sinNotif" style="display: none" data-toggle="dropdown"><i class="fa fa-bell-o"></i></a>
                    <a href="#" class="dropdown-toggle" id="notifNuevas" style="display: none" data-toggle="dropdown">
                        <v-badge
                            bordered
                            color="red"
                            dot
                            overlap
                        >
                            <i class="fa fa-bell-o"></i>
                        </v-badge>
                    </a>
                    <ul class="dropdown-menu" id="listaNotificaciones">
                    </ul>
                </li>
                <!-- // END messages -->
            </ul>
        </div>
    </div>
</div>
