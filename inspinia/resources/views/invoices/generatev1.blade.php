@extends('layouts.app')

@push('stylesheets')
<!-- Select2 -->
<link href="{{ URL::asset('js/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ URL::asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
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
                <h5>Generar Recibos <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        {{ Form::open(array('url' => 'invoices.generate', 'id'=>'form'), ['class'=>'form-horizontal'])}}
                            <div class="form-group" id="data_1">
                                <label>Fecha de Emisión *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date', null, ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'date', 'required']) }}
                                </div>
                            </div>
                            <div class="form-group" id="data_2">
                                <label>Mes de Facturación *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('month', null, ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>                                                        
                            <div class="form-group" id="data_3">
                                <label>Año de Facturación *</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('year', null, ['class'=>'form-control', 'type'=>'text', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>                            
                             <div class="form-group">
                                <label>Mensaje</label><small> Puede escribir un mensaje al ciudadano. Máx. 150 caracteres.</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-align-justify" aria-hidden="true"></i></span>
                                    {!! Form::textarea('observation', null, ['id'=>'observation', 'rows'=>'3', 'class'=>'form-control', 'placeholder'=>'Escriba aqui alguna observación (opcional)', 'maxlength'=>'150']) !!}
                                </div>
                            </div>                            
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Generar</button>
                                    <a href="{{URL::to('invoices/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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

        var date_input_1=$('#data_2 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
        if($('#data_2 .input-group.date').val() == ''){
          $('#data_2 .input-group.date').datepicker("setDate", new Date());                
        }
        
        var date_input_1=$('#data_3 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })
        if($('#data_3 .input-group.date').val() == ''){
          $('#data_3 .input-group.date').datepicker("setDate", new Date());                
        }

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