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
                <h5>{{ ($reading->id) ? "Modificar Lectura" : "Registrar Lectura" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        {{ Form::open(array('url' => 'readings/' . $reading->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($reading->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                        <div class="col-sm-4 b-r">
                            <div class="form-group" id="data_1">
                                <label>Mes y Año de Consumo *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('period', ($reading->id)?(strlen($reading->month)==1?'0'.$reading->month:$reading->month).'/'.$reading->year:$last_month.'/'.$last_year, ['class'=>'form-control', 'type'=>'date', 'placeholder'=>'Ej. 01/2021', 'required']) }}
                                </div>
                            </div>                                                         
                        </div>
                        <div class="col-sm-4 b-r">    
                            <div class="form-group" id="data_2">
                                <label>Fecha de Lectura *</label>
                                    <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date', ($reading->id)?$reading->date->format('d/m/Y'):$last_date->format('d/m/Y'), ['id'=>'date', 'class'=>'form-control', 'type'=>'date', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">    
                            <div class="form-group">
                                <label>Inspector *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    {{ Form::select('inspector', $inspectors, ($reading->id)?$reading->inspector_id:$last_inspector_id, ['id'=>'inspector', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>
                        </div>    
                        <div class="col-sm-4 b-r">    
                            <div class="form-group">
                                <label>Contrato *</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-tachometer" aria-hidden="true"></i></span>
                                    {{ Form::select('contract', $contracts, $reading->contract_id, ['id'=>'contract', 'class'=>'select2_single form-control', 'tabindex'=>'-1', 'placeholder'=>'', 'required'])}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 b-r">    
                            <div class="form-group">
                                <label>Lectura Anterior (m3) *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                    {!! Form::text('previous_reading', $reading->previous_reading, ['id'=>'previous_reading', 'class'=>'form-control', 'type'=>'numeric', 'placeholder'=>'Ej. 100', 'min'=>'0', 'required']) !!}
                                </div>
                            </div>                            
                        </div>
                        <div class="col-sm-4">     
                             <div class="form-group">
                                <label>Lectura Actual (m3) *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
                                    {!! Form::text('current_reading', $reading->current_reading, ['id'=>'current_reading', 'class'=>'form-control', 'type'=>'numeric', 'placeholder'=>'Ej. 120', 'min'=>'0', 'required']) !!}
                                </div>
                            </div>                            
                        </div>    
                        <div class="col-sm-12">    
                            <div class="form-group">
                                <label>Observación</label><small> Máx. 400 caracteres.</small>
                                <div class="input-group m-b">
                                <span class="input-group-addon"><i class="fa fa-align-justify" aria-hidden="true"></i></span>
                                {!! Form::textarea('observation', $reading->observation, ['id'=>'observation', 'rows'=>'3', 'class'=>'form-control', 'placeholder'=>'Escriba aqui alguna observación', 'maxlength'=>'400']) !!}
                                </div>
                            </div>
                        </div>    
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('readings/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
        
        // Select2 
        $("#inspector").select2({
          language: "es",
          placeholder: "Seleccione un inspector",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });
        
        // Select2 
        $("#contract").select2({
          language: "es",
          placeholder: "Seleccione un contrato",
          minimumResultsForSearch: 10,
          allowClear: false,
          width: '100%'
        });

        //Datepicker fecha del contrato
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'mm/yyyy',
            viewMode: 'months', 
            minViewMode: 'months',
            todayHighlight: true,
            autoclose: true,
            language: 'es',        
        })


        var date_input_2=$('#data_2 .input-group.date');
        date_input_2.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
   
    });
    </script>

@endpush