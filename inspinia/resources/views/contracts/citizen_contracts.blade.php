@extends('layouts.app')

@push('stylesheets')
<link href="{{ URL::asset('css/plugins/slick/slick.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/plugins/slick/slick-theme.css') }}" rel="stylesheet">    

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5> Contratos del Ciudadano</h5>
                <div class="ibox-tools">
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <!-- /ibox-title -->
            
            <!-- ibox-content -->
            <div class="ibox-content">

            @include('partials.errors')

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-3">
                            <div class="contact-box center-version">
                                <a style="cursor:default">
                                    <img alt="image" class="img-circle" src="{{ url('citizen_avatar/'.$citizen->id) }}">
                                    <h3 class="m-b-xs"><strong>{{ $citizen->name }}</strong></h3>
                                    <div class="font-bold">{{ $citizen->profession }}</div>
                                    <address class="m-t-md">
                                        <strong>{{ $citizen->RFC }}</strong><br>
                                        {{ $citizen->municipality->name }}, {{ $citizen->state->name }}<br/> 
                                        {{ $citizen->street }}<br>
                                        #Ext {{ $citizen->number_ext }}, #Int {{ $citizen->number_int }}<br>
                                    </address>
                                </a>
                                <div class="contact-box-footer">
                                    <div class="m-t-xs btn-group">
                                        <small>
                                            <i class="fa fa-phone"></i>&nbsp;{{ $citizen->phone }}&nbsp;&nbsp;
                                            <i class="fa fa-mobile"></i>&nbsp;{{ $citizen->mobile }}<br/>
                                            <i class="fa fa-envelope-o"></i>&nbsp;{{ $citizen->email }}</a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <div class="col-lg-9">                            
                            <a href="{{ route('contracts.create', Crypt::encrypt($citizen->id)) }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> Nuevo Contrato</a><br/><br/>
                        <!-- slick_contracts -->
                        <div class="slick_contracts">
                        @if($citizen->contracts->count()>0)
                            @foreach($citizen->contracts as $contract)
                            <div>
                                <div class="ibox-content">
                                    <h2>Contrato # <strong>{{ $contract->number }}</strong></h2>
                                    <div class="col-sm-6 b-r">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Fecha:</strong></td>
                                                    <td>{{ $contract->date->format('d/m/Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tarifa:</strong></td>
                                                    <td>{{ $contract->rate->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Administración:</strong></td>
                                                    <td>{{ $contract->administration->period }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Estado:</strong></td>
                                                    <td>{{ $contract->state->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Municipio: </td>
                                                    <td>{{ $contract->municipality->name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-6">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <td>Calle: </td>
                                                    <td>{{ $contract->street }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Barrio o Colonia: </td>
                                                    <td>{{ $contract->neighborhood }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Número externo: </td>
                                                    <td>{{ $contract->number_ext }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Número Interno: </td>
                                                    <td>{{ $contract->number_int }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Código Postal: </td>
                                                    <td>{{ $contract->postal_code }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group pull-right">
                                        <!-- edit_contracts -->
                                        <a href="{{ route('contracts.edit', Crypt::encrypt($contract->id)) }}" class="btn btn-sm btn-default" title="Editar"><i class="fa fa-pencil"></i></a>
                                        <!-- delete_contracts -->
                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el contrato?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="#" onclick="$(this).closest('form').submit()" class="btn btn-sm btn-default" title="Eliminar" style="color:inherit"><i class="fa fa-trash-o"></i></a>
                                        </form>                                        
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div>
                                <div class="ibox-content">
                                    <h2>No hay contratos asociados</h2>
                                </div>
                            </div>
                        @endif
                        </div>
                        <!-- /slick_contracts -->
                        </div>                    
                    </div>
                
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <a href="{{URL::to('citizens')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                </div>
                            </div>                            

                </div>
            </div>
            <!-- /ibox-content -->
            
        </div>
    </div>
</div>
@endsection

@push('scripts')    
<!-- slick carousel-->
<script src="{{ asset('js/plugins/slick/slick.min.js') }}"></script>
    
    <script>
        $(document).ready(function(){

            $('.slick_contracts').slick({
                dots: true
            });
        
            //Notifications
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 2000
                };
                if('{{ Session::get('notity') }}'=='create' &&  '{{ Session::get('create_notification') }}'=='1'){
                  toastr.success('Registro añadido exitosamente', '{{ Session::get('app_name') }}');
                }
                if('{{ Session::get('notity') }}'=='update' &&  '{{ Session::get('update_notification') }}'=='1'){
                  toastr.success('Registro actualizado exitosamente', '{{ Session::get('app_name') }}');
                }
                if('{{ Session::get('notity') }}'=='delete' &&  '{{ Session::get('delete_notification') }}'=='1'){
                  toastr.success('Registro eliminado exitosamente', '{{ Session::get('app_name') }}');
                }
            }, 1300);
        });

      </script>
@endpush