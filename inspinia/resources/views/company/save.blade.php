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
                <h5>{{ ($company->id) ? "Modificar Datos Compañía" : "Registrar Compañía" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        <form action="{{url('company/'.$company->id)}}" id="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        @if($company->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        <!-- Columna 1 -->
                        <div class="col-sm-6">                            
                            <div class="form-group">
                                <label>Logo </label><small> (Sólo formatos jpg, png. Máx. 2Mb.) Recomendación Máx. 200px por 200px</small>
                                <input id="logo" name="logo" class="file" type="file">
                            </div>
                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-building-o" aria-hidden="true"></i></span>
                                    {!! Form::text('name', $company->name, ['id'=>'name', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Nombre de la empresa', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Número de Identificación</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-id-badge" aria-hidden="true"></i></span>
                                    {!! Form::text('ID_company', $company->ID_company, ['id'=>'ID_company', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. RIF-NIT-ID', 'maxlength'=>'20', 'required']) !!}
                                </div>
                            </div>                                                        
                        </div>

                        <!-- Columna 2 -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Dirección</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('address', $company->address, ['id'=>'address', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Dirección', 'maxlength'=>'150', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Teléfono de la Empresa</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                    {!! Form::text('company_phone', $company->company_phone, ['id'=>'company_phone', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. 555-55555', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            
                            <div class="form-group">
                                <label>Correo electrónico de la Empresa</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    {!! Form::text('company_email', $company->company_email, ['id'=>'company_email', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'empresa@dominio.com', 'maxlength'=>'50', 'email', 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Contacto</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-male" aria-hidden="true"></i></span>
                                    {!! Form::text('contact', $company->contact, ['id'=>'contact', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Responsable legal', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Teléfono del contacto</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                    {!! Form::text('contact_phone', $company->contact_phone, ['id'=>'contact_phone', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'555-55555', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Correo electrónico del contacto</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    {!! Form::text('contact_email', $company->contact_email, ['id'=>'contact_email', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. micorreo@dominio.com', 'email', 'required']) !!}
                                </div>
                            </div>
                        </div>    
                            <!-- Botones pie de formulario -->
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{ route('home') }}" class="btn btn-sm btn-default" title="Inicio"><i class="fa fa-home"></i></a>
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
      
      var company_id = "{{$company->id}}";
      if( company_id == "" )
      {        
        logo_preview = "<img style='height:150px' src='{{ url('img/avatar_default.png') }}'>";
      }else{
        logo_preview = "<img style='height:150px' src= '{{ url('company_logo/'.$company->id) }}' >";
      }
      
      // Fileinput    
      $('#logo').fileinput({
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
          logo_preview
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

    });
    </script>

@endpush