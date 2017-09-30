@extends('layouts.app')

@push('stylesheets')

@endpush

@section('page-header')
@endsection

@section('content')

<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!-- ibox-title -->
                <div class="ibox-title">
                    <h5><i class="fa fa-file-text-o" aria-hidden="true"></i> Consulta de Recibos</h5>
                    <div class="ibox-tools">
                    	<a class="collapse-link">
                        	<i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        	<i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a></li>
                            <li><a href="#">Config option 2</a></li>
                        </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                    </div>
                </div>
                <!-- /ibox-title -->
                    
          <!-- ibox-content- -->
          <div class="ibox-content">
            <div class="row">
                
              {{ Form::open(array('url' => '', 'id' => 'form', 'method' => 'get'), ['' ])}}
              {{ Form::close() }} 

              @include('partials.errors')
              
              <div class="col-sm-8">
                <h2>Contrato Nro <strong>{{ $contract->number }}</strong></h2>
                <h3>{{ $contract->citizen->name }}</h3><br/>
              </div>
                                        
              <div class="col-sm-4">
                <button type="button" id="btn_print" class="btn btn-sm btn-default pull-right" title="Imprimir PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
              </div>
            
            @if($invoices->count())
              <div class="col-md-12 col-sm-12 col-xs-12">                
                <div class="table-responsive">                
                  <div class="col-md-12 col-sm-12 col-xs-12">                    
                    <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Recibo #</th>
                        <th>Facturación</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('invoices.show', Crypt::encrypt($invoice->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('invoices.invoice_pdf', Crypt::encrypt($invoice->id)) }}"><i class="fa fa-print"></i> Imprimir Recibo</a></li>
                                </ul>
                            </div>
                        <!-- /Split button -->                          
                        </td>                          
                        <td class="text-center">
                          <a href="{{ route('invoices.show', Crypt::encrypt($invoice->id)) }}" class="client-link" title="Vista previa">{{ $invoice->id }}</a>
                        </td>
                        <td>{{ $invoice->date->format('d/m/Y') }}</td>
                        <td>{{ money_fmt($invoice->total) }}</td>
                        <td>{{ $invoice->date_limit->format('d/m/Y') }}</td>
                        @php
                        @endphp
                        <td><p><span class="label {{ $invoice->label_status }}">{{ $invoice->status_description }}</span></p></td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th class="text-center">Recibo #</th>
                        <th>Facturación</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                        <th>Vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                    </tfoot>
                    </table>
                    <div class="text-right">
                      {{ $invoices->links() }}
                    </div>                    
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                  </div>
                </div>
              </div>
                @else
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="alert alert-info">
                      <ul>
                        <i class="fa fa-info-circle"></i> Ningún registro coincide con su criterio de busqueda!
                      </ul>
                    </div>
                  </div>
                @endif

                <div class="form-group pull-right">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                      <a href="{{URL::to('contracts')}}" class="btn btn-sm btn-default" title="Regresar"><i class="fa fa-hand-o-left"></i></a>
                    </div>
                </div>
                </div>
                <!-- /ibox-content- -->
              
              </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

  <script>
            
    //Notifications
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 2000
        };
        if('{{ Session::get('notity') }}'=='create' &&  '{{ Session::get('create_notification') }}'=='1'){
          toastr.success('Registro añadido exitosamente', '{{ Session::get('app_name') }}');
        }
        if('{{ Session::get('notity') }}'=='update' &&  '{{ Session::get('update_notification') }}'=='1'){
          toastr.success('Registro actualizado exitosamente', '{{ Session::get('app_name') }}');
        }
        if('{{ Session::get('notity') }}'=='delete' &&  '{{ Session::get('delete_notification') }}'=='1'){
          toastr.success('Registro eliminado exitosamente', '{{ Session::get('app_name') }}');
        }
    }, 1300);        

    $('#btn_print').on("click", function (e) { 
        url = `{{URL::to('citizens.rpt_contract_invoices/')}}/{{ $contract->id }}`;
        $('#form').attr('action', url);
        $('#form').submit();
    });
    
    </script>
@endpush