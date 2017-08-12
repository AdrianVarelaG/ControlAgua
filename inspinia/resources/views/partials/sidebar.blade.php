<style type="text/css">
  .img-shadow {
    box-shadow: 0 2px 4px 0 rgba(255, 255, 255, 0.8);
  }
</style>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span><img alt="image" class="img-circle" style="max-height:70px; max-width:70px;" src="{{ url('user_avatar/'.Auth::user()->id) }}"/></span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong>
                    </span> <span class="text-muted text-xs block">{{ Auth::user()->role_description }} <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Perfil</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            <li class="{{ set_active(['home', '/']) }}">
                <a href="{{ URL::to('home') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>                    
            <li class="{{ set_active(['states', 'municipalities', 'administrations', 'inspectors', 'authorizations', 'rates']) }}">
                <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Catálogos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['states']) }}">
                        <a href="{{URL::to('states')}}">Estados</a>
                    </li>
                    <li class="{{ set_active(['municipalities']) }}">
                        <a href="{{URL::to('municipalities')}}">Municipios</a>
                    </li>
                    <li class="{{ set_active(['administrations']) }}">
                        <a href="{{URL::to('administrations')}}">Administraciones</a>
                    </li>
                    <li class="{{ set_active(['inspectors']) }}">
                        <a href="{{URL::to('inspectors')}}">Inspectores</a>
                    </li>
                    <li class="{{ set_active(['authorizations']) }}">
                        <a href="{{URL::to('authorizations')}}">Autorizados</a>
                    </li>
                    <li class="{{ set_active(['rates']) }}">
                        <a href="{{URL::to('rates')}}">Tarifas</a>
                    </li>
                </ul>
            </li>
            <li class="{{ set_active(['company']) }}">
                <a href="{{URL::to('company')}}"><i class="fa fa-building-o"></i> <span class="nav-label">Empresa</span></a>
            </li>            
            <li class="{{ set_active(['users']) }}">
                <a href="{{URL::to('users')}}"><i class="fa fa-users"></i> <span class="nav-label">Usuarios</span></a>
            </li>
            <li class="{{ set_active(['citizens']) }}">
                <a href="{{URL::to('citizens')}}"><i class="fa fa-users"></i> <span class="nav-label">Ciudadanos</span></a>
            </li>            

            <li class="{{ set_active(['setting']) }}">
                <a href="{{URL::to('settings')}}"><i class="fa fa-cogs"></i> <span class="nav-label">Configuración</span></a>
            </li>            
        </ul>
    </div>
</nav>