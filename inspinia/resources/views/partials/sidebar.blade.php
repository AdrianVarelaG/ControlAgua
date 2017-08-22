<style type="text/css">
  .img-shadow {
    box-shadow: 0 2px 4px 0 rgba(255, 255, 255, 0.8);
  }
</style>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <!-- Profile -->
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
            <!-- /Profile -->
            
            <li class="{{ set_active(['home', '/']) }}">
                <a href="{{ URL::to('home') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>
        <!-- Menu ADM Administrador -->
        @if(Session::get('user_role')=='ADM')                                
            <li class="{{ set_active(['states', 'municipalities', 'administrations', 'inspectors', 'authorizations']) }}">
                <a href="index.html"><i class="fa fa-list-ul"></i> <span class="nav-label">Catálogos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['states']) }}">
                        <a href="{{URL::to('states')}}">Estados</a>
                    </li>
                    <li class="{{ set_active(['municipalities.index']) }}">
                        <a href="{{URL::to('municipalities.index', 1)}}">Municipios</a>
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
                </ul>
            </li>
            <li class="{{ set_active(['company']) }}">
                <a href="{{URL::to('company')}}"><i class="fa fa-building-o"></i> <span class="nav-label">Empresa</span></a>
            </li>            
            <li class="{{ set_active(['users']) }}">
                <a href="{{URL::to('users')}}"><i class="fa fa-users"></i> <span class="nav-label">Usuarios</span></a>
            </li>
            <li class="{{ set_active(['citizens']) }}">
                <a href="{{URL::to('citizens')}}"><i class="fa fa-address-book-o"></i> <span class="nav-label">Ciudadanos</span></a>
            </li>
            <li class="{{ set_active(['contracts']) }}">
                <a href="{{URL::to('contracts')}}"><i class="fa fa-tachometer"></i> <span class="nav-label">Contratos</span></a>
            </li>                        
            <li class="{{ set_active(['readings']) }}">
                <a href="{{URL::to('readings')}}"><i class="fa fa-pencil-square-o"></i> <span class="nav-label">Lecturas</span></a>
            </li>
            
            <li class="{{ set_active(['charges.iva', 'rates.flat_rate', 'rates', 'charges', 'discounts', 'discounts.age', 'invoices.routines', 'invoices.index_group']) }}">
                <a href="index.html"><i class="fa fa-file-text-o"></i> <span class="nav-label">Recibos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['charges.iva']) }}">
                        <a href="{{URL::to('charges.iva')}}">IVA</a>
                    </li>                    
                    <li class="{{ set_active(['rates.flat_rate']) }}">
                        <a href="{{URL::to('rates.flat_rate')}}">Tarifa Unica</a>
                    </li>                                        
                    <li class="{{ set_active(['rates']) }}">
                        <a href="{{URL::to('rates')}}">Tarifas por Consumo</a>
                    </li>                    
                    <li class="{{ set_active(['charges']) }}">
                        <a href="{{URL::to('charges')}}">Cargos Adicionales</a>
                    </li>
                    <li class="{{ set_active(['discounts.age']) }}">
                        <a href="{{URL::to('discounts.age')}}">Descuento 3ra Edad</a>
                    </li>                                                                                
                    <li class="{{ set_active(['discounts']) }}">
                        <a href="{{URL::to('discounts')}}">Otros Descuentos</a>
                    </li>                                                            
                    <li class="{{ set_active(['invoices.routines']) }}">
                        <a href="{{URL::to('invoices.routines')}}">Generar</a>
                    </li>
                    <li class="{{ set_active(['invoices.index_group']) }}">
                        <a href="{{URL::to('invoices.index_group')}}">Consultar</a>
                    </li>                    
                </ul>
            </li>
            <li class="{{ set_active(['payments.index', 'payments.contracts_debt', 'payments.contracts_solvent']) }}">
                <a href="index.html"><i class="fa fa-money"></i> <span class="nav-label">Pagos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['payments.index']) }}">
                        <a href="{{URL::to('payments.index', 1)}}">Consultar</a>
                    </li>
                    <li class="{{ set_active(['payments.contracts_debt']) }}">
                        <a href="{{URL::to('payments.contracts_debt')}}">Pagar</a>
                    </li>                    
                    <li class="{{ set_active(['payments.contracts_solvent']) }}">
                        <a href="{{URL::to('payments.contracts_solvent')}}">Pagar por Adelantado</a>
                    </li>                                                            
                </ul>
            </li>            
            <li class="{{ set_active(['setting']) }}">
                <a href="{{URL::to('settings')}}"><i class="fa fa-cogs"></i> <span class="nav-label">Configuración</span></a>
            </li>
        @endif
        <!-- /Menu ADM Administrador -->

        <!-- Menu OPE Operador -->
        @if(Session::get('user_role')=='OPE')
            <li class="{{ set_active(['citizens']) }}">
                <a href="{{URL::to('citizens')}}"><i class="fa fa-address-book-o"></i> <span class="nav-label">Ciudadanos</span></a>
            </li>
            <li class="{{ set_active(['contracts']) }}">
                <a href="{{URL::to('contracts')}}"><i class="fa fa-tachometer"></i> <span class="nav-label">Contratos</span></a>
            </li>                        
            <li class="{{ set_active(['readings']) }}">
                <a href="{{URL::to('readings')}}"><i class="fa fa-pencil-square-o"></i> <span class="nav-label">Lecturas</span></a>
            </li>
            
            <li class="{{ set_active(['charges.iva', 'rates.flat_rate', 'rates', 'charges', 'discounts', 'discounts.age', 'invoices.routines', 'invoices.index_group']) }}">
                <a href="index.html"><i class="fa fa-file-text-o"></i> <span class="nav-label">Recibos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['invoices.routines']) }}">
                        <a href="{{URL::to('invoices.routines')}}">Generar</a>
                    </li>
                    <li class="{{ set_active(['invoices.index_group']) }}">
                        <a href="{{URL::to('invoices.index_group')}}">Consultar</a>
                    </li>                    
                </ul>
            </li>
            <li class="{{ set_active(['payments', 'payments.contracts_debt', 'payments.contracts_solvent']) }}">
                <a href="index.html"><i class="fa fa-money"></i> <span class="nav-label">Pagos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['payments']) }}">
                        <a href="{{URL::to('payments')}}">Consultar</a>
                    </li>
                    <li class="{{ set_active(['payments.contracts_debt']) }}">
                        <a href="{{URL::to('payments.contracts_debt')}}">Pagar</a>
                    </li>                    
                    <li class="{{ set_active(['payments.contracts_solvent']) }}">
                        <a href="{{URL::to('payments.contracts_solvent')}}">Pagar por Adelantado</a>
                    </li>                                                            
                </ul>
            </li>            
        @endif
        <!-- /Menu OPE Operador -->

        <!-- Menu CAJ Cajero -->
        @if(Session::get('user_role')=='CAJ')
            <li class="{{ set_active(['payments', 'payments.contracts_debt', 'payments.contracts_solvent']) }}">
                <a href="index.html"><i class="fa fa-money"></i> <span class="nav-label">Pagos</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="{{ set_active(['payments']) }}">
                        <a href="{{URL::to('payments')}}">Consultar</a>
                    </li>
                    <li class="{{ set_active(['payments.contracts_debt']) }}">
                        <a href="{{URL::to('payments.contracts_debt')}}">Pagar</a>
                    </li>                    
                    <li class="{{ set_active(['payments.contracts_solvent']) }}">
                        <a href="{{URL::to('payments.contracts_solvent')}}">Pagar por Adelantado</a>
                    </li>                                                            
                </ul>
            </li>
        <!-- /Menu CAJ Cajero -->
        @endif        
        </ul>
    </div>
</nav>