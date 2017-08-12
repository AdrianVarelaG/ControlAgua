@extends('layouts.blank')

@push('stylesheets')
  <!-- Select2 -->
  <link href="{{ URL::asset('vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="{{ URL::asset('css/custom.min.css') }}" rel="stylesheet">
  <!-- Fileinput -->
  <link href="{{ URL::asset('/vendors/kartik-fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />    
@endpush

@section('main_container')

  <!-- page content -->
  <div class="right_col" plan="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
            
      <div class="x_panel">
        
        <!-- title widget -->
        <div class="x_title">
          <h2>{{ ($company->id) ? "Modificar Empresa" : "Agregar Empresa" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
          </ul>          
          <div class="clearfix"></div>
          </div>
          <!-- /title widget -->
          
          <!-- body widget -->
          <div class="x_content">
           
          @include('includes.errors')

           <form action="{{url('my_company/'.$company->id)}}" id="form" method="POST" enctype="multipart/form-data">
           <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
              @if($company->id)
                {{ Form::hidden ('_method', 'PUT') }}
              @endif
              
                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <input id="logo" name="logo" class="file" type="file">
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Nombre *</label>
                  {!! Form::text('name', $company->name, ['id'=>'name', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Nombre de la empresa', 'maxlength'=>'100', 'required']) !!}                    
                  <span class="fa fa-building-o form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Número de Identificación *</label>
                  {!! Form::text('ID_company', $company->ID_company, ['id'=>'ID_company', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. RIF-NIT-ID', 'maxlength'=>'20', 'required']) !!}
                  <span class="fa fa-id-badge form-control-feedback left" aria-hidden="true"></span>
                </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Dirección *</label>
                  {!! Form::text('address', $company->address, ['id'=>'address', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Dirección', 'maxlength'=>'150', 'required']) !!}   
                  <span class="fa fa-map-marker form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Teléfono de la empresa *</label>
                  {!! Form::text('company_phone', $company->company_phone, ['id'=>'company_phone', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. 555-55555', 'maxlength'=>'25', 'required']) !!}
                  <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Correo electrónico *</label>
                  {!! Form::text('company_email', $company->company_email, ['id'=>'company_email', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'empresa@dominio.com', 'maxlength'=>'50', 'email', 'required']) !!}
                  <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                </div>
                
              <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                <label>Twitter</label>
                {!! Form::text('company_twitter', $company->company_twitter, ['id'=>'company_twitter', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'@miempresa', 'maxlength'=>'25']) !!}                
                <span class="fa fa-twitter form-control-feedback left" aria-hidden="true"></span>
              </div>
                
                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Contacto *</label>
                  {!! Form::text('contact', $company->contact, ['id'=>'contact', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Responsable legal', 'maxlength'=>'100', 'required']) !!}                    
                  <span class="fa fa-male form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Teléfono del contacto *</label>
                  {!! Form::text('contact_phone', $company->contact_phone, ['id'=>'contact_phone', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'555-55555', 'maxlength'=>'25', 'required']) !!}
                  <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>Correo electrónico del contacto *</label>
                  {!! Form::text('contact_email', $company->contact_email, ['id'=>'contact_email', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. micorreo@dominio.com', 'email', 'required']) !!}
                  <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>WebMaster *</label>
                  {!! Form::text('webmaster_from', $company->webmaster_from, ['id'=>'webmaster_from', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Nombre del Web Master Email']) !!}                    
                  <span class="fa fa-building-o form-control-feedback left" aria-hidden="true"></span>
                </div>
              
                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">                
                  <label>WebMaster Email *</label>
                  {!! Form::text('webmaster_email', $company->webmaster_email, ['id'=>'webmaster_email', 'class'=>'form-control has-feedback-left', 'type'=>'text', 'placeholder'=>'Ej. webmaster@dominio.com', 'email', 'required']) !!}
                  <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                </div>
              
              <div class="form-group pull-right">
                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                  <button type="submit" id="btn_submit" class="btn btn-sm btn-success">Ok</button>
                  <a href="{{URL::to('home')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                </div>
              </div>          
            {{ Form::close() }}
          </div>
          <!-- /body widget -->        
        </div>
      </div>
    </div>    
    <!-- /page content -->
@endsection

@push('scripts')
  <!-- Select2 -->
  <script src="{{ URL::asset('vendors/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ URL::asset('vendors/select2/dist/js/i18n/es.js') }}"></script>
  <!-- Fileinput -->
  <script src="{{ URL::asset('vendors/kartik-fileinput/js/fileinput.min.js') }}"></script>
  <script src="{{ URL::asset('vendors/kartik-fileinput/js/fileinput_locale_es.js') }}"></script>

  <script>
      
      var company_id = "{{$company->id}}";
      if( company_id == "" )
      {        
        logo_preview = "<img style='height:150px' src='{{ url('images/condominium_avatar.jpg') }}'>";
      }else{
        logo_preview = "<img style='height:150px' src= '{{ url('logo_my_company/'.$company->id) }}' >";
      }
      
      // Fileinput    
      $('#logo').fileinput({
        language: 'es',
        allowedFileExtensions : ['jpg', 'png', 'gif'],
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        showUpload: false,        
        maxFileSize: 5000,
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
        
        // Select2 
        $("#country").select2({
          language: "es",
          placeholder: "Seleccione un país",
          allowClear: false,
          minimumResultsForSearch: -1,
          width: '100%'
        });

        $("#state").select2({
          language: "es",
          placeholder: "Seleccione un estado",
          allowClear: false,
          width: '100%'
        });
        // /Select2

        //ECMAScript 6 Metodo para combos anidados
        $("#country").change( event => {
          url = `{{URL::to('get_states/')}}/${event.target.value}`;                    
          $.get(url, function( response, state){
            $("#state").empty();
            response.forEach(element => {
              $("#state").append(`<option value=${element.id}> ${element.name} </option>`);
            });
          });
        });

        //ECMAScript 6 Metodo para setear el combo anidado al actualizar
        var country_id = "{{$company->country_id}}";
        if( country_id != "" )
        {
          url = `{{URL::to('get_states/')}}/${country_id}`;
          var state_id = "{{$company->state_id}}"; 
          $.get(url, function( response, state){
            $("#state").empty();
            response.forEach(element => {
              if (element.id == state_id){
                $("#state").append(`<option value=${element.id} selected> ${element.name} </option>`);
              }else{
                $("#state").append(`<option value=${element.id}> ${element.name} </option>`);
              }
            });
          });
        }
      
      });
    </script>
@endpush