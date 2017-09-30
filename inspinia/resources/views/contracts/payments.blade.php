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
                    <h5><i class="fa fa-money" aria-hidden="true"></i> Consulta de Pagos</h5>
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
                            
            
            @if($contract->payments->count())
              <div class="col-md-12 col-sm-12 col-xs-12">                
                <div class="table-responsive">                
                  <div class="col-md-12 col-sm-12 col-xs-12">                    
                    <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Fecha</th>
                        <th class="text-left">Tipo</th>
                        <th class="text-left">Descripción</th>
                        <th class="text-right">Monto {{ Session::get('coin') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('payments.show', Crypt::encrypt($payment->id)) }}"><i class="fa fa-eye"></i> Vista previa</a></li>
                                    <li><a href="{{ route('payments.print_voucher', Crypt::encrypt($payment->id)) }}"><i class="fa fa-print"></i> Imprimir Comprobante</a></li>
                                </ul>
                            </div>
                        <!-- /Split button -->                          
                        </td>
                        <td class="text-center">{{ $payment->date->format('d/m/Y') }}</td>
                        <td class="text-left">{{ $payment->type_description }}</td>
                        <td class="text-left"><small>{{ $payment->description }}</small></td>
                        <td class="text-right">{{ money_fmt($payment->amount) }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Monto {{ Session::get('coin') }}</th>
                    </tr>
                    </tfoot>
                    </table>
                    <div class="text-right">
                      {{ $payments->links() }}
                    </div>                                        
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
    $('#btn_print').on("click", function (e) { 
        url = `{{URL::to('citizens.rpt_contract_payments/')}}/{{ $contract->id }}`;
        $('#form').attr('action', url);
        $('#form').submit();
    });
  </script>
@endpush