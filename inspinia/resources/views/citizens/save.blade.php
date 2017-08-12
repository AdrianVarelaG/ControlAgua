@extends('layouts.app')

@push('stylesheets')
<!-- Fileinput -->
<link href="{{ URL::asset('js/plugins/kartik-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<!-- DatePicker -->
<link href="{{ URL::asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5>{{ ($citizen->id) ? "Modificar Ciudadano" : "Registrar Ciudadano" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        <form action="{{url('citizens/'.$citizen->id)}}" id="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        {!! Form::hidden('hdd_municipality_id', '', ['id'=>'hdd_municipality_id']) !!}
                        @if($citizen->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        <div class="col-sm-6 b-r">                            
                            <div class="form-group">
                                <label>Avatar </label><small> (Sólo formatos jpg, png. Máx. 2Mb.) Recomendación Máx. 200px por 200px</small>
                                <input id="avatar" name="avatar" class="file" type="file">
                            </div>
                            <div class="form-group">
                                <label>Nro de Identificación *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span>
                                    {!! Form::text('ID_number', $citizen->ID_number, ['id'=>'ID_number', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. 123456789', 'maxlength'=>'50', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>RFC *</label><small> Registro Federal del Contribuyente</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-id-card-o" aria-hidden="true"></i></span>
                                    {!! Form::text('RFC', $citizen->RFC, ['id'=>'RFC', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. MELM8305281H0', 'maxlength'=>'50', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Nombre *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {!! Form::text('name', $citizen->name, ['id'=>'name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Jhon Doe', 'maxlength'=>'100', 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Profesión</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                                    {!! Form::text('profession', $citizen->profession, ['id'=>'profession', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Web Developer', 'maxlength'=>'100']) !!}
                                </div>
                            </div>                        
                            <div class="form-group" id="data_1">
                                <label>Fecha de Nacimiento *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('birthdate', ($citizen->id)?$citizen->birthdate->format('d/m/Y'):'', ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'date', 'required']) }}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Teléfono</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                    {!! Form::text('phone', $citizen->phone, ['id'=>'phone', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. +52 555.55.55', 'maxlength'=>'25']) !!}
                                </div>
                            </div>                            
                        </div>
                        <div class="col-sm-6">                        
                            <div class="form-group">
                                <label>Móvil</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                                    {!! Form::text('mobile', $citizen->mobile, ['id'=>'mobile', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. +521 555.55.55', 'maxlength'=>'25']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    {!! Form::text('email', $citizen->email, ['id'=>'email', 'class'=>'form-control', 'type'=>'email', 'placeholder'=>'Ej. correo@dominio.com', 'minlength'=>'3', 'maxlength'=>'50']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Estado *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {{ Form::select('state', $states, $citizen->state_id, ['id'=>'state', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                             
                            <div class="form-group">
                                <label>Municipio *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::select('municipality', ['placeholder'=>'Seleccione un municipio'], $citizen->municipality_id, ['id'=>'municipality', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Calle *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('street', $citizen->street, ['id'=>'street', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Juarez', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Barrio o Colonia *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('neighborhood', $citizen->neighborhood, ['id'=>'neighborhood', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Cristo Rey', 'maxlength'=>'50', 'required']) !!}
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <label>Número externo *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                    {!! Form::text('number_ext', $citizen->number_ext, ['id'=>'number_ext', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. #15', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Número interno *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                    {!! Form::text('number_int', $citizen->number_int, ['id'=>'number_int', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. #1500', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Código Postal *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-location-arrow" aria-hidden="true"></i></span>
                                    {!! Form::text('postal_code', $citizen->postal_code, ['id'=>'postal_code', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. 21150', 'maxlength'=>'10', 'number', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('citizens/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
<!-- Fileinput -->
<script src="{{ URL::asset('js/plugins/kartik-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/kartik-fileinput/js/fileinput_locale_es.js') }}"></script>
<!-- Select2 -->
<script src="{{ URL::asset('js/plugins/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plugins/select2/dist/js/i18n/es.js') }}"></script>
<!-- DatePicker --> 
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>

<!-- Page-Level Scripts -->
<script>
      
      var citizen_id = "{{$citizen->id}}";
      if( citizen_id == "" )
      {        
        avatar_preview = "<img style='height:150px' src='{{ url('img/avatar_default.png') }}'>";
      }else{
        avatar_preview = "<img style='height:150px' src= '{{ url('citizen_avatar/'.$citizen->id) }}' >";
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
                
        // Regular Expresions
        $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
        );               

        // Validation
        $("#form").validate({
            rules: {
                RFC: {
                    required: true,
                    //regex: /^[0-9]+$/,
                },
            },
            messages: {
                RFC:  {
                    regex: "Debe introducir un RFC válido",
                },                
            },            
            submitHandler: function(form) {
                $("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });        
    
        //Datepicker fecha de nacimiento
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
        
        // Select2 
        $("#state").select2({
          language: "es",
          placeholder: "Seleccione un estado",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });
        
        // Select2 
        $("#municipality").select2({
          language: "es",
          placeholder: "Seleccione un municipio",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });

        //ECMAScript 6 Metodo para combos anidados
        $("#state").change( event => {
          url = `{{URL::to('get_municipalities/')}}/${event.target.value}`;                    
          $.get(url, function( response, state){
            $("#municipality").empty();
            response.forEach(element => {
              $("#municipality").append(`<option value=${element.id}> ${element.name} </option>`);
            });
          });
        });

        $("#municipality").on('change', function()
        {
          $('#hdd_municipality_id').val(this.value);
        });        
        
        //ECMAScript 6 Metodo para setear el combo anidado al actualizar
        var state_id = $('#state').val();
        if( state_id != "" )
        {
          url = `{{URL::to('get_municipalities/')}}/${state_id}`;
          var state_id = ''; 
          if ($('#hdd_municipality_id').val() == ''){
            municipality_id = "{{$citizen->municipality_id}}";
          }else{
            municipality_id = $('#hdd_municipality_id').val();            
          }
          $.get(url, function( response, state){
            $("#municipality").empty();
            response.forEach(element => {
              if (element.id == municipality_id){
                $("#municipality").append(`<option value=${element.id} selected> ${element.name} </option>`);
              }else{
                $("#municipality").append(`<option value=${element.id}> ${element.name} </option>`);
              }
            });
          });
        }
    
    });
    </script>

@endpush