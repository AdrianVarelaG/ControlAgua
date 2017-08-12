@extends('layouts.app')

@push('stylesheets')

@endpush

@section('page-header')
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="ibox float-e-margins">
            
            <!-- ibox-title -->
            <div class="ibox-title">
                <h5>{{ ($rate->id) ? "Modificar Tarifa" : "Registrar Tarifa" }} <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        {{ Form::open(array('url' => 'rates/' . $rate->id, 'id'=>'form'), ['class'=>'form-horizontal'])}}
                        @if($rate->id)
                            {{ Form::hidden ('_method', 'PUT') }}
                        @endif
                             <div class="form-group">
                                <label>Nombre de la Tarifa *</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-money" aria-hidden="true"></i></span>
                                    {!! Form::text('name', $rate->name, ['id'=>'name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Ej. Residencial Zona A', 'maxlength'=>'50', 'required']) !!}
                                </div>
                            </div>                            
                             <div class="form-group">
                                <label>Monto de la Tarifa por m3 *</label><small> Para decimales use el punto (.)</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon">{{ Session::get('coin') }}</span>
                                    {!! Form::text('amount', $rate->amount, ['id'=>'amount', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'', 'number', 'required', 'min'=>'0']) !!}
                                </div>
                            </div>                            
                             <div class="form-group">
                                <label>Observación</label><small> Máx. 150 caracteres.</small>
                                <div class="input-group m-b">
                                    <span class="input-group-addon"><i class="fa fa-align-justify" aria-hidden="true"></i></span>
                                    {!! Form::textarea('observation', $rate->observation, ['id'=>'observation', 'rows'=>'3', 'class'=>'form-control', 'placeholder'=>'Escriba aqui alguna observación (opcional)', 'maxlength'=>'150']) !!}
                                </div>
                            </div>                                                        
                            <div class="form-group pull-right">
                                <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
                                    <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                                    <a href="{{URL::to('rates/')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
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
        
    });
    </script>

@endpush