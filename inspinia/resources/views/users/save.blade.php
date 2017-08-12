@extends('layouts.app')

@push('stylesheets')
<!-- Fileinput -->
<link href="{{ URL::asset('js/plugins/kartik-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5>{{ ($user->id) ? "Modificar Usuario" : "Registrar Usuario" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        <form action="{{url('users/'.$user->id)}}" id="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        @if($user->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                    
                    <!-- columna 1 -->
                    <div class="col-sm-6">                            
                            <div class="form-group">
                                <label>Avatar </label><small> (Sólo formatos jpg, png. Máx. 2Mb.) Recomendación Máx. 200px por 200px</small>
                                <input id="avatar" name="avatar" class="file" type="file">
                            </div>
                            <div class="form-group">
                                <label>Nombre *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('name', $user->name, ['id'=>'name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Jhon Doe', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Correo electrónico *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    {!! Form::text('email', $user->email, ['id'=>'email', 'class'=>'form-control', 'type'=>'email', 'placeholder'=>'Ej. correo@dominio.com', 'minlength'=>'3', 'maxlength'=>'50', 'required']) !!}
                                </div>
                            </div>                            
                        </div>
                    <!-- columna 2 -->
                    <div class="col-sm-6">    
                            <div class="form-group">
                                <label>Rol *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-male" aria-hidden="true"></i></span>
                                    {{ Form::select('role', ['ADM' => 'Admnistrador', 'OPE' => 'Operador', 'CAJ' => 'Cajero'], $user->role, ['id'=>'role', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                                                                                    
                            @if($user->id)
                                <br/>
                                <div class="i-checks">
                                    <label>{!! Form::checkbox('change_password', null,  true, ['id'=>'change_password']) !!} Cambiar el password.</label>
                                </div>
                                <br/>
                            @endif
                            
                        <div id='div_password' style='display:solid;'>
                            <div class="form-group">
                                <label>Contraseña *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Contraseña" minlength="6" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Confirmar Contraseña *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme su contraseña" minlength="6" required="">
                                </div>
                            </div>
                        </div>
                    </div>                                
                            <!-- botones pie de formulario-->
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('users/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
<!-- Fileinput -->
<script src="{{ URL::asset('js/plugins/kartik-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/kartik-fileinput/js/fileinput_locale_es.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('js/plugins/iCheck/icheck.min.js') }}"></script>


<!-- Page-Level Scripts -->
<script>
      
      var user_id = "{{$user->id}}";
      if( user_id == "" )
      {        
        avatar_preview = "<img style='height:150px' src='{{ url('img/avatar_default.png') }}'>";
      }else{
        avatar_preview = "<img style='height:150px' src= '{{ url('user_avatar/'.$user->id) }}' >";
      }
      
      // Fileinput    
      $('#avatar').fileinput({
        language: 'es',
        allowedFileExtensions : ['jpg', 'jpeg', 'png'],
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        showUpload: false,        
        maxFileSize: 2000,
        maxFilesNum: 1,
        overwriteInitial: true,
        progressClass: true,
        progressCompleteClass: true,
        initialPreview: [
          avatar_preview
        ]      
      });            
    
    $(document).ready(function() {
                
        // Validation
        $("#form").validate({
            submitHandler: function(form) {
                $("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });
        
        // Select2 
        $("#role").select2({
          language: "es",
          placeholder: "Seleccione un rol",
          minimumResultsForSearch: 2,
          allowClear: false,
          width: '100%'
        });

        // iCheck
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('#change_password').on('ifChecked', function(event){ 
          $('#div_password').show();
        });       

        $('#change_password').on('ifUnchecked', function(event){ 
          $('#div_password').hide();
        });       

    });
    </script>

@endpush