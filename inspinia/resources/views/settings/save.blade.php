@extends('layouts.app')

@push('stylesheets')
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<!-- Switchery -->
<link href="{{ URL::asset('js/plugins/switchery/dist/switchery.css') }}" rel="stylesheet">
@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5>Configuraciones <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        {{ Form::open(array('url' => 'settings/' . $setting->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($setting->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        <!-- Columna 1 -->
                        <div class="col-sm-6">                              
                             <div class="form-group">
                                <label>Nombre de la aplicación *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('app_name', $setting->app_name, ['id'=>'app_name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>
                             <div class="form-group">
                                <label>Símbolo de moneda *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-usd" aria-hidden="true"></i></span>
                                    {!! Form::text('coin', $setting->coin, ['id'=>'coin', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. MXN', 'maxlength'=>'10', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Formato de moneda *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-money" aria-hidden="true"></i></span>
                                    {!! Form::select('money_format', ['PC2'=>'1.000,00', 'CP2' => '1,000.00'], $setting->money_format, ['id'=>'money_format', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required']) !!}
                                </div>
                            </div>
                        </div>
                        <!-- Columna 2 -->
                        <div class="col-sm-6">
                            <h2>Notificaciones</h2>
                            <div class="form-group">
                                <p>{!! Form::checkbox('create_notification', null,  $setting->create_notification, ['id'=>'create_notification', 'class'=>'js-switch']) !!}&nbsp;&nbsp;Al agregar registro.</p>
                            </div>
                            <div class="form-group">
                                <p>{!! Form::checkbox('update_notification', null,  $setting->update_notification, ['id'=>'update_notification', 'class'=>'js-switch']) !!}&nbsp;&nbsp;Al modificar registro.</p>
                            </div>
                            <div class="form-group">
                                <p>{!! Form::checkbox('delete_notification', null,  $setting->delete_notification, ['id'=>'delete_notification', 'class'=>'js-switch']) !!}&nbsp;&nbsp;Al eliminar registro.</p>
                            </div>
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('home')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-home"></i></a>
                                </div>
                            </div>
                        </div>                            
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <!-- /ibox-content -->
            
        </div>
    </div>
</div>
@endsection

@push('scripts')    
<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<!-- Switchery -->
<script src="{{ URL::asset('js/plugins/switchery/dist/switchery.js') }}"></script>

<!-- Page-Level Scripts -->
<script>
          
    $(document).ready(function() {
                
        // Validation
        $("#form").validate({
            submitHandler: function(form) {
                $("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });
        
        // Select2 
        $("#money_format").select2({
          language: "es",
          placeholder: "Seleccione un formato numérico",
          minimumResultsForSearch: 2,
          allowClear: false,
          width: '100%'
        });

        // Switchery
        var elem = document.querySelector('#create_notification');
        var init = new Switchery(elem, { size: 'small', color: '#1AB394' });

        var elem = document.querySelector('#update_notification');
        var init = new Switchery(elem, { size: 'small', color: '#1AB394' });

        var elem = document.querySelector('#delete_notification');
        var init = new Switchery(elem, { size: 'small', color: '#1AB394' });
    
    });
    </script>

@endpush