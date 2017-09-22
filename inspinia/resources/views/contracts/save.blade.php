@extends('layouts.app')

@push('stylesheets')
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
                <h5>{{ ($contract->id) ? "Modificar Contrato" : "Nuevo Contrato" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        <form action="{{url('contracts/'.$contract->id)}}" id="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="hdd_citizen_id" value="{{ $citizen->id }}" />
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        @if($contract->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2>{{ $citizen->name }}</h2>
                        </div>
                        <!-- 1ra Columna -->
                        <div class="col-sm-6 b-r">
                            <div class="form-group">                            
                                <label>Nro de Contrato *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-barcode" aria-hidden="true"></i></span>
                                    {!! Form::text('number', $contract->number, ['id'=>'number', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. 0005456328', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Estatus</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tachometer" aria-hidden="true"></i></span>
                                    {{ Form::select('status', ['A' => 'Activo', 'B' => 'Baja', 'D' => 'Desactivo', 'R'=>'Reparación'], ($contract->id)?$contract->status:'A', ['id'=>'status', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                                                                                                      
                            <div class="form-group">                            
                                <label>Tarifa *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {{ Form::select('rate', $rates, $contract->rate_id, ['id'=>'rate', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                                                        
                            <div class="form-group" id="data_1">
                                <label>Fecha de Contrato *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date', $contract->date, ['class'=>'form-control', 'type'=>'date', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Administración *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-gavel" aria-hidden="true"></i></span>
                                    {{ Form::select('administration', $administrations, $contract->administration_id, ['id'=>'administration', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {{ Form::select('state', $states, ($contract->id)?$contract->state_id:$citizen->state_id, ['id'=>'state', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>                             
                            <div class="form-group">
                                <label>Municipio *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::select('municipality', ['placeholder'=>'Seleccione un municipio'], null, ['id'=>'municipality', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required']) !!}
                                </div>
                            </div>                                                         
                        </div>
                        <!-- /1ra Columna -->

                        <!-- 2ra Columna -->
                        <div class="col-sm-6">                                                    
                            <div class="form-group">
                                <label>Calle *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('street', ($contract->id)?$contract->street:$citizen->street, ['id'=>'street', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Juarez', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Barrio o Colonia *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    {!! Form::text('neighborhood', ($contract->id)?$contract->neighborhood:$citizen->neighborhood, ['id'=>'neighborhood', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Cristo Rey', 'maxlength'=>'50', 'required']) !!}
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <label>Número externo *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                    {!! Form::text('number_ext', ($contract->id)?$contract->number_ext:$citizen->number_ext, ['id'=>'number_ext', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. #15', 'maxlength'=>'25', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Número interno</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-home" aria-hidden="true"></i></span>
                                    {!! Form::text('number_int', ($contract->id)?$contract->number_int:$citizen->number_int, ['id'=>'number_int', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. #1500', 'maxlength'=>'25']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Código Postal</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-location-arrow" aria-hidden="true"></i></span>
                                    {!! Form::text('postal_code', ($contract->id)?$contract->postal_code:$citizen->postal_code, ['id'=>'postal_code', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. 21150', 'maxlength'=>'10', 'number']) !!}
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label>Observación</label><small> Máx. 400 caracteres.</small>
                                <div class="input-group m-b">
                                <span class="input-group-addon"><i class="fa fa-align-justify" aria-hidden="true"></i></span>
                                {!! Form::textarea('observation', $contract->observation, ['id'=>'observation', 'rows'=>'3', 'class'=>'form-control', 'placeholder'=>'Escriba aqui alguna observación', 'maxlength'=>'400']) !!}
                                </div>
                            </div>
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{ route('contracts.citizen_contracts', Crypt::encrypt($citizen->id)) }}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                                </div>
                            </div>                            
                        </div>
                        <!-- /2d Columna -->
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
<!-- DatePicker --> 
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('js/plugins/datapicker/bootstrap-datepicker.es.min.js') }}"></script>

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
    
        //Datepicker fecha del contrato
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
        if($('#data_1 .input-group.date').val() == ''){
          $('#data_1 .input-group.date').datepicker("setDate", new Date());                
        }            
        
        // Select2 
        $("#status").select2({
          language: "es",
          placeholder: "Seleccione un estatus",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });
        
        // Select2 
        $("#rate").select2({
          language: "es",
          placeholder: "Seleccione una tarifa",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });

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

        // Select2 
        $("#administration").select2({
          language: "es",
          placeholder: "Seleccione una Administración",
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
          municipality_id = "{{($contract->id)?$contract->municipality_id:$citizen->municipality_id}}";
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