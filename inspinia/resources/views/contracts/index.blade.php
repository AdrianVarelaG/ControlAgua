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
                    <h5><i class="fa fa-tachometer" aria-hidden="true"></i> Contratos Activos</h5>
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

              <div class="col-sm-8">
              </div>
                                        
              <div class="col-sm-4">
                <div class="col-sm-10">
                  <div class="input-group m-b">
                    <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                      {!! Form::text('filter_name', Session::get('filter_name'), ['id'=>'filter_name', 'class'=>'form-control', 'type'=>'text', 'placeholder'=>'Buscar por nombre' ,'maxlength'=>'100']) !!}
                  </div>
                </div>
                <button type="button" id="btn_print" class="btn btn-sm btn-default" title="Imprimir PDF"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
              </div>
            
              <div class="col-md-12 col-sm-12 col-xs-12">
                @include('partials.errors')
              </div>

            @if($contracts->count())
              <div class="col-md-12 col-sm-12 col-xs-12">    
                <div class="table-responsive">
                  <table class="table table-striped table-hover dataTables-example" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nro Contrato</th>
                        <th>Ciudadano</th>
                        <th>RFC</th>
                        <th>Deuda {{ Session::get('coin') }}</th>
                        <th>Solvente hasta</th>
                        <th>Estatus</th>                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contracts as $contract)
                    <tr class="gradeX">
                        <td class="text-center">                            
                        <!-- Split button -->
                            <div class="input-group-btn">
                                <button data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle" type="button" title="Aciones"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></button>
                                <ul class="dropdown-menu">
                                  @if(Session::get('user_role') == 'ADM' || Session::get('user_role') == 'TES')
                                    @if($contract->balance > 0)
                                      <!-- Si tiene deuda aparece el boton de pagar -->
                                      <li><a href="{{ route('payments.create', Crypt::encrypt($contract->id)) }}"><i class="fa fa-money"></i> Pagar</a></li>
                                    @else
                                      <!-- Si esta solvente y su ultimo recibo cancelado es antes de diciembre sale aparece el boton de pagar por adelantado -->
                                      @if(($contract->last_invoice_canceled && $contract->last_invoice_canceled->year.$contract->last_invoice_canceled->month < $current_year.'12'))
                                        <li><a href="{{ route('payments.future', Crypt::encrypt($contract->id)) }}"><i class="fa fa-money"></i> Pagar por adelantado</a></li>
                                      @endif
                                    @endif
                                  @endif
                                    <li><a href="{{ route('contracts.balance', [Crypt::encrypt($contract->id), '3']) }}"><i class="fa fa-th-list"></i> Estado de Cuenta</a></li>
                                    <li><a href="{{ route('contracts.invoices', [Crypt::encrypt($contract->id)]) }}"><i class="fa fa-file-text-o"></i> Recibos</a></li>
                                    <li><a href="{{ route('contracts.payments', [Crypt::encrypt($contract->id)]) }}"><i class="fa fa-money"></i> Pagos</a></li>
                                  @if(Session::get('user_role') == 'ADM')
                                    <li class="divider"></li>                                               
                                    <li><a href="{{ route('contracts.edit', Crypt::encrypt($contract->id)) }}"><i class="fa fa-pencil"></i> Editar</a></li>
                                    <li><a href="{{ route('contracts.status', Crypt::encrypt($contract->id)) }}"><i class="fa fa-ban"></i> Desactivar</a></li>
                                    <li>
                                        <!-- href para eliminar registro -->                            
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Desea eliminar el Contrato {{ $contract->number }} ?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="#" onclick="$(this).closest('form').submit()" style="color:inherit"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;Eliminar</a>
                                        </form>
                                        <br/><br/>
                                    </li>
                                  @endif
                                  @if(Session::get('user_role') == 'DDA')
                                    <li><a href="{{ route('contracts.status', Crypt::encrypt($contract->id)) }}"><i class="fa fa-ban"></i> Desactivar</a></li>
                                  @endif
                                </ul>
                            </div>
                        <!-- /Split button -->
                        <td><strong>{{ $contract->number }}</strong></td>
                        <td>{{ $contract->citizen->name }}</td>
                        <td>{{ $contract->citizen->RFC }}</td>
                        <td>
                          @if($contract->balance>=0)
                            {{ money_fmt($contract->balance) }}
                          @else
                            {{ money_fmt(abs($contract->balance)) }} <i class="fa fa-level-up" style="color:#1ab394;cursor:help;" title="Saldo a favor"></i>
                          @endif
                        </td>
                        <td>
                          {{ ($contract->last_invoice_canceled)?$contract->last_invoice_canceled->month.'/'.$contract->last_invoice_canceled->year:'Sin pagos' }}
                        </td>                                                
                        <td>
                          <p><span class="label {{ $contract->label_status }}">{{ $contract->status_description }}</span></p>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th>Nro Contrato</th>
                        <th>Ciudadano</th>
                        <th>RFC</th>
                        <th>Deuda {{ Session::get('coin') }}</th>
                        <th>Solvente hasta</th>
                        <th>Estatus</th>                        
                    </tr>
                    </tfoot>
                    </table>
                    <div class="text-right">
                      {{ $contracts->links() }}
                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <br/>                    
                    <br/>
                    <br/>
                    <br/>
                    <br/>
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
              </div> <!-- /row- --> 
            </div> <!-- /ibox-content- -->
          </div>
        </div>
    </div>
</div>

            <!-- Modal advertencia para imprimir-->
            <div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content animated fadeIn">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i><strong> Atención!</strong></h4>
                        </div>
                        <div class="modal-body">
                          <p>Debido a la gran cantidad de registros. Primero debe filtrar los registros para poder imprimirlos. Gracias!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn_close" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal advertencia para reverso de ventas-->

@endsection

@push('scripts')
    
  <script>        
    
    //Filter Name
    var timerid;    
    $("#filter_name").on("input",function(e){
      var value = $(this).val().trim();;
      if($(this).data("lastval")!= value){

        $(this).data("lastval",value);        
        clearTimeout(timerid);

        timerid = setTimeout(function() {
          //change action
          if(value!=''){
            url = `{{URL::to('contracts.filter/')}}/${e.target.value}`;
          }else{
            {{ Session::put('filter_name', '') }}
            url = `{{URL::to('contracts')}}`;
          }
          $('#form').attr('action', url);
          $('#form').submit();
        },800);
      };
    });
  
    $('#btn_print').on("click", function (e) { 
      filter = $("#filter_name").val().trim();
      if(filter ==''){
        $("#myModal1").modal("show"); 
      }else{
        url = `{{URL::to('contracts.rpt_contracts/')}}/${filter}`;
        $('#form').attr('action', url);
        $('#form').submit();
      }
    });

  </script>

@endpush