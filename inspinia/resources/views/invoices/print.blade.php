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
                <h5><i class="fa fa-print" aria-hidden="true"></i> Imprimir Recibos en Lote <small>Complete el formulario <b>(*) Campos obligatorios.</b></small></h5>
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
                        <i class="fa fa-info-circle"></i><small> Esta rutina permite generar hasta un <strong>máximo de 50 recibos</strong> en lote.</small><br/><br/> 
                    </div>


                    {{ Form::open(array('url' => 'invoices.invoices_pdf', 'id'=>'form'), ['class'=>'form-horizontal'])}}
                    <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-sm-2 b-r">
                            </div>
                            <div class="col-sm-4 b-r">  
                                <div class="form-group">
                                    <label>Desde</label> <small>Introduzca el Nro de Recibo inicial</small>
                                    <div class="input-group m-b">
                                        <span class="input-group-addon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                        {!! Form::text('invoice_from', null, ['id'=>'invoice_from', 'class'=>'form-control', 'type'=>'numeric', 'placeholder'=>'', 'min'=>'1', 'required']) !!}
                                    </div>
                                </div>
                            </div> 
                            <div class="col-sm-4"> 
                                <div class="form-group">
                                    <label>Hasta</label> <small>Introduzca el Nro de Recibo final</small>
                                    <div class="input-group m-b">
                                        <span class="input-group-addon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                        {!! Form::text('invoice_to', null, ['id'=>'invoice_to', 'class'=>'form-control', 'type'=>'numeric', 'placeholder'=>'', 'min'=>'1', 'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                            </div>                                                        
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group pull-right">
                            <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">Ok</button>
                        </div>                            
                    </div>
                    {{ Form::close() }}
                
                </div><!-- /row -->
            </div><!-- /ibox-content -->
        </div>
    </div>
</div>
@endsection

@push('scripts')    

<script>
          
    $(document).ready(function() {
                
        // Validation
        $("#form").validate({
            submitHandler: function(form) {
                //$("#btn_submit").attr("disabled",true);
                form.submit();
            }        
        });        
    });
    </script>

@endpush