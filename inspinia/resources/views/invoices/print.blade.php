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
                        <i class="fa fa-exclamation-triangle"></i> ATENCION!
                        <ul>
                            <li>
                               <small>Esta rutina permite generar hasta un <strong>máximo de 500 recibos</strong> en lote.</small> 
                            </li>
                            <li>
                               <small>Si solicita <strong>50 o menos</strong> el sistema le generará <strong>un archivo PDF</strong>.</small> 
                            </li>
                            <li>
                               <small>Si solicita <strong>más de 50</strong> el sistema le generará <strong>un archivo ZIP</strong>.</small> 
                            </li>
                            <li>
                               <small>La rutina tarda aproximadamente <strong>1 min por cada 100 recibos generados</strong>.</small>
                            </li>
                        </ul><br/><br/>
                        <button type="button" id="btn_confirm" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal1">Consecutivo de recibos</button> 
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
                                        {!! Form::number('invoice_from', null, ['id'=>'invoice_from', 'class'=>'form-control', 'type'=>'numeric', 'placeholder'=>'', 'step'=>'1', 'min'=>'1', 'required']) !!}
                                    </div>
                                </div>
                            </div> 
                            <div class="col-sm-4"> 
                                <div class="form-group">
                                    <label>Hasta</label> <small>Introduzca el Nro de Recibo final</small>
                                    <div class="input-group m-b">
                                        <span class="input-group-addon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                                        {!! Form::number('invoice_to', null, ['id'=>'invoice_to', 'class'=>'form-control', 'type'=>'numeric', 'placeholder'=>'', 'step'=>'1', 'min'=>'1', 'required']) !!}
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

            <!-- Modal para control de consecutivos de recibos -->
            <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content animated fadeIn">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <p class="modal-title"><i class="fa fa-file-text-o"></i> Consecutivo de Recibos</p>
                        </div>
                        <div class="modal-body">
                            
                          <div class="table-responsive">              
                            <table id="tabladinamica" class="table table-striped table-hover">
                              <thead>
                                <tr>
                                  <th class="text-center">Año</th>
                                  <th class="text-center">Mes</th>
                                  <th class="text-center"># Inicial</th>
                                  <th class="text-center"># Final</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($routines as $routine)
                                <tr>
                                  <td class="text-center">{{ $routine->year }}</td>
                                  <td class="text-center">{{ $routine->month }}</td>
                                  <td class="text-center">{{ $routine->start }}</td>
                                  <td class="text-center">{{ $routine->end }}</td>
                                </tr>
                                @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th class="text-center">Año</th>
                                  <th class="text-center">Mes</th>
                                  <th class="text-center"># Inicial</th>
                                  <th class="text-center"># Final</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Modal para control de consecutivos de recibos -->


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