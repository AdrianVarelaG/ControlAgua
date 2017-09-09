@extends('layouts.app')

@push('stylesheets')
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
                <h5>Registrar Datos Iniciales y Activar</h5>
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
                        {{ Form::open(array('url' => 'contracts.activate/' . $contract->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($contract->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                            <div class="form-group" id="data_1">
                                <label>Fecha del Saldo Inicial</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date', null, ['class'=>'form-control', 'type'=>'date', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Atención</label>
                                <div class="input-group date">
                                    <small>Saldo <strong>positivo (+)</strong> será considerado como <strong>saldo a favor</strong> del ciudadano.</small> <i class="fa fa-level-up" style="color:#1ab394"></i><br/>
                                    <small>Saldo <strong>negativo (-)</strong> será considerado como <strong>deuda</strong> contraída por el ciudadano. <i class="fa fa-level-down" style="color:#ed5565"></i></small>
                                </div>
                            </div>                                                         
                            <div class="form-group">                                
                                <label>Saldo Inicial {{ Session::get('coin') }}</label><small class="hidden-xs"> Para decimales use punto (.).</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-dollar" aria-hidden="true"></i></span>
                                    {!! Form::text('initial_balance', null, ['id'=>'initial_balance', 'class'=>'form-control', 'type'=>'text', 'number', 'placeholder'=>'Ej. 1000.00', 'required']) !!}
                                </div>
                            </div>                            
                            <div class="form-group" id="data_2">
                                <label>Fecha del Ultimo Pago efectuado</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {{ Form::text ('date_last_payment', null, ['class'=>'form-control', 'type'=>'date_last_payment', 'placeholder'=>'01/01/2017', 'required']) }}
                                </div>
                            </div>                                                         
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('contracts.initial_balance/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
        
        //Datepicker fecha del pago
        var date_input_1=$('#data_1 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            endDate: 'd',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })        

        //Datepicker fecha del pago
        var date_input_1=$('#data_2 .input-group.date');
        date_input_1.datepicker({
            format: 'dd/mm/yyyy',
            endDate: 'd',
            todayHighlight: true,
            autoclose: true,
            language: 'es',
        })        

    });
    </script>

@endpush